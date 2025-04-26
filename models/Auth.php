<?php
class Auth extends Model {
    protected $table = 'users';

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email AND deleted_at IS NULL AND status = 'active'");
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($data) {
        $stmt = $this->db->prepare("INSERT INTO users (email, password, nom, prenom, telephone, adresse, role) 
            VALUES (:email, :password, :nom, :prenom, :telephone, :adresse, :role)");
        
        $stmt->bindValue(':email', $data['email']);
        $stmt->bindValue(':password', $this->hashPassword($data['password']));
        $stmt->bindValue(':nom', $data['nom']);
        $stmt->bindValue(':prenom', $data['prenom']);
        $stmt->bindValue(':telephone', $data['telephone']);
        $stmt->bindValue(':adresse', $data['adresse']);
        $stmt->bindValue(':role', $data['role'] ?? 'user');
        
        return $stmt->execute();
    }

    public function updateUser($userId, $data) {
        $fields = [];
        $values = [':id' => $userId];

        foreach ($data as $key => $value) {
            if ($key !== 'id' && $key !== 'password') {
                $fields[] = "$key = :$key";
                $values[":$key"] = $value;
            }
        }

        if (isset($data['password']) && !empty($data['password'])) {
            $fields[] = "password = :password";
            $values[':password'] = $this->hashPassword($data['password']);
        }

        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }

    public function updateProfile($userId, $data) {
        return $this->updateUser($userId, $data);
    }

    public function updateLastLogin($userId) {
        $stmt = $this->db->prepare("UPDATE users SET derniere_connexion = NOW() WHERE id = :id");
        $stmt->bindValue(':id', $userId);
        return $stmt->execute();
    }

    public function verifyPassword($password, $hashedPassword) {
        return password_verify($password, $hashedPassword);
    }

    public function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function createPasswordResetToken($email) {
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        $stmt = $this->db->prepare("UPDATE users SET reset_token = :token, reset_token_expiry = :expiry 
            WHERE email = :email AND deleted_at IS NULL AND status = 'active'");
        
        $stmt->bindValue(':token', $token);
        $stmt->bindValue(':expiry', $expiry);
        $stmt->bindValue(':email', $email);
        
        if ($stmt->execute()) {
            return $token;
        }
        return false;
    }

    public function verifyResetToken($token) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE reset_token = :token 
            AND reset_token_expiry > NOW() AND deleted_at IS NULL AND status = 'active'");
        $stmt->bindValue(':token', $token);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function resetPassword($token, $newPassword) {
        $user = $this->verifyResetToken($token);
        if (!$user) {
            return false;
        }

        $stmt = $this->db->prepare("UPDATE users SET password = :password, reset_token = NULL, 
            reset_token_expiry = NULL WHERE id = :id");
        
        $stmt->bindValue(':password', $this->hashPassword($newPassword));
        $stmt->bindValue(':id', $user['id']);
        
        return $stmt->execute();
    }

    public function getAllUsers() {
        $stmt = $this->db->prepare("SELECT id, email, nom, prenom, telephone, adresse, role, status, 
            derniere_connexion, created_at, updated_at 
            FROM users 
            WHERE deleted_at IS NULL 
            ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id AND deleted_at IS NULL");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
