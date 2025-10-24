<?php
// Goods.php
class Goods {
    private $db;
    public function __construct(PDO $pdo) { $this->db = $pdo; }
    public function findAll() {
        $stmt = $this->db->query("SELECT g.*, u.first_name, u.last_name FROM goods g LEFT JOIN users u ON g.created_by = u.id ORDER BY g.created_at DESC");
        return $stmt->fetchAll();
    }
}
