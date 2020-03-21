<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 08/02/18
 * Time: 21:43
 */

namespace Umbrella\CoreBundle\Services;

/**
 * Class UmbrellaRedis
 */
class UmbrellaRedis
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $port;

    /**
     * @var integer
     */
    private $db;

    /**
     * @var \Redis
     */
    private $conn;

    /**
     * UmbrellaRedis constructor.
     */
    public function __construct()
    {
    }

    public function loadConfig(array $config)
    {
        $this->host = trim($config['host']);
        $this->port = trim($config['port']);
        $this->db = trim($config['db']);
    }

    /**
     * @return \Redis
     */
    private function openConn()
    {
        $connection = new \Redis();
        $connection->connect($this->host, $this->port);
        $connection->select($this->db);
        return $connection;
    }

    /**
     * @return \Redis
     */
    public function getConn()
    {
        if ($this->conn == null) {
            $this->conn = $this->openConn();
        }
        return $this->conn;
    }


}