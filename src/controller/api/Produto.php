<?php

namespace Daoo\Aula03\controller\api;

use Daoo\Aula03\model\Produto as ProdutoModel;
use Exception;

class Produto extends Controller
{

	public function __construct()
	{
		$this->setHeader();
		$this->model = new ProdutoModel();
		// error_log(print_r($this->model, TRUE));
	}

	public function index()
	{
		echo json_encode($this->model->read());
	}

	public function show($id)
	{
		$produto = $this->model->read($id);
		if ($produto) {
			$response = ['produto' => $produto];
		} else {
			$response = ['Erro' => "Produto não encontrado"];
			header('HTTP/1.0 404 Not Found');
		}
		echo json_encode($response);
	}

	public function store()
	{
		try {
			$this->validateProdutoRequest();

			$this->model = new ProdutoModel(
				$_POST['nome'],
				$_POST['descricao'],
				$_POST['quantidade'],
				$_POST['preco']
			);

			$this->model->importado = isset($_POST['importado']);

			// error_log(print_r($this->model,TRUE));
			// throw new \Exception('LOG');

			if ($this->model->create())
				echo json_encode([
					"success" => "Produto criado com sucesso!",
					"data" => $this->model->getColumns()
				]);
			else throw new \Exception("Erro ao criar produto!");
		} catch (\Exception $error) {
			$this->setHeader(500,'Erro interno do servidor!!!!');
			echo json_encode([
				"error" => $error->getMessage()
			]);
		}
	}

	public function update()
	{
		try {
			if(!$this->validatePostRequest(['id']))
				throw new Exception("Informe o ID do Produto!!");
			
			$this->validateProdutoRequest();

			$this->model = new ProdutoModel(
				$_POST['nome'],
				$_POST['descricao'],
				$_POST['quantidade'],
				$_POST['preco']
			);
			$this->model->id = $_POST["id"];
			$this->model->importado = isset($_POST['importado']);

			// error_log(print_r($this->model,TRUE));
			// throw new \Exception('LOG');

			if ($this->model->update())
				echo json_encode([
					"success" => "Produto atualizado com sucesso!",
					"data" => $this->model->getColumns()
				]);
			else throw new \Exception("Erro ao criar produto!");
		} catch (\Exception $error) {
			echo json_encode([
				"error" => $error->getMessage()
			]);
		}
	}

	public function remove()
	{
		try {
			if (!isset($_POST["id"])){
				$this->setHeader(400,'Bad Request.');
				throw new \Exception('Erro: id obrigatorio!');
			}
			$id = $_POST["id"];
			if ($this->model->delete($id)) {
				$response = ["message:" => "Produto id:$id removido com sucesso!"];
			} else {
				$this->setHeader(500,'Internal Error.');
				throw new Exception("Erro ao remover Produto!");
			}
			echo json_encode($response);
		} catch (\Exception $error) {
			echo json_encode([
				"error" => $error->getMessage()
			]);
		}
	}

	private function validateProdutoRequest()
	{
		$fields = [
			'nome',
			'descricao',
			'quantidade',
			'preco'
		];
		if (!$this->validatePostRequest($fields))
			throw new \Exception('Erro: campos imcompletos!');
	}
}
