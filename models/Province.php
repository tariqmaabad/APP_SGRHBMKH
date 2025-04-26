<?php
class Province extends Model {
    public function __construct() {
        parent::__construct();
        $this->table = 'provinces';
    }

    public function validate($data) {
        $errors = [];
        
        if (empty($data['nom_province'])) {
            $errors['nom_province'] = 'Le nom de la province est obligatoire';
        }
        
        return $errors;
    }

    public function findAllSorted() {
        try {
            $sql = "SELECT p.*, 
                    COUNT(DISTINCT fs.id) as nombre_formations,
                    (SELECT COUNT(*) FROM personnel pe 
                     JOIN formations_sanitaires fs2 ON pe.formation_sanitaire_id = fs2.id 
                     WHERE fs2.province_id = p.id 
                     AND pe.deleted_at IS NULL) as nombre_personnel
                    FROM {$this->table} p
                    LEFT JOIN formations_sanitaires fs ON p.id = fs.province_id AND fs.deleted_at IS NULL
                    WHERE p.deleted_at IS NULL
                    GROUP BY p.id
                    ORDER BY p.nom_province ASC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error in findAllSorted: " . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération des provinces");
        }
    }

    public function findDetailById($id) {
        try {
            $sql = "SELECT p.*, 
                    COUNT(DISTINCT fs.id) as nombre_formations,
                    (SELECT COUNT(*) FROM personnel pe 
                     JOIN formations_sanitaires fs2 ON pe.formation_sanitaire_id = fs2.id 
                     WHERE fs2.province_id = p.id 
                     AND pe.deleted_at IS NULL) as nombre_personnel
                    FROM {$this->table} p
                    LEFT JOIN formations_sanitaires fs ON p.id = fs.province_id AND fs.deleted_at IS NULL
                    WHERE p.id = :id
                    AND p.deleted_at IS NULL
                    GROUP BY p.id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":id", $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error in findDetailById: " . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération des détails de la province");
        }
    }

    public function create($data) {
        try {
            $sql = "INSERT INTO {$this->table} (nom_province, created_at, updated_at) 
                    VALUES (:nom_province, NOW(), NOW())";
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                ':nom_province' => $data['nom_province']
            ]);

            if (!$result) {
                throw new \Exception("Erreur lors de la création de la province");
            }

            return $this->db->lastInsertId();
        } catch (\PDOException $e) {
            error_log("Error in create: " . $e->getMessage());
            throw new \Exception("Erreur lors de la création de la province");
        }
    }

    public function update($id, $data) {
        try {
            $sql = "UPDATE {$this->table} 
                    SET nom_province = :nom_province,
                        updated_at = NOW() 
                    WHERE id = :id
                    AND deleted_at IS NULL";
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                ':id' => $id,
                ':nom_province' => $data['nom_province']
            ]);

            if (!$result) {
                throw new \Exception("Erreur lors de la mise à jour de la province");
            }

            return true;
        } catch (\PDOException $e) {
            error_log("Error in update: " . $e->getMessage());
            throw new \Exception("Erreur lors de la mise à jour de la province");
        }
    }

    public function delete($id) {
        try {
            $sql = "UPDATE {$this->table} SET deleted_at = NOW() WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (\PDOException $e) {
            error_log("Error in delete: " . $e->getMessage());
            throw new \Exception("Erreur lors de la suppression de la province");
        }
    }

    public function getStatsByType($type = null) {
        try {
            $sql = "SELECT p.*, 
                    COUNT(DISTINCT fs.id) as nombre_formations,
                    COUNT(DISTINCT CASE WHEN pe.deleted_at IS NULL THEN pe.id END) as nombre_personnel
                    FROM {$this->table} p
                    LEFT JOIN formations_sanitaires fs ON p.id = fs.province_id AND fs.deleted_at IS NULL
                    LEFT JOIN personnel pe ON fs.id = pe.formation_sanitaire_id
                    WHERE p.deleted_at IS NULL";
            
            $params = [];
            if ($type) {
                $sql .= " AND fs.type_formation = :type";
                $params[':type'] = $type;
            }
            
            $sql .= " GROUP BY p.id ORDER BY p.nom_province";
            
            $stmt = $this->db->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error in getStatsByType: " . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération des statistiques par type");
        }
    }
}
