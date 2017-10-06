<?php
if (!empty($_POST)) {
    $argstring = '';
    if ($_POST['Euler']) {
        $argstring .= '-eu ';
    }
    if ($_POST['ModernEuler']) {
        $argstring .= '-en ';
    }
    if ($_POST['RK']) {
        $argstring .= '-rk ';
    }
    if ($_POST['Analythic']) {
        $argstring .= '-an ';
    }
    if ($_POST['share'] == 'graphic') {
        $argstring .= '-gc ';
    }
    $argstring .= '-cc="' . $_POST['cool_coef'] . '" -ct=' . $_POST['first_temp'] . ' -et=' . $_POST['air_temp'] . ' -tr=' . $_POST['interval'] . ' -sc=' . $_POST['step'];
    $argstring = urlencode($argstring);
    $json = shell_exec("/home/a/alexanei/.local/bin/python3 ../scripts/main.py $argstring");
    $json = shell_exec("/home/a/alexanei/.local/bin/python3 ../scripts/main.py $argstring");
    $json = json_decode($json);
    $google_translate = array(
    'Euler' => 'Метод Эйлера',
    'Analytical' => 'Аналитический метод',
    'Euler Enhanced' => 'Улучшенный метод Эйлера',
    'RK4' => 'Метод Рунге-Кутты 4 порядка'
);
    if ($_POST['share'] == 'table') {
        $existing_key = array_keys(get_object_vars($json->data[0]))[0];
$n = count(array_keys(get_object_vars($json->data[0]->$existing_key)));
    }
}
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1 plus MathML 2.0//EN" "http://www.w3.org/Math/DTD/mathml2/xhtml-math11-f.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ru">
<head>
	<meta charset="utf-8">
    <title>Моделирование процесса остывания кофе</title>
    <link rel="icon" type="image/x-icon" href="/img/favicon/favicon.ico">
    <link rel="apple-touch-icon-precomposed" href="/img/favicon/favicon.ico">
	<!-- <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">
	<script>window.MathJax = { MathML: { extensions: ["mml3.js", "content-mathml.js"]}};</script>
	<script type="text/javascript" async src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.0/MathJax.js?config=MML_HTMLorMML"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script
            src="https://code.jquery.com/jquery-3.2.1.min.js"
            integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
            crossorigin="anonymous"></script>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="css/styles.css">
    <?php if ($_POST['share'] != 'table') { ?>
        <script type="text/javascript">

            google.charts.load('current', {'packages': ['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var myRequest = new XMLHttpRequest();
            var args = document.getElementById('args').value;
            if (!args) {
                var google_translate = {
                    'a1': '-eu ',
                    'a2': '-en ',
                    'a3': '-rk ',
                    'a4': '-an '
                };
                var checkboxes = $('input[type="checkbox"]:checked');
                checkboxes.each(function (index, item) { args += google_translate[item.value]; });
                args += ' -cc=' + $('[name="cool_coef"]').val();
                args += ' -ct=' + $('[name="first_temp"]').val();
                args += ' -et=' + $('[name="air_temp"]').val();
                args += ' -tr=' + $('[name="interval"]').val();
                args += ' -sc=' + $('[name="step"]').val();
            }
            myRequest.open('GET', '/return.php?args=' + args);
            myRequest.send();

            myRequest.onreadystatechange = function () {
                if (myRequest.readyState !== 4) {
                    return;
                }

                if (myRequest.status !== 200) {
                    alert(myRequest.status + ': ' + myRequest.statusText);
                } else {
                    var json = JSON.parse(myRequest.responseText);
                    json.data.forEach(function (item, i) {
                        if (i > 0) {
                            item.forEach(function (item, i, arr) {
                                arr[i] = parseFloat(item);
                            });
                        }
                    });

                    var data = new google.visualization.arrayToDataTable(json.data);

                    var options = {
                        title: json.title,
                        width: 600,
                        height: 508,
                        curveType: 'line',
                        legend: {position: 'bottom'},
                        explorer: {
                            actions: ['dragToZoom', 'rightClickToReset'],
                            axis: 'horizontal',
                            keepInBounds: true,
                            maxZoomIn: 10.0
                        }
                    };

                    var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
                    chart.draw(data, options);
                }
            };
        }
    </script>
    <?php } ?>
</head>
<body>


<div class="container">
    <div class="row">
        <div id="page" class="col-md-12">
            <div id="form" class="col-md-6 col-xs-12">
                <form method="post">
                    <div id="main_form">

                        <div id="container">
                            <div id="process_name" align="center"> <span class="form_headers"> Моделирование процесса остывания кофе </span> </div><br> <hr>

                            <div id="methods">
                                <fieldset>
                                    <legend><span class="form_headers"> Алгоритмы моделирования</span></legend>
                                    <br>
                                    <p class="methods"><label><input title="Метод Эйлера" type="checkbox" name="Euler"
                                                                     value="a1" <?php echo isset($_POST['Euler']) ? 'checked' : '' ?>>
                                            Метод Эйлера</label><Br>
                                        <label><input title="Улучшеннный метод Эйлера" type="checkbox"
                                                      name="ModernEuler"
                                                      value="a2" <?php echo isset($_POST['ModernEuler']) ? 'checked' : '' ?>>
                                            Усовершенствованный метод Эйлера</label><Br>
                                        <label><input title="Метод Рунге-Кутты" type="checkbox" name="RK"
                                                      value="a3" <?php echo isset($_POST['RK']) ? 'checked' : '' ?>>
                                            Метод Рунге-Кутты</label><Br>
                                        <label><input title="Аналитический метод" type="checkbox" name="Analythic"
                                                      value="a4" <?php echo isset($_POST['Analythic']) ? 'checked' : 'checked' ?>>
                                            Аналитический</label>
                                </fieldset>
                            </div>

                            <div id="first_cond">
                                <fieldset>
                                    <legend><span class="form_headers"> Начальные условия </span></legend>
                                    <br>
                                    <label for="temp">
                                        <math xmlns="http://www.w3.org/1998/Math/MathML">
                                            <mstyle fontfamily="'Open Sans Condensed', sans-serif" displaystyle="true"
                                                    fontstyle="normal" mathsize="20px">
                                                <msub>
                                                    <mi>T</mi>
                                                    <mrow>
                                                        <mn>0</mn>
                                                    </mrow>
                                                </msub>
                                            </mstyle>
                                        </math>
                                    </label>

                                    <input type="number" min="-99999" max="99999" id="temp" name="first_temp" required
                                           value="<?php echo isset($_POST['first_temp']) ? $_POST['first_temp'] : 80 ?>">
                                    <span>°C</span>
                                </fieldset>
                            </div>
                            <div id="model_params">
                                <fieldset>
                                    <legend><span class="form_headers"> Параметры модели</span></legend>
                                    <br>
                                    <label for="air_temp">
                                        <math xmlns="http://www.w3.org/1998/Math/MathML">
                                            <mstyle fontfamily="'Open Sans Condensed', sans-serif" displaystyle="true"
                                                    fontstyle="normal" mathsize="20px">
                                                <msub>
                                                    <mi>T</mi>
                                                    <mrow>
                                                        <mn>воздуха</mn>
                                                    </mrow>
                                                </msub>
                                            </mstyle>
                                        </math>
                                    </label>
                                    <input type="number" min="-99999" max="99999" id="air_temp" name="air_temp" required
                                           value="<?php echo isset($_POST['air_temp']) ? $_POST['air_temp'] : 40 ?>">
                                    <span>°C</span>

                                    <label for="cool_coef">
                                        <math xmlns="http://www.w3.org/1998/Math/MathML">
                                            <mstyle fontfamily="'Open Sans Condensed', sans-serif" displaystyle="true"
                                                    fontstyle="normal" mathsize="20px">
                                                <mi>r</mi>
                                            </mstyle>
                                        </math>
                                    </label>
                                    <input type="number" min="-99999" max="99999" step="0.01" id="cool_coef"
                                           name="cool_coef" required
                                           value="<?php echo isset($_POST['cool_coef']) ? $_POST['cool_coef'] : 0.3 ?>">
                                    <br>
                                    <span class="form_headers ps ps1" >(где r - коэффициент остывания)</span>
                                </fieldset>
                            </div>

                            <div id="scheme_params">
                                <fieldset>
                                    <legend><span class="form_headers"> Параметры схемы</span></legend>
                                    <br>
                                    <label for="interval">
                                        <math xmlns="http://www.w3.org/1998/Math/MathML">
                                            <mstyle fontfamily="'Open Sans Condensed', sans-serif" displaystyle="true"
                                                    fontstyle="normal" mathsize="20px">
                                                <mi>t</mi>
                                            </mstyle>
                                        </math>
                                    </label>
                                    <input type="number" min="-99999" max="99999" id="interval" name="interval" required
                                           value="<?php echo isset($_POST['interval']) ? $_POST['interval'] : 10 ?>">

                                    <label for="step">
                                        <math xmlns="http://www.w3.org/1998/Math/MathML">
                                            <mstyle fontfamily="'Open Sans Condensed', sans-serif" displaystyle="true"
                                                    fontstyle="normal" mathsize="20px">
                                                <mi>с</mi>
                                            </mstyle>
                                        </math>
                                    </label>
                                    <input type="number" min="-99999" max="99999" id="step" name="step" required
                                           value="<?php echo isset($_POST['step']) ? $_POST['step'] : 20 ?>"> <br>
                                    <span class="form_headers ps ps2" >(где t - интервал времени,
                						<br> с - количество шагов)</span>
                                </fieldset>
                            </div>

                            <div id="formula">
                                <math>
                                    <mstyle mathsize="20px" fontstyle="italic">
                                        <mfrac>
                                            <mrow>
                                                <mi>dT</mi>
                                            </mrow>
                                            <mrow>
                                                <mi> dt </mi>
                                            </mrow>
                                        </mfrac>
                                        <mo>=</mo>
                                        <mi>-r</mi>
                                        <!-- <mo style="font-size: 10px">•</mo> -->
                                        <mfenced open="(" close=")" separators=" ">
                                            <mi>T(t)</mi>
                                            <mo>-</mo>
                                            <msub>
                                                <mi>T</mi>
                                                <mrow>
                                                    <mn>воздуха</mn>
                                                </mrow>
                                            </msub>
                                        </mfenced>
                                    </mstyle>
                                </math>
                            </div>

                            <div id="lower">
                                <div id="bottom">
                                    <fieldset id="bottom_form">
                                        <p><strong>Построить в виде:</strong></p> <br>
                                        <label><input title="Построить график" name="share" type="radio" value="graphic" checked>
                                            Графика</label>
                                        <label><input title="Построить таблицу" name="share" type="radio" value="table"> Таблицы</label>
                                        <input type="submit" value="Построить">
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <?php 
            switch($_POST['share']) {
                case 'table':?>
                    <div id="table" class="col-md-6 col-xs-12">
                        <table cellspacing="5px" style="text-align: center">
                        <caption>Результаты моделирования процесса остывания чашки кофе</caption>
                        <thead>
                        <tr>
                            <th rowspan="2">№ шага</th>
                            <th rowspan="2">Время, мин.</th><?php foreach ($json->data as $data) {
                                echo '<th colspan="2">' . $google_translate[array_keys(get_object_vars($data))[0]] . '</th>';
                            } ?></tr>
                        <tr><?php foreach ($json->data as $data) {
                                echo '<th>Температура</th><th>Ошибка</th>';
                            } ?></tr>
                        </thead>
                        <tbody>
                        <?php for ($i = 0; $i < $n; $i++) {
                            echo '<tr>';
                            echo "<td>$i</td>";
                            $time = array_keys(get_object_vars($json->data[0]->$existing_key))[$i];
                            echo "<td>$time</td>";
                            foreach ($json->data as $data) {
                                $key = array_keys(get_object_vars($data))[0];
                                $values = $data->$key->$time;
                                echo '<td>' . $values[0] . '</td><td>' . $values[1] . '</td>';
                            }
                            echo '</tr>';
                        } ?>
                        </tbody>
                    </table>
                    <?php
                    break;
                    case 'graphic':?>
                    <div id="graphic" class="col-md-6 col-xs-12">
                        <input id="args" type="hidden" value="<?php echo htmlspecialchars($argstring); ?>">
                        <div id="chart_div"></div>
                    </div>
                    <?php
                    break;
                    default: ?>
                    <div id="graphic" class="col-md-6 col-xs-12">
                        <input id="args" type="hidden" value="">
                        <div id="chart_div"></div>
                    </div>
                    <?php
                    break;}
                ?>
                </div>
            </div>
     </div>
</div>  
</body>
</html>