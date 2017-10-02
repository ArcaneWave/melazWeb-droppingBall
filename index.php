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
    $argstring .= '-cc=' . $_POST['cool_coef'] . ' -ct=' . $_POST['first_temp'] . ' -et=' . $_POST['air_temp'] . ' -tr=' . $_POST['interval'] . ' -sc=' . $_POST['step'];
    $json .= substr(shell_exec("python scripts/main.py $argstring"), 0, -2) . ']}';
    setcookie('json', $json);

    switch ($_POST['share']) {
        case 'graphic':
            header('Location: /graphic.php');
            break;

        case 'table':
            header('Location: /table.php');
            break;

        default:
            break;
    }
}

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1 plus MathML 2.0//EN" "http://www.w3.org/Math/DTD/mathml2/xhtml-math11-f.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ru">
<head>
	<meta charset="utf-8">
	<!-- <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> -->
	<link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" href="css/animate.css">
	<link rel="stylesheet" href="css/styles.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">
	<script>window.MathJax = { MathML: { extensions: ["mml3.js", "content-mathml.js"]}};</script>
	<script type="text/javascript" async src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.0/MathJax.js?config=MML_HTMLorMML"></script>
</head>
<body>
<form method="post">
    <div id="main_form">

        <div id="container">
            <div id="process_name" align="center"> <span class="form_headers"> Моделирование процесса остывания кофе </span> </div><br> <hr>

            <div id="methods">
                <fieldset>
                    <legend><span class="form_headers"> Алгоритмы моделирования</span></legend>
                    <br>
                    <p><input title="Метод Эйлера" type="checkbox" name="Euler" value="a1" checked>Метод Эйлера<Br>
                        <input title="Усовершенствованный метод Эйлера" type="checkbox" name="ModernEuler"
                               value="a2">Усовершенствованный метод Эйлера<Br>
                        <input title="Метод Рунге-Кутты" type="checkbox" name="RK" value="a3">Метод Рунге-Кутты<Br>
                        <input title="Аналитический метод" type="checkbox" name="Analythic" value="a4">Аналитический<Br>
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

                    <input id="temp" name="first_temp" required value="80">
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
                    <input id="air_temp" name="air_temp" required value="40">
                    <span>°C</span>

                    <label for="cool_coef">
                        <math xmlns="http://www.w3.org/1998/Math/MathML">
                            <mstyle fontfamily="'Open Sans Condensed', sans-serif" displaystyle="true"
                                    fontstyle="normal" mathsize="20px">
                                <mi>r</mi>
                            </mstyle>
                        </math>
                    </label>
                    <input id="cool_coef" name="cool_coef" required value="0.3"> <br>
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
                    <input id="interval" name="interval" required value="10">

                    <label for="step">
                        <math xmlns="http://www.w3.org/1998/Math/MathML">
                            <mstyle fontfamily="'Open Sans Condensed', sans-serif" displaystyle="true"
                                    fontstyle="normal" mathsize="20px">
                                <mi>с</mi>
                            </mstyle>
                        </math>
                    </label>
                    <input id="step" name="step" required value="20"> <br>
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
</body>
</html>