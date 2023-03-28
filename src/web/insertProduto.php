<?php
require_once '../../vendor/autoload.php';

header("Content-Type:application/json;charset=utf-8'");

use Daoo\Aula03\model\Produto;

if( !isset($_POST['nome']) ||
    !isset($_POST['descricao']) ||
    !isset($_POST['quantidade']) ||
    !isset($_POST['preco'])) die('Erro: falta de parametros !');

$produto = new Produto( $_POST['nome'],
                        $_POST['descricao'],
                        $_POST['quantidade'],
                        $_POST['preco']);

if($produto->create())
    echo json_encode([
            "success"=>"Produto criado com sucesso!", 
            "data"=>(array)$produto
        ]);
else echo json_encode([
            "error"=>"Erro ao criar produto!"
        ]);
