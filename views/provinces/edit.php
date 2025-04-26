<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Modifier la Province</h1>
        <a href="/APP_SGRHBMKH/provinces" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="/APP_SGRHBMKH/provinces/edit/<?= $province['id'] ?>" method="POST">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                <?php if (isset($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <div class="mb-3">
                    <label for="nom_province" class="form-label">Nom de la Province *</label>
                    <input type="text" class="form-control" id="nom_province" name="nom_province" required 
                           maxlength="100" value="<?= htmlspecialchars($province['nom_province']) ?>"
                           placeholder="Entrez le nom de la province">
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                    <a href="/APP_SGRHBMKH/provinces" class="btn btn-danger">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="mt-4">
        <h2>Informations Supplémentaires</h2>
        <div class="card">
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">ID</dt>
                    <dd class="col-sm-9"><?= $province['id'] ?></dd>

                    <dt class="col-sm-3">Date de création</dt>
                    <dd class="col-sm-9"><?= date('d/m/Y H:i', strtotime($province['created_at'])) ?></dd>

                    <dt class="col-sm-3">Dernière modification</dt>
                    <dd class="col-sm-9"><?= date('d/m/Y H:i', strtotime($province['updated_at'])) ?></dd>
                </dl>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Focus sur le champ nom au chargement de la page
    document.getElementById('nom_province').focus();

    // Validation du formulaire
    document.querySelector('form').addEventListener('submit', function(e) {
        const nomProvince = document.getElementById('nom_province').value.trim();
        
        if (!nomProvince) {
            e.preventDefault();
            alert('Le nom de la province est obligatoire.');
            document.getElementById('nom_province').focus();
        }
    });
});
</script>
