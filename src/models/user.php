<?php
// User.php
class User {
    private $db;

    public function __construct(PDO $pdo) {
        $this->db = $pdo;
    }

    public function create(array $data) {
        $sql = "INSERT INTO users (first_name,last_name,email,phone,password_hash) VALUES (:fn,:ln,:email,:phone,:pwd)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
          ':fn'=>$data['first_name'],
          ':ln'=>$data['last_name'],
          ':email'=>$data['email'],
          ':phone'=>$data['phone'] ?? null,
          ':pwd'=>password_hash($data['password'], PASSWORD_DEFAULT)
        ]);
        return $this->db->lastInsertId();
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email'=>$email]);
        return $stmt->fetch();
    }

    public function findAll() {
        $stmt = $this->db->query("SELECT id,first_name,last_name,email,phone,created_at FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id=:id");
        $stmt->execute([':id'=>$id]);
        return $stmt->fetch();
    }
}
