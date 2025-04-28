<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= htmlspecialchars($corps['nom_corps']) ?></h1>
        <div>
            <?php if ($canCreate): ?>
                <a href="/APP_SGRHBMKH/grades/create?corps_id=<?= $corps['id'] ?>" class="btn btn-success">
                    <i class="fas fa-plus"></i> Ajouter un Grade
                </a>
            <?php endif; ?>
            <a href="/APP_SGRHBMKH/corps" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h2 class="h5 mb-0">Détails du Corps</h2>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">ID</dt>
                        <dd class="col-sm-8"><?= $corps['id'] ?></dd>

                        <dt class="col-sm-4">Nom</dt>
                        <dd class="col-sm-8"><?= htmlspecialchars($corps['nom_corps']) ?></dd>

                        <dt class="col-sm-4">Type</dt>
                        <dd class="col-sm-8">
                            <?php
                            $badgeClass = match($corps['type_corps']) {
                                Corps::TYPE_MEDICAL => 'bg-success',
                                Corps::TYPE_PARAMEDICAL => 'bg-info',
                                Corps::TYPE_ADMINISTRATIF => 'bg-primary',
                                default => 'bg-secondary'
                            };
                            ?>
                            <span class="badge <?= $badgeClass ?>">
                                <?= $types[$corps['type_corps']] ?? $corps['type_corps'] ?>
                            </span>
                        </dd>

                        <dt class="col-sm-4">Description</dt>
                        <dd class="col-sm-8"><?= nl2br(htmlspecialchars($corps['description'] ?? '-')) ?></dd>

                        <dt class="col-sm-4">Créé le</dt>
                        <dd class="col-sm-8"><?= date('d/m/Y H:i', strtotime($corps['created_at'])) ?></dd>

                        <dt class="col-sm-4">Modifié le</dt>
                        <dd class="col-sm-8"><?= date('d/m/Y H:i', strtotime($corps['updated_at'])) ?></dd>
                    </dl>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <?php if ($canEdit): ?>
                            <a href="/APP_SGRHBMKH/corps/edit/<?= $corps['id'] ?>" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                        <?php endif; ?>
                        <?php if ($canDelete && empty($grades)): ?>
                            <button type="button" class="btn btn-danger" 
                                    onclick="confirmDelete(<?= $corps['id'] ?>, '<?= addslashes($corps['nom_corps']) ?>')"
                                    aria-label="Supprimer <?= htmlspecialchars($corps['nom_corps']) ?>">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="h5 mb-0">Grades associés</h2>
                    <?php if (!empty($grades)): ?>
                        <span class="badge bg-info">
                            <?= count($grades) ?> grade(s)
                        </span>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if (empty($grades)): ?>
                        <div class="alert alert-info" role="alert">
                            Aucun grade n'est associé à ce corps.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" role="grid" aria-label="Liste des grades">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Grade</th>
                                        <th scope="col">Échelle</th>
                                        <th scope="col">Date de création</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($grades as $grade): ?>
                                        <tr>
                                            <td><?= $grade['id'] ?></td>
                                            <td><?= htmlspecialchars($grade['nom_grade']) ?></td>
                                            <td><?= htmlspecialchars($grade['echelle'] ?? '-') ?></td>
                                            <td><?= date('d/m/Y', strtotime($grade['created_at'])) ?></td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Actions pour le grade">
                                                    <?php if ($canEdit): ?>
                                                        <a href="/APP_SGRHBMKH/grades/edit/<?= $grade['id'] ?>" 
                                                           class="btn btn-sm btn-warning"
                                                           aria-label="Modifier <?= htmlspecialchars($grade['nom_grade']) ?>">
                                                            <i class="fas fa-edit"></i> Modifier
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if ($canDelete): ?>
                                                        <button type="button" 
                                                                class="btn btn-sm btn-danger" 
                                                                onclick="confirmDeleteGrade(<?= $grade['id'] ?>, '<?= addslashes($grade['nom_grade']) ?>')"
                                                                aria-label="Supprimer <?= htmlspecialchars($grade['nom_grade']) ?>">
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
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression du corps -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer le corps "<span id="corpsName"></span>" ?</p>
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

<!-- Modal de confirmation de suppression du grade -->
<div class="modal fade" id="deleteGradeModal" tabindex="-1" aria-labelledby="deleteGradeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteGradeModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer le grade "<span id="gradeName"></span>" ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteGradeForm" action="" method="POST" style="display: inline;">
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, name) {
    document.getElementById('corpsName').textContent = name;
    document.getElementById('deleteForm').action = '/APP_SGRHBMKH/corps/delete/' + id;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

function confirmDeleteGrade(id, name) {
    document.getElementById('gradeName').textContent = name;
    document.getElementById('deleteGradeForm').action = '/APP_SGRHBMKH/grades/delete/' + id;
    new bootstrap.Modal(document.getElementById('deleteGradeModal')).show();
}
</script>
