<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0"><?php echo htmlspecialchars($personnel['nom'] . ' ' . $personnel['prenom']); ?></h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/APP_SGRHBMKH/personnel">Personnel</a></li>
                    <li class="breadcrumb-item active">Détails</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="/APP_SGRHBMKH/personnel/edit/<?php echo $personnel['id']; ?>" class="btn btn-warning me-2">
                <i class="fas fa-edit me-2"></i>Modifier
            </a>
            <a href="/APP_SGRHBMKH/personnel" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour à la liste
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Informations personnelles -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0"><i class="fas fa-user me-2"></i>Informations Personnelles</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th width="30%" class="border-0">PPR</th>
                            <td class="border-0"><?php echo htmlspecialchars($personnel['ppr']); ?></td>
                        </tr>
                        <tr>
                            <th class="border-0">CIN</th>
                            <td class="border-0"><?php echo htmlspecialchars($personnel['cin']); ?></td>
                        </tr>
                        <tr>
                            <th class="border-0">Date de naissance</th>
                            <td class="border-0"><?php echo date('d/m/Y', strtotime($personnel['date_naissance'])); ?></td>
                        </tr>
                        <tr>
                            <th class="border-0">Sexe</th>
                            <td class="border-0"><?php echo $personnel['sexe'] === 'M' ? 'Masculin' : 'Féminin'; ?></td>
                        </tr>
                        <tr>
                            <th class="border-0">Situation familiale</th>
                            <td class="border-0">
                                <?php
                                $situations = [
                                    'CELIBATAIRE' => 'Célibataire',
                                    'MARIE' => 'Marié(e)',
                                    'DIVORCE' => 'Divorcé(e)',
                                    'VEUF' => 'Veuf/Veuve'
                                ];
                                echo $situations[$personnel['situation_familiale']] ?? '-';
                                ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Informations professionnelles -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0"><i class="fas fa-briefcase me-2"></i>Informations Professionnelles</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th width="30%" class="border-0">Corps</th>
                            <td class="border-0"><?php echo htmlspecialchars($personnel['nom_corps'] ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <th class="border-0">Grade</th>
                            <td class="border-0"><?php echo htmlspecialchars($personnel['nom_grade'] ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <th class="border-0">Spécialité</th>
                            <td class="border-0"><?php echo htmlspecialchars($personnel['nom_specialite'] ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <th class="border-0">Date recrutement</th>
                            <td class="border-0"><?php echo date('d/m/Y', strtotime($personnel['date_recrutement'])); ?></td>
                        </tr>
                        <tr>
                            <th class="border-0">Date prise service</th>
                            <td class="border-0"><?php echo date('d/m/Y', strtotime($personnel['date_prise_service'])); ?></td>
                        </tr>
                        <tr>
                            <th class="border-0">Formation Sanitaire</th>
                            <td class="border-0"><?php echo htmlspecialchars($personnel['nom_formation'] ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <th class="border-0">Province</th>
                            <td class="border-0"><?php echo htmlspecialchars($personnel['nom_province'] ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <th class="border-0">Catégorie</th>
                            <td class="border-0"><?php echo htmlspecialchars($personnel['categorie'] ?? '-'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Visualisations des mouvements -->
        <div class="col-md-12">
            <div class="row g-4">
                <!-- Timeline des mouvements -->
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-transparent">
                            <h5 class="card-title mb-0"><i class="fas fa-chart-line me-2"></i>Timeline des Mouvements</h5>
                        </div>
                        <div class="card-body">
                            <div id="timelineChart" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <!-- Distribution des types -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-transparent">
                            <h5 class="card-title mb-0"><i class="fas fa-chart-pie me-2"></i>Types de Mouvements</h5>
                        </div>
                        <div class="card-body">
                            <div id="typeDistributionChart" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des mouvements -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="fas fa-exchange-alt me-2"></i>Historique des Mouvements</h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#mouvementModal">
                        <i class="fas fa-plus me-2"></i>Nouveau Mouvement
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Formation d'origine</th>
                                    <th>Formation de destination</th>
                                    <th>Commentaire</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($mouvements)): ?>
                                    <?php foreach ($mouvements as $mouvement): ?>
                                        <tr>
                                            <td><?php echo date('d/m/Y', strtotime($mouvement['date_mouvement'])); ?></td>
                                            <td>
                                                <?php 
                                                $types = [
                                                    'DECES' => '<span class="badge bg-dark">Décès</span>',
                                                    'MUTATION' => '<span class="badge bg-info">Mutation</span>',
                                                    'DEMISSION' => '<span class="badge bg-warning">Démission</span>',
                                                    'FORMATION' => '<span class="badge bg-primary">Formation</span>',
                                                    'SUSPENSION' => '<span class="badge bg-danger">Suspension</span>',
                                                    'MISE_A_DISPOSITION' => '<span class="badge bg-success">Mise à disposition</span>',
                                                    'MISE_EN_DISPONIBILITE' => '<span class="badge bg-secondary">Mise en disponibilité</span>',
                                                    'RETRAITE_AGE' => '<span class="badge bg-danger">Retraite</span>'
                                                ];
                                                echo $types[$mouvement['type_mouvement']] ?? $mouvement['type_mouvement'];
                                                ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($mouvement['formation_origine'] ?? '-'); ?></td>
                                            <td><?php echo htmlspecialchars($mouvement['formation_destination'] ?? '-'); ?></td>
                                            <td><?php echo htmlspecialchars($mouvement['commentaire'] ?? '-'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">Aucun mouvement enregistré</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour ajouter un mouvement -->
<div class="modal fade" id="mouvementModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/personnel/addMouvement" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                <input type="hidden" name="personnel_id" value="<?php echo $personnel['id']; ?>">

                <div class="modal-header">
                    <h5 class="modal-title">Nouveau Mouvement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="type_mouvement" class="form-label">Type de mouvement <span class="text-danger">*</span></label>
                        <select class="form-select" id="type_mouvement" name="type_mouvement" required>
                            <option value="">Sélectionner...</option>
                            <option value="DECES">Décès</option>
                            <option value="MUTATION">Mutation</option>
                            <option value="DEMISSION">Démission</option>
                            <option value="FORMATION">Formation</option>
                            <option value="SUSPENSION">Suspension</option>
                            <option value="MISE_A_DISPOSITION">Mise à disposition</option>
                            <option value="MISE_EN_DISPONIBILITE">Mise en disponibilité</option>
                            <option value="RETRAITE_AGE">Retraite</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="date_mouvement" class="form-label">Date du mouvement <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="date_mouvement" name="date_mouvement" required>
                    </div>

                    <div class="mb-3 formation-select">
                        <label for="formation_sanitaire_origine_id" class="form-label">Formation sanitaire d'origine</label>
                        <select class="form-select" id="formation_sanitaire_origine_id" name="formation_sanitaire_origine_id">
                            <option value="">Sélectionner...</option>
                            <?php foreach ($formations_sanitaires ?? [] as $fs): ?>
                                <option value="<?php echo $fs['id']; ?>">
                                    <?php echo htmlspecialchars($fs['nom_formation']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3 formation-select">
                        <label for="formation_sanitaire_destination_id" class="form-label">Formation sanitaire de destination</label>
                        <select class="form-select" id="formation_sanitaire_destination_id" name="formation_sanitaire_destination_id">
                            <option value="">Sélectionner...</option>
                            <?php foreach ($formations_sanitaires ?? [] as $fs): ?>
                                <option value="<?php echo $fs['id']; ?>">
                                    <?php echo htmlspecialchars($fs['nom_formation']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="commentaire" class="form-label">Commentaire</label>
                        <textarea class="form-control" id="commentaire" name="commentaire" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Highcharts -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/timeline.js"></script>
<script>
// Préparer les données pour les graphiques
const movements = <?= json_encode($mouvements) ?>;
const typeColors = {
    'DECES': '#212529',
    'MUTATION': '#0dcaf0',
    'DEMISSION': '#ffc107',
    'FORMATION': '#0d6efd',
    'SUSPENSION': '#dc3545',
    'MISE_A_DISPOSITION': '#198754',
    'MISE_EN_DISPONIBILITE': '#6c757d',
    'RETRAITE_AGE': '#dc3545'
};

// Timeline Chart
Highcharts.chart('timelineChart', {
    chart: {
        type: 'timeline'
    },
    title: { text: '' },
    credits: { enabled: false },
    xAxis: {
        type: 'datetime',
        visible: true
    },
    yAxis: {
        gridLineWidth: 1,
        title: null,
        labels: {
            enabled: false
        }
    },
    legend: { enabled: false },
    tooltip: {
        style: {
            width: '250px'
        }
    },
    series: [{
        dataLabels: {
            allowOverlap: false,
            format: '<span style="color:{point.color}">● </span><span style="font-weight: bold;" > ' +
                '{point.x:%d/%m/%Y}</span><br/>{point.name}'
        },
        marker: {
            symbol: 'circle'
        },
        data: movements.map(m => ({
            x: new Date(m.date_mouvement).getTime(),
            name: m.type_mouvement.replace(/_/g, ' '),
            label: m.type_mouvement,
            color: typeColors[m.type_mouvement] || '#0d6efd',
            description: m.commentaire || 'Aucun commentaire'
        }))
    }]
});

// Type Distribution Chart
const typeDistribution = movements.reduce((acc, m) => {
    acc[m.type_mouvement] = (acc[m.type_mouvement] || 0) + 1;
    return acc;
}, {});

Highcharts.chart('typeDistributionChart', {
    chart: {
        type: 'pie'
    },
    title: { text: '' },
    credits: { enabled: false },
    plotOptions: {
        pie: {
            innerSize: '60%',
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '{point.percentage:.1f}%'
            },
            showInLegend: true
        }
    },
    series: [{
        name: 'Mouvements',
        colorByPoint: true,
        data: Object.entries(typeDistribution).map(([type, count]) => ({
            name: type.replace(/_/g, ' '),
            y: count,
            color: typeColors[type]
        }))
    }]
});

// Gérer l'affichage des champs de formation sanitaire
document.getElementById('type_mouvement').addEventListener('change', function() {
    const formationSelects = document.querySelectorAll('.formation-select');
    const showFormations = ['MUTATION', 'MISE_A_DISPOSITION'].includes(this.value);
    
    formationSelects.forEach(select => {
        select.style.display = showFormations ? 'block' : 'none';
        select.querySelector('select').required = showFormations;
    });
});
</script>
