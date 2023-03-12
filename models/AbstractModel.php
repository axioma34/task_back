<?php

abstract class AbstractModel
{
    protected $conn;
    public function __construct($db)
    {
        $this->conn = $db;
    }
}
