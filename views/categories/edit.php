<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Modifier la Catégorie d'Établissement</h1>
        <a href="/APP_SGRHBMKH/categories" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="/APP_SGRHBMKH/categories/edit/<?= $categorie['id'] ?>" method="POST">
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
                    <label for="nom_categorie" class="form-label">Nom de la Catégorie *</label>
                    <input type="text" class="form-control" id="nom_categorie" name="nom_categorie" required 
                           maxlength="100" value="<?= htmlspecialchars($categorie['nom_categorie']) ?>"
                           placeholder="Entrez le nom de la catégorie">
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" 
                              rows="3" placeholder="Entrez une description de la catégorie"><?= htmlspecialchars($categorie['description'] ?? '') ?></textarea>
                    <div class="form-text">Cette description est optionnelle.</div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                    <a href="/APP_SGRHBMKH/categories" class="btn btn-danger">
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
                    <dd class="col-sm-9"><?= $categorie['id'] ?></dd>

                    <dt class="col-sm-3">Date de création</dt>
                    <dd class="col-sm-9"><?= date('d/m/Y H:i', strtotime($categorie['created_at'])) ?></dd>

                    <dt class="col-sm-3">Dernière modification</dt>
                    <dd class="col-sm-9"><?= date('d/m/Y H:i', strtotime($categorie['updated_at'])) ?></dd>
                </dl>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Focus sur le champ nom au chargement de la page
    document.getElementById('nom_categorie').focus();

    // Validation du formulaire
    document.querySelector('form').addEventListener('submit', function(e) {
        const nomCategorie = document.getElementById('nom_categorie').value.trim();
        
        if (!nomCategorie) {
            e.preventDefault();
            alert('Le nom de la catégorie est obligatoire.');
            document.getElementById('nom_categorie').focus();
        }
    });
});
</script>
