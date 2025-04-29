<div class="container-fluid">
    <h2 class="mb-4"><i class="fas fa-file-medical me-2"></i>Rapport des Établissements</h2>

    <!-- Filtres -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Province</label>
                    <select name="province_id" class="form-select">
                        <option value="">Toutes les provinces</option>
                        <?php foreach ($provinces as $province): ?>
                            <option value="<?= $province['id'] ?>" <?= isset($_GET['province_id']) && $_GET['province_id'] == $province['id'] ? 'selected' : '' ?>>
                                <?= $province['nom_province'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Catégorie</label>
                    <select name="categorie_id" class="form-select">
                        <option value="">Toutes les catégories</option>
                        <?php foreach ($categories as $categorie): ?>
                            <option value="<?= $categorie['id'] ?>" <?= isset($_GET['categorie_id']) && $_GET['categorie_id'] == $categorie['id'] ? 'selected' : '' ?>>
                                <?= $categorie['nom_categorie'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Milieu</label>
                    <select name="milieu" class="form-select">
                        <option value="">Tous les milieux</option>
                        <option value="URBAIN" <?= isset($_GET['milieu']) && $_GET['milieu'] == 'URBAIN' ? 'selected' : '' ?>>Urbain</option>
                        <option value="RURAL" <?= isset($_GET['milieu']) && $_GET['milieu'] == 'RURAL' ? 'selected' : '' ?>>Rural</option>
                    </select>
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
                    <div id="statsChart" style="width: 100%; height: 120px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Répartition par Catégorie</h5>
                </div>
                <div class="card-body">
                    <div id="categorieChart" style="width: 100%; height: 400px;"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Répartition par Province</h5>
                </div>
                <div class="card-body">
                    <div id="provinceChart" style="width: 100%; height: 400px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des établissements -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Liste des Établissements</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Type</th>
                            <th>Province</th>
                            <th>Catégorie</th>
                            <th>Milieu</th>
                            <th>Personnel Affecté</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($etablissements as $etablissement): ?>
                            <tr>
                                <td><?= $etablissement['nom_formation'] ?></td>
                                <td><?= $etablissement['type_formation'] ?></td>
                                <td><?= $etablissement['nom_province'] ?></td>
                                <td><?= $etablissement['nom_categorie'] ?></td>
                                <td><?= ucfirst(strtolower($etablissement['milieu'])) ?></td>
                                <td><?= $etablissement['nombre_personnel'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- JSCharting -->
<script src="https://code.jscharting.com/latest/jscharting.js"></script>
<script>
// Colors configuration
const colors = {
    primary: '#0d6efd',
    success: '#198754',
    info: '#0dcaf0',
    warning: '#ffc107'
};

// Stats Overview Chart
JSC.chart('statsChart', {
    type: 'horizontal column',
    palette: Object.values(colors),
    defaultSeries: {
        type: 'column roundcaps',
    },
    yAxis: { visible: false },
    xAxis: { scale: { interval: "auto" } },
    legend: { visible: false },
    defaultPoint_label: {
        text: '%name: <b>%value</b>',
        align: 'center'
    },
    series: [
        {
            points: [
                { name: 'Total Établissements', y: <?= $stats['total'] ?> },
                { name: 'Milieu Urbain', y: <?= $stats['urbain'] ?> },
                { name: 'Milieu Rural', y: <?= $stats['rural'] ?> },
                { name: 'Personnel Affecté', y: <?= $stats['personnel'] ?> }
            ]
        }
    ]
});

// Category Distribution Chart
JSC.chart('categorieChart', {
    type: 'pie donut',
    legend_position: 'right',
    defaultPoint: {
        label: {
            text: '%name<br/><b>%value</b>',
            placement: 'inside',
            align: 'center'
        },
        tooltip: '%name<br/><b>Total:</b> %value<br/><b>Pourcentage:</b> %percentOfTotal%'
    },
    series: [
        {
            points: <?= json_encode(array_map(function($item) {
                return [
                    'name' => $item['nom_categorie'],
                    'y' => $item['total']
                ];
            }, $stats['par_categorie'])) ?>
        }
    ]
});

// Province Distribution Chart
JSC.chart('provinceChart', {
    type: 'column',
    palette: [colors.primary],
    defaultPoint: {
        tooltip: '%name<br/><b>Total:</b> %value',
        label: {
            text: '%value',
            align: 'center'
        }
    },
    xAxis: {
        label_text: 'Province',
        scale: { label_rotate: -45 }
    },
    yAxis: {
        label_text: 'Nombre d\'établissements',
        scale: { interval: 'auto' }
    },
    series: [
        {
            name: 'Établissements',
            points: <?= json_encode(array_map(function($item) {
                return [
                    'name' => $item['nom_province'],
                    'y' => $item['total']
                ];
            }, $stats['par_province'])) ?>
        }
    ]
});
</script>
