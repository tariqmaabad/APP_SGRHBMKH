<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Nouveau Mouvement</h1>
        <a href="/APP_SGRHBMKH/mouvements" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0">Informations du mouvement</h5>
        </div>
        <div class="card-body">
            <form action="/APP_SGRHBMKH/mouvements/create" method="POST" id="mouvementForm" autocomplete="off" class="needs-validation" novalidate>
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                
                <?php if (isset($_SESSION['flash_messages'])): ?>
                    <?php foreach ($_SESSION['flash_messages'] as $message): ?>
                        <div class="alert alert-<?= $message['type'] ?> mb-3">
                            <?= htmlspecialchars($message['message']) ?>
                        </div>
                    <?php endforeach; ?>
                    <?php unset($_SESSION['flash_messages']); ?>
                <?php endif; ?>

                <?php if (isset($errors) && !empty($errors)): ?>
                    <div class="alert alert-danger mb-3">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label for="personnel_id" class="form-label fw-bold">
                                <i class="fas fa-user me-2"></i>Agent *
                            </label>
                            <select class="form-select form-select-lg <?php echo isset($errors['personnel_id']) ? 'is-invalid' : ''; ?>" 
                    id="personnel_id" 
                    name="personnel_id" 
                    required>
                            <div class="invalid-feedback">
                                Veuillez sélectionner un agent
                            </div>
                                <option value="">Sélectionnez un agent</option>
                                <?php foreach ($personnel as $agent): ?>
                                    <option value="<?= $agent['id'] ?>"
                                            data-formation="<?= $agent['formation_sanitaire_id'] ?>"
                                            <?= (isset($data['personnel_id']) && $data['personnel_id'] == $agent['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($agent['nom'] . ' ' . $agent['prenom']) ?> 
                                        (PPR: <?= htmlspecialchars($agent['ppr']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="type_mouvement" class="form-label fw-bold">
                                <i class="fas fa-exchange-alt me-2"></i>Type de Mouvement *
                            </label>
                            <select class="form-select form-select-lg <?php echo isset($errors['type_mouvement']) ? 'is-invalid' : ''; ?>" 
                    id="type_mouvement" 
                    name="type_mouvement" 
                    required>
                            <div class="invalid-feedback">
                                Veuillez sélectionner un type de mouvement
                            </div>
                                <option value="">Sélectionnez un type</option>
                                <?php foreach ($types as $key => $value): ?>
                                    <option value="<?= $key ?>" <?= (isset($data['type_mouvement']) && $data['type_mouvement'] === $key) ? 'selected' : '' ?>>
                                        <?= $value ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-4">
                            <label for="date_mouvement" class="form-label fw-bold">
                                <i class="fas fa-calendar-alt me-2"></i>Date du Mouvement *
                            </label>
                            <input type="date" 
                                   class="form-control form-control-lg <?php echo isset($errors['date_mouvement']) ? 'is-invalid' : ''; ?>" 
                                   id="date_mouvement" 
                                   name="date_mouvement" 
                                   value="<?= isset($data['date_mouvement']) ? htmlspecialchars($data['date_mouvement']) : date('Y-m-d') ?>"
                                   required>
                            <div class="invalid-feedback">
                                Veuillez sélectionner une date
                            </div>
                        </div>

                        <div id="formationFields" class="d-none">
                            <div class="mb-4">
                                <label for="formation_sanitaire_origine_id" class="form-label fw-bold">
                                    <i class="fas fa-hospital me-2"></i>Formation Sanitaire d'Origine
                                </label>
                                <select class="form-select form-select-lg" id="formation_sanitaire_origine_id" name="formation_sanitaire_origine_id">
                                    <option value="">Sélectionnez une formation</option>
                                    <?php foreach ($formations as $formation): ?>
                                        <option value="<?= $formation['id'] ?>" <?= (isset($data['formation_sanitaire_origine_id']) && $data['formation_sanitaire_origine_id'] == $formation['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($formation['nom_formation']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="formation_sanitaire_destination_id" class="form-label fw-bold">
                                    <i class="fas fa-hospital-alt me-2"></i>Formation Sanitaire de Destination *
                                </label>
                                <select class="form-select form-select-lg <?php echo isset($errors['formation_sanitaire_destination_id']) ? 'is-invalid' : ''; ?>" 
                        id="formation_sanitaire_destination_id" 
                        name="formation_sanitaire_destination_id">
                            <div class="invalid-feedback">
                                La formation sanitaire de destination est requise pour ce type de mouvement
                            </div>
                                    <option value="">Sélectionnez une formation</option>
                                    <?php foreach ($formations as $formation): ?>
                                        <option value="<?= $formation['id'] ?>" <?= (isset($data['formation_sanitaire_destination_id']) && $data['formation_sanitaire_destination_id'] == $formation['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($formation['nom_formation']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="mb-4">
                            <label for="commentaire" class="form-label fw-bold">
                                <i class="fas fa-comment me-2"></i>Commentaire
                            </label>
                            <textarea class="form-control form-control-lg" id="commentaire" name="commentaire" rows="3"
                                      placeholder="Ajoutez des détails supplémentaires si nécessaire"><?= isset($data['commentaire']) ? htmlspecialchars($data['commentaire']) : '' ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary btn-lg px-4 py-2" id="submitBtn">
                                <i class="fas fa-save me-2"></i><span>Enregistrer</span>
                            </button>
                            <a href="/APP_SGRHBMKH/mouvements" class="btn btn-outline-secondary btn-lg px-4 py-2">
                                <i class="fas fa-times me-2"></i> Annuler
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const formationFields = document.getElementById('formationFields');
    const typeMouvement = document.getElementById('type_mouvement');
    const personnelSelect = document.getElementById('personnel_id');
    const origineSelect = document.getElementById('formation_sanitaire_origine_id');
    const destinationSelect = document.getElementById('formation_sanitaire_destination_id');

    // Gestion de l'affichage des champs de formation sanitaire
    typeMouvement.addEventListener('change', function() {
        const showFormationFields = ['MUTATION', 'MISE_A_DISPOSITION'].includes(this.value);
        formationFields.classList.toggle('d-none', !showFormationFields);
        
        if (showFormationFields) {
            destinationSelect.required = true;
            // Mettre à jour la formation d'origine
            if (personnelSelect.value) {
                const formationId = personnelSelect.options[personnelSelect.selectedIndex].dataset.formation;
                if (formationId) {
                    origineSelect.value = formationId;
                }
            }
        } else {
            destinationSelect.required = false;
        }
    });

    // Mise à jour de la formation d'origine lors du changement d'agent
    personnelSelect.addEventListener('change', function() {
        if (this.value && !formationFields.classList.contains('d-none')) {
            const formationId = this.options[this.selectedIndex].dataset.formation;
            if (formationId) {
                origineSelect.value = formationId;
            }
        }
    });

    // Validation du formulaire
    const form = document.getElementById('mouvementForm');
    const submitBtn = document.getElementById('submitBtn');
    
    form.addEventListener('submit', function(e) {
        if (!this.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        const personnel = personnelSelect.value;
        const type = typeMouvement.value;
        const date = document.getElementById('date_mouvement').value;
        
        if (!personnel || !type || !date) {
            e.preventDefault();
            showToast('error', 'Veuillez remplir tous les champs obligatoires');
            return;
        }

        this.classList.add('was-validated');

        if (['MUTATION', 'MISE_A_DISPOSITION'].includes(type)) {
            const destination = destinationSelect.value;
            if (!destination) {
                e.preventDefault();
                showToast('error', 'La formation sanitaire de destination est obligatoire pour ce type de mouvement');
                return;
            }
        }

        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i><span>Enregistrement...</span>';
    });
});
</script>

<!-- Toast Notification -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="toast" class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<script>
function showToast(type, message) {
    const toast = document.getElementById('toast');
    toast.classList.remove('bg-danger', 'bg-success');
    toast.classList.add(type === 'error' ? 'bg-danger' : 'bg-success');
    toast.querySelector('.toast-body').textContent = message;
    new bootstrap.Toast(toast).show();
}
</script>
