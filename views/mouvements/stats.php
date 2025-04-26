<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Statistiques des Mouvements</h1>
        <a href="/APP_SGRHBMKH/mouvements" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <!-- Résumé global -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Vue d'ensemble</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Type de Mouvement</th>
                                    <th class="text-center">Cette année</th>
                                    <th class="text-center">Année précédente</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Évolution</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stats as $stat): ?>
                                    <tr>
                                        <td>
                                            <a href="/APP_SGRHBMKH/mouvements/by-type/<?= $stat['type_mouvement'] ?>">
                                                <?= $types[$stat['type_mouvement']] ?? $stat['type_mouvement'] ?>
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info">
                                                <?= $stat['cette_annee'] ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary">
                                                <?= $stat['annee_precedente'] ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <strong><?= $stat['total'] ?></strong>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            if ($stat['annee_precedente'] > 0) {
                                                $evolution = (($stat['cette_annee'] - $stat['annee_precedente']) / $stat['annee_precedente']) * 100;
                                                $badge_class = $evolution > 0 ? 'success' : ($evolution < 0 ? 'danger' : 'secondary');
                                                $icon = $evolution > 0 ? 'up' : ($evolution < 0 ? 'down' : 'right');
                                                echo sprintf(
                                                    '<span class="badge bg-%s"><i class="fas fa-arrow-%s"></i> %.1f%%</span>',
                                                    $badge_class,
                                                    $icon,
                                                    abs($evolution)
                                                );
                                            } else {
                                                echo '<span class="text-muted">-</span>';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Visualisations -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Répartition par type</h5>
                    <canvas id="typeDistributionChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Comparaison annuelle</h5>
                    <canvas id="yearComparisonChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Préparation des données pour les graphiques
    const stats = <?= json_encode($stats) ?>;
    const types = <?= json_encode($types) ?>;
    
    // Graphique de répartition par type
    const distributionCtx = document.getElementById('typeDistributionChart').getContext('2d');
    new Chart(distributionCtx, {
        type: 'pie',
        data: {
            labels: stats.map(s => types[s.type_mouvement] || s.type_mouvement),
            datasets: [{
                data: stats.map(s => s.total),
                backgroundColor: [
                    '#dc3545', // Rouge
                    '#0d6efd', // Bleu
                    '#198754', // Vert
                    '#ffc107', // Jaune
                    '#6610f2', // Violet
                    '#fd7e14', // Orange
                    '#20c997', // Turquoise
                    '#6c757d'  // Gris
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });

    // Graphique de comparaison annuelle
    const comparisonCtx = document.getElementById('yearComparisonChart').getContext('2d');
    new Chart(comparisonCtx, {
        type: 'bar',
        data: {
            labels: stats.map(s => types[s.type_mouvement] || s.type_mouvement),
            datasets: [
                {
                    label: 'Cette année',
                    data: stats.map(s => s.cette_annee),
                    backgroundColor: '#0d6efd'
                },
                {
                    label: 'Année précédente',
                    data: stats.map(s => s.annee_precedente),
                    backgroundColor: '#6c757d'
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top'
                }
            }
        }
    });
});
</script>
