<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-map-marker-alt me-2"></i>Provinces</h1>
        <?php if ($canCreate): ?>
            <a href="/APP_SGRHBMKH/provinces/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nouvelle Province
            </a>
        <?php endif; ?>
    </div>

    <?php if (empty($provinces)): ?>
        <div class="alert alert-info">
            Aucune province n'a été trouvée.
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom de la Province</th>
                                <th>Date de création</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($provinces as $province): ?>
                                <tr>
                                    <td><?= $province['id'] ?></td>
                                    <td>
                                      
                                            <?= htmlspecialchars($province['nom_province']) ?>
                                       
                                    </td>
                                    <td><?= date('d/m/Y H:i', strtotime($province['created_at'])) ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                         
                                            <?php if ($canEdit): ?>
                                                <a href="/APP_SGRHBMKH/provinces/edit/<?= $province['id'] ?>" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i> Modifier
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($canDelete): ?>
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                        onclick="confirmDelete(<?= $province['id'] ?>, '<?= addslashes($province['nom_province']) ?>', <?= $province['nombre_formations'] ?? 0 ?>)">
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
                <p>Êtes-vous sûr de vouloir supprimer la province "<span id="provinceName"></span>" ?</p>
                <div id="warningFormations" class="alert alert-warning d-none">
                    Attention: Cette province contient des formations sanitaires. Vous devez d'abord les réaffecter.
                </div>
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
function confirmDelete(id, name, nombreFormations) {
    document.getElementById('provinceName').textContent = name;
    document.getElementById('deleteForm').action = '/APP_SGRHBMKH/provinces/delete/' + id;
    
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
