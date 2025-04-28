<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= $types[$type_actuel] ?? $type_actuel ?></h1>
        <div>
            <a href="/APP_SGRHBMKH/mouvements/stats" class="btn btn-info">
                <i class="fas fa-chart-bar"></i> Statistiques
            </a>
            <a href="/APP_SGRHBMKH/mouvements" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>

    <!-- Sélecteur de type -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <label for="type_select" class="form-label">Type de mouvement</label>
                </div>
                <div class="col-md-9">
                    <select class="form-select" id="type_select" onchange="window.location.href='/mouvements/by-type/' + this.value">
                        <?php foreach ($types as $key => $value): ?>
                            <option value="<?= $key ?>" <?= $type_actuel === $key ? 'selected' : '' ?>>
                                <?= $value ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des mouvements -->
    <?php if (empty($mouvements)): ?>
        <div class="alert alert-info">
            Aucun mouvement de type "<?= $types[$type_actuel] ?? $type_actuel ?>" n'a été trouvé.
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
                                <?php if (in_array($type_actuel, ['MUTATION', 'MISE_A_DISPOSITION'])): ?>
                                    <th>Origine</th>
                                    <th>Destination</th>
                                <?php endif; ?>
                                <th>Commentaire</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($mouvements as $mouvement): ?>
                                <tr>
                                    <td><?= date('d/m/Y', strtotime($mouvement['date_mouvement'])) ?></td>
                                    <td>
                                        <?php if ($mouvement['personnel_id']): ?>
                                            <a href="/personnel/view/<?= $mouvement['personnel_id'] ?>">
                                                <?= htmlspecialchars($mouvement['nom'] . ' ' . $mouvement['prenom']) ?>
                                                <br>
                                                <small class="text-muted">PPR: <?= htmlspecialchars($mouvement['ppr']) ?></small>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                    <?php if (in_array($type_actuel, ['MUTATION', 'MISE_A_DISPOSITION'])): ?>
                                        <td>
                                            <?php if ($mouvement['origine_nom']): ?>
                                                <a href="/formations/view/<?= $mouvement['formation_sanitaire_origine_id'] ?>">
                                                    <?= htmlspecialchars($mouvement['origine_nom']) ?>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($mouvement['destination_nom']): ?>
                                                <a href="/formations/view/<?= $mouvement['formation_sanitaire_destination_id'] ?>">
                                                    <?= htmlspecialchars($mouvement['destination_nom']) ?>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                    <?php endif; ?>
                                    <td><?= nl2br(htmlspecialchars($mouvement['commentaire'] ?? '-')) ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="/mouvements/view/<?= $mouvement['id'] ?>" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Voir
                                            </a>
                                            <?php if ($canDelete): ?>
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                        onclick="confirmDelete(<?= $mouvement['id'] ?>, '<?= addslashes($types[$type_actuel]) ?>')">
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
    document.getElementById('deleteForm').action = '/mouvements/delete/' + id;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
