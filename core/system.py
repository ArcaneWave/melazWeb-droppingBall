from math import sqrt

class System:
    def __init__(self,
                 body_mass=0, start_coordinate=100, start_velocity=0, experiment_time=10, step_amount=100,  # Main
                 using_complex_gravity=False,  # Gravity
                 using_archimedes_force=False, body_density=None, environment_density=None,  # Archimedes force
                 using_environment_resistance=False, resistance_coefficient=None,  # Linear velocity dependence
                 ):

        # Main
        self.currentTime = 0
        self.bodyMass = body_mass
        self.currentCoordinate = start_coordinate
        self.currentVelocity = start_velocity
        self.experimentTime = experiment_time
        self.stepAmount = step_amount
        self.result = [[0] * self.stepAmount for _ in range(6)]
        self.step = self.experimentTime / self.stepAmount

        # Gravity
        self.usingComplexGravity = using_complex_gravity

        # Archimedes force
        self.usingArchimedesForce = using_archimedes_force
        self.environmentDensity = environment_density
        self.bodyDensity = body_density
        self.archimedesCoefficient = self.get_archimedes_coefficient()  # Calculate using gotten data

        # Environment resistance
        self.usingEnvironmentResistance = using_environment_resistance
        self.resistanceCoefficient = resistance_coefficient
        self.linearResistanceCoefficient = self.get_resistance_coefficient()  # Calculate using gotten data

    def perform_experiment(self):
        self.result[0][0] = self.currentTime
        self.result[1][0] = self.currentCoordinate
        self.result[2][0] = self.currentVelocity
        self.result[3][0] = self.result[4][0] = self.result[5][0] = 0

        for i in range(1, self.stepAmount):

            gravity = self.get_current_gravity()
            arch_force = self.get_archimedes_force()
            resistance = self.get_environment_resistance_force()
            if self.currentVelocity > 0:
                resistance *= -1
            current_resultant = -gravity + arch_force + resistance

            self.currentVelocity = self.currentVelocity + current_resultant * self.step

            if abs(self.currentVelocity) > abs(self.get_maximum_speed()):
                if (self.currentVelocity) > 0:
                    self.currentVelocity = self.get_maximum_speed()
                else:
                    self.currentVelocity = -self.get_maximum_speed()

            self.currentCoordinate = self.currentCoordinate + self.currentVelocity * self.step

            if self.currentCoordinate <= 0:
                self.currentCoordinate = 0
                self.currentVelocity = 0
                gravity = arch_force = resistance = 0

            self.currentTime += self.step

            self.result[0][i] = self.currentTime
            self.result[1][i] = self.currentCoordinate
            self.result[2][i] = self.currentVelocity
            self.result[3][i] = gravity
            self.result[4][i] = arch_force
            self.result[5][i] = resistance

            if self.currentCoordinate == 0:
                break

        return self.result

    # Gravity
    def get_current_gravity(self):
        if self.usingComplexGravity:
            return 0.0000000000067408 * (5972400000000000000000000 * self.bodyMass) / (
                (6371000 + self.currentCoordinate) * (6371000 + self.currentCoordinate))
        else:
            return 9.81

    # Archimedes force
    def get_archimedes_coefficient(self):
        if not self.usingArchimedesForce:
            return None
        return self.environmentDensity / self.bodyDensity

    def get_archimedes_force(self):
        if self.usingArchimedesForce:
            return self.archimedesCoefficient * self.get_current_gravity()
        else:
            return 0

    # Linear velocity dependence
    def get_resistance_coefficient(self):
        if not self.usingEnvironmentResistance:
            return None
        else:
            return self.resistanceCoefficient / self.bodyMass

    def get_environment_resistance_force(self):
        if self.usingEnvironmentResistance:
            if (self.environmentDensity > 10):
                return self.resistanceCoefficient * self.currentVelocity
            else:
                return self.resistanceCoefficient * abs(self.currentVelocity) * self.currentVelocity
        else:
            return 0

    # Main
    def get_current_speed(self, current_time, current_resultant):
        return self.currentVelocity - current_resultant * current_time

    def get_maximum_speed(self):
        if (self.usingEnvironmentResistance):
            if (self.environmentDensity > 10):
                return self.bodyMass * self.get_current_gravity() / self.resistanceCoefficient
            else:
                return sqrt(self.bodyMass * self.get_current_gravity() / self.resistanceCoefficient)
        else:
            return 9999999999999
