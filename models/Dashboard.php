<?php
class Dashboard extends Model {
    public function getStats() {
        try {
            return [
                // Statistiques principales
                'total_personnel' => $this->getTotalPersonnel(),
                'total_provinces' => $this->getTotalProvinces(),
                'total_etablissements_urbains' => $this->getEtablissementsByMilieu('URBAIN'),
                'total_etablissements_ruraux' => $this->getEtablissementsByMilieu('RURAL'),

                // Statistiques ressources humaines
                'effectif_medical' => $this->getPersonnelByCategorie('MEDICAL'),
                'effectif_paramedical' => $this->getPersonnelByCategorie('PARAMEDICAL'),
                'effectif_administratif' => $this->getPersonnelByCategorie('ADMINISTRATIF'),
                'effectif_technique' => $this->getPersonnelByCategorie('TECHNIQUE'),

                // Statistiques par genre
                'effectif_masculin' => $this->getPersonnelByGender('M'),
                'effectif_feminin' => $this->getPersonnelByGender('F'),

                // Statistiques par situation familiale
                'effectif_celibataire' => $this->getPersonnelBySituation('CELIBATAIRE'),
                'effectif_marie' => $this->getPersonnelBySituation('MARIE'),
                'effectif_divorce' => $this->getPersonnelBySituation('DIVORCE'),
                'effectif_veuf' => $this->getPersonnelBySituation('VEUF'),

                // Statistiques infrastructures
                'centres_sante_urbains' => $this->getEtablissementsByType('CSU'),
                'centres_sante_ruraux' => $this->getEtablissementsByType('CSR'),
                'hopitaux_regionaux' => $this->getEtablissementsByType('HR'),
                'hopitaux_provinciaux' => $this->getEtablissementsByType('HP'),
                'hopitaux_locaux' => $this->getEtablissementsByType('HL'),
                'centres_oncologie' => $this->getEtablissementsByType('CO')
            ];
        } catch (PDOException $e) {
            error_log("Error getting dashboard stats: " . $e->getMessage());
            return array_fill_keys([
                'total_personnel', 'total_provinces', 
                'total_etablissements_urbains', 'total_etablissements_ruraux',
                'effectif_medical', 'effectif_paramedical', 
                'effectif_administratif', 'effectif_technique',
                'centres_sante_urbains', 'centres_sante_ruraux',
                'hopitaux_regionaux', 'hopitaux_provinciaux', 
                'hopitaux_locaux', 'centres_oncologie',
                'effectif_masculin', 'effectif_feminin',
                'effectif_celibataire', 'effectif_marie', 'effectif_divorce', 'effectif_veuf'
            ], 0);
        }
    }

    private function getTotalPersonnel() {
        try {
            $sql = "SELECT COUNT(*) as count FROM personnel WHERE deleted_at IS NULL";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$result['count'];
        } catch (PDOException $e) {
            error_log("Error getting total personnel: " . $e->getMessage());
            return 0;
        }
    }

    private function getTotalProvinces() {
        try {
            $sql = "SELECT COUNT(*) as count FROM provinces WHERE deleted_at IS NULL";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$result['count'];
        } catch (PDOException $e) {
            error_log("Error getting total provinces: " . $e->getMessage());
            return 0;
        }
    }

    private function getEtablissementsByMilieu($milieu) {
        if (!in_array($milieu, ['URBAIN', 'RURAL'])) {
            error_log("Invalid milieu value: " . $milieu);
            return 0;
        }

        try {
            $sql = "SELECT COUNT(*) as count 
                    FROM formations_sanitaires 
                    WHERE milieu = :milieu 
                    AND deleted_at IS NULL";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['milieu' => $milieu]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$result['count'];
        } catch (PDOException $e) {
            error_log("Error getting etablissements by milieu: " . $e->getMessage());
            return 0;
        }
    }

    private function getPersonnelByCategorie($type) {
        if (!in_array($type, ['MEDICAL', 'PARAMEDICAL', 'ADMINISTRATIF', 'TECHNIQUE'])) {
            error_log("Invalid type value: " . $type);
            return 0;
        }

        try {
            if ($type === 'TECHNIQUE') {
                // For technical staff, look for administrative corps members with technical grades
                $sql = "SELECT COUNT(DISTINCT p.id) as count 
                        FROM personnel p 
                        JOIN corps c ON p.corps_id = c.id
                        JOIN grades g ON p.grade_id = g.id
                        WHERE c.type_corps = 'ADMINISTRATIF'
                        AND g.nom_grade LIKE '%Technicien%'
                        AND p.deleted_at IS NULL";
                $stmt = $this->db->prepare($sql);
                $stmt->execute();
            } else {
                // For other categories, use corps type
                $sql = "SELECT COUNT(DISTINCT p.id) as count 
                        FROM personnel p 
                        JOIN corps c ON p.corps_id = c.id
                        WHERE c.type_corps = :type 
                        AND p.deleted_at IS NULL";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(['type' => $type]);
            }
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$result['count'];
        } catch (PDOException $e) {
            error_log("Error getting personnel by categorie: " . $e->getMessage());
            return 0;
        }
    }

    private function getEtablissementsByType($type) {
        if (!in_array($type, ['CSU', 'CSR', 'HR', 'HP', 'HL', 'CO'])) {
            error_log("Invalid type value: " . $type);
            return 0;
        }

        try {
            $sql = "SELECT COUNT(*) as count FROM formations_sanitaires fs 
                    JOIN categories_etablissements ce ON fs.categorie_id = ce.id 
                    WHERE fs.deleted_at IS NULL AND ";
            
            switch ($type) {
                case 'CSU':
                    $sql .= "type_formation = 'CENTRE_SANTE' AND milieu = 'URBAIN'";
                    break;
                case 'CSR':
                    $sql .= "type_formation = 'CENTRE_SANTE' AND milieu = 'RURAL'";
                    break;
                case 'HR':
                    $sql .= "ce.nom_categorie = 'Hôpital Régional'";
                    break;
                case 'HP':
                    $sql .= "ce.nom_categorie = 'Hôpital Provincial'";
                    break;
                case 'HL':
                    $sql .= "ce.nom_categorie = 'Hôpital Local'";
                    break;
                case 'CO':
                    $sql .= "ce.nom_categorie = 'Centre d\\'Oncologie'";
                    break;
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$result['count'];
        } catch (PDOException $e) {
            error_log("Error getting etablissements by type: " . $e->getMessage());
            return 0;
        }
    }

    private function getPersonnelByGender($gender) {
        if (!in_array($gender, ['M', 'F'])) {
            error_log("Invalid gender value: " . $gender);
            return 0;
        }

        try {
            $sql = "SELECT COUNT(*) as count 
                    FROM personnel 
                    WHERE sexe = :gender 
                    AND deleted_at IS NULL";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['gender' => $gender]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$result['count'];
        } catch (PDOException $e) {
            error_log("Error getting personnel by gender: " . $e->getMessage());
            return 0;
        }
    }

    private function getPersonnelBySituation($situation) {
        if (!in_array($situation, ['CELIBATAIRE', 'MARIE', 'DIVORCE', 'VEUF'])) {
            error_log("Invalid situation familiale value: " . $situation);
            return 0;
        }

        try {
            $sql = "SELECT COUNT(*) as count 
                    FROM personnel 
                    WHERE situation_familiale = :situation 
                    AND deleted_at IS NULL";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['situation' => $situation]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$result['count'];
        } catch (PDOException $e) {
            error_log("Error getting personnel by situation familiale: " . $e->getMessage());
            return 0;
        }
    }
}
