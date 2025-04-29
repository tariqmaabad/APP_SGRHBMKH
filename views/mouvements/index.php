<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-exchange-alt me-2"></i>Mouvements du Personnel</h1>
        <div class="btn-group">
            <a href="/APP_SGRHBMKH/mouvements/stats" class="btn btn-info">
                <i class="fas fa-chart-bar me-1"></i> Statistiques
            </a>
            <?php if ($canCreate): ?>
                <a href="/APP_SGRHBMKH/mouvements/create" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Nouveau Mouvement
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Résumé des statistiques -->
    <div class="row g-4 mb-4">
        <?php foreach ($stats as $stat): ?>
            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-muted mb-2">
                                    <?= $types[$stat['type_mouvement']] ?? $stat['type_mouvement'] ?>
                                </h6>
                                <h3 class="mb-0"><?= $stat['total'] ?></h3>
                                <div class="text-success mt-2">
                                    <i class="fas fa-chart-line me-1"></i>
                                    <span class="text-sm"><?= $stat['cette_annee'] ?> cette année</span>
                                </div>
                            </div>
                            <div class="rounded-circle p-3 bg-light">
                                <i class="fas <?= getMovementIcon($stat['type_mouvement']) ?> fa-lg text-primary"></i>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 4px;">
                            <div class="progress-bar bg-primary" role="progressbar" 
                                 style="width: <?= ($stat['cette_annee'] / max($stat['total'], 1)) * 100 ?>%" 
                                 aria-valuenow="<?= $stat['cette_annee'] ?>" 
                                 aria-valuemin="0" 
                                 aria-valuemax="<?= $stat['total'] ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Filtres -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="/APP_SGRHBMKH/mouvements" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="type" class="form-label">Type de mouvement</label>
                    <select class="form-select" id="type" name="type">
                        <option value="">Tous les types</option>
                        <?php foreach ($types as $key => $value): ?>
                            <option value="<?= $key ?>" <?= isset($_GET['type']) && $_GET['type'] === $key ? 'selected' : '' ?>>
                                <?= $value ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="date_debut" class="form-label">Date début</label>
                    <input type="date" class="form-control" id="date_debut" name="date_debut" 
                           value="<?= $_GET['date_debut'] ?? '' ?>">
                </div>
                <div class="col-md-3">
                    <label for="date_fin" class="form-label">Date fin</label>
                    <input type="date" class="form-control" id="date_fin" name="date_fin" 
                           value="<?= $_GET['date_fin'] ?? '' ?>">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Filtrer</button>
                    <a href="/APP_SGRHBMKH/mouvements" class="btn btn-secondary">Réinitialiser</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des mouvements -->
    <?php if (isset($pagination)): ?>
        <div class="mb-3">
            <p class="text-muted">
                Affichage de <?php echo ($pagination['current_page'] - 1) * $pagination['per_page'] + 1; ?> 
                à <?php echo min($pagination['current_page'] * $pagination['per_page'], $pagination['total']); ?> 
                sur <?php echo $pagination['total']; ?> mouvements
            </p>
        </div>
    <?php endif; ?>

    <?php if (empty($mouvements)): ?>
        <div class="alert alert-info">
            Aucun mouvement n'a été trouvé.
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Agent</th>
                                <th>Type</th>
                                <th>Origine</th>
                                <th>Destination</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($mouvements as $mouvement): ?>
                                <tr>
                                    <td><?= date('d/m/Y', strtotime($mouvement['date_mouvement'])) ?></td>
                                    <td>
                                        <?php if ($mouvement['personnel_id']): ?>
                                            <a href="/APP_SGRHBMKH/personnel/show/<?= $mouvement['personnel_id'] ?>">
                                                <?= htmlspecialchars($mouvement['nom'] . ' ' . $mouvement['prenom']) ?>
                                                <br>
                                                <small class="text-muted">PPR: <?= htmlspecialchars($mouvement['ppr']) ?></small>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge <?= getBadgeClass($mouvement['type_mouvement']) ?>">
                                            <?= $types[$mouvement['type_mouvement']] ?? $mouvement['type_mouvement'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($mouvement['origine_nom']): ?>
                                            
                                                <?= htmlspecialchars($mouvement['origine_nom']) ?>
                                          
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($mouvement['destination_nom']): ?>
                                             
                                                <?= htmlspecialchars($mouvement['destination_nom']) ?>
                                         
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="/APP_SGRHBMKH/mouvements/show/<?= $mouvement['id'] ?>" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Voir
                                            </a>
                                            <?php if ($canDelete): ?>
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                        onclick="confirmDelete(<?= $mouvement['id'] ?>, '<?= addslashes($types[$mouvement['type_mouvement']] ?? $mouvement['type_mouvement']) ?>')">
                                                    <i class="fas fa-trash"></i> Supprimer
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
                <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
                    <nav aria-label="Navigation des pages" class="mt-3">
                        <ul class="pagination justify-content-center">
                            <?php if ($pagination['has_previous']): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $pagination['previous_page']])); ?>">
                                        Précédent
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                                <li class="page-item <?php echo $i === $pagination['current_page'] ? 'active' : ''; ?>">
                                    <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($pagination['has_next']): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $pagination['next_page']])); ?>">
                                        Suivant
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer ce mouvement de type "<span id="mouvementType"></span>" ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteForm" action="" method="POST" style="display: inline;">
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, type) {
    document.getElementById('mouvementType').textContent = type;
    document.getElementById('deleteForm').action = '/APP_SGRHBMKH/mouvements/delete/' + id;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>

<?php
function getBadgeClass($type) {
    switch ($type) {
        case 'DECES':
            return 'bg-dark';
        case 'MUTATION':
            return 'bg-primary';
        case 'DEMISSION':
            return 'bg-danger';
        case 'FORMATION':
            return 'bg-success';
        case 'SUSPENSION':
            return 'bg-warning text-dark';
        case 'MISE_A_DISPOSITION':
            return 'bg-info text-dark';
        case 'MISE_EN_DISPONIBILITE':
            return 'bg-secondary';
        case 'RETRAITE_AGE':
            return 'bg-dark';
        default:
            return 'bg-primary';
    }
}

function getMovementIcon($type) {
    switch ($type) {
        case 'DECES':
            return 'fa-cross';
        case 'MUTATION':
            return 'fa-exchange-alt';
        case 'DEMISSION':
            return 'fa-door-open';
        case 'FORMATION':
            return 'fa-graduation-cap';
        case 'SUSPENSION':
            return 'fa-pause-circle';
        case 'MISE_A_DISPOSITION':
            return 'fa-handshake';
        case 'MISE_EN_DISPONIBILITE':
            return 'fa-clock';
        case 'RETRAITE_AGE':
            return 'fa-user-clock';
        default:
            return 'fa-arrows-alt';
    }
}
?>
