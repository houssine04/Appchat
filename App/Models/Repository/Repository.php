<?php

namespace App\Models\Repository;

use PDO;
use App\Config;

abstract class Repository
{
    /** @var  PDO */
    protected static $db;

    protected static $tableName;

    protected static function getDbConnection()
    {
        if (!self::$db) {
            self::connect();
        }
        return self::$db;
    }

    protected static function connect()
    {

        $host = Config::DB_HOST;
        $db = Config::DB_NAME;
        $user = Config::DB_USER;
        $pass = Config::DB_PASSWORD;
        $charset = 'utf8mb4';
        $opt = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        self::$db = new PDO($dsn, $user, $pass, $opt);
    }

    public function find($id)
    {
        $db = self::getDbConnection();
        $query = 'SELECT * FROM ' . static::$tableName . ' WHERE id=:id';
        $stmt = $db->prepare($query);
        $stmt->execute(['id' => $id]);

        return $this->hydrate($stmt->fetch());
    }

    public function findBy($criteria = array(), $limit = null, $sortBy = array())
    {
        $db = self::getDbConnection();
        $where = '';
        $i = 0;
        foreach ($criteria as $criterion => $value) {
            if ($i > 0) {
                $where .= ' AND ';
            }
            $where .= sprintf(' %s=:%s ', $criterion, $criterion);
            $i++;

        }
        $orderBy = '';
        $i = 0;
        $countSortBy = count($sortBy);
        foreach ($sortBy as $sort => $order) {
            $orderBy .= sprintf('  %s %s ', $sort, $order);
            $i++;
            if ($i < $countSortBy) {
                $orderBy .= ' , ';
            }

        }
        $query = 'SELECT * FROM ' . static::$tableName;
        if ($criteria) {
            $query .= ' WHERE ' . $where;
        }

        if ($sortBy) {
            $query .= ' ORDER BY ' . $orderBy;
        }

        if ($limit) {
            $query .= 'LIMIT ' . $limit;
        }
        $stmt = $db->prepare($query);
        $stmt->execute($criteria);

        $result = [];
        while ($array = $stmt->fetch()) {
            $result[] = $this->hydrate($array);
        }
        if ($limit == 1) {
            return reset($result);
        }

        return $result;

    }

    public function findAll()
    {

        return $this->findBy();
    }

    abstract function hydrate($array);

}