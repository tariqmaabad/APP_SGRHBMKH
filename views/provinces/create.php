<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Nouvelle Province</h1>
        <a href="/APP_SGRHBMKH/provinces" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="/APP_SGRHBMKH/provinces/create" method="POST">
                <?php if (isset($csrf_token)): ?>
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                <?php endif; ?>
                <div class="mb-3">
                    <label for="nom_province" class="form-label">Nom de la Province *</label>
                    <input type="text" class="form-control" id="nom_province" name="nom_province" required 
                           maxlength="100" placeholder="Entrez le nom de la province">
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
