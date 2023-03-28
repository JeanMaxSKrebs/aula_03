<?php
require_once '../../vendor/autoload.php';
header("Content-Type:application/json;charset=utf-8'");

use Daoo\Aula03\model\Desconto;
use Daoo\Aula03\model\DescontoDao;

if(!isset($_POST['descricao']) || !isset($_POST['taxa']))
    die('Erro: falta de parametros !');


$desconto = new Desconto( $_POST['descricao'],$_POST['taxa']);

$descDao = new DescontoDao($desconto);

if($descDao->create())
    echo json_encode(["success"=>"Desconto cadastrado com sucesso!", "data"=>$desconto->toArray()]);
else
    echo json_encode(["error"=>"Erro ao inserir produto!"]);