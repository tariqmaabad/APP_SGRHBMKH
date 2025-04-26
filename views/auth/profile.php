<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - SGRH-BMKH</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            background-color: #f8f9fa;
            padding: 20px 0;
        }
        .profile-container {
            max-width: 800px;
            width: 100%;
            padding: 15px;
            margin: auto;
        }
        .card {
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .card-header {
            border-radius: 1rem 1rem 0 0;
            background-color: #fff;
            padding: 2rem 1rem;
            text-align: center;
        }
        .logo {
            font-size: 2rem;
            font-weight: bold;
            color: #0d6efd;
            margin-bottom: 1rem;
        }
        .flash-message {
            margin-bottom: 1rem;
        }
        .avatar-container {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 0 auto 1rem;
        }
        .avatar {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #0d6efd;
        }
        .avatar-placeholder {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            color: #adb5bd;
            border: 3px solid #0d6efd;
        }
        .avatar-upload {
            position: absolute;
            bottom: 0;
            right: 0;
            background: #0d6efd;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .avatar-upload:hover {
            background: #0b5ed7;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <?php if (isset($messages) && !empty($messages)): ?>
            <?php foreach ($messages as $message): ?>
                <div class="alert alert-<?php echo $message['type'] === 'error' ? 'danger' : $message['type']; ?> flash-message">
                    <?php echo $message['message']; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <div class="logo">SGRH-BMKH</div>
                <div class="avatar-container">
                    <?php if (isset($user['avatar']) && $user['avatar']): ?>
                        <img src="<?php echo htmlspecialchars($user['avatar']); ?>" alt="Avatar" class="avatar">
                    <?php else: ?>
                        <div class="avatar-placeholder">
                            <i class="fas fa-user"></i>
                        </div>
                    <?php endif; ?>
                    <label for="avatar-input" class="avatar-upload">
                        <i class="fas fa-camera"></i>
                    </label>
                    <input type="file" id="avatar-input" style="display: none" accept="image/*">
                </div>
                <h4 class="card-title mb-0">Mon Profil</h4>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="/APP_SGRHBMKH/auth/profile" class="needs-validation" novalidate>
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nom" class="form-label">Nom *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" 
                                       class="form-control <?php echo isset($errors['nom']) ? 'is-invalid' : ''; ?>" 
                                       id="nom" 
                                       name="nom" 
                                       value="<?php echo htmlspecialchars($user['nom'] ?? ''); ?>"
                                       required>
                                <?php if (isset($errors['nom'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['nom']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="prenom" class="form-label">Prénom *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" 
                                       class="form-control <?php echo isset($errors['prenom']) ? 'is-invalid' : ''; ?>" 
                                       id="prenom" 
                                       name="prenom" 
                                       value="<?php echo htmlspecialchars($user['prenom'] ?? ''); ?>"
                                       required>
                                <?php if (isset($errors['prenom'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['prenom']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" 
                                   class="form-control" 
                                   value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>"
                                   disabled>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="telephone" class="form-label">Téléphone</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            <input type="tel" 
                                   class="form-control" 
                                   id="telephone" 
                                   name="telephone" 
                                   value="<?php echo htmlspecialchars($user['telephone'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="adresse" class="form-label">Adresse</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                            <textarea class="form-control" 
                                      id="adresse" 
                                      name="adresse" 
                                      rows="2"><?php echo htmlspecialchars($user['adresse'] ?? ''); ?></textarea>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="current_password" class="form-label">Mot de passe actuel</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" 
                                   class="form-control <?php echo isset($errors['current_password']) ? 'is-invalid' : ''; ?>" 
                                   id="current_password" 
                                   name="current_password">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')">
                                <i class="fas fa-eye"></i>
                            </button>
                            <?php if (isset($errors['current_password'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['current_password']; ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="form-text">Requis uniquement si vous souhaitez changer votre mot de passe</div>
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label">Nouveau mot de passe</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" 
                                   class="form-control <?php echo isset($errors['new_password']) ? 'is-invalid' : ''; ?>" 
                                   id="new_password" 
                                   name="new_password">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password')">
                                <i class="fas fa-eye"></i>
                            </button>
                            <?php if (isset($errors['new_password'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['new_password']; ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="form-text">Laissez vide si vous ne souhaitez pas changer votre mot de passe</div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Enregistrer les modifications
                        </button>
                        <a href="/APP_SGRHBMKH/dashboard" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour au tableau de bord
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.nextElementSibling.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Form validation
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()

        // Avatar upload preview
        document.getElementById('avatar-input').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const container = document.querySelector('.avatar-container');
                    const existingAvatar = container.querySelector('.avatar, .avatar-placeholder');
                    if (existingAvatar) {
                        existingAvatar.remove();
                    }
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('avatar');
                    container.insertBefore(img, container.firstChild);
                }
                reader.readAsDataURL(file);
            }
        });

        // Auto-hide flash messages
        setTimeout(function() {
            document.querySelectorAll('.flash-message').forEach(function(element) {
                element.style.display = 'none';
            });
        }, 5000);
    </script>
</body>
</html>
