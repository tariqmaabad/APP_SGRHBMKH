<?php
class Grade extends Model {
    public function __construct() {
        parent::__construct();
        $this->table = 'grades';
    }

    public function findAllSorted() {
        return $this->findAll([], 'nom_grade ASC');
    }

    public function findByCorps($corps_id) {
        return $this->findAll(['corps_id' => $corps_id], 'nom_grade ASC');
    }

    public function findWithCorps() {
        try {
            $sql = "SELECT g.*, c.nom_corps, c.description as corps_description
                    FROM " . $this->table . " g 
                    LEFT JOIN corps c ON g.corps_id = c.id 
                    WHERE g.deleted_at IS NULL
                    ORDER BY g.nom_grade ASC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error in findWithCorps: " . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération des grades");
        }
    }

    public function findWithCorpsAndPagination($corps_id = null, $page = 1, $perPage = 10) {
        try {
            $offset = ($page - 1) * $perPage;
            $params = [];
            
            $sql = "SELECT g.*, c.nom_corps, c.description as corps_description
                    FROM " . $this->table . " g 
                    LEFT JOIN corps c ON g.corps_id = c.id 
                    WHERE g.deleted_at IS NULL";

            if ($corps_id) {
                $sql .= " AND g.corps_id = :corps_id";
                $params[':corps_id'] = $corps_id;
            }

            $sql .= " ORDER BY c.nom_corps, g.nom_grade 
                     LIMIT :limit OFFSET :offset";

            $stmt = $this->db->prepare($sql);
            
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->bindValue(':limit', $perPage, \PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
            
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error in findWithCorpsAndPagination: " . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération des grades");
        }
    }

    public function countTotal($corps_id = null) {
        try {
            $sql = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE deleted_at IS NULL";
            $params = [];

            if ($corps_id) {
                $sql .= " AND corps_id = :corps_id";
                $params[':corps_id'] = $corps_id;
            }

            $stmt = $this->db->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();
            return (int)$stmt->fetch(\PDO::FETCH_ASSOC)['total'];
        } catch (\PDOException $e) {
            error_log("Error in countTotal: " . $e->getMessage());
            throw new \Exception("Erreur lors du comptage des grades");
        }
    }

    public function getStatsParCorps() {
        try {
            $sql = "SELECT c.nom_corps, COUNT(g.id) as total_grades,
                    SUM(CASE WHEN p.id IS NOT NULL THEN 1 ELSE 0 END) as total_personnel
                    FROM " . $this->table . " g
                    JOIN corps c ON g.corps_id = c.id
                    LEFT JOIN personnel p ON g.id = p.grade_id AND p.deleted_at IS NULL
                    WHERE g.deleted_at IS NULL
                    GROUP BY c.id, c.nom_corps
                    ORDER BY c.nom_corps";

            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error in getStatsParCorps: " . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération des statistiques");
        }
    }
}
