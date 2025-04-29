<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-hospital me-2"></i>Formations Sanitaires</h1>
        <?php if ($canCreate): ?>
            <a href="/APP_SGRHBMKH/formations/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nouvelle Formation
            </a>
        <?php endif; ?>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="categorie_filter">Sélectionner une catégorie</label>
                        <select id="categorie_filter" class="form-select">
                            <option value="">Toutes les catégories</option>
                            <?php foreach ($categories as $categorie): ?>
                                <option value="<?= $categorie['id'] ?>"><?= htmlspecialchars($categorie['nom_categorie']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (empty($formations)): ?>
        <div class="alert alert-info">
            Aucune formation sanitaire n'a été trouvée.
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Formation</th>
                                <th>Province</th>
                                <th>Catégorie</th>
                                <th>Type</th>
                                <th>Milieu</th>
                                <th>Personnel</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($formations as $formation): ?>
                                <tr>
                                    <td><?= $formation['id'] ?></td>
                                    <td><?= htmlspecialchars($formation['nom_formation']) ?></td>
                                    <td>
                                        <?php if ($formation['nom_province']): ?>
                                                <?= htmlspecialchars($formation['nom_province']) ?>
                                            
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($formation['nom_categorie']): ?>
                                                <?= htmlspecialchars($formation['nom_categorie']) ?>
                                           
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($formation['type_formation'] ?? '-') ?></td>
                                    <td>
                                        <span class="badge <?= $formation['milieu'] === 'URBAIN' ? 'bg-primary' : 'bg-success' ?>">
                                            <?= $formation['milieu'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?= $formation['nombre_personnel'] ?> agent(s)
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="/APP_SGRHBMKH/formations/show/<?= $formation['id'] ?>" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Voir
                                            </a>
                                            <?php if ($canEdit): ?>
                                                <a href="/APP_SGRHBMKH/formations/edit/<?= $formation['id'] ?>" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i> Modifier
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($canDelete): ?>
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                        onclick="confirmDelete(<?= $formation['id'] ?>, '<?= addslashes($formation['nom_formation']) ?>', <?= $formation['nombre_personnel'] ?>)">
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
                <p>Êtes-vous sûr de vouloir supprimer la formation "<span id="formationName"></span>" ?</p>
                <div id="warningPersonnel" class="alert alert-warning d-none">
                    Attention: Cette formation sanitaire compte du personnel. Vous devez d'abord réaffecter ce personnel.
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
// Function to escape HTML to prevent XSS
function escapeHtml(text) {
    return text
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

// Handle category filter change
document.getElementById('categorie_filter').addEventListener('change', function() {
    const categorieId = this.value;
    const tableBody = document.querySelector('table tbody');
    const noResultsDiv = document.querySelector('.alert-info');
    
    // Show loading state
    if (tableBody) {
        tableBody.innerHTML = '<tr><td colspan="8" class="text-center"><i class="fas fa-spinner fa-spin me-2"></i> Chargement...</td></tr>';
    }
    
    // Make API call to get filtered formations
    fetch(`/APP_SGRHBMKH/api/formations/by-categorie/${categorieId || 'all'}`)
        .then(response => response.json())
        .then(formations => {
            if (formations.length === 0) {
                if (tableBody) tableBody.innerHTML = '<tr><td colspan="8" class="text-center">Aucune formation sanitaire trouvée</td></tr>';
                return;
            }

            const canEdit = <?= json_encode($canEdit) ?>;
            const canDelete = <?= json_encode($canDelete) ?>;

            const rows = formations.map(formation => `
                <tr>
                    <td>${formation.id}</td>
                    <td>${escapeHtml(formation.nom_formation)}</td>
                    <td>${formation.nom_province ? escapeHtml(formation.nom_province) : '<span class="text-muted">-</span>'}</td>
                    <td>${formation.nom_categorie ? escapeHtml(formation.nom_categorie) : '<span class="text-muted">-</span>'}</td>
                    <td>${formation.type_formation ? escapeHtml(formation.type_formation) : '-'}</td>
                    <td>
                        <span class="badge ${formation.milieu === 'URBAIN' ? 'bg-primary' : 'bg-success'}">
                            ${formation.milieu}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-info">
                            ${formation.nombre_personnel || 0} agent(s)
                        </span>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="/APP_SGRHBMKH/formations/show/${formation.id}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> Voir
                            </a>
                            ${canEdit ? `
                                <a href="/APP_SGRHBMKH/formations/edit/${formation.id}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Modifier
                                </a>
                            ` : ''}
                            ${canDelete ? `
                                <button type="button" class="btn btn-sm btn-danger" 
                                        onclick="confirmDelete(${formation.id}, '${escapeHtml(formation.nom_formation)}', ${formation.nombre_personnel || 0})">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            ` : ''}
                        </div>
                    </td>
                </tr>
            `).join('');

            if (tableBody) tableBody.innerHTML = rows;
        })
        .catch(error => {
            console.error('Error:', error);
            if (tableBody) {
                tableBody.innerHTML = '<tr><td colspan="8" class="text-center text-danger"><i class="fas fa-exclamation-circle me-2"></i>Erreur lors du chargement des données</td></tr>';
            }
        });
});

// Delete confirmation handler
function confirmDelete(id, name, nombrePersonnel) {
    document.getElementById('formationName').textContent = name;
    document.getElementById('deleteForm').action = '/APP_SGRHBMKH/formations/delete/' + id;
    
    const warningPersonnel = document.getElementById('warningPersonnel');
    const deleteButton = document.getElementById('deleteButton');
    
    if (nombrePersonnel > 0) {
        warningPersonnel.classList.remove('d-none');
        deleteButton.disabled = true;
    } else {
        warningPersonnel.classList.add('d-none');
        deleteButton.disabled = false;
    }
    
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
