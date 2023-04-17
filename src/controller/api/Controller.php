<?php 

namespace Daoo\Aula03\controller\api;

abstract class Controller{

	protected $model;

	public abstract function index();

	protected function setHeader(int $statusCode = 0,string $message = ''){
		if(!$statusCode)
			header("Content-Type:application/json;charset=utf-8'");
		else header("HTTP/1.0 $statusCode $message");
	}

	protected function validatePostRequest(array $fields):bool{
		error_log("Field   fi: " . print_r($fields, TRUE));
		foreach($fields as $field) {
			error_log(print_r($field, TRUE));
			if(array_key_exists($field, $_POST[$field])) {
				error_log("Field" . print_r($fields, TRUE));

			} else if(!isset($_POST[$field])){
				$this->setHeader(400,'Bad Request');
				return false;
			}
		}
		return true;
	}
}