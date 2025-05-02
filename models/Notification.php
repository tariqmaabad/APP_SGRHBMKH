<?php
class Notification extends Model {
    protected $table = 'personnel';
    
    public function getPersonnelProcheRetraite() {
        $sql = "SELECT p.*, fs.nom_formation, c.nom_corps, g.nom_grade 
               FROM personnel p
               LEFT JOIN formations_sanitaires fs ON p.formation_sanitaire_id = fs.id
               LEFT JOIN corps c ON p.corps_id = c.id
               LEFT JOIN grades g ON p.grade_id = g.id
               WHERE 
               p.deleted_at IS NULL AND
               (YEAR(CURRENT_DATE) - YEAR(p.date_naissance)) >= 58 
               AND (YEAR(CURRENT_DATE) - YEAR(p.date_naissance)) <= 60
               ORDER BY p.date_naissance ASC";
               
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
