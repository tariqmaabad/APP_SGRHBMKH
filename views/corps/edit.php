<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Modifier le Corps</h1>
        <a href="/APP_SGRHBMKH/corps" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="/APP_SGRHBMKH/corps/edit/<?= $corps['id'] ?>" method="POST" class="row g-3" novalidate>
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                
                <?php if (isset($errors) && !empty($errors)): ?>
                    <div class="col-12">
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="col-md-6">
                    <label for="nom_corps" class="form-label">Nom du Corps *</label>
                    <input type="text" 
                           class="form-control" 
                           id="nom_corps" 
                           name="nom_corps" 
                           value="<?= htmlspecialchars($corps['nom_corps']) ?>"
                           required 
                           autofocus
                           aria-required="true"
                           aria-describedby="nom_corps_help">
                    <div id="nom_corps_help" class="form-text">
                        Entrez le nom complet du corps
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="type_corps" class="form-label">Type de Corps *</label>
                    <select class="form-select" 
                            id="type_corps" 
                            name="type_corps" 
                            required
                            aria-required="true"
                            aria-describedby="type_corps_help">
                        <option value="">Sélectionnez un type</option>
                        <?php foreach ($types as $key => $value): ?>
                            <option value="<?= $key ?>" <?= $corps['type_corps'] === $key ? 'selected' : '' ?>>
                                <?= $value ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div id="type_corps_help" class="form-text">
                        Sélectionnez le type de corps (Médical, Paramédical, ou Administratif et Technique)
                    </div>
                </div>

                <div class="col-12">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" 
                              id="description" 
                              name="description" 
                              rows="3"
                              aria-describedby="description_help"><?= htmlspecialchars($corps['description'] ?? '') ?></textarea>
                    <div id="description_help" class="form-text">
                        Ajoutez une description optionnelle pour ce corps
                    </div>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Enregistrer les modifications
                    </button>
                    <a href="/APP_SGRHBMKH/corps" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Validation du formulaire côté client
(function () {
    'use strict'

    var form = document.querySelector('form')
    form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
        }

        form.classList.add('was-validated')
    }, false)
})()
</script>
