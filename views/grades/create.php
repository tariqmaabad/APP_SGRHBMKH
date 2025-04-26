<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Nouveau Grade</h1>
        <a href="/APP_SGRHBMKH/grades" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="/APP_SGRHBMKH/grades/create" method="POST">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                
                <?php if (isset($errors) && !empty($errors)): ?>
                    <div class="alert alert-danger mb-3">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <div class="mb-3">
                    <label for="nom_grade" class="form-label">Nom du Grade *</label>
                    <input type="text" class="form-control" id="nom_grade" name="nom_grade" required 
                           maxlength="100" placeholder="Entrez le nom du grade"
                           value="<?= isset($_GET['nom_grade']) ? htmlspecialchars($_GET['nom_grade']) : '' ?>">
                </div>

                <div class="mb-3">
                    <label for="corps_id" class="form-label">Corps *</label>
                    <select class="form-select" id="corps_id" name="corps_id" required>
                        <option value="">Sélectionnez un corps</option>
                        <?php foreach ($corps as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= (isset($_GET['corps_id']) && $_GET['corps_id'] == $c['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c['nom_corps']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="echelle" class="form-label">Échelle</label>
                    <input type="text" class="form-control" id="echelle" name="echelle" 
                           maxlength="50" placeholder="Entrez l'échelle du grade"
                           value="<?= isset($_GET['echelle']) ? htmlspecialchars($_GET['echelle']) : '' ?>">
                    <div class="form-text">Cette information est optionnelle.</div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                    <a href="/APP_SGRHBMKH/grades" class="btn btn-danger">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Focus sur le champ nom au chargement de la page
    document.getElementById('nom_grade').focus();

    // Validation du formulaire
    document.querySelector('form').addEventListener('submit', function(e) {
        const nomGrade = document.getElementById('nom_grade').value.trim();
        const corpsId = document.getElementById('corps_id').value;
        
        if (!nomGrade) {
            e.preventDefault();
            alert('Le nom du grade est obligatoire.');
            document.getElementById('nom_grade').focus();
            return;
        }

        if (!corpsId) {
            e.preventDefault();
            alert('Le corps est obligatoire.');
            document.getElementById('corps_id').focus();
        }
    });
});
</script>
