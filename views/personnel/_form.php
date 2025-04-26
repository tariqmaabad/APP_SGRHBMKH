<?php
$isEdit = isset($personnel['id']);
$formAction = $isEdit ? "/APP_SGRHBMKH/personnel/update/{$personnel['id']}" : "/APP_SGRHBMKH/personnel/store";
?>

<form action="<?php echo $formAction; ?>" method="POST" class="needs-validation" novalidate enctype="multipart/form-data">
    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>
    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

    <div class="row g-3">
        <!-- PPR -->
        <div class="col-md-4">
            <label for="ppr" class="form-label">PPR <span class="text-danger">*</span></label>
            <input type="text" 
                   class="form-control <?php echo isset($errors['ppr']) ? 'is-invalid' : ''; ?>" 
                   id="ppr" 
                   name="ppr" 
                   value="<?php echo htmlspecialchars($personnel['ppr'] ?? ''); ?>" 
                   required>
            <?php if (isset($errors['ppr'])): ?>
                <div class="invalid-feedback"><?php echo $errors['ppr']; ?></div>
            <?php endif; ?>
        </div>

        <!-- CIN -->
        <div class="col-md-4">
            <label for="cin" class="form-label">CIN <span class="text-danger">*</span></label>
            <input type="text" 
                   class="form-control <?php echo isset($errors['cin']) ? 'is-invalid' : ''; ?>" 
                   id="cin" 
                   name="cin" 
                   value="<?php echo htmlspecialchars($personnel['cin'] ?? ''); ?>" 
                   required>
            <?php if (isset($errors['cin'])): ?>
                <div class="invalid-feedback"><?php echo $errors['cin']; ?></div>
            <?php endif; ?>
        </div>

        <!-- Date de naissance -->
        <div class="col-md-4">
            <label for="date_naissance" class="form-label">Date de naissance <span class="text-danger">*</span></label>
            <input type="date" 
                   class="form-control <?php echo isset($errors['date_naissance']) ? 'is-invalid' : ''; ?>" 
                   id="date_naissance" 
                   name="date_naissance" 
                   value="<?php echo htmlspecialchars($personnel['date_naissance'] ?? ''); ?>" 
                   required>
            <?php if (isset($errors['date_naissance'])): ?>
                <div class="invalid-feedback"><?php echo $errors['date_naissance']; ?></div>
            <?php endif; ?>
        </div>

        <!-- Nom -->
        <div class="col-md-6">
            <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
            <input type="text" 
                   class="form-control <?php echo isset($errors['nom']) ? 'is-invalid' : ''; ?>" 
                   id="nom" 
                   name="nom" 
                   value="<?php echo htmlspecialchars($personnel['nom'] ?? ''); ?>" 
                   required>
            <?php if (isset($errors['nom'])): ?>
                <div class="invalid-feedback"><?php echo $errors['nom']; ?></div>
            <?php endif; ?>
        </div>

        <!-- Prénom -->
        <div class="col-md-6">
            <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
            <input type="text" 
                   class="form-control <?php echo isset($errors['prenom']) ? 'is-invalid' : ''; ?>" 
                   id="prenom" 
                   name="prenom" 
                   value="<?php echo htmlspecialchars($personnel['prenom'] ?? ''); ?>" 
                   required>
            <?php if (isset($errors['prenom'])): ?>
                <div class="invalid-feedback"><?php echo $errors['prenom']; ?></div>
            <?php endif; ?>
        </div>

        <!-- Sexe -->
        <div class="col-md-4">
            <label for="sexe" class="form-label">Sexe <span class="text-danger">*</span></label>
            <select class="form-select <?php echo isset($errors['sexe']) ? 'is-invalid' : ''; ?>" 
                    id="sexe" 
                    name="sexe" 
                    required>
                <option value="">Sélectionner...</option>
                <option value="M" <?php echo (isset($personnel['sexe']) && $personnel['sexe'] === 'M') ? 'selected' : ''; ?>>Masculin</option>
                <option value="F" <?php echo (isset($personnel['sexe']) && $personnel['sexe'] === 'F') ? 'selected' : ''; ?>>Féminin</option>
            </select>
            <?php if (isset($errors['sexe'])): ?>
                <div class="invalid-feedback"><?php echo $errors['sexe']; ?></div>
            <?php endif; ?>
        </div>

        <!-- Situation familiale -->
        <div class="col-md-4">
            <label for="situation_familiale" class="form-label">Situation familiale <span class="text-danger">*</span></label>
            <select class="form-select <?php echo isset($errors['situation_familiale']) ? 'is-invalid' : ''; ?>" 
                    id="situation_familiale" 
                    name="situation_familiale" 
                    required>
                <option value="">Sélectionner...</option>
                <option value="CELIBATAIRE" <?php echo (isset($personnel['situation_familiale']) && $personnel['situation_familiale'] === 'CELIBATAIRE') ? 'selected' : ''; ?>>Célibataire</option>
                <option value="MARIE" <?php echo (isset($personnel['situation_familiale']) && $personnel['situation_familiale'] === 'MARIE') ? 'selected' : ''; ?>>Marié(e)</option>
                <option value="DIVORCE" <?php echo (isset($personnel['situation_familiale']) && $personnel['situation_familiale'] === 'DIVORCE') ? 'selected' : ''; ?>>Divorcé(e)</option>
                <option value="VEUF" <?php echo (isset($personnel['situation_familiale']) && $personnel['situation_familiale'] === 'VEUF') ? 'selected' : ''; ?>>Veuf/Veuve</option>
            </select>
            <?php if (isset($errors['situation_familiale'])): ?>
                <div class="invalid-feedback"><?php echo $errors['situation_familiale']; ?></div>
            <?php endif; ?>
        </div>

        <!-- Corps -->
        <div class="col-md-4">
            <label for="corps_id" class="form-label">Corps</label>
            <select class="form-select" id="corps_id" name="corps_id">
                <option value="">Sélectionner...</option>
                <?php foreach ($corps ?? [] as $c): ?>
                    <option value="<?php echo $c['id']; ?>" 
                            <?php echo (isset($personnel['corps_id']) && $personnel['corps_id'] == $c['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($c['nom_corps']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Grade -->
        <div class="col-md-4">
            <label for="grade_id" class="form-label">Grade</label>
            <select class="form-select" id="grade_id" name="grade_id">
                <option value="">Sélectionner...</option>
                <?php foreach ($grades ?? [] as $g): ?>
                    <option value="<?php echo $g['id']; ?>" 
                            <?php echo (isset($personnel['grade_id']) && $personnel['grade_id'] == $g['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($g['nom_grade']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Spécialité -->
        <div class="col-md-4">
            <label for="specialite_id" class="form-label">Spécialité</label>
            <select class="form-select" id="specialite_id" name="specialite_id">
                <option value="">Sélectionner...</option>
                <?php foreach ($specialites ?? [] as $s): ?>
                    <option value="<?php echo $s['id']; ?>" 
                            <?php echo (isset($personnel['specialite_id']) && $personnel['specialite_id'] == $s['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($s['nom_specialite']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Date recrutement -->
        <div class="col-md-4">
            <label for="date_recrutement" class="form-label">Date de recrutement <span class="text-danger">*</span></label>
            <input type="date" 
                   class="form-control <?php echo isset($errors['date_recrutement']) ? 'is-invalid' : ''; ?>" 
                   id="date_recrutement" 
                   name="date_recrutement" 
                   value="<?php echo htmlspecialchars($personnel['date_recrutement'] ?? ''); ?>" 
                   required>
            <?php if (isset($errors['date_recrutement'])): ?>
                <div class="invalid-feedback"><?php echo $errors['date_recrutement']; ?></div>
            <?php endif; ?>
        </div>

        <!-- Date prise service -->
        <div class="col-md-4">
            <label for="date_prise_service" class="form-label">Date de prise de service <span class="text-danger">*</span></label>
            <input type="date" 
                   class="form-control <?php echo isset($errors['date_prise_service']) ? 'is-invalid' : ''; ?>" 
                   id="date_prise_service" 
                   name="date_prise_service" 
                   value="<?php echo htmlspecialchars($personnel['date_prise_service'] ?? ''); ?>" 
                   required>
            <?php if (isset($errors['date_prise_service'])): ?>
                <div class="invalid-feedback"><?php echo $errors['date_prise_service']; ?></div>
            <?php endif; ?>
        </div>

        <!-- Formation sanitaire -->
        <div class="col-md-4">
            <label for="formation_sanitaire_id" class="form-label">Formation sanitaire</label>
            <select class="form-select" id="formation_sanitaire_id" name="formation_sanitaire_id">
                <option value="">Sélectionner...</option>
                <?php foreach ($formations_sanitaires ?? [] as $fs): ?>
                    <option value="<?php echo $fs['id']; ?>" 
                            <?php echo (isset($personnel['formation_sanitaire_id']) && $personnel['formation_sanitaire_id'] == $fs['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($fs['nom_formation']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

    
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-2"></i><?php echo $isEdit ? 'Mettre à jour' : 'Enregistrer'; ?>
        </button>
        <a href="/APP_SGRHBMKH/personnel" class="btn btn-secondary ms-2">
            <i class="fas fa-times me-2"></i>Annuler
        </a>
    </div>
</form>

<script>
// Form validation and submission
(function () {
    'use strict'
    
    // Get all forms with the 'needs-validation' class
    var forms = document.querySelectorAll('.needs-validation')
    
    // Loop through each form
    Array.prototype.slice.call(forms).forEach(function (form) {
        var submitButton = form.querySelector('button[type="submit"]')
        
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            if (!form.checkValidity()) {
                event.stopPropagation();
            } else {
                // Show loading state
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Enregistrement...';
                }
                // Submit form
                this.submit();
            }
            
            form.classList.add('was-validated')
        }, false)
    })
})()

// Dynamically update grades based on selected corps
document.getElementById('corps_id').addEventListener('change', function() {
    const corpsId = this.value;
    const gradeSelect = document.getElementById('grade_id');
    
    // Clear current options
    gradeSelect.innerHTML = '<option value="">Sélectionner...</option>';
    
    if (corpsId) {
        fetch(`/APP_SGRHBMKH/api/grades/${corpsId}`)
            .then(response => response.json())
            .then(grades => {
                grades.forEach(grade => {
                    const option = document.createElement('option');
                    option.value = grade.id;
                    option.textContent = grade.nom_grade;
                    gradeSelect.appendChild(option);
                });
            });
    }
});
</script>
