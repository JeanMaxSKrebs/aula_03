<?php

namespace Daoo\Aula03\controller\api;

use Daoo\Aula03\model\Festa as FestaModel;
use Exception;

class Festa extends Controller
{

	public function __construct()
	{
		$this->setHeader();
		$this->model = new FestaModel();
		// error_log(print_r($this->model, TRUE));
	}

	public function index()
	{
		echo json_encode($this->model->read());
	}

	public function show($id)
	{
		$festa = $this->model->read($id);
		if ($festa) {
			$response = ['festa' => $festa];
		} else {
			$response = ['Erro' => "Festa não encontrado"];
			header('HTTP/1.0 404 Not Found');
		}
		echo json_encode($response);
	}

	public function store()
	{
		try {
			$this->validateFestaRequest();

			$this->model = new FestaModel(
				$_POST['nome'],
				$_POST['tipo'],
				$_POST['cpfCliente'],
				$_POST['cnpjSalao'],
				$_POST['data'],
			);

			// error_log(print_r($this->model,TRUE));
			// throw new \Exception('LOG');

			if ($this->model->create())
				echo json_encode([
					"success" => "Festa adicionado com sucesso!",
					"data" => $this->model->getColumns()
				]);
			else
				throw new Exception("Erro ao adicionar Festa!");
		} catch (Exception $error) {
			$this->setHeader(500, 'Erro interno do servidor!!!!');
			echo json_encode([
				"error" => $error->getMessage()
			]);
		}
	}

	public function update()
	{
		try {
			if (!$this->validatePostRequest(['id']))
				throw new Exception("Informe o ID da Festa!!");

			$this->validateFestaRequest();

			$this->model = new FestaModel(
				$_POST['nome'],
				$_POST['tipo'],
				$_POST['cpfCliente'],
				$_POST['cnpjSalao'],
				$_POST['data'],
			);
			$this->model->id = $_POST["id"];

			// error_log(print_r($this->model,TRUE));
			// throw new \Exception('LOG');

			if ($this->model->update())
				echo json_encode([
					"success" => "Dado(s) da Festa atualizado(s) com sucesso!",
					"data" => $this->model->getColumns()
				]);
			else
				throw new Exception("Erro ao atualizar dado(s) da Festa!");
		} catch (Exception $error) {
			echo json_encode([
				"error" => $error->getMessage()
			]);
		}
	}

	public function remove()
	{
		try {
			if (!isset($_POST["id"])) {
				$this->setHeader(400, 'Bad Request.');
				throw new Exception('Erro: id obrigatorio!');
			}
			$id = $_POST["id"];
			if ($this->model->delete($id)) {
				$response = ["message:" => "Festa id:$id removido com sucesso!"];
			} else {
				$this->setHeader(500, 'Internal Error.');
				throw new Exception("Erro ao remover Festa!");
			}
			echo json_encode($response);
		} catch (Exception $error) {
			echo json_encode([
				"error" => $error->getMessage()
			]);
		}
	}

	private function validateFestaRequest()
	{
		$fields = [
			'nome',
			'tipo',
			'cpfCliente',
			'cnpjSalao',
			'data'
		];
		if (!$this->validatePostRequest($fields))
			throw new Exception('Erro: campos incompletos!');
	}
}