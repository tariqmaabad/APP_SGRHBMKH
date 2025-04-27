<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Export Liste des Établissements</h3>
                </div>
                <div class="card-body">
                    <form method="GET" action="/APP_SGRHBMKH/export/establishments">
                        <div class="row">
                            <div class="col-md-4 offset-md-4">
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
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-download"></i> Télécharger la liste complète
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
