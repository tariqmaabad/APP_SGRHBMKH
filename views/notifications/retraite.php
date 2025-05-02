<?php
// Ensure $messages is defined
$messages = $messages ?? [];
?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2><i class="fas fa-bell text-warning"></i> Notifications de Retraite</h2>
            <p class="text-muted">Personnel approchant l'âge de la retraite (60 ans) dans les deux prochaines années</p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Nom et Prénom</th>
                            <th>Formation Sanitaire</th>
                            <th>Corps</th>
                            <th>Grade</th>
                            <th>Âge Actuel</th>
                            <th>Date de Retraite</th>
                            <th>Jours Restants</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($personnels) && !empty($personnels)): ?>
                            <?php foreach ($personnels as $personnel): ?>
                                <?php
                                    $urgencyClass = '';
                                    if ($personnel['jours_restants'] <= 180) { // 6 mois
                                        $urgencyClass = 'table-danger';
                                    } elseif ($personnel['jours_restants'] <= 365) { // 1 an
                                        $urgencyClass = 'table-warning';
                                    }
                                ?>
                                <tr class="<?php echo $urgencyClass; ?>">
                                    <td><?php echo htmlspecialchars($personnel['nom'] . ' ' . $personnel['prenom']); ?></td>
                                    <td><?php echo htmlspecialchars($personnel['nom_formation'] ?? 'Non assigné'); ?></td>
                                    <td><?php echo htmlspecialchars($personnel['nom_corps'] ?? 'Non assigné'); ?></td>
                                    <td><?php echo htmlspecialchars($personnel['nom_grade'] ?? 'Non assigné'); ?></td>
                                    <td><?php echo $personnel['age']; ?> ans</td>
                                    <td><?php echo date('d/m/Y', strtotime($personnel['date_retraite'])); ?></td>
                                    <td>
                                        <span class="badge <?php echo $personnel['jours_restants'] <= 180 ? 'bg-danger' : ($personnel['jours_restants'] <= 365 ? 'bg-warning' : 'bg-info'); ?>">
                                            <?php echo $personnel['jours_restants']; ?> jours
                                        </span>
                                    </td>
                                    <td>
                                        <a href="/APP_SGRHBMKH/personnel/show/<?php echo $personnel['id']; ?>" 
                                           class="btn btn-sm btn-outline-primary"
                                           title="Voir le profil">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">Aucun personnel n'approche de l'âge de la retraite pour le moment.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
