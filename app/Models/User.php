<?php
namespace App\Models;

use PDO;

class User extends Model {
    public function findByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($username, $email, $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :hash)");
        return $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':hash' => $hash
        ]);
    }

    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
}
