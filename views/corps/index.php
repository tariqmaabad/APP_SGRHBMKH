<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-sitemap me-2"></i>Corps</h1>
        <?php if ($canCreate): ?>
            <a href="/APP_SGRHBMKH/corps/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nouveau Corps
            </a>
        <?php endif; ?>
    </div>

    <!-- Statistiques par type -->
    <div class="row mb-4">
        <?php foreach ($stats as $stat): ?>
            <?php
            $typeLabel = $types[$stat['type_corps']] ?? $stat['type_corps'];
            $badgeClass = match($stat['type_corps']) {
                Corps::TYPE_MEDICAL => 'bg-success',
                Corps::TYPE_PARAMEDICAL => 'bg-info',
                Corps::TYPE_ADMINISTRATIF => 'bg-primary',
                default => 'bg-secondary'
            };
            ?>
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title d-flex justify-content-between align-items-center">
                            <?= $typeLabel ?>
                            <span class="badge <?= $badgeClass ?>"><?= $stat['total_corps'] ?> corps</span>
                        </h5>
                        <div class="mt-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Grades associés:</span>
                                <span class="badge bg-secondary"><?= $stat['total_grades'] ?> grade(s)</span>
                            </div>
                            <a href="/APP_SGRHBMKH/corps?type=<?= $stat['type_corps'] ?>" 
                               class="btn btn-sm btn-outline-primary w-100"
                               aria-label="Voir les corps de type <?= $typeLabel ?>">
                                Voir les corps
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Filtres -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="/APP_SGRHBMKH/corps" method="GET" class="row g-3" role="search">
                <div class="col-md-4">
                    <label for="type" class="form-label">Filtrer par type</label>
                    <select class="form-select" id="type" name="type" aria-label="Filtrer les corps par type">
                        <option value="">Tous les types</option>
                        <?php foreach ($types as $key => $value): ?>
                            <option value="<?= $key ?>" <?= ($current_type === $key) ? 'selected' : '' ?>>
                                <?= $value ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Filtrer</button>
                    <?php if ($current_type): ?>
                        <a href="/APP_SGRHBMKH/corps" class="btn btn-secondary">Réinitialiser</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <?php if (empty($corps)): ?>
        <div class="alert alert-info" role="alert">
            Aucun corps n'a été trouvé.
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" role="grid" aria-label="Liste des corps">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Type</th>
                                <th scope="col">Nom du Corps</th>
                                <th scope="col">Description</th>
                                <th scope="col">Grades</th>
                                <th scope="col">Date de création</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($corps as $c): ?>
                                <?php
                                $badgeClass = match($c['type_corps']) {
                                    Corps::TYPE_MEDICAL => 'bg-success',
                                    Corps::TYPE_PARAMEDICAL => 'bg-info',
                                    Corps::TYPE_ADMINISTRATIF => 'bg-primary',
                                    default => 'bg-secondary'
                                };
                                ?>
                                <tr>
                                    <td><?= $c['id'] ?></td>
                                    <td>
                                        <span class="badge <?= $badgeClass ?>">
                                            <?= $types[$c['type_corps']] ?? $c['type_corps'] ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($c['nom_corps']) ?></td>
                                    <td><?= htmlspecialchars($c['description'] ?? '') ?></td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?= $c['nombre_grades'] ?> grade(s)
                                        </span>
                                    </td>
                                    <td><?= date('d/m/Y H:i', strtotime($c['created_at'])) ?></td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Actions pour le corps">
                                            <a href="/APP_SGRHBMKH/corps/show/<?= $c['id'] ?>" 
                                               class="btn btn-sm btn-info" 
                                               aria-label="Voir les détails de <?= htmlspecialchars($c['nom_corps']) ?>">
                                                <i class="fas fa-eye"></i> Voir
                                            </a>
                                            <?php if ($canEdit): ?>
                                                <a href="/APP_SGRHBMKH/corps/edit/<?= $c['id'] ?>" 
                                                   class="btn btn-sm btn-warning"
                                                   aria-label="Modifier <?= htmlspecialchars($c['nom_corps']) ?>">
                                                    <i class="fas fa-edit"></i> Modifier
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($canDelete && $c['nombre_grades'] == 0): ?>
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger" 
                                                        onclick="confirmDelete(<?= $c['id'] ?>, '<?= addslashes($c['nom_corps']) ?>')"
                                                        aria-label="Supprimer <?= htmlspecialchars($c['nom_corps']) ?>">
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
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="text-muted mb-0">
                            Affichage de <?php echo ($pagination['current_page'] - 1) * $pagination['per_page'] + 1; ?> 
                            à <?php echo min($pagination['current_page'] * $pagination['per_page'], $pagination['total']); ?> 
                            sur <?php echo $pagination['total']; ?> corps
                        </p>
                        <nav aria-label="Navigation des pages">
                            <ul class="pagination mb-0">
                                <?php if ($pagination['has_previous']): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $pagination['previous_page']])); ?>">
                                            <i class="fas fa-chevron-left"></i>
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
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Modal de confirmation de suppression -->
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

<script>
function confirmDelete(id, name) {
    document.getElementById('corpsName').textContent = name;
    document.getElementById('deleteForm').action = '/APP_SGRHBMKH/corps/delete/' + id;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
