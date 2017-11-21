#!/home/a/alexanei/.local/bin/python3

import cgi
import html
import http.cookies as cookie
import sys

sys.path.append('../core')
from system import System as Model


def safe_int_cast(string, default):
    try:
        result = int(string)
    except (ValueError, TypeError):
        result = default

    return result


def safe_float_cast(string, default):
    try:
        result = float(string)
    except (ValueError, TypeError):
        result = default

    return result


def get_int_form_value(name: str, default_value: int):
    return safe_int_cast(html.escape(form.getfirst(name, str(default_value))), default_value)


def get_float_form_value(name: str, default_value: int):
    return safe_float_cast(html.escape(form.getfirst(name, str(default_value))), default_value)


def get_checkbox_form_value(name: str):
    return True if html.escape(form.getfirst(name, '')) else False


f_template = open('../template/index.html')
template = f_template.read()

form = cgi.FieldStorage()

start_coordinate = get_float_form_value('start_coordinate', 100)
start_velocity = get_float_form_value('start_velocity', 0)
experiment_time = get_float_form_value('experiment_time', 10)
step_amount = get_int_form_value('step_amount', 100)
body_density = get_float_form_value('body_density', 4200)
environment_density = get_float_form_value('environment_density', 4000)
resistance_coefficient = get_float_form_value('resistance_coefficient', 1)
body_mass = get_float_form_value('body_mass', 0)

using_complex_gravity = get_checkbox_form_value('using_complex_gravity')
using_archimedes_force = get_checkbox_form_value('using_archimedes_force')
using_environment_resistance = get_checkbox_form_value('using_environment_resistance')

cookies = cookie.SimpleCookie()
cookies['using_complex_gravity'] = using_complex_gravity
cookies['using_archimedes_force'] = using_archimedes_force
cookies['using_environment_resistance'] = using_environment_resistance

model = Model(start_coordinate=start_coordinate, start_velocity=start_velocity,
              experiment_time=experiment_time, step_amount=step_amount,
              body_density=body_density, using_complex_gravity=using_complex_gravity,
              environment_density=environment_density, using_environment_resistance=using_environment_resistance,
              using_archimedes_force=using_archimedes_force, body_mass=body_mass,
              resistance_coefficient=resistance_coefficient)

res = model.perform_experiment()
res_len = len(res[0])
table = ''
coordinate_time_data = "['Time', 'Object 1'],"
for i in range(res_len):
    table += '<tr><td>' + str(i) + '</td><td>' + str('%5.3f' % res[0][i]) + '</td><td>' + str('%12.3f' % res[1][i]) + \
             '</td><td>' + str('%93.f' % res[2][i]) + '</td></tr>'
    coordinate_time_data += '[%5.3f' % res[0][i] + ', %12.3f' % res[1][i] + ']'

    if not res[1][i]:
        break
    if i + 1 < res_len:
        coordinate_time_data += ','

coordinate_time_data = '[' + coordinate_time_data + ']'

print(cookies)
print('Content-type:text/html')
print()
print(template.format(start_coordinate=start_coordinate, start_velocity=start_velocity,
                      experiment_time=experiment_time, step_amount=step_amount,
                      using_complex_gravity=using_complex_gravity, using_archimedes_force=using_archimedes_force,
                      body_density=body_density, environment_density=environment_density,
                      using_environment_resistance=using_environment_resistance,
                      body_mass=body_mass, table=table, coordinate_time_data=coordinate_time_data,
                      resistance_coefficient=resistance_coefficient))
