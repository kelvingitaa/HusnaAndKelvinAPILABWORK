<?php
// OTP.php
class OTP {
    private $db;
    public function __construct(PDO $pdo) { $this->db = $pdo; }

    public function create($userId, $code, DateTime $expires) {
        $stmt = $this->db->prepare("INSERT INTO otp_codes (user_id, code, expires_at) VALUES (:uid,:code,:exp)");
        $stmt->execute([':uid'=>$userId, ':code'=>$code, ':exp'=>$expires->format('Y-m-d H:i:s')]);
        return $this->db->lastInsertId();
    }

    public function validate($userId, $code) {
        $stmt = $this->db->prepare("SELECT * FROM otp_codes WHERE user_id=:uid AND code=:code AND used=0 AND expires_at >= NOW() ORDER BY id DESC LIMIT 1");
        $stmt->execute([':uid'=>$userId, ':code'=>$code]);
        $row = $stmt->fetch();
        if ($row) {
            $u = $this->db->prepare("UPDATE otp_codes SET used=1 WHERE id=:id");
            $u->execute([':id'=>$row['id']]);
            return true;
        }
        return false;
    }
}
