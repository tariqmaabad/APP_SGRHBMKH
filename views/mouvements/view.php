<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Détails du Mouvement</h1>
        <div>
            <button type="button" class="btn btn-danger" onclick="confirmDelete(<?= $mouvement['id'] ?>, '<?= addslashes($types[$mouvement['type_mouvement']] ?? $mouvement['type_mouvement']) ?>')">
                <i class="fas fa-trash"></i> Supprimer
            </button>
            <a href="/APP_SGRHBMKH/mouvements" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h2 class="h5 mb-0">Informations du Mouvement</h2>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Type</dt>
                        <dd class="col-sm-8">
                            <span class="badge <?= getBadgeClass($mouvement['type_mouvement']) ?>">
                                <?= $types[$mouvement['type_mouvement']] ?? $mouvement['type_mouvement'] ?>
                            </span>
                        </dd>

                        <dt class="col-sm-4">Date</dt>
                        <dd class="col-sm-8"><?= date('d/m/Y', strtotime($mouvement['date_mouvement'])) ?></dd>

                        <dt class="col-sm-4">Commentaire</dt>
                        <dd class="col-sm-8"><?= nl2br(htmlspecialchars($mouvement['commentaire'] ?? '-')) ?></dd>

                        <dt class="col-sm-4">Créé le</dt>
                        <dd class="col-sm-8"><?= date('d/m/Y H:i', strtotime($mouvement['created_at'])) ?></dd>

                        <dt class="col-sm-4">Modifié le</dt>
                        <dd class="col-sm-8"><?= date('d/m/Y H:i', strtotime($mouvement['updated_at'])) ?></dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h2 class="h5 mb-0">Informations de l'Agent</h2>
                </div>
                <div class="card-body">
                    <?php if ($mouvement['personnel_id']): ?>
                        <dl class="row mb-0">
                            <dt class="col-sm-4">Nom complet</dt>
                            <dd class="col-sm-8">
                                <a href="/APP_SGRHBMKH/personnel/show/<?= $mouvement['personnel_id'] ?>">
                                    <?= htmlspecialchars($mouvement['nom'] . ' ' . $mouvement['prenom']) ?>
                                </a>
                            </dd>

                            <dt class="col-sm-4">PPR</dt>
                            <dd class="col-sm-8"><?= htmlspecialchars($mouvement['ppr']) ?></dd>
                        </dl>
                    <?php else: ?>
                        <div class="alert alert-warning mb-0">
                            Les informations de l'agent ne sont pas disponibles.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (in_array($mouvement['type_mouvement'], ['MUTATION', 'MISE_A_DISPOSITION'])): ?>
            <div class="card">
                <div class="card-header">
                    <h2 class="h5 mb-0">Formations Sanitaires</h2>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Origine</dt>
                        <dd class="col-sm-8">
                            <?php if ($mouvement['origine_nom']): ?>
                                <a href="/APP_SGRHBMKH/formations/show/<?= $mouvement['formation_sanitaire_origine_id'] ?>">
                                    <?= htmlspecialchars($mouvement['origine_nom']) ?>
                                </a>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </dd>

                        <dt class="col-sm-4">Destination</dt>
                        <dd class="col-sm-8">
                            <?php if ($mouvement['destination_nom']): ?>
                                <a href="/APP_SGRHBMKH/formations/show/<?= $mouvement['formation_sanitaire_destination_id'] ?>">
                                    <?= htmlspecialchars($mouvement['destination_nom']) ?>
                                </a>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </dd>
                    </dl>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
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
?>
