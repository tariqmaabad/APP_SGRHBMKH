<?php

class NotificationController extends Controller {
    private $notificationModel;
    
    public function __construct() {
        parent::__construct();
        $this->notificationModel = new Notification();
    }
    
    public function retraite() {
        try {
            // Obtenir le personnel proche de la retraite
            $personnels = $this->notificationModel->getPersonnelProcheRetraite();
            
            // Calculer les dates et âges
            $today = new DateTime();
            
            if ($personnels) {
                foreach($personnels as &$personnel) {
                    $dateNaissance = new DateTime($personnel['date_naissance']);
                    $age = $today->diff($dateNaissance)->y;
                    $dateRetraite = clone $dateNaissance;
                    $dateRetraite->add(new DateInterval('P60Y'));
                    
                    $personnel['age'] = $age;
                    $personnel['date_retraite'] = $dateRetraite->format('Y-m-d');
                    $personnel['jours_restants'] = $today->diff($dateRetraite)->days;
                }
            }
            
            $this->render('notifications/retraite', [
                'personnels' => $personnels,
                'messages' => $this->getFlashMessages()
            ]);
            
        } catch(Exception $e) {
            $this->setFlashMessage('error', 'Une erreur est survenue lors du chargement des notifications : ' . $e->getMessage());
            $this->redirect('/APP_SGRHBMKH/dashboard');
        }
    }
}
