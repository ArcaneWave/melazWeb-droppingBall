from models import CoolingProcessCalculation as CPC
import argparse
import json

if __name__ == '__main__':
    parser = argparse.ArgumentParser()
    parser.add_argument('-an', '--analytical', help='use analytical method', action='store_true')
    parser.add_argument('-eu', '--euler', help='use Euler method', action='store_true')
    parser.add_argument('-en', '--enhanced', help='use enhanced Euler method', action='store_true')
    parser.add_argument('-rk', '--RK4', help='use 4th order Runge--Kutta method', action='store_true')
    parser.add_argument('-cc', '--coefficient', help='thermal conductivity coefficient', type=float, metavar='')
    parser.add_argument('-ct', '--coffee', help='coffee temperature', type=float, metavar='')
    parser.add_argument('-et', '--environment', help='environment temperature', type=float, metavar='')
    parser.add_argument('-tr', '--time', help='time range', type=int, metavar='')
    parser.add_argument('-sc', '--segments', help='segments count', type=int, metavar='')
    args = parser.parse_args()

    parameters = {}
    methods = []
    if args.coefficient:
        print("Temperature coefficient value: ", args.coefficient)
        parameters['CoolingCoefficient'] = args.coefficient
    if args.coffee:
        print("Coffee temperature value: ", args.coffee)
        parameters['BaseTemperature'] = args.coffee
    if args.environment:
        print("Environment temperature value: ", args.environment)
        parameters['EnvironmentTemperature'] = args.environment
    if args.time:
        print("Time range: ", args.time)
        parameters['TimeRange'] = args.time
    if args.segments:
        print("Segments count: ", args.segments)
        parameters['SegmentsCount'] = args.segments
    if args.analytical:
        methods.append('analytical')
    if args.euler:
        methods.append('euler')
    if args.enhanced:
        methods.append('euler_enhanced')
    if args.RK4:
        methods.append('RK4')
    for x in methods if len(methods) else CPC.defaultMethods:
        print(eval(''.join(("json.dumps(CPC(parameters).", x, "(), sort_keys=True, indent=4, separators=(',', ': '))"))),
              file=open(''.join((x, '.json')),'w'))
