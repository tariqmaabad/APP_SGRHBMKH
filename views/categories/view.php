<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= htmlspecialchars($categorie['nom_categorie']) ?></h1>
        <div>
            <a href="/APP_SGRHBMKH/formations/create?categorie_id=<?= $categorie['id'] ?>" class="btn btn-success">
                <i class="fas fa-plus"></i> Ajouter une Formation
            </a>
            <a href="/APP_SGRHBMKH/categories" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h2 class="h5 mb-0">Détails de la Catégorie</h2>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">ID</dt>
                        <dd class="col-sm-8"><?= $categorie['id'] ?></dd>

                        <dt class="col-sm-4">Nom</dt>
                        <dd class="col-sm-8"><?= htmlspecialchars($categorie['nom_categorie']) ?></dd>

                        <dt class="col-sm-4">Description</dt>
                        <dd class="col-sm-8"><?= nl2br(htmlspecialchars($categorie['description'] ?? '-')) ?></dd>

                        <dt class="col-sm-4">Créé le</dt>
                        <dd class="col-sm-8"><?= date('d/m/Y H:i', strtotime($categorie['created_at'])) ?></dd>

                        <dt class="col-sm-4">Modifié le</dt>
                        <dd class="col-sm-8"><?= date('d/m/Y H:i', strtotime($categorie['updated_at'])) ?></dd>
                    </dl>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="/APP_SGRHBMKH/categories/edit/<?= $categorie['id'] ?>" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        <?php if (empty($formations)): ?>
                            <button type="button" class="btn btn-danger" 
                                    onclick="confirmDelete(<?= $categorie['id'] ?>, '<?= addslashes($categorie['nom_categorie']) ?>')">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="h5 mb-0">Formations Sanitaires</h2>
                </div>
                <div class="card-body">
                    <?php if (empty($formations)): ?>
                        <div class="alert alert-info">
                            Aucune formation sanitaire n'est associée à cette catégorie.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Formation</th>
                                        <th>Province</th>
                                        <th>Type</th>
                                        <th>Milieu</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($formations as $formation): ?>
                                        <tr>
                                            <td><?= $formation['id'] ?></td>
                                            <td><?= htmlspecialchars($formation['nom_formation']) ?></td>
                                            <td><?= htmlspecialchars($formation['nom_province']) ?></td>
                                            <td><?= htmlspecialchars($formation['type_formation'] ?? '-') ?></td>
                                            <td><?= htmlspecialchars($formation['milieu']) ?></td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="/APP_SGRHBMKH/formations/edit/<?= $formation['id'] ?>" class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i> Modifier
                                                    </a>
                                                    <a href="/APP_SGRHBMKH/formations/show/<?= $formation['id'] ?>" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i> Voir
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
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
                <p>Êtes-vous sûr de vouloir supprimer la catégorie "<span id="categorieName"></span>" ?</p>
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
    document.getElementById('categorieName').textContent = name;
    document.getElementById('deleteForm').action = '/APP_SGRHBMKH/categories/delete/' + id;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
