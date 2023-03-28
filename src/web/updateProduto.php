<?php
require_once '../../vendor/autoload.php';

use Daoo\Aula03\model\Produto;

header("Content-Type:application/json");

if (!isset($_POST['id']) || !sizeof($_POST) > 1)
    die('Erro: falta de parametros !');

$produto = new Produto(
    $_POST['nome'],
    $_POST['descricao'],
    $_POST['qtd_estoque'],
    $_POST['preco']
);

$produto->id = $_POST['id'];

if($produto->update()) {
    echo json_encode(
        [
            'sucesso' => "Produto Atualizado!",
            "produto" => $produto->mapColumns()
        ]
    );
} else {
    echo json_encode(['Error' => "Erro ao inserir produto!"]);
}
