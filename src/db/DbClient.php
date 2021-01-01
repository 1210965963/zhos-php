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
    public function query(string $sql, array $params = [])
    {
        $this->execSql[] = $sql;
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt;
    }

    public function fetchOne(string $field, string $table, string $where, array $params = []) : array
    {
        $sql = 'SELECT ' . $field . ' FROM ' . $table . ' WHERE ' . $where;
        $stmt = $this->query($sql, $params);
        $data = $stmt->fetch();
        $stmt->closeCursor();

        return $data;
    }

    public function fetchAll(string $field, string $table, string $where, array $params = []) : array
    {
        $sql = 'SELECT ' . $field . ' FROM ' . $table . ' WHERE ' . $where;
        $stmt = $this->query($sql, $params);
        $data = $stmt->fetchAll();
        $stmt->closeCursor();

        return $data;
    }

    public function insert(string $table, string $data) : string
    {
        $keys = array_keys($data);
        $sql = 'INSERT INTO ' . $table . '(`'.implode('`,`', $keys).'`) VALUES (:'.implode(',:', $keys).')';
        $stmt = $this->query($sql, $data);
        $stmt->closeCursor();

        return $this->pdo->lastInsertId();
    }

    public function update(string $table, array $data, string $where, array $params = []) : bool
    {
        $attr = [];
        $sql = 'UPDATE ' . $table . ' SET ';
        foreach ($data as $k => $v) {
            $attr[] = $k . '=:'.$k;
        }

        $sql = $sql . implode(',', $attr) . ' WHERE ' . $where;
        $stmt = $this->query($sql, array_merge($data, $params));
        $stmt->closeCursor();

        return true;
    }

    public function beginTransaction() : bool
    {
        return $this->pdo->beginTransaction();
    }

    public function commit() : bool
    {
        return $this->pdo->commit();
    }

    public function rollBack() : bool
    {
        return $this->pdo->rollBack();
    }

    public function getPdo() : \PDO
    {
        return $this->pdo;
    }

    public function __destruct()
    {
        $this->pdo = null;
    }

    public static function getInstance() : DbClient
    {
        if (! self::$client instanceof DbClient) {
            self::$client = new DbClient();
        }

        return self::$client;
    }
}