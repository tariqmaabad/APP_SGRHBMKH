<div class="container-fluid">
    <!-- Add JSCharting and Chart.js -->
    <script src="https://code.jscharting.com/latest/jscharting.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center">
                <div>
                    <h2 class="mb-0"><i class="fas fa-users-cog me-2"></i>Rapport des Effectifs</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Rapports</a></li>
                            <li class="breadcrumb-item active">Effectifs</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                        <i class="fas fa-filter me-2"></i> Filtres
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- New Filter Design -->
    <div class="collapse mb-4" id="filterCollapse">
        <div class="card border-0 shadow-sm">
            <div class="card-body bg-light rounded">
                <form method="GET" class="row g-3">
                    <div class="col-md-5">
                        <div class="form-floating">
                            <select name="province_id" class="form-select shadow-none" id="provinceSelect">
                                <option value="">Toutes les provinces</option>
                                <?php foreach ($provinces as $province): ?>
                                    <option value="<?= $province['id'] ?>" <?= isset($_GET['province_id']) && $_GET['province_id'] == $province['id'] ? 'selected' : '' ?>>
                                        <?= $province['nom_province'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <label for="provinceSelect">Province</label>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-floating">
                            <select name="corps_id" class="form-select shadow-none" id="corpsSelect">
                                <option value="">Tous les corps</option>
                                <?php foreach ($corps as $c): ?>
                                    <option value="<?= $c['id'] ?>" <?= isset($_GET['corps_id']) && $_GET['corps_id'] == $c['id'] ? 'selected' : '' ?>>
                                        <?= $c['nom_corps'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <label for="corpsSelect">Corps</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100 h-100">
                            <i class="fas fa-search me-2"></i> Rechercher
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div id="overviewChart" style="width: 100%; height: 120px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Distribution Charts -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0">Répartition par Spécialité</h5>
                </div>
                <div class="card-body">
                    <div id="specialtyChart" style="width: 100%; height: 300px;"></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0">Répartition par Corps</h5>
                </div>
                <div class="card-body">
                    <div id="corpsChart" style="width: 100%; height: 300px;"></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0">Répartition par Province</h5>
                </div>
                <div class="card-body">
                    <div id="provinceChart" style="width: 100%; height: 300px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Age Pyramid with Chart.js -->
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Pyramide des Âges</h5>
                    <div class="btn-group">
                        <button class="btn btn-outline-primary btn-sm active" onclick="togglePyramidType('bar')">
                            <i class="fas fa-chart-bar"></i> Barres
                        </button>
                        <button class="btn btn-outline-primary btn-sm" onclick="togglePyramidType('pyramid')">
                            <i class="fas fa-sort-amount-down"></i> Pyramide
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="pyramidChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// JSCharting Configuration
const colors = {
    primary: '#0d6efd',
    success: '#198754',
    info: '#0dcaf0',
    warning: '#ffc107',
    danger: '#dc3545',
    purple: '#6f42c1',
    pink: '#d63384',
    gradient: ['#0d6efd', '#198754', '#0dcaf0', '#ffc107', '#dc3545']
};

// Overview Chart
JSC.chart('overviewChart', {
    type: 'horizontal column',
    palette: colors.gradient,
    defaultSeries: { type: 'column roundcaps' },
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
                { name: 'Total Personnel', y: <?= $stats['total'] ?>, color: colors.primary },
                { name: 'Formations Sanitaires', y: <?= $stats['formations'] ?>, color: colors.success }
            ]
        }
    ]
});

// Specialty Chart
JSC.chart('specialtyChart', {
    type: 'pie donut',
    legend_position: 'right',
    defaultPoint: {
        label: {
            text: '<b>%value</b>',
            placement: 'inside'
        },
        tooltip: '%name<br/><b>Total:</b> %value<br/><b>%percentOfTotal%</b>'
    },
    series: [
        {
            points: <?= json_encode(array_map(function($item) {
                return [
                    'name' => $item['nom_specialite'],
                    'y' => $item['total']
                ];
            }, $stats['par_specialite'])) ?>
        }
    ]
});

// Corps Chart
JSC.chart('corpsChart', {
    type: 'pie donut',
    legend_position: 'right',
    defaultPoint: {
        label: {
            text: '<b>%value</b>',
            placement: 'inside'
        },
        tooltip: '%name<br/><b>Total:</b> %value<br/><b>%percentOfTotal%</b>'
    },
    series: [
        {
            points: <?= json_encode(array_map(function($item) {
                return [
                    'name' => $item['nom_corps'],
                    'y' => $item['total']
                ];
            }, $stats['par_corps'])) ?>
        }
    ]
});

// Province Chart
JSC.chart('provinceChart', {
    type: 'pie donut',
    legend_position: 'right',
    defaultPoint: {
        label: {
            text: '<b>%value</b>',
            placement: 'inside'
        },
        tooltip: '%name<br/><b>Total:</b> %value<br/><b>%percentOfTotal%</b>'
    },
    series: [
        {
            points: <?= json_encode(array_map(function($item) {
                return [
                    'name' => $item['nom_province'],
                    'y' => $item['total']
                ];
            }, $stats['par_province'])) ?>
        }
    ]
});

// Chart.js Age Pyramid
let pyramidType = 'bar';
let pyramidChart;

function createPyramidChart() {
    const ctx = document.getElementById('pyramidChart');
    if (pyramidChart) {
        pyramidChart.destroy();
    }

    const isBar = pyramidType === 'bar';
    pyramidChart = new Chart(ctx, {
        type: isBar ? 'bar' : 'bar',
        data: {
            labels: <?= json_encode(array_column($stats['pyramide_ages'], 'tranche')) ?>,
            datasets: [
                {
                    label: 'Hommes',
                    data: <?= json_encode(array_map(function($item) {
                        return $item['hommes'] * -1;
                    }, $stats['pyramide_ages'])) ?>,
                    backgroundColor: colors.primary
                },
                {
                    label: 'Femmes',
                    data: <?= json_encode(array_column($stats['pyramide_ages'], 'femmes')) ?>,
                    backgroundColor: colors.pink
                }
            ]
        },
        options: {
            indexAxis: isBar ? 'y' : 'x',
            responsive: true,
            scales: {
                x: {
                    stacked: false,
                    ticks: {
                        callback: value => Math.abs(value)
                    }
                },
                y: {
                    stacked: true
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: (context) => {
                            const value = Math.abs(context.parsed.x || context.parsed.y);
                            return `${context.dataset.label}: ${value}`;
                        }
                    }
                }
            }
        }
    });
}

function togglePyramidType(type) {
    pyramidType = type;
    createPyramidChart();
    
    // Update button states
    document.querySelectorAll('.btn-group .btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');
}

// Initial pyramid chart
createPyramidChart();
</script>
