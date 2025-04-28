<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Grades</h1>
        <?php if ($canCreate): ?>
            <a href="/APP_SGRHBMKH/grades/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nouveau Grade
            </a>
        <?php endif; ?>
    </div>

    <?php if (empty($grades)): ?>
        <div class="alert alert-info">
            Aucun grade n'a été trouvé.
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Grade</th>
                                <th>Corps</th>
                                <th>Échelle</th>
                                <th>Date de création</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($grades as $grade): ?>
                                <tr>
                                    <td><?= $grade['id'] ?></td>
                                    <td><?= htmlspecialchars($grade['nom_grade']) ?></td>
                                    <td>
                                        <?php if ($grade['nom_corps']): ?>
                                      
                                                <?= htmlspecialchars($grade['nom_corps']) ?>
                                       
                                        <?php else: ?>
                                            <span class="text-muted">Non défini</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($grade['echelle'] ?? '-') ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($grade['created_at'])) ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <?php if ($canEdit): ?>
                                                <a href="/APP_SGRHBMKH/grades/edit/<?= $grade['id'] ?>" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i> Modifier
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($canDelete): ?>
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                        onclick="confirmDelete(<?= $grade['id'] ?>, '<?= addslashes($grade['nom_grade']) ?>')">
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
                <p>Êtes-vous sûr de vouloir supprimer le grade "<span id="gradeName"></span>" ?</p>
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
    document.getElementById('gradeName').textContent = name;
    document.getElementById('deleteForm').action = '/APP_SGRHBMKH/grades/delete/' + id;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
