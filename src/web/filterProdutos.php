<?php
require_once '../../vendor/autoload.php';

use Daoo\Aula03\model\Produto;

header("Content-Type:application/json");

$prodDao = new Produto();

if(!isset($_GET) || !sizeof($_GET))
    die("Filtros vazios!");
echo json_encode($prodDao->filter($_GET));