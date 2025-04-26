<?php
class CategorieEtablissement extends Model {
    public function __construct() {
        parent::__construct();
        $this->table = 'categories_etablissements';
    }

    public function findAllSorted() {
        try {
            $result = $this->findAll(['deleted_at' => null], 'nom_categorie ASC');
            error_log("SQL Query: SELECT * FROM " . $this->table . " WHERE deleted_at IS NULL ORDER BY nom_categorie ASC");
            error_log("Result: " . print_r($result, true));
            return $result;
        } catch (\PDOException $e) {
            error_log("Error in findAllSorted: " . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération des catégories");
        }
    }

    public function findWithStats() {
        try {
            $sql = "SELECT ce.*, COUNT(fs.id) as nombre_formations,
                    (SELECT COUNT(*) FROM personnel p 
                     JOIN formations_sanitaires fs2 ON p.formation_sanitaire_id = fs2.id 
                     WHERE fs2.categorie_id = ce.id AND p.deleted_at IS NULL) as nombre_personnel
                    FROM " . $this->table . " ce 
                    LEFT JOIN formations_sanitaires fs ON ce.id = fs.categorie_id AND fs.deleted_at IS NULL
                    WHERE ce.deleted_at IS NULL
                    GROUP BY ce.id 
                    ORDER BY ce.nom_categorie ASC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error in findWithStats: " . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération des statistiques des catégories");
        }
    }

    public function findById($id) {
        try {
            $sql = "SELECT ce.*, 
                    (SELECT COUNT(*) FROM formations_sanitaires 
                     WHERE categorie_id = ce.id 
                     AND deleted_at IS NULL) as nombre_formations
                    FROM " . $this->table . " ce
                    WHERE ce.id = :id 
                    AND ce.deleted_at IS NULL";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error in findById: " . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération de la catégorie");
        }
    }

    public function create($data) {
        try {
            $sql = "INSERT INTO " . $this->table . " (nom_categorie, description, created_at, updated_at) 
                    VALUES (:nom_categorie, :description, NOW(), NOW())";
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                ':nom_categorie' => $data['nom_categorie'],
                ':description' => $data['description'] ?? null
            ]);

            if (!$result) {
                throw new \Exception("Erreur lors de la création de la catégorie");
            }

            return $this->db->lastInsertId();
        } catch (\PDOException $e) {
            error_log("Error in create: " . $e->getMessage());
            throw new \Exception("Erreur lors de la création de la catégorie");
        }
    }

    public function update($id, $data) {
        try {
            $sql = "UPDATE " . $this->table . " 
                    SET nom_categorie = :nom_categorie, 
                        description = :description,
                        updated_at = NOW() 
                    WHERE id = :id 
                    AND deleted_at IS NULL";
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                ':id' => $id,
                ':nom_categorie' => $data['nom_categorie'],
                ':description' => $data['description'] ?? null
            ]);

            if (!$result) {
                throw new \Exception("Erreur lors de la mise à jour de la catégorie");
            }

            return true;
        } catch (\PDOException $e) {
            error_log("Error in update: " . $e->getMessage());
            throw new \Exception("Erreur lors de la mise à jour de la catégorie");
        }
    }

    public function delete($id) {
        try {
            // Soft delete
            $sql = "UPDATE " . $this->table . " 
                    SET deleted_at = NOW() 
                    WHERE id = :id";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (\PDOException $e) {
            error_log("Error in delete: " . $e->getMessage());
            throw new \Exception("Erreur lors de la suppression de la catégorie");
        }
    }

    public function validate($data) {
        $errors = [];
        
        if (empty($data['nom_categorie'])) {
            $errors['nom_categorie'] = 'Le nom de la catégorie est obligatoire';
        }
        
        return $errors;
    }
}
