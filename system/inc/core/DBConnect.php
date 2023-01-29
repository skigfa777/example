<?php

namespace core;

if (!defined('PRFK_SITE_ROOT_DIR')) {
  die();
}

use PDO;

/**
 * DBConnect
 *
 * PDO-коннект с БД
 * @package core
 */
class DBConnect
{

    use Singleton;

    private $cfg = [
        'host' => 'localhost',
        'port' => '3306',
        'dbname' => 'profkontur',
        'charset' => 'utf8',
        'user' => 'root',
        'password' => 'root'
    ];
    private $pdo = null;

    /**
     * @return DBConnect
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new DBConnect();
            self::$instance->initialize();
        }
        return self::$instance->pdo();
    }

    /**
     * @return PDO
     */
    private function pdo()
    {
        return $this->pdo;
    }

    private function initialize()
    {
        $this->pdo = new PDO(
            "mysql:host={$this->cfg['host']};
                port={$this->cfg['port']};
                    dbname={$this->cfg['dbname']};
                        charset={$this->cfg['charset']}",
				$this->cfg['user'],
				$this->cfg['password']);

        $db = static::getInstance();
    }
    
    /**
     * Обновление данных из таблицы БД по значениям fields:
     *  $data = [
     *      'table' => 'tablename', 
     *      'fields' => [
     *          'field1' => 'value1',
     *          'field2' => 'value3',
     *          ...
     *          'fieldN' => 'valueN',
     *      ],
     *      ['byFields' => [
     *          'field1' => 'value1',
     *          'field2' => 'value3',
     *          ...
     *          'fieldN' => 'valueN',
     *      ]]
     *  ];
     * 
     * @param array $data
     * @return boolean
     */
    public static function up($data)
    {
        $db = static::getInstance();
 
        $sqlFieldsArr = [];
        
        foreach (array_keys($data['fields']) as $key) {
            $sqlFieldsArr[] = "`$key` = :$key";
        }
        if (!$sqlFieldsArr) {
            return false;
        }
        $sqlFields = implode(', ', $sqlFieldsArr);

        $sqlByFieldsArr = [];
        
        foreach (array_keys($data['byFields']) as $key) {
            $sqlByFieldsArr['byFields'][] = "`$key` = :$key";
        }
        if (!$sqlByFieldsArr) {
            return false;
        }
        $sqlByFields = implode(' AND ', $sqlByFieldsArr['byFields']);

        $sql = <<<sql
UPDATE `{$data['table']}` SET $sqlFields WHERE $sqlByFields
sql;

        $q = $db->prepare($sql);
        foreach ($data['fields'] as $key => $value) {
            switch (gettype($value)) {
                case 'string':
                    $q->bindValue(":$key", (string) $value, $db::PARAM_STR);
                    break;
                case 'integer':
                    $q->bindValue(":$key", (int) $value, $db::PARAM_INT);
                    break;
                case 'boolean':
                    $q->bindValue(":$key", (bool) $value, $db::PARAM_BOOL);
                    break;
                default:
                    $q->bindValue(":$key", NULL, $db::PARAM_STR);
                    break;
            }
        }
        foreach ($data['byFields'] as $key => $value) {
            switch (gettype($value)) {
                case 'string':
                    $q->bindValue(":$key", (string) $value, $db::PARAM_STR);
                    break;
                case 'integer':
                    $q->bindValue(":$key", (int) $value, $db::PARAM_INT);
                    break;
                case 'boolean':
                    $q->bindValue(":$key", (bool) $value, $db::PARAM_BOOL);
                    break;
                default:
                    $q->bindValue(":$key", NULL, $db::PARAM_STR);
                    break;
            }
        }
        return $q->execute() ? $q->rowCount() : false;
    }     
    
 /**
     * Вставка в данных в таблицу БД:
     *  $data = [
     *      'table' => 'tablename', 
     *      'fields' => [
     *          'field1' => 'value1',
     *          'field2' => 'value3',
     *          ...
     *          'fieldN' => 'valueN',
     *      ]
     *  ];
     * 
     * @param array $data
     * @return mixed
     */
    public static function insert($data)
    {
        $db = static::getInstance();
 
        $sqlFieldsArr = [];
        
        foreach (array_keys($data['fields']) as $key) {
            $sqlFieldsArr['fields'][] = "`$key`";
            $sqlFieldsArr['values'][] = ":$key";
        }
        if (!$sqlFieldsArr) {
            return false;
        }
        $sqlFields = implode(', ', $sqlFieldsArr['fields']);
        $sqlValues = implode(', ', $sqlFieldsArr['values']);

        $sql = <<<sql
INSERT INTO `{$data['table']}` ($sqlFields) VALUES ($sqlValues)
sql;
        $q = $db->prepare($sql);  
        foreach ($data['fields'] as $key => $value) {
            switch (gettype($value)) {
                case 'string':
                    $q->bindValue(":$key", (string) $value, $db::PARAM_STR);
                    break;
                case 'integer':
                    $q->bindValue(":$key", (int) $value, $db::PARAM_INT);
                    break;
                case 'boolean':
                    $q->bindValue(":$key", (bool) $value, $db::PARAM_BOOL);
                    break;
                default:
                    $q->bindValue(":$key", NULL, $db::PARAM_STR);
                    break;
            }
        }
        return $q->execute() ? $db->lastInsertId() : false;
    }

    //...
}
