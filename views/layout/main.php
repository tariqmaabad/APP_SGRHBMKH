<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Système de Gestion RH</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: #343a40;
        }
        .sidebar .nav-link {
            color: #fff;
            padding: 0.5rem 1rem;
        }
        .sidebar .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
        }
        .sidebar .nav-link.active {
            background-color: rgba(255,255,255,0.2);
        }
        .main-content {
            padding: 20px;
        }
        .flash-message {
            margin-bottom: 1rem;
        }
        .sidebar .nav-group {
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding: 0.5rem 0;
        }
        .sidebar .nav-group-title {
            color: #adb5bd;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .sidebar .nav-link i {
            width: 20px;
            text-align: center;
            margin-right: 0.5rem;
        }
        .dropdown-menu {
            border-radius: 0;
            margin-top: 0;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/APP_SGRHBMKH">Système de Gestion des Ressources Humaines-BENI MELLAL KHENIFRA</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user me-1"></i> <?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Mon Compte'; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item" href="/APP_SGRHBMKH/auth/profile">
                                    <i class="fas fa-id-card me-2"></i> Mon Profil
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form id="logout-form" action="/APP_SGRHBMKH/auth/logout" method="POST" style="display: none;">
                                    <input type="hidden" name="csrf_token" value="<?php echo isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : ''; ?>">
                                </form>
                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 sidebar py-3">
                <ul class="nav flex-column">
                    <!-- Tableau de bord -->
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/dashboard') !== false ? 'active' : '' ?>" 
                           href="/APP_SGRHBMKH/dashboard">
                            <i class="fas fa-tachometer-alt"></i> Tableau de bord
                        </a>
                    </li>

                    <!-- Gestion du Personnel -->
                    <li class="nav-group">
                        <div class="nav-group-title">Personnel</div>
                        <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/personnel') !== false ? 'active' : '' ?>" 
                           href="/APP_SGRHBMKH/personnel">
                            <i class="fas fa-users"></i> Liste du Personnel
                        </a>
                        <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/mouvements') !== false ? 'active' : '' ?>" 
                           href="/APP_SGRHBMKH/mouvements">
                            <i class="fas fa-exchange-alt"></i> Mouvements
                        </a>
                    </li>

                    <!-- Référentiels -->
                    <li class="nav-group">
                        <div class="nav-group-title">Référentiels</div>
                        <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/provinces') !== false ? 'active' : '' ?>" 
                           href="/APP_SGRHBMKH/provinces">
                            <i class="fas fa-map-marker-alt"></i> Provinces
                        </a>
                        <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/corps') !== false ? 'active' : '' ?>" 
                           href="/APP_SGRHBMKH/corps">
                            <i class="fas fa-sitemap"></i> Corps
                        </a>
                        <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/grades') !== false ? 'active' : '' ?>" 
                           href="/APP_SGRHBMKH/grades">
                            <i class="fas fa-star"></i> Grades
                        </a>
                        <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/categories') !== false ? 'active' : '' ?>" 
                           href="/APP_SGRHBMKH/categories">
                            <i class="fas fa-tags"></i> Catégories
                        </a>
                    </li>

                    <!-- Établissements -->
                    <li class="nav-group">
                        <div class="nav-group-title">Établissements</div>
                        <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/formations') !== false ? 'active' : '' ?>" 
                           href="/APP_SGRHBMKH/formations">
                            <i class="fas fa-hospital"></i> Formations Sanitaires
                        </a>
                    </li>

                    <!-- Rapports -->
                    <li class="nav-group">
                        <div class="nav-group-title">Rapports</div>
                        <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/rapports/effectifs') !== false ? 'active' : '' ?>" 
                           href="/APP_SGRHBMKH/rapports/effectifs">
                            <i class="fas fa-users"></i> Effectifs
                        </a>
                        <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/rapports/mouvements') !== false ? 'active' : '' ?>" 
                           href="/APP_SGRHBMKH/rapports/mouvements">
                            <i class="fas fa-chart-line"></i> Mouvements
                        </a>
                        <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/rapports/etablissements') !== false ? 'active' : '' ?>" 
                           href="/APP_SGRHBMKH/rapports/etablissements">
                            <i class="fas fa-hospital-alt"></i> Établissements
                        </a>
                    </li>

                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                    <!-- Administration -->
                    <li class="nav-group">
                        <div class="nav-group-title">Administration</div>
                        <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/users') !== false ? 'active' : '' ?>" 
                           href="/APP_SGRHBMKH/users">
                            <i class="fas fa-users-cog"></i> Gestion des Utilisateurs
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="col-md-10 main-content">
                <?php if (isset($messages) && !empty($messages)): ?>
                    <?php foreach ($messages as $message): ?>
                        <div class="alert alert-<?= $message['type'] === 'error' ? 'danger' : $message['type'] ?> flash-message">
                            <?= $message['message'] ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?= $content ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Auto-hide flash messages
        setTimeout(function() {
            $('.flash-message').fadeOut('slow');
        }, 5000);
    </script>
</body>
</html>
