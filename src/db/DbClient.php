<?php


namespace zhos\db;


class DbClient
{
    /**
     * @var DbClient
     */
    private static $client;

    /**
     * @var \PDO
     */
    private $pdo;

    private function __construct()
    {
        $options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_CASE => \PDO::CASE_LOWER,
            \PDO::ATTR_ORACLE_NULLS => \PDO::NULL_TO_STRING,
            \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'
        ];
        $this->pdo = new \PDO(DB_DSN, DB_USER, DB_PASS, $options);
    }

    public function fetchOne($field, $table, $where, $params = [])
    {
        
    }

    public function fetchAll($field, $table, $where, $params = [])
    {

    }

    public function insert($table, $data)
    {

    }

    public function update($table, $data, $where, $params = [])
    {

    }

    public function beginTransaction()
    {
        $this->pdo->beginTransaction();
    }

    public function commit()
    {
        $this->pdo->commit();
    }

    public function rollBack()
    {
        $this->pdo->rollBack();
    }

    public function getPdo()
    {
        return $this->pdo;
    }

    public function __destruct()
    {
        $this->pdo = null;
    }

    public static function getInstance()
    {
        if (! self::$client instanceof DbClient) {
            self::$client = new DbClient();
        }

        return self::$client;
    }
}