<?php

namespace Daoo\Aula03\model;

use Exception;
use PDO;

class ORM
{
    protected $conn;    //connection

    protected $table;   //tableName
    protected $primary; //primary Key
    protected $columns; //columnNames
    protected $params;  //:columnNames
    protected $updated; //set columnNames=:columnNames
    protected $values;  //array values
    protected $filters; //like
    private $delimiter; //Delmitadores de campo
    protected const FETCH = PDO::FETCH_ASSOC;

    public function __construct()
    {
        $this->conn = Connection::getConnection();
        $this->resetMappers();
        $this->delimiter = '`';
        if(Connection::getDrive()=='pgsql')
            $this->delimiter = "\"";
    }

    private function resetMappers(){
        $this->filters = '';
        $this->columns = '';
        $this->params = '';
        $this->updated = '';
        $this->values = [];
    }

    protected function mapColumns(iDAO $daoInterface)
    {
        if(count($this->values))
            $this->resetMappers();

        if (isset($daoInterface)) {
            foreach ($daoInterface->getColumns() as $key => $value) {
                $this->params .= " :$key,";
                $this->columns .= " $key,";
                $this->values[":$key"] = is_bool($value) ? (int)$value : $value;
                $this->updated .= $this->delimite($key)." = :$key,";//POSTGRE
            }
            $this->params = substr($this->params, 0, strlen($this->params) - 1);
            $this->columns = substr($this->columns, 0, strlen($this->columns) - 1);
            $this->updated = substr($this->updated, 0, strlen($this->updated) - 1);
        }
    }


    protected function setWhere($arrayWhere)
    {
       
        $this->filters = '';
        $this->values = [];

		// print_r($_POST);

        // foreach($_POST as $field => $value)
        // {
        //     $arrayKey[] = $field;
        //     $arrayValue[] = $value;
            
        //     // echo json_encode($field);
        //     // echo json_encode($value);
            
        //     $array[$field] = $value;
        // }

        // print_r($arrayKey);
        // print_r($arrayValue);
        // print_r($array);

        $firstKey  = array_key_first($arrayWhere);
        $firstValue = array_shift($arrayWhere);
        
        $compareOperator = 'like';
        if(Connection::getDrive()=='pgsql')
            $compareOperator = 'ilike';

        $this->filters .= $this->delimite($firstKey)." $compareOperator :$firstKey";

        $this->values[":$firstKey"] = "%$firstValue%" ;
    
        foreach ($arrayWhere as $key => $value) {
            error_log("ERRO: " . print_r($this->filters, TRUE));

            $this->filters .= " AND ".$this->delimite($key)." $compareOperator  :$key";
            $this->values[":$key"] = "%$value%";
        }
    }


    protected function setFilters($arrayFilter)
    {
       
        $this->filters = '';
        $this->values = [];

        $firstKey  = array_key_first($arrayFilter);
        $firstValue = array_shift($arrayFilter);
        
        $compareOperator = 'like';
        if(Connection::getDrive()=='pgsql')
            $compareOperator = 'ilike';

        $this->filters .= $this->delimite($firstKey)." $compareOperator :$firstKey";
        $this->values[":$firstKey"] = "%$firstValue%";
    
        foreach ($arrayFilter as $key => $value) {
            $this->filters .= " AND ".$this->delimite($key)." $compareOperator  :$key";
            $this->values[":$key"] = "%$value%";
        }
    }

    protected function select(array $columns=[])
    {
        $selectedColumns = '';
        foreach($columns as $column)
            $selectedColumns .=", $column";

        if(!$columns)
            $selectedColumns = "*";
        
        $sql = "SELECT $selectedColumns FROM $this->table";
        $prepStmt = $this->conn->prepare($sql);

        if ($prepStmt->execute()) {
            $this->dumpQuery($prepStmt);
            return $prepStmt->fetchAll(self::FETCH);
        }else{
            throw new Exception("Erro no select!");
        }
    }

    protected function selectById($id)
    {
        $sql = "SELECT * FROM $this->table WHERE $this->primary = :id";
        $prepStmt = $this->conn->prepare($sql);
        $prepStmt->bindValue(':id', $id);

        if (!$prepStmt->execute())
            throw new Exception("Erro no select!");
        
        $this->dumpQuery($prepStmt);
        return $prepStmt->fetchAll(self::FETCH);
    }

    protected function executeTransaction($sqlCommands, $parameters, $useLastId = false)
    {
        try {
            $this->conn->beginTransaction();
            //implementar   
        } catch (\PDOException $error) {
            var_dump([$error->getMessage(), $error->getTraceAsString()]);
            $this->conn->rollBack();
            unset($this->conn);
            return false;
        }
    }

    protected function dumpQuery($prepStatement)
    {
        ob_start();
        $prepStatement->debugDumpParams();
        error_log(ob_get_contents());
        ob_end_clean();
    }

    protected function lastId(){
        if(Connection::getDrive()=='pgsql'){
            $sequenceName = $this->table."_".$this->primary."_seq";
            return $this->conn->lastInsertId($sequenceName);
        }
        return $this->conn->lastInsertId();
    }

    private function delimite($field){
        return $this->delimiter.$field.$this->delimiter;
    }
}