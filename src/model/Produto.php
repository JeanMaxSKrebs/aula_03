<?php

namespace Daoo\Aula03\model;

use Exception;

class Produto extends ORM implements iDAO
{
    private $id, $nome, $descricao,
        $quantidadeEstoque, $preco, $importado;

    public function __construct(
        $nome = '',
        $descricao = '',
        $quantidade = 0,
        $preco = 0,
        $importado = false
    ) {
        parent::__construct();

        $this->table = 'produtos';
        $this->primary = 'id_prod';
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->quantidadeEstoque = $quantidade;
        $this->preco = $preco;
        $this->importado = $importado;
        $this->mapColumns($this);
    }

    public function read($id = null)
    {
        try {
            if ($id) {
                return $this->selectById($id);
            }
            return $this->select([]);
        } catch (Exception $error) {
            error_log("ERRO: " . print_r($error, TRUE));
            throw new Exception($error->getMessage());
        }
    }

    public function create()
    {
        try {
            $sql = "INSERT INTO $this->table ($this->columns) "
                . "VALUES ($this->params)";

            $prepStmt = $this->conn->prepare($sql);
            $result = $prepStmt->execute($this->values);
            
            if(!$result || $prepStmt->rowCount() != 1)
                throw new Exception("Erro ao inserir produto!!");

            $this->id = $this->conn->lastInsertId();
            $this->dumpQuery($prepStmt);
            return true;
        } catch (Exception $error) {
            error_log("ERRO: " . print_r($error, TRUE));
            $prepStmt ?? $this->dumpQuery($prepStmt);
            return false;
        }
    }

    public function update()
    {
        try {
            $this->values[':id'] = $this->id;
            $sql = "UPDATE $this->table SET $this->updated  WHERE id_prod = :id";
            $prepStmt = $this->conn->prepare($sql);
            $prepStmt->bindValue(':importado', $this->importado);
            if ($prepStmt->execute($this->values)) {
                $this->dumpQuery($prepStmt);
                return $prepStmt->rowCount() > 0;
            }
        } catch (Exception $error) {
            error_log("ERRO: " . print_r($error, TRUE));
            $this->dumpQuery($prepStmt);
            return false;
        }
    }

    public function delete($id)
    {
        $sql = "DELETE FROM $this->table WHERE id_prod = :id";
        $prepStmt = $this->conn->prepare($sql);
        if ($prepStmt->execute([':id' => $id]))
            return $prepStmt->rowCount() > 0;
        else return false;
    }

    public function filter($arrayFilter)
    {
        try {
            if (!sizeof($arrayFilter))
                throw new Exception("Filtros vazios!");
            $this->setFilters($arrayFilter);
            $sql = "SELECT * FROM produtos WHERE $this->filters";
            $prepStmt = $this->conn->prepare($sql);
            if (!$prepStmt->execute($this->values))
                return false;
            $this->dumpQuery($prepStmt);
            return $prepStmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (Exception $error) {
            error_log("ERRO: " . print_r($error, TRUE));
            if(isset($prepStmt))
                $this->dumpQuery($prepStmt);
            throw new Exception($error->getMessage());
        }
    }

    public function getColumns(): array
    {
        $columns = [
            "nome" => $this->nome,
            "descricao" => $this->descricao,
            "qtd_estoque" => $this->quantidadeEstoque,
            "preco" => $this->preco,
            "importado" => $this->importado
        ];
        if($this->id) $columns['id_prod']=$this->id;
        return $columns;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
        if ($name != 'id') $this->mapColumns($this);
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function insertProdWithDesc($array_ids_desc)
    {
        try{
            $this->conn->beginTransaction();
            if(!$this->create())
                throw new \PDOException("Erro ao inserir Produto!!!");
            $this->id = $this->lastId();
            $sql = "INSERT INTO prod_desc (id_prod, id_desc) 
                    VALUES (:new_prod_id, :id_desc)";
            $prepStmt = $this->conn->prepare($sql);
            foreach($array_ids_desc as $id_desc){
                $params = [':new_prod_id'=>$this->id, ':id_desc'=>$id_desc];
                if(!$prepStmt->execute($params)){
                    error_log(print_r($params,true));
                    throw new \PDOException("ERRO: ".$prepStmt->errorCode());
                }
                $this->dumpQuery($prepStmt);
            }
            $this->conn->commit();
            return true;
        }catch(\PDOException $error){
            $this->id = null;
            error_log($error->getMessage());
            $prepStmt ?? $this->dumpQuery($prepStmt);
            $this->conn->rollBack();
            return false;
        }
    }
}