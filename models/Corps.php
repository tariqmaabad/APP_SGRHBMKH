<?php
class Corps extends Model {
    public const TYPE_MEDICAL = 'MEDICAL';
    public const TYPE_PARAMEDICAL = 'PARAMEDICAL';
    public const TYPE_ADMINISTRATIF = 'ADMINISTRATIF';

    public function __construct() {
        parent::__construct();
        $this->table = 'corps';
    }

    public function getTypes() {
        return [
            self::TYPE_MEDICAL => 'Médical',
            self::TYPE_PARAMEDICAL => 'Paramédical',
            self::TYPE_ADMINISTRATIF => 'Administratif et Technique'
        ];
    }

    public function create($data) {
        try {
            $sql = "INSERT INTO {$this->table} (nom_corps, description, type_corps, created_at, updated_at) 
                    VALUES (:nom_corps, :description, :type_corps, NOW(), NOW())";
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                ':nom_corps' => $data['nom_corps'],
                ':description' => $data['description'] ?? null,
                ':type_corps' => $data['type_corps']
            ]);

            if (!$result) {
                throw new \Exception("Erreur lors de la création du corps");
            }
            
            return $this->db->lastInsertId();
        } catch (\PDOException $e) {
            error_log("Error in create corps: " . $e->getMessage());
            throw new \Exception("Erreur lors de la création du corps");
        }
    }

    public function update($id, $data) {
        try {
            $sql = "UPDATE {$this->table} 
                    SET nom_corps = :nom_corps, 
                        description = :description,
                        type_corps = :type_corps,
                        updated_at = NOW() 
                    WHERE id = :id 
                    AND deleted_at IS NULL";
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                ':id' => $id,
                ':nom_corps' => $data['nom_corps'],
                ':description' => $data['description'] ?? null,
                ':type_corps' => $data['type_corps']
            ]);

            if (!$result) {
                throw new \Exception("Erreur lors de la mise à jour du corps");
            }
            
            return true;
        } catch (\PDOException $e) {
            error_log("Error in update corps: " . $e->getMessage());
            throw new \Exception("Erreur lors de la mise à jour du corps");
        }
    }

    public function findWithGrades($limit = null, $offset = null) {
        try {
            $sql = "SELECT c.*, COUNT(CASE WHEN g.deleted_at IS NULL THEN g.id END) as nombre_grades 
                    FROM {$this->table} c 
                    LEFT JOIN grades g ON c.id = g.corps_id
                    WHERE c.deleted_at IS NULL
                    GROUP BY c.id, c.nom_corps, c.type_corps, c.description, c.created_at, c.updated_at, c.deleted_at
                    ORDER BY c.nom_corps";
            
            if ($limit !== null) {
                $sql .= " LIMIT :limit";
                if ($offset !== null) {
                    $sql .= " OFFSET :offset";
                }
            }
            
            $stmt = $this->db->prepare($sql);
            
            if ($limit !== null) {
                $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
                if ($offset !== null) {
                    $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
                }
            }
            
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error in findWithGrades: " . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération des corps");
        }
    }

    public function findByType($type, $limit = null) {
        try {
            $sql = "SELECT c.*, COUNT(CASE WHEN g.deleted_at IS NULL THEN g.id END) as nombre_grades 
                    FROM {$this->table} c 
                    LEFT JOIN grades g ON c.id = g.corps_id 
                    WHERE c.deleted_at IS NULL
                    AND c.type_corps = :type
                    GROUP BY c.id, c.nom_corps, c.type_corps, c.description, c.created_at, c.updated_at, c.deleted_at
                    ORDER BY c.nom_corps";
            
            if ($limit !== null) {
                $sql .= " " . $limit;
            }

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':type', $type, \PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error in findByType: " . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération des corps");
        }
    }

    public function findById($id) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE id = :id AND deleted_at IS NULL";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error in findById: " . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération du corps");
        }
    }

    public function findDetailById($id) {
        try {
            $sql = "SELECT c.*, COUNT(CASE WHEN g.deleted_at IS NULL THEN g.id END) as nombre_grades 
                    FROM {$this->table} c 
                    LEFT JOIN grades g ON c.id = g.corps_id 
                    WHERE c.id = :id 
                    AND c.deleted_at IS NULL
                    GROUP BY c.id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error in findDetailById: " . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération des détails du corps");
        }
    }

    public function findAllSorted() {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE deleted_at IS NULL ORDER BY nom_corps";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error in findAllSorted: " . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération des corps");
        }
    }

    public function delete($id) {
        try {
            // Soft delete
            $sql = "UPDATE {$this->table} SET deleted_at = NOW() WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (\PDOException $e) {
            error_log("Error in delete: " . $e->getMessage());
            throw new \Exception("Erreur lors de la suppression du corps");
        }
    }

    public function countByType($type) {
        try {
            $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE type_corps = :type AND deleted_at IS NULL";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':type' => $type]);
            return (int)$stmt->fetch(\PDO::FETCH_ASSOC)['total'];
        } catch (\PDOException $e) {
            error_log("Error in countByType: " . $e->getMessage());
            throw new \Exception("Erreur lors du comptage des corps");
        }
    }

    public function getStatsByType() {
        try {
            $sql = "SELECT 
                    c.type_corps,
                    COUNT(DISTINCT c.id) as total_corps,
                    COUNT(DISTINCT g.id) as total_grades
                FROM {$this->table} c 
                LEFT JOIN grades g ON c.id = g.corps_id AND g.deleted_at IS NULL
                WHERE c.deleted_at IS NULL
                GROUP BY c.type_corps
                ORDER BY c.type_corps";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error in getStatsByType: " . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération des statistiques");
        }
    }
}
