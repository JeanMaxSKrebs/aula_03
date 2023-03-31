<?php

namespace Daoo\Aula03\controller\api;

use Daoo\Aula03\model\Salao as SalaoModel;
use Exception;

class Salao extends Controller
{

	public function __construct()
	{
		$this->setHeader();
		$this->model = new SalaoModel();
		// error_log(print_r($this->model, TRUE));
	}

	public function index()
	{
		echo json_encode($this->model->read());
	}

	public function filter()
	{
		try {
			$this->validateFilter();
			error_log("teste");
			
			$this->model = new SalaoModel(
				$_POST['key'],
				$_POST['value'],
			);
			
			$array = [$_POST['key'] => $_POST['value']];

			$salao = $this->model->filter($array);
			if ($salao) {
				

				echo json_encode(["success" => "Filtrado com sucesso!", 
									"data" => $salao]);
			}
			else throw new Exception("Erro ao filtrar Salão!");
			

		} catch (Exception $error) {
			$this->setHeader(500,'Erro interno do servidor!!!!');
			echo json_encode([
				"error" => $error->getMessage()
			]);
		}

	}

	public function show($id)
	{
		$salao = $this->model->read($id);
		if ($salao) {
			$response = ['salao' => $salao];
		} else {
			$response = ['Erro' => "Salão não encontrado"];
			header('HTTP/1.0 404 Not Found');
		}
		echo json_encode($response);
	}

	public function store()
	{
		try {
			$this->validateSalaoRequest();

			$this->model = new SalaoModel(
				$_POST['nome'],
				$_POST['descricao'],
				$_POST['localizacao'],
				$_POST['cnpj'],
			);

			// error_log(print_r($this->model,TRUE));
			// throw new \Exception('LOG');

			if ($this->model->create())
				echo json_encode([
					"success" => "Salão adicionado com sucesso!",
					"data" => $this->model->getColumns()
				]);
			else throw new Exception("Erro ao adicionar Salão!");
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
				throw new Exception("Informe o ID do Salão!!");
			
			$this->validateSalaoRequest();

			$this->model = new SalaoModel(
				$_POST['nome'],
				$_POST['descricao'],
				$_POST['localizacao'],
				$_POST['cnpj'],
			);
			$this->model->id = $_POST["id"];

			// error_log(print_r($this->model,TRUE));
			// throw new \Exception('LOG');

			if ($this->model->update())
				echo json_encode([
					"success" => "Dado(s) do Salão atualizado(s) com sucesso!",
					"data" => $this->model->getColumns()
				]);
			else throw new Exception("Erro ao atualizar dado(s) do Salão!");
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
				$response = ["message:" => "Salão de id:$id removido com sucesso!"];
			} else {
				$this->setHeader(500,'Internal Error.');
				throw new Exception("Erro ao remover Salão!");
			}
			echo json_encode($response);
		} catch (Exception $error) {
			echo json_encode([
				"error" => $error->getMessage()
			]);
		}
	}

	private function validateSalaoRequest()
	{
		$fields = [
			'nome',
			'descricao',
			'localizacao',
			'cnpj'
		];
		if (!$this->validatePostRequest($fields))
			throw new Exception('Erro: campos incompletos!');
	}

	private function validateFilter()
	{
		$fields = [
			'key',
			'value'
		];
		if (!$this->validatePostRequest($fields))
			throw new Exception('Erro: campos incompletos!');
	}
}
