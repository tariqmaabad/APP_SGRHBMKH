<?php
require_once __DIR__ . '/Model.php';

class Specialite extends Model {
    protected $table = 'specialites';

    public function __construct() {
        parent::__construct();
    }

    public function validate($data) {
        $errors = [];
        
        // Validation nom_specialite
        if (empty($data['nom_specialite'])) {
            $errors['nom_specialite'] = 'Le nom de la spécialité est obligatoire';
        }
        
        return $errors;
    }

    public function findAllSorted() {
        return $this->findAll([], 'nom_specialite ASC');
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (nom_specialite, description, created_at, updated_at) 
                VALUES (:nom_specialite, :description, NOW(), NOW())";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nom_specialite' => $data['nom_specialite'],
            ':description' => $data['description'] ?? null
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE {$this->table} 
                SET nom_specialite = :nom_specialite, 
                    description = :description,
                    updated_at = NOW() 
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':nom_specialite' => $data['nom_specialite'],
            ':description' => $data['description'] ?? null
        ]);
    }

    public function getDetailsWithCount() {
        $sql = "SELECT s.*, COUNT(p.id) as nombre_personnel 
                FROM {$this->table} s
                LEFT JOIN personnel p ON s.id = p.specialite_id
                GROUP BY s.id
                ORDER BY s.nom_specialite";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findDetailById($id) {
        $sql = "SELECT s.*, COUNT(p.id) as nombre_personnel 
                FROM {$this->table} s
                LEFT JOIN personnel p ON s.id = p.specialite_id
                WHERE s.id = :id
                GROUP BY s.id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
