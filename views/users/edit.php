<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1>Modifier l'Utilisateur</h1>
        </div>
        <div class="col text-end">
            <a href="/APP_SGRHBMKH/users" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="/APP_SGRHBMKH/users/edit/<?php echo $user['id']; ?>" class="needs-validation" novalidate>
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" 
                                   class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" 
                                   id="email" 
                                   name="email" 
                                   value="<?php echo htmlspecialchars($user['email']); ?>"
                                   required>
                            <?php if (isset($errors['email'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['email']; ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="role" class="form-label">Rôle <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user-shield"></i></span>
                            <select class="form-select <?php echo isset($errors['role']) ? 'is-invalid' : ''; ?>" 
                                    id="role" 
                                    name="role" 
                                    required 
                                    <?php echo $user['id'] === $_SESSION['user_id'] ? 'disabled' : ''; ?>>
                                <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>Utilisateur</option>
                                <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Administrateur</option>
                            </select>
                            <?php if ($user['id'] === $_SESSION['user_id']): ?>
                                <input type="hidden" name="role" value="<?php echo $user['role']; ?>">
                            <?php endif; ?>
                            <?php if (isset($errors['role'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['role']; ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="password" class="form-label">Nouveau mot de passe</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" 
                                   class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" 
                                   id="password" 
                                   name="password"
                                   placeholder="Laisser vide pour ne pas modifier">
                            <button class="btn btn-outline-secondary" 
                                    type="button" 
                                    onclick="togglePassword('password')">
                                <i class="fas fa-eye"></i>
                            </button>
                            <?php if (isset($errors['password'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['password']; ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" 
                                   class="form-control <?php echo isset($errors['nom']) ? 'is-invalid' : ''; ?>" 
                                   id="nom" 
                                   name="nom" 
                                   value="<?php echo htmlspecialchars($user['nom']); ?>"
                                   required>
                            <?php if (isset($errors['nom'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['nom']; ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" 
                                   class="form-control <?php echo isset($errors['prenom']) ? 'is-invalid' : ''; ?>" 
                                   id="prenom" 
                                   name="prenom" 
                                   value="<?php echo htmlspecialchars($user['prenom']); ?>"
                                   required>
                            <?php if (isset($errors['prenom'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['prenom']; ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
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

                    <div class="col-md-6">
                        <label for="status" class="form-label">Statut</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-toggle-on"></i></span>
                            <select class="form-select" 
                                    id="status" 
                                    name="status"
                                    <?php echo $user['id'] === $_SESSION['user_id'] ? 'disabled' : ''; ?>>
                                <option value="active" <?php echo $user['status'] === 'active' ? 'selected' : ''; ?>>Actif</option>
                                <option value="inactive" <?php echo $user['status'] === 'inactive' ? 'selected' : ''; ?>>Inactif</option>
                            </select>
                            <?php if ($user['id'] === $_SESSION['user_id']): ?>
                                <input type="hidden" name="status" value="<?php echo $user['status']; ?>">
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="adresse" class="form-label">Adresse</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                        <textarea class="form-control" 
                                  id="adresse" 
                                  name="adresse" 
                                  rows="3"><?php echo htmlspecialchars($user['adresse'] ?? ''); ?></textarea>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="/APP_SGRHBMKH/users" class="btn btn-light me-md-2">Annuler</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function togglePassword(id) {
    const input = document.getElementById(id);
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
</script>
