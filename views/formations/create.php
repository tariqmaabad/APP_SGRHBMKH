<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Nouvelle Formation Sanitaire</h1>
        <a href="/APP_SGRHBMKH/formations" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="/APP_SGRHBMKH/formations/create" method="POST">
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
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nom_formation" class="form-label">Nom de la Formation *</label>
                            <input type="text" class="form-control" id="nom_formation" name="nom_formation" required 
                                   maxlength="255" placeholder="Entrez le nom de la formation sanitaire"
                                   value="<?= isset($data['nom_formation']) ? htmlspecialchars($data['nom_formation']) : '' ?>">
                        </div>

                        <div class="mb-3">
                            <label for="type_formation" class="form-label">Type de Formation</label>
                            <input type="text" class="form-control" id="type_formation" name="type_formation" 
                                   maxlength="100" placeholder="Entrez le type de formation"
                                   value="<?= isset($data['type_formation']) ? htmlspecialchars($data['type_formation']) : '' ?>">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="province_id" class="form-label">Province *</label>
                            <select class="form-select" id="province_id" name="province_id" required>
                                <option value="">Sélectionnez une province</option>
                                <?php foreach ($provinces as $province): ?>
                                    <option value="<?= $province['id'] ?>" <?= (isset($data['province_id']) && $data['province_id'] == $province['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($province['nom_province']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="categorie_id" class="form-label">Catégorie *</label>
                            <select class="form-select" id="categorie_id" name="categorie_id" required>
                                <option value="">Sélectionnez une catégorie</option>
                                <?php foreach ($categories_etablissements as $categorie): ?>
                                    <option value="<?= $categorie['id'] ?>" <?= (isset($data['categorie_id']) && $data['categorie_id'] == $categorie['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($categorie['nom_categorie']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="milieu" class="form-label">Milieu *</label>
                            <select class="form-select" id="milieu" name="milieu" required>
                                <option value="">Sélectionnez le milieu</option>
                                <option value="URBAIN" <?= (isset($data['milieu']) && $data['milieu'] === 'URBAIN') ? 'selected' : '' ?>>Urbain</option>
                                <option value="RURAL" <?= (isset($data['milieu']) && $data['milieu'] === 'RURAL') ? 'selected' : '' ?>>Rural</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                    <a href="/APP_SGRHBMKH/formations" class="btn btn-danger">
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
    document.getElementById('nom_formation').focus();

    // Validation du formulaire
    document.querySelector('form').addEventListener('submit', function(e) {
        const nomFormation = document.getElementById('nom_formation').value.trim();
        const provinceId = document.getElementById('province_id').value;
        const categorieId = document.getElementById('categorie_id').value;
        const milieu = document.getElementById('milieu').value;
        
        if (!nomFormation) {
            e.preventDefault();
            alert('Le nom de la formation est obligatoire.');
            document.getElementById('nom_formation').focus();
            return;
        }

        if (!provinceId) {
            e.preventDefault();
            alert('La province est obligatoire.');
            document.getElementById('province_id').focus();
            return;
        }

        if (!categorieId) {
            e.preventDefault();
            alert('La catégorie est obligatoire.');
            document.getElementById('categorie_id').focus();
            return;
        }

        if (!milieu) {
            e.preventDefault();
            alert('Le milieu est obligatoire.');
            document.getElementById('milieu').focus();
        }
    });
});
</script>
