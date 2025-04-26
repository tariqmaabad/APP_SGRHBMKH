<div class="container-fluid">
    <h2 class="mb-4">Rapport des Mouvements</h2>

    <!-- Filtres -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Type de Mouvement</label>
                    <select name="type_mouvement" class="form-select">
                        <option value="">Tous les types</option>
                        <option value="MUTATION" <?= isset($_GET['type_mouvement']) && $_GET['type_mouvement'] == 'MUTATION' ? 'selected' : '' ?>>Mutation</option>
                        <option value="FORMATION" <?= isset($_GET['type_mouvement']) && $_GET['type_mouvement'] == 'FORMATION' ? 'selected' : '' ?>>Formation</option>
                        <option value="MISE_A_DISPOSITION" <?= isset($_GET['type_mouvement']) && $_GET['type_mouvement'] == 'MISE_A_DISPOSITION' ? 'selected' : '' ?>>Mise à disposition</option>
                        <option value="SUSPENSION" <?= isset($_GET['type_mouvement']) && $_GET['type_mouvement'] == 'SUSPENSION' ? 'selected' : '' ?>>Suspension</option>
                        <option value="RETRAITE_AGE" <?= isset($_GET['type_mouvement']) && $_GET['type_mouvement'] == 'RETRAITE_AGE' ? 'selected' : '' ?>>Retraite</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date début</label>
                    <input type="date" name="date_debut" class="form-control" value="<?= isset($_GET['date_debut']) ? $_GET['date_debut'] : '' ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date fin</label>
                    <input type="date" name="date_fin" class="form-control" value="<?= isset($_GET['date_fin']) ? $_GET['date_fin'] : '' ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistiques globales -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Aperçu Global</h5>
                </div>
                <div class="card-body">
                    <canvas id="statsChart" style="width: 100%; height: 120px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Répartition par Type de Mouvement</h5>
                </div>
                <div class="card-body">
                    <canvas id="typeChart" style="width: 100%; height: 400px;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Évolution Mensuelle</h5>
                </div>
                <div class="card-body">
                    <canvas id="evolutionChart" style="width: 100%; height: 400px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des mouvements -->
    <div class="card">
        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Liste des Mouvements</h5>
            <div class="text-muted small">Total: <?= count($mouvements) ?> mouvements</div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4">Date</th>
                            <th>Personnel</th>
                            <th>Type</th>
                            <th>Origine</th>
                            <th>Destination</th>
                            <th class="px-4">Commentaire</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($mouvements)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="fas fa-info-circle me-2"></i>Aucun mouvement trouvé
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($mouvements as $mouvement): ?>
                                <tr>
                                    <td class="px-4"><?= date('d/m/Y', strtotime($mouvement['date_mouvement'])) ?></td>
                                    <td>
                                        <div class="fw-medium"><?= $mouvement['nom'] . ' ' . $mouvement['prenom'] ?></div>
                                    </td>
                                    <td>
                                        <?php
                                        $typeClass = match($mouvement['type_mouvement']) {
                                            'MUTATION' => 'success',
                                            'FORMATION' => 'info',
                                            'MISE_A_DISPOSITION' => 'primary',
                                            'SUSPENSION' => 'warning',
                                            'RETRAITE_AGE' => 'danger',
                                            default => 'secondary'
                                        };
                                        ?>
                                        <span class="badge bg-<?= $typeClass ?>">
                                            <?= str_replace('_', ' ', $mouvement['type_mouvement']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if (empty($mouvement['origine'])): ?>
                                            <span class="text-muted">-</span>
                                        <?php else: ?>
                                            <?= $mouvement['origine'] ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (empty($mouvement['destination'])): ?>
                                            <span class="text-muted">-</span>
                                        <?php else: ?>
                                            <?= $mouvement['destination'] ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4">
                                        <?php if (empty($mouvement['commentaire'])): ?>
                                            <span class="text-muted">-</span>
                                        <?php else: ?>
                                            <span class="text-truncate d-inline-block" style="max-width: 200px;" title="<?= htmlspecialchars($mouvement['commentaire']) ?>">
                                                <?= $mouvement['commentaire'] ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js and plugins -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
// Register Chart.js components
Chart.register({
    id: 'customCanvasBackgroundColor',
    beforeDraw: (chart) => {
        const ctx = chart.canvas.getContext('2d');
        ctx.save();
        ctx.globalCompositeOperation = 'destination-over';
        ctx.fillStyle = 'white';
        ctx.fillRect(0, 0, chart.width, chart.height);
        ctx.restore();
    }
});

// Global Chart.js defaults
Chart.defaults.responsive = true;
Chart.defaults.maintainAspectRatio = false;
Chart.defaults.animation = {
    duration: 1000,
    easing: 'easeInOutQuart'
};
Chart.defaults.font.family = "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif";
Chart.defaults.font.size = 12;
// Colors and data preparation
const colors = {
    total: '#0d6efd',
    mutations: '#198754',
    formations: '#0dcaf0',
    autres: '#ffc107'
};

// Initialize charts when DOM is ready
window.addEventListener('load', function() {
    try {
        console.log('Initializing charts...');

        // Initialize Stats Chart
        const statsCtx = document.getElementById('statsChart');
        console.log('Stats data:', [
            <?= $stats['total'] ?>,
            <?= $stats['mutations'] ?>,
            <?= $stats['formations'] ?>,
            <?= $stats['autres'] ?>
        ]);
        const statsChart = new Chart(statsCtx, {
    type: 'bar',
    data: {
        labels: ['Total', 'Mutations', 'Formations', 'Autres'],
        datasets: [{
            data: [
                <?= $stats['total'] ?>,
                <?= $stats['mutations'] ?>,
                <?= $stats['formations'] ?>,
                <?= $stats['autres'] ?>
            ],
            backgroundColor: Object.values(colors),
            borderColor: 'transparent',
            borderRadius: 4
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: (context) => `${context.label}: ${context.raw} mouvements`
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    display: false
                }
            },
            x: {
                grid: {
                    color: '#eee'
                }
            }
        }
    }
});

        // Type Distribution Chart
        const typeCtx = document.getElementById('typeChart');
        console.log('Type data:', <?= json_encode($stats['par_type']) ?>);
        const typeChart = new Chart(typeCtx, {
    type: 'pie',
    data: {
        labels: <?= json_encode(array_map(function($item) { return $item['type']; }, $stats['par_type'])) ?>,
        datasets: [{
            data: <?= json_encode(array_map(function($item) { return $item['total']; }, $stats['par_type'])) ?>,
            backgroundColor: [
                'rgba(13, 110, 253, 0.8)',
                'rgba(25, 135, 84, 0.8)',
                'rgba(13, 202, 240, 0.8)',
                'rgba(255, 193, 7, 0.8)',
                'rgba(220, 53, 69, 0.8)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'right',
                labels: {
                    padding: 20
                }
            },
            tooltip: {
                callbacks: {
                    label: (context) => {
                        const value = context.raw;
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = Math.round((value / total) * 100);
                        return `${value} mouvements (${percentage}%)`;
                    }
                }
            }
        }
    }
});

        // Evolution Chart
        const evolutionCtx = document.getElementById('evolutionChart');
        console.log('Evolution data:', <?= json_encode($stats['evolution_mensuelle']) ?>);
        const evolutionChart = new Chart(evolutionCtx, {
    type: 'line',
    data: {
        labels: <?= json_encode(array_map(function($item) { return $item['mois']; }, $stats['evolution_mensuelle'])) ?>,
        datasets: [{
            label: 'Mouvements',
            data: <?= json_encode(array_map(function($item) { return $item['total']; }, $stats['evolution_mensuelle'])) ?>,
            borderColor: colors.total,
            backgroundColor: 'rgba(13, 110, 253, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4,
            pointRadius: 4,
            pointBackgroundColor: 'white',
            pointBorderColor: colors.total,
            pointBorderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: (context) => `${context.raw} mouvements`
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Nombre de mouvements'
                },
                grid: {
                    color: '#eee'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
        });

        console.log('Charts initialized successfully!');
    } catch (error) {
        console.error('Error initializing charts:', error);
    }
});
</script>
