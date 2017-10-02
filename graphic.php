<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 03.10.2017
 * Time: 3:47
 */

?>

<!DICKTYPE HTML>
<html lang="ru">
<head>
    <meta charset="utf8">
    <title>Представление в виде графика (пример)</title>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages': ['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var myRequest = new XMLHttpRequest();
            myRequest.open('GET', '/return.php');
            myRequest.send();

            myRequest.onreadystatechange = function () {
                if (myRequest.readyState !== 4) {
                    return;
                }

                if (myRequest.status !== 200) {
                    alert(myRequest.status + ': ' + myRequest.statusText);
                } else {
                    var json = JSON.parse(myRequest.responseText);

                    var data = new google.visualization.arrayToDataTable(json.table);

                    var options = {
                        title: json.title,
                        width: 800,
                        height: 600,
                        curveType: 'function',
                        legend: {position: 'bottom'}
                    };

                    var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
                    chart.draw(data, options);
                }
            };
        }
    </script>
</head>
<body>
<div id="chart_div"></div>
</body>
</html>
