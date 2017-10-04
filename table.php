<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 03.10.2017
 * Time: 3:46
 */

$json = json_decode(file_get_contents('data.json'));

$google_translate = array(
    'Euler' => 'Метод Эйлера',
    'Analytical' => 'Аналитический метод',
    'Euler Enhanced' => 'Улучшенный метод Эйлера',
    'RK4' => 'Метод Рунге-Кутты 4 порядка'
);

$existing_key = array_keys(get_object_vars($json->data[0]))[0];
$n = count(array_keys(get_object_vars($json->data[0]->$existing_key)));

?>

<!DOCTYPE HTML>
<html lang="ru">
<head>
    <meta charset="utf8">
    <title>Таблица результатов</title>
</head>
<body>
<table cellspacing="5px" style="text-align: center">
    <caption>Результаты моделирования процесса остывания чашки кофе</caption>
    <thead>
    <tr>
        <th rowspan="2">№ шага</th>
        <th rowspan="2">Время, сек.</th><?php foreach ($json->data as $data) {
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
            echo '<td>' . $data->$key->$time . '</td><td>0</td>';
        }
        echo '</tr>';
    } ?>
    </tbody>
</table>
</body>
</html>