<?php
// app/core/Model.php

class Model 
{
    protected PDO $pdo;
    
    public function __construct() 
    {
        $this->pdo = Database::getConnection();
    }
}