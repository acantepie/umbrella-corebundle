<?php

namespace Umbrella\CoreBundle\Component\Redis;

/**
 * Class RedisClient
 */
class RedisClient
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
     * @var int
     */
    private $db;

    /**
     * @var \Redis
     */
    private $conn;

    public function loadConfig(array $config) : void
    {
        $this->host = trim($config['host']);
        $this->port = trim($config['port']);
        $this->db = trim($config['db']);
    }

    public function getConn() : \Redis
    {
        if (null == $this->conn) {
            $this->conn = $this->openConn();
        }

        return $this->conn;
    }

    private function openConn() : \Redis
    {
        $connection = new \Redis();
        $connection->connect($this->host, $this->port);
        $connection->select($this->db);

        return $connection;
    }
}