from math import exp


class CoolingProcessCalculation:
    defaultMethods = [
        'analytical'
    ]

    defaultParameters = {
        'BaseTemperature': 80.0,
        'EnvironmentTemperature': 40.0,
        'CoolingCoefficient': 0.5,
        'TimeRange': 10,
        'SegmentsCount': 20
    }

    def __init__(self, parameters):
        parameters = {**self.defaultParameters, **parameters}
        self.BaseTemperature = parameters['BaseTemperature']
        self.EnvironmentTemperature = parameters['EnvironmentTemperature']
        self.CoolingCoefficient = parameters['CoolingCoefficient']
        self.SegmentsCount = parameters['SegmentsCount']
        self.Step = parameters['TimeRange'] / parameters['SegmentsCount']
        self.TemperatureDifference = self.BaseTemperature - self.EnvironmentTemperature

    def _nloc(self, t):
        return -self.CoolingCoefficient * (t - self.EnvironmentTemperature)

    def _nloc_solution(self, t):
        return self.EnvironmentTemperature + exp(-t * self.CoolingCoefficient) * self.TemperatureDifference

    def analytical(self):
        result = {'Analytical': {0: self.BaseTemperature}}
        for i in range(1, self.SegmentsCount + 1):
            result['Analytical'][self.Step * i] = self._nloc_solution(self.Step * i)
        return result

    def euler(self):
        result = {'Euler': {0: self.BaseTemperature}}
        for i in range(0, self.SegmentsCount):
            result_i = result['Euler'][self.Step * i]
            result['Euler'][self.Step * (i + 1)] = result_i + self.Step * self._nloc(result_i)
        return result

    def euler_enhanced(self):
        result = {'Euler Enhanced': {0: self.BaseTemperature}}
        for i in range(0, self.SegmentsCount):
            result_i = result['Euler Enhanced'][self.Step * i]
            k = result_i + self.Step * self._nloc(result_i)
            result['Euler Enhanced'][self.Step * (i + 1)] = result_i + self.Step * (self._nloc(result_i) +
                self._nloc(k)) / 2.0
        return result

    def RK4(self):
        result = {'RK4': {0: self.BaseTemperature}}
        for i in range(0, self.SegmentsCount):
            result_i = result['RK4'][self.Step * i]
            k1 = self._nloc(result_i)
            k2 = self._nloc(result_i + self.Step * k1 / 2.0)
            k3 = self._nloc(result_i + self.Step * k2 / 2.0)
            k4 = self._nloc(result_i + self.Step * k3)
            k = result_i + self.Step * self._nloc(result_i)
            result['RK4'][self.Step * (i + 1)] = result_i + self.Step * (k1 + 2.0 * k2 + 2.0 * k3 + k4) / 6.0
        return result
