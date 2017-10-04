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

    parameters, methods = {}, []
    if args.coefficient:
        parameters['CoolingCoefficient'] = args.coefficient
    if args.coffee:
        parameters['BaseTemperature'] = args.coffee
    if args.environment:
        parameters['EnvironmentTemperature'] = args.environment
    if args.time:
        parameters['TimeRange'] = args.time
    if args.segments:
        parameters['SegmentsCount'] = args.segments
    if args.analytical:
        methods.append('analytical')
    if args.euler:
        methods.append('euler')
    if args.enhanced:
        methods.append('euler_enhanced')
    if args.RK4:
        methods.append('RK4')

    data, cpc = [], CPC(parameters)
    print('{"data":')
    for x in methods if len(methods) else CPC.defaultMethods:
        data.append(eval(''.join(("cpc.", x, "()"))))
    print(json.dumps(data, sort_keys=True, indent=4, separators=(',', ': ')), '\n}}')