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
        self.googleLineChartFormattedData = {k:[] for k in [self.Step * i for i in range(0, self.SegmentsCount)]}
        self.analyticalSolution = self._analytical()

    def _nloc(self, t):
        return -self.CoolingCoefficient * (t - self.EnvironmentTemperature)

    def _nloc_solution(self, t):
        return self.EnvironmentTemperature + exp(-t * self.CoolingCoefficient) * self.TemperatureDifference

    def _analytical(self):
        result = {'Analytical': {0: [self.BaseTemperature, 0]}}
        for i in range(1, self.SegmentsCount):
            result['Analytical'][self.Step * i] = [self._nloc_solution(self.Step * i), 0]
        return result

    def analytical(self):
        self.googleLineChartFormattedData[0].append(self.BaseTemperature)
        for i in range(1, self.SegmentsCount):
            self.googleLineChartFormattedData[self.Step * i].append(self.analyticalSolution['Analytical'][self.Step * i][0])
        return self.analyticalSolution

    def euler(self):
        result = {'Euler': {0: [self.BaseTemperature, 0]}}
        self.googleLineChartFormattedData[0].append(self.BaseTemperature)
        for i in range(0, self.SegmentsCount - 1):
            result_i = result['Euler'][self.Step * i][0]
            result['Euler'][self.Step * (i + 1)] = [result_i + self.Step * self._nloc(result_i)]
            result['Euler'][self.Step * (i + 1)].append(abs(result['Euler'][self.Step * (i + 1)][0] -
                                                        self.analyticalSolution['Analytical'][self.Step * (i + 1)][0]))
            self.googleLineChartFormattedData[self.Step * (i + 1)].append(result['Euler'][self.Step * (i + 1)][0])
        return result

    def euler_enhanced(self):
        result = {'Euler Enhanced': {0: [self.BaseTemperature, 0]}}
        self.googleLineChartFormattedData[0].append(self.BaseTemperature)
        for i in range(0, self.SegmentsCount - 1):
            result_i = result['Euler Enhanced'][self.Step * i][0]
            k = result_i + self.Step * self._nloc(result_i)
            result['Euler Enhanced'][self.Step * (i + 1)] = [result_i + self.Step * (self._nloc(result_i) +
                self._nloc(k)) / 2.0]
            result['Euler Enhanced'][self.Step * (i + 1)].append(abs(result['Euler Enhanced'][self.Step * (i + 1)][0] -
                                                        self.analyticalSolution['Analytical'][self.Step * (i + 1)][0]))
            self.googleLineChartFormattedData[self.Step * (i + 1)].append(result['Euler Enhanced'][self.Step * (i + 1)][0])
        return result

    def RK4(self):
        result = {'RK4': {0: [self.BaseTemperature, 0]}}
        self.googleLineChartFormattedData[0].append(self.BaseTemperature)
        for i in range(0, self.SegmentsCount - 1):
            result_i = result['RK4'][self.Step * i][0]
            k1 = self._nloc(result_i)
            k2 = self._nloc(result_i + self.Step * k1 / 2.0)
            k3 = self._nloc(result_i + self.Step * k2 / 2.0)
            k4 = self._nloc(result_i + self.Step * k3)
            result['RK4'][self.Step * (i + 1)] = [result_i + self.Step * (k1 + 2.0 * k2 + 2.0 * k3 + k4) / 6.0]
            result['RK4'][self.Step * (i + 1)].append(abs(result['RK4'][self.Step * (i + 1)][0] -
                                                        self.analyticalSolution['Analytical'][self.Step * (i + 1)][0]))
            self.googleLineChartFormattedData[self.Step * (i + 1)].append(result['RK4'][self.Step * (i + 1)][0])
        return result
