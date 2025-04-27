<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Export Liste du Personnel</h3>
                </div>
                <div class="card-body">
                    <form method="GET" action="/APP_SGRHBMKH/export/staff">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="province_id">Province</label>
                                    <select name="province_id" id="province_id" class="form-control">
                                        <option value="">Toutes les provinces</option>
                                        <?php foreach ($provinces as $province): ?>
                                            <option value="<?= $province['id'] ?>"><?= htmlspecialchars($province['nom_province']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="formation_id">Établissement</label>
                                    <select name="formation_id" id="formation_id" class="form-control">
                                        <option value="">Tous les établissements</option>
                                        <?php foreach ($formations as $formation): ?>
                                            <option value="<?= $formation['id'] ?>"><?= htmlspecialchars($formation['nom_formation']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="corps_id">Corps</label>
                                    <select name="corps_id" id="corps_id" class="form-control">
                                        <option value="">Tous les corps</option>
                                        <?php foreach ($corps as $corp): ?>
                                            <option value="<?= $corp['id'] ?>"><?= htmlspecialchars($corp['nom_corps']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="format">Format</label>
                                    <select name="format" id="format" class="form-control">
                                        <option value="excel">Excel</option>
                                        <option value="pdf">PDF</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-download"></i> Télécharger
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
