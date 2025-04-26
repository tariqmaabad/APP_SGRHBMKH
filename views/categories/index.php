<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Catégories d'Établissement</h1>
        <a href="/APP_SGRHBMKH/categories/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouvelle Catégorie
        </a>
    </div>

    <?php if (empty($categories)): ?>
        <div class="alert alert-info">
            Aucune catégorie n'a été trouvée.
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom de la Catégorie</th>
                                <th>Description</th>
                                <th>Formations Sanitaires</th>
                                <th>Date de création</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $categorie): ?>
                                <tr>
                                    <td><?= $categorie['id'] ?></td>
                                    <td><?= htmlspecialchars($categorie['nom_categorie']) ?></td>
                                    <td><?= htmlspecialchars($categorie['description'] ?? '') ?></td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?= $categorie['nombre_formations'] ?> formation(s)
                                        </span>
                                    </td>
                                    <td><?= date('d/m/Y H:i', strtotime($categorie['created_at'])) ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="/APP_SGRHBMKH/categories/show/<?= $categorie['id'] ?>" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Voir
                                            </a>
                                            <a href="/APP_SGRHBMKH/categories/edit/<?= $categorie['id'] ?>" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i> Modifier
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    onclick="confirmDelete(<?= $categorie['id'] ?>, '<?= addslashes($categorie['nom_categorie']) ?>', <?= $categorie['nombre_formations'] ?>)">
                                                <i class="fas fa-trash"></i> Supprimer
                                            </button>
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
                <p>Êtes-vous sûr de vouloir supprimer la catégorie "<span id="categorieName"></span>" ?</p>
                <div id="warningFormations" class="alert alert-warning d-none">
                    Attention: Cette catégorie est utilisée par des formations sanitaires. Vous devez d'abord réaffecter ces formations à d'autres catégories.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteForm" action="" method="POST" style="display: inline;">
                    <button type="submit" class="btn btn-danger" id="deleteButton">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, name, nombreFormations) {
    document.getElementById('categorieName').textContent = name;
    document.getElementById('deleteForm').action = '/APP_SGRHBMKH/categories/delete/' + id;
    
    const warningFormations = document.getElementById('warningFormations');
    const deleteButton = document.getElementById('deleteButton');
    
    if (nombreFormations > 0) {
        warningFormations.classList.remove('d-none');
        deleteButton.disabled = true;
    } else {
        warningFormations.classList.add('d-none');
        deleteButton.disabled = false;
    }
    
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
