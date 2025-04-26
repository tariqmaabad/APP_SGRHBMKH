<div class="container-fluid">
    <h2 class="mb-4" style="color: #0066CC;">Chiffres clés de l'offre de soins</h2>

    <!-- Main Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card h-100" style="background-color: #003366;">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Total du personnel</h6>
                            <h2 class="mb-0"><?php echo number_format($stats['total_personnel'] ?? 0); ?></h2>
                        </div>
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100" style="background-color: #800080;">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Provinces sanitaires</h6>
                            <h2 class="mb-0"><?php echo number_format($stats['total_provinces'] ?? 0); ?></h2>
                        </div>
                        <i class="fas fa-map-marker-alt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100" style="background-color: #008080;">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Établissements urbains</h6>
                            <h2 class="mb-0"><?php echo number_format($stats['total_etablissements_urbains'] ?? 0); ?></h2>
                        </div>
                        <i class="fas fa-city fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100" style="background-color: #D2B48C;">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Établissements ruraux</h6>
                            <h2 class="mb-0"><?php echo number_format($stats['total_etablissements_ruraux'] ?? 0); ?></h2>
                        </div>
                        <i class="fas fa-clinic-medical fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

  

    <!-- Detailed Information -->
    <div class="row">
        <!-- Human Resources -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-md me-2"></i>RESSOURCES HUMAINES
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="resourcesChart"></canvas>
                    <p class="text-muted mt-3 mb-0 small">*Personnel des établissements de santé publics uniquement</p>
                </div>
            </div>
        </div>

        <!-- Infrastructure -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-hospital me-2"></i>INFRASTRUCTURES PUBLIQUES
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="infrastructureChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
  <!-- Personnel Statistics -->
  <div class="row mb-4">
        <!-- Gender Distribution -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-venus-mars me-2"></i>RÉPARTITION PAR GENRE
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="genderChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Marital Status -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users me-2"></i>SITUATION FAMILIALE
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="maritalChart"></canvas>
                </div>
            </div>
        </div>
    </div>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const colors = {
    navy: '#003366',
    purple: '#800080',
    teal: '#008080',
    tan: '#D2B48C',
    primary: '#0d6efd',
    success: '#198754',
    info: '#0dcaf0',
    warning: '#ffc107'
};

// Gender Distribution Chart
new Chart(document.getElementById('genderChart'), {
    type: 'doughnut',
    data: {
        labels: ['Hommes', 'Femmes'],
        datasets: [{
            data: [
                <?= $stats['effectif_masculin'] ?? 0 ?>,
                <?= $stats['effectif_feminin'] ?? 0 ?>
            ],
            backgroundColor: [colors.primary, colors.info]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'right' }
        }
    }
});

// Marital Status Chart
new Chart(document.getElementById('maritalChart'), {
    type: 'doughnut',
    data: {
        labels: ['Célibataires', 'Marié(e)s', 'Divorcé(e)s', 'Veuf(ve)s'],
        datasets: [{
            data: [
                <?= $stats['effectif_celibataire'] ?? 0 ?>,
                <?= $stats['effectif_marie'] ?? 0 ?>,
                <?= $stats['effectif_divorce'] ?? 0 ?>,
                <?= $stats['effectif_veuf'] ?? 0 ?>
            ],
            backgroundColor: [colors.primary, colors.success, colors.warning, colors.purple]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'right' }
        }
    }
});

// Human Resources Chart
new Chart(document.getElementById('resourcesChart'), {
    type: 'bar',
    data: {
        labels: ['Corps médical', 'Corps paramédical', 'Corps administratif', 'Corps technique'],
        datasets: [{
            label: 'Effectif',
            data: [
                <?= $stats['effectif_medical'] ?? 0 ?>,
                <?= $stats['effectif_paramedical'] ?? 0 ?>,
                <?= $stats['effectif_administratif'] ?? 0 ?>,
                <?= $stats['effectif_technique'] ?? 0 ?>
            ],
            backgroundColor: colors.primary
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            x: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Nombre de personnel'
                }
            }
        }
    }
});

// Infrastructure Chart
new Chart(document.getElementById('infrastructureChart'), {
    type: 'bar',
    data: {
        labels: ['Centres Urbains', 'Centres Ruraux', 'Hôp. Régionaux', 'Hôp. Provinciaux', 'Hôp. Locaux', 'Centres Oncologie'],
        datasets: [{
            label: 'Établissements',
            data: [
                <?= $stats['centres_sante_urbains'] ?? 0 ?>,
                <?= $stats['centres_sante_ruraux'] ?? 0 ?>,
                <?= $stats['hopitaux_regionaux'] ?? 0 ?>,
                <?= $stats['hopitaux_provinciaux'] ?? 0 ?>,
                <?= $stats['hopitaux_locaux'] ?? 0 ?>,
                <?= $stats['centres_oncologie'] ?? 0 ?>
            ],
            backgroundColor: [
                colors.teal, colors.teal,
                colors.navy, colors.navy, colors.navy, colors.navy
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Nombre d\'établissements'
                }
            }
        }
    }
});
</script>
