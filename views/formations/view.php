<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= htmlspecialchars($formation['nom_formation']) ?></h1>
        <div>
            <?php if ($canCreate): ?>
                <a href="/APP_SGRHBMKH/personnel/create?formation_id=<?= $formation['id'] ?>" class="btn btn-success">
                    <i class="fas fa-plus"></i> Ajouter du Personnel
                </a>
            <?php endif; ?>
            <a href="/APP_SGRHBMKH/formations" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h2 class="h5 mb-0">Détails de la Formation</h2>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">ID</dt>
                        <dd class="col-sm-8"><?= $formation['id'] ?></dd>

                        <dt class="col-sm-4">Nom</dt>
                        <dd class="col-sm-8"><?= htmlspecialchars($formation['nom_formation']) ?></dd>

                        <dt class="col-sm-4">Type</dt>
                        <dd class="col-sm-8"><?= htmlspecialchars($formation['type_formation'] ?? '-') ?></dd>

                        <dt class="col-sm-4">Province</dt>
                        <dd class="col-sm-8">
                            <a href="/APP_SGRHBMKH/provinces/show/<?= $formation['province_id'] ?>">
                                <?= htmlspecialchars($formation['nom_province']) ?>
                            </a>
                        </dd>

                        <dt class="col-sm-4">Catégorie</dt>
                        <dd class="col-sm-8">
                            <a href="/APP_SGRHBMKH/categories/show/<?= $formation['categorie_id'] ?>">
                                <?= htmlspecialchars($formation['nom_categorie']) ?>
                            </a>
                        </dd>

                        <dt class="col-sm-4">Milieu</dt>
                        <dd class="col-sm-8">
                            <span class="badge <?= $formation['milieu'] === 'URBAIN' ? 'bg-primary' : 'bg-success' ?>">
                                <?= $formation['milieu'] ?>
                            </span>
                        </dd>

                        <dt class="col-sm-4">Créé le</dt>
                        <dd class="col-sm-8"><?= date('d/m/Y H:i', strtotime($formation['created_at'])) ?></dd>

                        <dt class="col-sm-4">Modifié le</dt>
                        <dd class="col-sm-8"><?= date('d/m/Y H:i', strtotime($formation['updated_at'])) ?></dd>
                    </dl>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <?php if ($canEdit): ?>
                            <a href="/APP_SGRHBMKH/formations/edit/<?= $formation['id'] ?>" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                        <?php endif; ?>
                        <?php if ($canDelete && empty($stats['nombre_personnel'])): ?>
                            <button type="button" class="btn btn-danger" 
                                    onclick="confirmDelete(<?= $formation['id'] ?>, '<?= addslashes($formation['nom_formation']) ?>')">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php if ($stats): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h2 class="h5 mb-0">Statistiques du Personnel</h2>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="h3 mb-0"><?= $stats['nombre_personnel'] ?></div>
                            <div class="small text-muted">Total</div>
                        </div>
                        <div class="col-4">
                            <div class="h3 mb-0"><?= $stats['personnel_homme'] ?></div>
                            <div class="small text-muted">Hommes</div>
                        </div>
                        <div class="col-4">
                            <div class="h3 mb-0"><?= $stats['personnel_femme'] ?></div>
                            <div class="small text-muted">Femmes</div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <div class="col-md-8">
            <?php if ($stats && $stats['nombre_personnel'] > 0): ?>
            <div class="card">
                <div class="card-header">
                    <h2 class="h5 mb-0">Personnel Affecté</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nom et Prénom</th>
                                    <th>Grade</th>
                                    <th>Corps</th>
                                    <th>Spécialité</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($personnel as $person): ?>
                                <tr>
                                    <td>
                                        <a href="/APP_SGRHBMKH/personnel/show/<?= $person['id'] ?>">
                                            <?= htmlspecialchars($person['nom'] . ' ' . $person['prenom']) ?>
                                        </a>
                                    </td>
                                    <td><?= htmlspecialchars($person['nom_grade'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($person['nom_corps'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($person['nom_specialite'] ?? '-') ?></td>
                                    <td>
                                        <?php if ($canEdit): ?>
                                            <a href="/APP_SGRHBMKH/personnel/edit/<?= $person['id'] ?>" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($canCreate): ?>
                                            <a href="/APP_SGRHBMKH/mouvements/create?personnel_id=<?= $person['id'] ?>" class="btn btn-sm btn-info">
                                                <i class="fas fa-exchange-alt"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-info mb-0">
                        Aucun personnel n'est actuellement affecté à cette formation sanitaire.
                    </div>
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
                <p>Êtes-vous sûr de vouloir supprimer la formation "<span id="formationName"></span>" ?</p>
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
function confirmDelete(id, name) {
    document.getElementById('formationName').textContent = name;
    document.getElementById('deleteForm').action = '/APP_SGRHBMKH/formations/delete/' + id;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
