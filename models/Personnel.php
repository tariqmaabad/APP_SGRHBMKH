<?php
require_once __DIR__ . '/Model.php';
class Personnel extends Model {
    protected $table = 'personnel';

    public function create($data) {
        try {
            error_log("Starting Personnel create process");
            error_log("Validating data: " . print_r($data, true));

            // Check for existing PPR or CIN
            $stmt = $this->db->prepare("SELECT id FROM {$this->table} WHERE (ppr = :ppr OR cin = :cin) AND deleted_at IS NULL");
            $stmt->bindValue(':ppr', $data['ppr']);
            $stmt->bindValue(':cin', $data['cin']);
            $stmt->execute();
            
            if ($stmt->fetch()) {
                error_log("Validation failed: PPR or CIN already exists");
                throw new PDOException("Le PPR ou le CIN existe déjà");
            }

            // Remove any empty optional fields
            foreach (['corps_id', 'grade_id', 'specialite_id', 'formation_sanitaire_id'] as $field) {
                if (isset($data[$field]) && $data[$field] === '') {
                    error_log("Removing empty optional field: " . $field);
                    unset($data[$field]);
                }
            }

            error_log("Cleaned data before create: " . print_r($data, true));
            
            // Create the record
            $result = parent::create($data);
            if ($result) {
                error_log("Personnel created successfully with ID: " . $result);
            } else {
                error_log("Personnel creation failed");
            }
            return $result;

        } catch (PDOException $e) {
            error_log("Error creating personnel: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            throw new PDOException("Erreur lors de la création du personnel: " . $e->getMessage());
        }
    }

    public function validate($data) {
        error_log("Validating personnel data: " . print_r($data, true));
        
        $errors = [];

        // Required fields
        $required_fields = [
            'ppr' => 'PPR',
            'cin' => 'CIN',
            'nom' => 'Nom',
            'prenom' => 'Prénom',
            'date_naissance' => 'Date de naissance',
            'sexe' => 'Sexe',
            'situation_familiale' => 'Situation familiale',
            'date_recrutement' => 'Date de recrutement',
            'date_prise_service' => 'Date de prise de service'
        ];

        foreach ($required_fields as $field => $label) {
            if (empty($data[$field])) {
                error_log("Validation error: $label is required");
                $errors[$field] = "Le champ $label est obligatoire";
            }
        }

        // Date validations
        if (!empty($data['date_naissance'])) {
            $birth_date = new DateTime($data['date_naissance']);
            $today = new DateTime();
            $age = $birth_date->diff($today)->y;
            
            if ($age < 18) {
                error_log("Validation error: Invalid birth date (age < 18)");
                $errors['date_naissance'] = "L'âge doit être supérieur à 18 ans";
            }
        }

        if (!empty($errors)) {
            error_log("Validation errors found: " . print_r($errors, true));
        } else {
            error_log("Validation successful");
        }

        return $errors;
    }

    public function update($id, $data) {
        try {
            // Check if PPR/CIN is unique (excluding current record)
            $stmt = $this->db->prepare(
                "SELECT id FROM {$this->table} 
                WHERE (ppr = :ppr OR cin = :cin) 
                AND id != :id 
                AND deleted_at IS NULL"
            );
            $stmt->bindValue(':ppr', $data['ppr']);
            $stmt->bindValue(':cin', $data['cin']);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            
            if ($stmt->fetch()) {
                throw new PDOException("Le PPR ou le CIN existe déjà");
            }

            foreach (['corps_id', 'grade_id', 'specialite_id', 'formation_sanitaire_id'] as $field) {
                if (isset($data[$field]) && $data[$field] === '') {
                    unset($data[$field]);
                }
            }
            
            return parent::update($id, $data);
        } catch (PDOException $e) {
            error_log("Error updating personnel $id: " . $e->getMessage());
            throw $e;
        }
    }

    public function findByPPR($ppr) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE ppr = :ppr AND deleted_at IS NULL");
        $stmt->bindValue(':ppr', $ppr);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByCIN($cin) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE cin = :cin AND deleted_at IS NULL");
        $stmt->bindValue(':cin', $cin);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findAllWithDetails($conditions = [], $order = '', $limit = null) {
        $sql = "SELECT p.*, 
                c.nom_corps, 
                g.nom_grade, 
                s.nom_specialite,
                fs.nom_formation, 
                fs.type_formation,
                fs.milieu,
                ce.nom_categorie as categorie_etablissement,
                pr.nom_province 
                FROM personnel p
                LEFT JOIN corps c ON p.corps_id = c.id
                LEFT JOIN grades g ON p.grade_id = g.id
                LEFT JOIN specialites s ON p.specialite_id = s.id
                LEFT JOIN formations_sanitaires fs ON p.formation_sanitaire_id = fs.id
                LEFT JOIN categories_etablissements ce ON fs.categorie_id = ce.id
                LEFT JOIN provinces pr ON fs.province_id = pr.id
                WHERE p.deleted_at IS NULL";

        if (!empty($conditions)) {
            foreach ($conditions as $key => $value) {
                $sql .= " AND p.$key = :$key";
            }
        }

        if (!empty($order)) {
            $sql .= " ORDER BY " . $order;
        }

        if ($limit !== null) {
            $sql .= " " . $limit;
        }

        try {
            $stmt = $this->db->prepare($sql);
            foreach ($conditions as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in findAllWithDetails: " . $e->getMessage());
            return [];
        }
    }

    public function findByIdWithDetails($id) {
        $sql = "SELECT p.*, 
                c.nom_corps, 
                g.nom_grade,
                s.nom_specialite,
                fs.nom_formation,
                fs.type_formation,
                fs.milieu,
                ce.nom_categorie as categorie_etablissement,
                pr.nom_province 
                FROM personnel p
                LEFT JOIN corps c ON p.corps_id = c.id
                LEFT JOIN grades g ON p.grade_id = g.id
                LEFT JOIN specialites s ON p.specialite_id = s.id
                LEFT JOIN formations_sanitaires fs ON p.formation_sanitaire_id = fs.id
                LEFT JOIN categories_etablissements ce ON fs.categorie_id = ce.id
                LEFT JOIN provinces pr ON fs.province_id = pr.id
                WHERE p.id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function search($term) {
        try {
            error_log("Starting search with term: " . $term);
            
            $sql = "SELECT p.*, 
                    c.nom_corps, 
                    g.nom_grade,
                    fs.nom_formation,
                    pr.nom_province
                FROM {$this->table} p
                LEFT JOIN corps c ON p.corps_id = c.id
                LEFT JOIN grades g ON p.grade_id = g.id
                LEFT JOIN formations_sanitaires fs ON p.formation_sanitaire_id = fs.id
                LEFT JOIN provinces pr ON fs.province_id = pr.id
                WHERE p.deleted_at IS NULL 
                AND (p.ppr LIKE :ppr 
                     OR p.nom LIKE :nom 
                     OR p.prenom LIKE :prenom
                     OR p.cin LIKE :cin
                     OR CONCAT(p.nom, ' ', p.prenom) LIKE :full_name
                     OR CONCAT(p.prenom, ' ', p.nom) LIKE :reverse_name)";

            error_log("Search SQL: " . $sql);
            $stmt = $this->db->prepare($sql);
            $searchTerm = "%" . trim($term) . "%";
            $stmt->bindValue(":ppr", $searchTerm);
            $stmt->bindValue(":nom", $searchTerm);
            $stmt->bindValue(":prenom", $searchTerm);
            $stmt->bindValue(":cin", $searchTerm);
            $stmt->bindValue(":full_name", $searchTerm);
            $stmt->bindValue(":reverse_name", $searchTerm);
            
            error_log("Executing search with term: " . $searchTerm);
            $stmt->execute();
            
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Search found " . count($results) . " results");
            
            return $results;
            
        } catch (PDOException $e) {
            error_log("Search error: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            throw new PDOException("Erreur lors de la recherche: " . $e->getMessage());
        }
    }

    public function countTotal() {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE deleted_at IS NULL";
        $stmt = $this->db->query($sql);
        return $stmt->fetchColumn();
    }

    public function count($province_id = null, $corps_id = null) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE deleted_at IS NULL";
        $params = [];

        if ($province_id) {
            $sql .= " AND formation_sanitaire_id IN (SELECT id FROM formations_sanitaires WHERE province_id = :province_id)";
            $params[':province_id'] = $province_id;
        }
        if ($corps_id) {
            $sql .= " AND corps_id = :corps_id";
            $params[':corps_id'] = $corps_id;
        }

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function countByCategorie($status, $province_id = null, $corps_id = null) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE deleted_at IS NULL";
        $params = [];

        // For now, just return the total count since we don't have the status column yet
        if ($province_id) {
            $sql .= " AND formation_sanitaire_id IN (SELECT id FROM formations_sanitaires WHERE province_id = :province_id)";
            $params[':province_id'] = $province_id;
        }
        if ($corps_id) {
            $sql .= " AND corps_id = :corps_id";
            $params[':corps_id'] = $corps_id;
        }

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        
        // Return half the total count as a temporary measure
        return (int)($stmt->fetchColumn() * 0.5);
    }

    public function getStatsByCorps($province_id = null) {
        $sql = "SELECT c.nom_corps, COUNT(*) as total,
                ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM {$this->table} WHERE deleted_at IS NULL), 1) as pourcentage
                FROM {$this->table} p
                JOIN corps c ON p.corps_id = c.id
                WHERE p.deleted_at IS NULL";
        
        $params = [];
        if ($province_id) {
            $sql .= " AND p.formation_sanitaire_id IN (SELECT id FROM formations_sanitaires WHERE province_id = :province_id)";
            $params[':province_id'] = $province_id;
        }

        $sql .= " GROUP BY c.nom_corps ORDER BY total DESC";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStatsByProvince($corps_id = null) {
        $sql = "SELECT pr.nom_province, COUNT(*) as total,
                ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM {$this->table} WHERE deleted_at IS NULL), 1) as pourcentage
                FROM {$this->table} p
                JOIN formations_sanitaires fs ON p.formation_sanitaire_id = fs.id
                JOIN provinces pr ON fs.province_id = pr.id
                WHERE p.deleted_at IS NULL";
        
        $params = [];
        if ($corps_id) {
            $sql .= " AND p.corps_id = :corps_id";
            $params[':corps_id'] = $corps_id;
        }

        $sql .= " GROUP BY pr.nom_province ORDER BY total DESC";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStatsBySpecialite($province_id = null, $corps_id = null) {
        $sql = "SELECT s.nom_specialite, COUNT(*) as total,
                ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM {$this->table} WHERE deleted_at IS NULL), 1) as pourcentage
                FROM {$this->table} p
                JOIN specialites s ON p.specialite_id = s.id
                WHERE p.deleted_at IS NULL";
        
        $params = [];
        if ($province_id) {
            $sql .= " AND p.formation_sanitaire_id IN (SELECT id FROM formations_sanitaires WHERE province_id = :province_id)";
            $params[':province_id'] = $province_id;
        }
        if ($corps_id) {
            $sql .= " AND p.corps_id = :corps_id";
            $params[':corps_id'] = $corps_id;
        }

        $sql .= " GROUP BY s.nom_specialite ORDER BY total DESC";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPyramideAges($province_id = null, $corps_id = null) {
        $sql = "SELECT 
            CASE 
                WHEN TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) < 20 THEN 'Moins de 20 ans'
                WHEN TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) BETWEEN 20 AND 24 THEN '20-24 ans'
                WHEN TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) BETWEEN 25 AND 29 THEN '25-29 ans'
                WHEN TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) BETWEEN 30 AND 34 THEN '30-34 ans'
                WHEN TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) BETWEEN 35 AND 39 THEN '35-39 ans'
                WHEN TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) BETWEEN 40 AND 44 THEN '40-44 ans'
                WHEN TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) BETWEEN 45 AND 49 THEN '45-49 ans'
                WHEN TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) BETWEEN 50 AND 54 THEN '50-54 ans'
                WHEN TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) BETWEEN 55 AND 59 THEN '55-59 ans'
                WHEN TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) BETWEEN 60 AND 64 THEN '60-64 ans'
                ELSE '64 ans et plus'
            END as tranche,
            SUM(CASE WHEN sexe = 'M' THEN 1 ELSE 0 END) as hommes,
            SUM(CASE WHEN sexe = 'F' THEN 1 ELSE 0 END) as femmes,
            COUNT(*) as total
            FROM {$this->table}
            WHERE deleted_at IS NULL";
        
        $params = [];
        if ($province_id) {
            $sql .= " AND formation_sanitaire_id IN (SELECT id FROM formations_sanitaires WHERE province_id = :province_id)";
            $params[':province_id'] = $province_id;
        }
        if ($corps_id) {
            $sql .= " AND corps_id = :corps_id";
            $params[':corps_id'] = $corps_id;
        }

        $sql .= " GROUP BY tranche ORDER BY 
            CASE tranche
                WHEN 'Moins de 20 ans' THEN 1
                WHEN '20-24 ans' THEN 2
                WHEN '25-29 ans' THEN 3
                WHEN '30-34 ans' THEN 4
                WHEN '35-39 ans' THEN 5
                WHEN '40-44 ans' THEN 6
                WHEN '45-49 ans' THEN 7
                WHEN '50-55 ans' THEN 8
                WHEN '55-59 ans' THEN 9
                WHEN '60-63 ans' THEN 10
                ELSE 11
            END";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMouvements($personnel_id) {
        $sql = "SELECT m.*, 
                fs_origine.nom_formation as origine,
                fs_dest.nom_formation as destination
                FROM mouvements_personnel m
                LEFT JOIN formations_sanitaires fs_origine ON m.formation_sanitaire_origine_id = fs_origine.id
                LEFT JOIN formations_sanitaires fs_dest ON m.formation_sanitaire_destination_id = fs_dest.id
                WHERE m.personnel_id = :personnel_id
                ORDER BY m.date_mouvement DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':personnel_id', $personnel_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addMouvement($personnel_id, $type_mouvement, $date_mouvement, $origine_id = null, $destination_id = null, $commentaire = '') {
        try {
            $sql = "INSERT INTO mouvements_personnel 
                    (personnel_id, type_mouvement, date_mouvement, formation_sanitaire_origine_id, 
                     formation_sanitaire_destination_id, commentaire) 
                    VALUES 
                    (:personnel_id, :type_mouvement, :date_mouvement, :origine_id, :destination_id, :commentaire)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':personnel_id', $personnel_id);
            $stmt->bindValue(':type_mouvement', $type_mouvement);
            $stmt->bindValue(':date_mouvement', $date_mouvement);
            $stmt->bindValue(':origine_id', $origine_id);
            $stmt->bindValue(':destination_id', $destination_id);
            $stmt->bindValue(':commentaire', $commentaire);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error adding movement: " . $e->getMessage());
            return false;
        }
    }
}
?>
