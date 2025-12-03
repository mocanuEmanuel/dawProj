<?php
namespace App\Models;

use App\Config\Database;
use PDO;

abstract class Model {
    protected $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }
}
