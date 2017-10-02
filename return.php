<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 03.10.2017
 * Time: 6:09
 */

echo json_encode(array(
    'table' => [
        ['Время', 'Температура'],
        ['0', 80],
        ['2', 68.9],
        ['7', 52.8],
        ['10', 47.8]
    ],
    'title' => isset($_POST['title']) ? $_POST['title'] : 'Зависимость по Эйлеру'
));