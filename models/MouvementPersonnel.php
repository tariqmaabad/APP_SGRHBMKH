<?php
class MouvementPersonnel extends Model {
    protected $table = 'mouvements_personnel';

    public function __construct() {
        parent::__construct();
    }

    public function getTypesMouvement() {
        return [
            'MUTATION' => 'Mutation',
            'MISE_A_DISPOSITION' => 'Mise à disposition',
            'RETRAITE_AGE' => 'Retraite',
            'DECES' => 'Décès',
            'DEMISSION' => 'Démission',
            'FORMATION' => 'Formation',
            'SUSPENSION' => 'Suspension',
            'MISE_EN_DISPONIBILITE' => 'Mise en disponibilité'
        ];
    }

    public function findWithDetails($conditions = [], $limit = '') {
        try {
            $sql = "SELECT m.*, 
                    p.nom, p.prenom, p.ppr,
                    fo.nom_formation as origine_nom,
                    fd.nom_formation as destination_nom
                    FROM {$this->table} m
                    LEFT JOIN personnel p ON m.personnel_id = p.id
                    LEFT JOIN formations_sanitaires fo ON m.formation_sanitaire_origine_id = fo.id
                    LEFT JOIN formations_sanitaires fd ON m.formation_sanitaire_destination_id = fd.id
                    WHERE m.deleted_at IS NULL";

            $params = [];
            if (!empty($conditions['type_mouvement'])) {
                $sql .= " AND m.type_mouvement = :type";
                $params[':type'] = $conditions['type_mouvement'];
            }
            if (!empty($conditions['date_debut'])) {
                $sql .= " AND m.date_mouvement >= :date_debut";
                $params[':date_debut'] = $conditions['date_debut'];
            }
            if (!empty($conditions['date_fin'])) {
                $sql .= " AND m.date_mouvement <= :date_fin";
                $params[':date_fin'] = $conditions['date_fin'];
            }

            $sql .= " ORDER BY m.date_mouvement DESC";
            if ($limit) {
                $sql .= " " . $limit;
            }

            $stmt = $this->db->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error in findWithDetails: " . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération des mouvements");
        }
    }

    public function findDetailById($id) {
        try {
            $sql = "SELECT m.*, 
                    p.nom, p.prenom, p.ppr,
                    fo.nom_formation as origine_nom,
                    fd.nom_formation as destination_nom
                    FROM {$this->table} m
                    LEFT JOIN personnel p ON m.personnel_id = p.id
                    LEFT JOIN formations_sanitaires fo ON m.formation_sanitaire_origine_id = fo.id
                    LEFT JOIN formations_sanitaires fd ON m.formation_sanitaire_destination_id = fd.id
                    WHERE m.id = :id
                    AND m.deleted_at IS NULL";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error in findDetailById: " . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération du mouvement");
        }
    }

    public function findByType($type) {
        try {
            return $this->findWithDetails(['type_mouvement' => $type]);
        } catch (\PDOException $e) {
            error_log("Error in findByType: " . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération des mouvements par type");
        }
    }

    public function createMouvement($data) {
        $this->db->beginTransaction();
        try {
            error_log("Starting createMouvement with data: " . json_encode($data));
            
            $sql = "INSERT INTO {$this->table} 
                    (personnel_id, type_mouvement, date_mouvement, 
                     formation_sanitaire_origine_id, formation_sanitaire_destination_id, 
                     commentaire, created_at, updated_at)
                    VALUES 
                    (:personnel_id, :type_mouvement, :date_mouvement,
                     :origine_id, :destination_id, :commentaire, NOW(), NOW())";

            error_log("Prepared SQL: " . $sql);

            $stmt = $this->db->prepare($sql);
            if (!$stmt) {
                error_log("Prepare statement failed: " . json_encode($this->db->errorInfo()));
                throw new \Exception("Erreur de préparation de la requête");
            }

            $params = [
                ':personnel_id' => $data['personnel_id'],
                ':type_mouvement' => $data['type_mouvement'],
                ':date_mouvement' => $data['date_mouvement'],
                ':origine_id' => $data['formation_sanitaire_origine_id'] ?? null,
                ':destination_id' => $data['formation_sanitaire_destination_id'] ?? null,
                ':commentaire' => $data['commentaire'] ?? null
            ];
            
            error_log("Executing with params: " . json_encode($params));
            
            $result = $stmt->execute($params);

            if (!$result) {
                error_log("Execute failed: " . json_encode($stmt->errorInfo()));
                throw new \Exception("Erreur lors de l'exécution de la requête");
            }

            $id = $this->db->lastInsertId();
            error_log("Successfully created mouvement with ID: " . $id);
            
            $this->db->commit();
            return $id;
        } catch (\PDOException $e) {
            error_log("PDO Error in createMouvement: " . $e->getMessage());
            $this->db->rollBack();
            throw new \Exception("Erreur lors de la création du mouvement: " . $e->getMessage());
        } catch (\Exception $e) {
            error_log("General Error in createMouvement: " . $e->getMessage());
            $this->db->rollBack();
            throw $e;
        }
    }

    public function delete($id) {
        try {
            $sql = "UPDATE {$this->table} SET deleted_at = NOW() WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (\PDOException $e) {
            error_log("Error in delete: " . $e->getMessage());
            throw new \Exception("Erreur lors de la suppression du mouvement");
        }
    }

    public function count($type_mouvement = null, $date_debut = null, $date_fin = null) {
        try {
            $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE deleted_at IS NULL";
            $params = [];

            if ($type_mouvement) {
                $sql .= " AND type_mouvement = :type";
                $params[':type'] = $type_mouvement;
            }
            if ($date_debut) {
                $sql .= " AND date_mouvement >= :date_debut";
                $params[':date_debut'] = $date_debut;
            }
            if ($date_fin) {
                $sql .= " AND date_mouvement <= :date_fin";
                $params[':date_fin'] = $date_fin;
            }

            $stmt = $this->db->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();
            return (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
        } catch (\PDOException $e) {
            error_log("Error in count: " . $e->getMessage());
            throw new \Exception("Erreur lors du comptage des mouvements");
        }
    }

    public function countByType($type, $date_debut = null, $date_fin = null) {
        try {
            $sql = "SELECT COUNT(*) as total FROM {$this->table} 
                    WHERE type_mouvement = :type 
                    AND deleted_at IS NULL";
            $params = [':type' => $type];

            if ($date_debut) {
                $sql .= " AND date_mouvement >= :date_debut";
                $params[':date_debut'] = $date_debut;
            }
            if ($date_fin) {
                $sql .= " AND date_mouvement <= :date_fin";
                $params[':date_fin'] = $date_fin;
            }

            $stmt = $this->db->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();
            return (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
        } catch (\PDOException $e) {
            error_log("Error in countByType: " . $e->getMessage());
            throw new \Exception("Erreur lors du comptage des mouvements par type");
        }
    }

    public function countAutres($date_debut = null, $date_fin = null) {
        try {
            $sql = "SELECT COUNT(*) as total FROM {$this->table} 
                    WHERE type_mouvement NOT IN ('MUTATION', 'FORMATION')
                    AND deleted_at IS NULL";
            $params = [];

            if ($date_debut) {
                $sql .= " AND date_mouvement >= :date_debut";
                $params[':date_debut'] = $date_debut;
            }
            if ($date_fin) {
                $sql .= " AND date_mouvement <= :date_fin";
                $params[':date_fin'] = $date_fin;
            }

            $stmt = $this->db->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();
            return (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
        } catch (\PDOException $e) {
            error_log("Error in countAutres: " . $e->getMessage());
            throw new \Exception("Erreur lors du comptage des autres mouvements");
        }
    }

    public function getEvolutionMensuelle($type_mouvement = null, $date_debut = null, $date_fin = null) {
        try {
            $sql = "SELECT DATE_FORMAT(date_mouvement, '%Y-%m') as mois,
                    COUNT(*) as total
                    FROM {$this->table}
                    WHERE deleted_at IS NULL";
            $params = [];

            if ($type_mouvement) {
                $sql .= " AND type_mouvement = :type";
                $params[':type'] = $type_mouvement;
            }
            if ($date_debut) {
                $sql .= " AND date_mouvement >= :date_debut";
                $params[':date_debut'] = $date_debut;
            }
            if ($date_fin) {
                $sql .= " AND date_mouvement <= :date_fin";
                $params[':date_fin'] = $date_fin;
            }

            $sql .= " GROUP BY DATE_FORMAT(date_mouvement, '%Y-%m')
                      ORDER BY mois DESC";

            $stmt = $this->db->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error in getEvolutionMensuelle: " . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération de l'évolution mensuelle");
        }
    }

    public function getStats($date_debut = null, $date_fin = null) {
        try {
            $current_year = date('Y');
            $sql = "SELECT 
                    type_mouvement,
                    SUM(CASE WHEN YEAR(date_mouvement) = :current_year THEN 1 ELSE 0 END) as cette_annee,
                    SUM(CASE WHEN YEAR(date_mouvement) = :last_year THEN 1 ELSE 0 END) as annee_precedente,
                    COUNT(*) as total
                    FROM {$this->table} 
                    WHERE deleted_at IS NULL";
            $params = [
                ':current_year' => $current_year,
                ':last_year' => $current_year - 1
            ];

            if ($date_debut) {
                $sql .= " AND date_mouvement >= :date_debut";
                $params[':date_debut'] = $date_debut;
            }
            if ($date_fin) {
                $sql .= " AND date_mouvement <= :date_fin";
                $params[':date_fin'] = $date_fin;
            }

            $sql .= " GROUP BY type_mouvement ORDER BY total DESC";
            $stmt = $this->db->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error in getStats: " . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération des statistiques");
        }
    }

    public function getStatsByType($type_mouvement = null, $date_debut = null, $date_fin = null) {
        try {
            $sql = "SELECT DATE_FORMAT(date_mouvement, '%Y-%m') as mois,
                    COUNT(*) as total
                    FROM {$this->table} 
                    WHERE deleted_at IS NULL";
            $params = [];

            if ($type_mouvement) {
                $sql .= " AND type_mouvement = :type";
                $params[':type'] = $type_mouvement;
            }
            if ($date_debut) {
                $sql .= " AND date_mouvement >= :date_debut";
                $params[':date_debut'] = $date_debut;
            }
            if ($date_fin) {
                $sql .= " AND date_mouvement <= :date_fin";
                $params[':date_fin'] = $date_fin;
            }

            $sql .= " GROUP BY DATE_FORMAT(date_mouvement, '%Y-%m')
                     ORDER BY mois DESC LIMIT 12";

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

    public function getRecents($limit = 50) {
        try {
            $sql = "SELECT m.*, p.nom, p.prenom,
                    fo.nom_formation as origine,
                    fd.nom_formation as destination
                    FROM {$this->table} m
                    JOIN personnel p ON m.personnel_id = p.id
                    LEFT JOIN formations_sanitaires fo ON m.formation_sanitaire_origine_id = fo.id
                    LEFT JOIN formations_sanitaires fd ON m.formation_sanitaire_destination_id = fd.id
                    WHERE m.deleted_at IS NULL
                    ORDER BY m.date_mouvement DESC
                    LIMIT :limit";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error in getRecents: " . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération des mouvements récents");
        }
    }
}
