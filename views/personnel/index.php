<div class="container-fluid">
    <?php if (isset($messages) && !empty($messages)): ?>
        <?php foreach ($messages as $message): ?>
            <div class="alert alert-<?php echo $message['type'] === 'error' ? 'danger' : $message['type']; ?> alert-dismissible fade show">
                <?php echo $message['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Liste du Personnel</h2>
        <?php if ($canCreate): ?>
            <a href="/APP_SGRHBMKH/personnel/create" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Nouveau Personnel
            </a>
        <?php endif; ?>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form action="/APP_SGRHBMKH/personnel" method="GET" class="row g-3">
                <div class="col-md-3">
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="Rechercher par PPR, Nom, Prénom ou CIN..." 
                           value="<?php echo htmlspecialchars($searchTerm ?? ''); ?>">
                </div>
                <div class="col-md-2">
                    <select name="corps_id" class="form-select">
                        <option value="">-- Corps --</option>
                        <?php foreach ($corps_list as $corps): ?>
                            <option value="<?php echo $corps['id']; ?>" 
                                <?php echo ($corps_id == $corps['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($corps['nom_corps']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="grade_id" class="form-select">
                        <option value="">-- Grade --</option>
                        <?php foreach ($grades_list as $grade): ?>
                            <option value="<?php echo isset($grade['id']) ? $grade['id'] : ''; ?>" 
                                <?php echo (isset($grade_id) && isset($grade['id']) && $grade_id == $grade['id']) ? 'selected' : ''; ?>>
                                <?php echo isset($grade['nom_grade']) ? htmlspecialchars($grade['nom_grade']) : ''; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="formation_id" class="form-select">
                        <option value="">-- Formation Sanitaire --</option>
                        <?php foreach ($formations_list as $formation): ?>
                            <option value="<?php echo isset($formation['id']) ? $formation['id'] : ''; ?>" 
                                <?php echo (isset($formation_id) && isset($formation['id']) && $formation_id == $formation['id']) ? 'selected' : ''; ?>>
                                <?php echo isset($formation['nom_formation']) ? htmlspecialchars($formation['nom_formation']) : ''; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i>Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <?php if (empty($personnel) && !empty($searchTerm)): ?>
                    <div class="alert alert-info">
                        Aucun résultat trouvé pour la recherche "<?php echo htmlspecialchars($searchTerm); ?>"
                    </div>
                <?php endif; ?>
                
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <?php 
                            $columns = [
                                'ppr' => 'PPR',
                                'nom' => 'Nom & Prénom',
                                'cin' => 'CIN',
                                'corps_id' => 'Corps',
                                'grade_id' => 'Grade',
                                'formation_sanitaire_id' => 'Formation Sanitaire',
                                'province_id' => 'Province'
                            ];
                            foreach ($columns as $column => $label): 
                                $currentOrder = ($sort === $column) ? $order : 'ASC';
                                $newOrder = ($currentOrder === 'ASC') ? 'DESC' : 'ASC';
                                $sortIcon = '';
                                if ($sort === $column) {
                                    $sortIcon = ($order === 'ASC') ? '↑' : '↓';
                                }
                            ?>
                                <th>
                                    <a href="?<?php echo http_build_query(array_merge($_GET, ['sort' => $column, 'order' => $newOrder])); ?>" 
                                       class="text-dark text-decoration-none">
                                        <?php echo $label; ?> <?php echo $sortIcon; ?>
                                    </a>
                                </th>
                            <?php endforeach; ?>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($personnel)): ?>
                            <?php foreach ($personnel as $p): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($p['ppr']); ?></td>
                                    <td><?php echo htmlspecialchars(($p['nom'] ?? '') . ' ' . ($p['prenom'] ?? '')); ?></td>
                                    <td><?php echo htmlspecialchars($p['cin']); ?></td>
                    <td><?php echo htmlspecialchars($p['nom_corps'] ?? '-'); ?></td>
                    <td><?php echo htmlspecialchars($p['nom_grade'] ?? '-'); ?></td>
                    <td><?php echo htmlspecialchars($p['nom_formation'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($p['nom_province'] ?? '-'); ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="/APP_SGRHBMKH/personnel/show/<?php echo $p['id']; ?>" 
                                               class="btn btn-sm btn-info" 
                                               data-bs-toggle="tooltip" 
                                               title="Détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php if ($canEdit): ?>
                                                <a href="/APP_SGRHBMKH/personnel/edit/<?php echo $p['id']; ?>" 
                                                   class="btn btn-sm btn-warning" 
                                                   data-bs-toggle="tooltip" 
                                                   title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($canDelete): ?>
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#deleteModal<?php echo $p['id']; ?>"
                                                        title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Modal de confirmation de suppression -->
                                        <div class="modal fade" id="deleteModal<?php echo $p['id']; ?>" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Confirmer la suppression</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Êtes-vous sûr de vouloir supprimer <?php echo htmlspecialchars(($p['nom'] ?? '') . ' ' . ($p['prenom'] ?? '')); ?> ?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                        <form action="/APP_SGRHBMKH/personnel/delete/<?php echo $p['id']; ?>" method="POST" class="d-inline">
                                                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                                            <button type="submit" class="btn btn-danger">Supprimer</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">Aucun personnel trouvé</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

                        <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
                            <div class="mt-3">
                                <p class="text-muted">
                                    Affichage de <?php echo ($pagination['current_page'] - 1) * $pagination['per_page'] + 1; ?> 
                                    à <?php echo min($pagination['current_page'] * $pagination['per_page'], $pagination['total']); ?> 
                                    sur <?php echo $pagination['total']; ?> résultats
                                </p>
                            </div>
                <nav aria-label="Navigation des pages">
                    <ul class="pagination justify-content-center">
                        <?php if ($pagination['has_previous']): ?>
                            <li class="page-item">
                                <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $pagination['previous_page']])); ?>">Précédent</a>
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
                                <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $pagination['next_page']])); ?>">Suivant</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>
