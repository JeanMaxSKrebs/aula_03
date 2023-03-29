<?php

namespace Daoo\Aula03\model;

use Exception;
use PDO;

class Connection
{
    private static $instance;
    private static $config;

    public static function getConnection():PDO{
        if(!isset(self::$instance)){
            $configFile = file_get_contents(__DIR__.'/config.json');
            self::$config = json_decode($configFile);
            $db = self::$config->db;
            if(!$db)
                throw new Exception("Erro ao ler arquivo de configuração!");   
            
            switch($db->drive){
                case 'mysql':
                    $dsn = "mysql:host=$db->host;"
                        . "dbname=$db->name;"
                        . "charset=$db->charset";
                    $port = $db->port ?? 3306;
                    $dsn.=";port=$port";
                    break;
                case 'pgsql':
                    $dsn = "pgsql:host=$db->host;"
                        . "dbname=$db->name;";
                    $port = $db->port ?? 5432;
                    $dsn.=";port=$port";
                    break;
                default: throw new Exception("Driver $db->drive não suportado!");   
            }

            try{
                self::$instance = new PDO($dsn,$db->user,$db->pass);
            }catch(\PDOException $error){
                error_log(
                    print_r([
                        $error->getMessage(),
                        $error->getTraceAsString()
                    ],true));
                return null;
            }
        }
        return self::$instance;
    }

    public static function getDrive():string{        
        return self::$config->db->drive;
    }
}