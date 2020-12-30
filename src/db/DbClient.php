<?php


namespace zhos\db;


class DbClient
{
    /**
     * @var DbClient
     */
    private static $client;

    private $execSql = [];

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
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'
        ];
        $this->pdo = new \PDO(DB_DSN, DB_USER, DB_PASS, $options);
    }

    /**
     * @param string $sql
     * @param  array $params
     *
     * @return \PDOStatement
     */
    public function query($sql, $params = [])
    {
        $this->execSql[] = $sql;
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt;
    }

    public function fetchOne($field, $table, $where, $params = [])
    {
        $sql = 'SELECT ' . $field . ' FROM ' . $table . ' WHERE ' . $where;
        $stmt = $this->query($sql, $params);
        $data = $stmt->fetch();
        $stmt->closeCursor();

        return $data;
    }

    public function fetchAll($field, $table, $where, $params = [])
    {
        $sql = 'SELECT ' . $field . ' FROM ' . $table . ' WHERE ' . $where;
        $stmt = $this->query($sql, $params);
        $data = $stmt->fetchAll();
        $stmt->closeCursor();

        return $data;
    }

    public function insert($table, $data)
    {
        $keys = array_keys($data);
        $sql = 'INSERT INTO ' . $table . '(`'.implode('`,`', $keys).'`) VALUES (:'.implode(',:', $keys).')';
        $stmt = $this->query($sql, $data);
        $stmt->closeCursor();

        return $this->pdo->lastInsertId();
    }

    public function update($table, $data, $where, $params = [])
    {
        $attr = [];
        $sql = 'UPDATE ' . $table . ' SET ';
        foreach ($data as $k => $v) {
            $attr[] = $k . '=:'.$k;
        }

        $sql = $sql . implode(',', $attr) . $where;
        $stmt = $this->query($sql, $params);
        $stmt->closeCursor();

        return true;
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
        print_r($this->execSql);
    }

    public static function getInstance()
    {
        if (! self::$client instanceof DbClient) {
            self::$client = new DbClient();
        }

        return self::$client;
    }
}