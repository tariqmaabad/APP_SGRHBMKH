<?php
require_once __DIR__ . '/Model.php';

class FormationSanitaire extends Model {
    protected $table = 'formations_sanitaires';
    
    public function validate($data) {
        $errors = [];
        
        // Validation nom formation
        if (empty($data['nom_formation'])) {
            $errors['nom_formation'] = 'Le nom de la formation sanitaire est obligatoire';
        }
        
        // Validation type formation
        if (empty($data['type_formation'])) {
            $errors['type_formation'] = 'Le type de formation est obligatoire';
        }
        
        // Validation province
        if (empty($data['province_id'])) {
            $errors['province_id'] = 'La province est obligatoire';
        }
        
        // Validation catégorie
        if (empty($data['categorie_id'])) {
            $errors['categorie_id'] = 'La catégorie est obligatoire';
        }
        
        // Validation milieu
        if (empty($data['milieu'])) {
            $errors['milieu'] = 'Le milieu est obligatoire';
        } elseif (!in_array($data['milieu'], ['URBAIN', 'RURAL'])) {
            $errors['milieu'] = 'Le milieu doit être URBAIN ou RURAL';
        }
        
        return $errors;
    }
    
    public function findByProvince($province_id) {
        try {
            return $this->findAll(['province_id' => $province_id, 'deleted_at' => null], 'nom_formation ASC');
        } catch (\PDOException $e) {
            error_log("Error in findByProvince: " . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération des formations par province");
        }
    }
    
    public function findByCategorie($categorie_id = null) {
        try {
            $sql = "SELECT fs.*, p.nom_province, c.nom_categorie,
                    (SELECT COUNT(*) FROM personnel 
                     WHERE formation_sanitaire_id = fs.id 
                     AND deleted_at IS NULL) as nombre_personnel
                    FROM formations_sanitaires fs
                    JOIN provinces p ON fs.province_id = p.id
                    JOIN categories_etablissements c ON fs.categorie_id = c.id
                    WHERE fs.deleted_at IS NULL";
            
            if ($categorie_id) {
                $sql .= " AND fs.categorie_id = :categorie_id";
            }
            
            $sql .= " ORDER BY p.nom_province, fs.nom_formation";
            
            $stmt = $this->db->prepare($sql);
            if ($categorie_id) {
                $stmt->bindValue(':categorie_id', $categorie_id);
            }
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error in findByCategorie: " . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération des formations par catégorie");
        }
    }
    
    public function findDetailById($id) {
        try {
            $sql = "SELECT fs.*, 
                    p.nom_province,
                    c.nom_categorie,
                    (SELECT COUNT(*) FROM personnel 
                     WHERE formation_sanitaire_id = fs.id 
                     AND deleted_at IS NULL) as nombre_personnel
                    FROM formations_sanitaires fs
                    JOIN provinces p ON fs.province_id = p.id
                    JOIN categories_etablissements c ON fs.categorie_id = c.id
                    WHERE fs.id = :id
                    AND fs.deleted_at IS NULL";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":id", $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error in findDetailById: " . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération des détails de la formation");
        }
    }
    
    public function search($term) {
        try {
            $sql = "SELECT fs.*, p.nom_province, c.nom_categorie
                    FROM formations_sanitaires fs
                    JOIN provinces p ON fs.province_id = p.id
                    JOIN categories_etablissements c ON fs.categorie_id = c.id
                    WHERE fs.deleted_at IS NULL 
                    AND (fs.nom_formation LIKE :term 
                        OR fs.type_formation LIKE :term
                        OR p.nom_province LIKE :term)
                    ORDER BY fs.nom_formation";
                    
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":term", "%$term%");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error in search: " . $e->getMessage());
            throw new \Exception("Erreur lors de la recherche de formations");
        }
    }

    public function count($province_id = null, $categorie_id = null, $milieu = null) {
        try {
            $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE deleted_at IS NULL";
            $params = [];

            if ($province_id) {
                $sql .= " AND province_id = :province_id";
                $params[':province_id'] = $province_id;
            }
            if ($categorie_id) {
                $sql .= " AND categorie_id = :categorie_id";
                $params[':categorie_id'] = $categorie_id;
            }
            if ($milieu) {
                $sql .= " AND milieu = :milieu";
                $params[':milieu'] = $milieu;
            }

            $stmt = $this->db->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();
            return (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
        } catch (\PDOException $e) {
            error_log("Error in count: " . $e->getMessage());
            throw new \Exception("Erreur lors du comptage des formations");
        }
    }

    public function countByMilieu($milieu, $province_id = null, $categorie_id = null) {
        return $this->count($province_id, $categorie_id, $milieu);
    }

    public function getStatsByCategorie($province_id = null, $milieu = null) {
        try {
            $sql = "SELECT c.nom_categorie, COUNT(*) as total,
                    ROUND(COUNT(*) * 100.0 / (
                        SELECT COUNT(*) 
                        FROM formations_sanitaires 
                        WHERE deleted_at IS NULL
                    ), 1) as pourcentage
                    FROM formations_sanitaires fs
                    JOIN categories_etablissements c ON fs.categorie_id = c.id
                    WHERE fs.deleted_at IS NULL";
            $params = [];

            if ($province_id) {
                $sql .= " AND fs.province_id = :province_id";
                $params[':province_id'] = $province_id;
            }
            if ($milieu) {
                $sql .= " AND fs.milieu = :milieu";
                $params[':milieu'] = $milieu;
            }

            $sql .= " GROUP BY c.id, c.nom_categorie";
            $stmt = $this->db->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error in getStatsByCategorie: " . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération des statistiques par catégorie");
        }
    }

    public function getStatsByProvince($categorie_id = null, $milieu = null) {
        try {
            $sql = "SELECT p.nom_province, COUNT(*) as total,
                    ROUND(COUNT(*) * 100.0 / (
                        SELECT COUNT(*) 
                        FROM formations_sanitaires 
                        WHERE deleted_at IS NULL
                    ), 1) as pourcentage
                    FROM formations_sanitaires fs
                    JOIN provinces p ON fs.province_id = p.id
                    WHERE fs.deleted_at IS NULL";
            $params = [];

            if ($categorie_id) {
                $sql .= " AND fs.categorie_id = :categorie_id";
                $params[':categorie_id'] = $categorie_id;
            }
            if ($milieu) {
                $sql .= " AND fs.milieu = :milieu";
                $params[':milieu'] = $milieu;
            }

            $sql .= " GROUP BY p.id, p.nom_province";
            $stmt = $this->db->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error in getStatsByProvince: " . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération des statistiques par province");
        }
    }

    public function getAllWithDetails($province_id = null, $categorie_id = null, $milieu = null) {
        try {
            $sql = "SELECT fs.*, p.nom_province, c.nom_categorie,
                    (SELECT COUNT(*) FROM personnel 
                     WHERE formation_sanitaire_id = fs.id 
                     AND deleted_at IS NULL) as nombre_personnel
                    FROM formations_sanitaires fs
                    JOIN provinces p ON fs.province_id = p.id
                    JOIN categories_etablissements c ON fs.categorie_id = c.id
                    WHERE fs.deleted_at IS NULL";
            $params = [];

            if ($province_id) {
                $sql .= " AND fs.province_id = :province_id";
                $params[':province_id'] = $province_id;
            }
            if ($categorie_id) {
                $sql .= " AND fs.categorie_id = :categorie_id";
                $params[':categorie_id'] = $categorie_id;
            }
            if ($milieu) {
                $sql .= " AND fs.milieu = :milieu";
                $params[':milieu'] = $milieu;
            }

            $sql .= " ORDER BY p.nom_province, fs.nom_formation";
            $stmt = $this->db->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error in getAllWithDetails: " . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération des formations");
        }
    }

    public function getPersonnelList($formation_id) {
        try {
            $sql = "SELECT p.*, g.nom_grade, c.nom_corps, s.nom_specialite
                    FROM personnel p
                    LEFT JOIN grades g ON p.grade_id = g.id
                    LEFT JOIN corps c ON p.corps_id = c.id
                    LEFT JOIN specialites s ON p.specialite_id = s.id
                    WHERE p.formation_sanitaire_id = :formation_id 
                    AND p.deleted_at IS NULL
                    ORDER BY p.nom, p.prenom";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":formation_id", $formation_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error in getPersonnelList: " . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération de la liste du personnel");
        }
    }

    public function getPersonnelStats($formation_id) {
        try {
            $sql = "SELECT 
                    COUNT(*) as nombre_personnel,
                    SUM(CASE WHEN sexe = 'M' THEN 1 ELSE 0 END) as personnel_homme,
                    SUM(CASE WHEN sexe = 'F' THEN 1 ELSE 0 END) as personnel_femme
                    FROM personnel 
                    WHERE formation_sanitaire_id = :formation_id 
                    AND deleted_at IS NULL";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":formation_id", $formation_id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error in getPersonnelStats: " . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération des statistiques du personnel");
        }
    }

    public function delete($id) {
        try {
            $sql = "UPDATE {$this->table} SET deleted_at = NOW() WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (\PDOException $e) {
            error_log("Error in delete: " . $e->getMessage());
            throw new \Exception("Erreur lors de la suppression de la formation");
        }
    }
}
