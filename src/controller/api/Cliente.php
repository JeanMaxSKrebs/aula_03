<?php

namespace Daoo\Aula03\controller\api;

use Daoo\Aula03\model\Cliente as ClienteModel;
use Exception;

class Cliente extends Controller
{

	public function __construct()
	{
		$this->setHeader();
		$this->model = new ClienteModel();
		// error_log(print_r($this->model, TRUE));
	}

	public function index()
	{
		echo json_encode($this->model->read());
	}

	public function show($id)
	{
		$cliente = $this->model->read($id);
		if ($cliente) {
			$response = ['cliente' => $cliente];
		} else {
			$response = ['Erro' => "Cliente não encontrado"];
			header('HTTP/1.0 404 Not Found');
		}
		echo json_encode($response);
	}

	public function store()
	{
		try {
			$this->validateClienteRequest();

			$this->model = new ClienteModel(
				$_POST['nome'],
				$_POST['idade'],
				$_POST['cpf'],
			);

			// error_log(print_r($this->model,TRUE));
			// throw new \Exception('LOG');

			if ($this->model->create())
				echo json_encode([
					"success" => "Cliente adicionado com sucesso!",
					"data" => $this->model->getColumns()
				]);
			else throw new Exception("Erro ao adicionar Cliente!");
		} catch (Exception $error) {
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
				throw new Exception("Informe o ID do Cliente!!");
			
			$this->validateClienteRequest();

			$this->model = new ClienteModel(
				$_POST['nome'],
				$_POST['idade'],
				$_POST['cpf'],
			);
			$this->model->id = $_POST["id"];

			// error_log(print_r($this->model,TRUE));
			// throw new \Exception('LOG');

			if ($this->model->update())
				echo json_encode([
					"success" => "Dado(s) do Cliente atualizado(s) com sucesso!",
					"data" => $this->model->getColumns()
				]);
			else throw new Exception("Erro ao atualizar dado(s) do Cliente!");
		} catch (Exception $error) {
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
				throw new Exception('Erro: id obrigatorio!');
			}
			$id = $_POST["id"];
			if ($this->model->delete($id)) {
				$response = ["message:" => "Cliente de id:$id removido com sucesso!"];
			} else {
				$this->setHeader(500,'Internal Error.');
				throw new Exception("Erro ao remover Cliente!");
			}
			echo json_encode($response);
		} catch (Exception $error) {
			echo json_encode([
				"error" => $error->getMessage()
			]);
		}
	}

	private function validateClienteRequest()
	{
		$fields = [
			'nome',
			'idade',
			'cpf'
		];
		if (!$this->validatePostRequest($fields))
			throw new Exception('Erro: campos incompletos!');
	}
}
