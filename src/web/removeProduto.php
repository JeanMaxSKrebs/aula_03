<?php
require_once '../vendor/autoload.php';

use Daoo\Aula03\model\Produto;

header("Content-Type:application/json");

if(!isset($_POST['id']))
    die('Erro: falta de parametros !');

$prodDao = new Produto();

if($prodDao->delete($_POST['id'])) {
    echo json_encode(['sucesso'=>"Produto $_POST[id] Removido!"]);
}else {
    echo json_encode(['Error'=>"Erro ao inserir produto!"]);
}
