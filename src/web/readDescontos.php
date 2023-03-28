<?php
require_once '../../vendor/autoload.php';

use Daoo\Aula03\model\DescontoDao;

header("Content-Type:application/json");

$descDao = new DescontoDao();
if(!isset($_GET['id_desc']))
    echo json_encode($descDao->read());
else
    echo json_encode($descDao->show($_GET['id_desc']));