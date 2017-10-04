<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 03.10.2017
 * Time: 6:09
 */

echo shell_exec('python scripts/main.py -gc ' . $_GET['args']);