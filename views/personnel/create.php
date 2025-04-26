<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2>Nouveau Personnel</h2>
        </div>
        <div class="col text-end">
            <a href="/APP_SGRHBMKH/personnel" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour à la liste
            </a>
        </div>
    </div>

    <?php if (isset($messages) && !empty($messages)): ?>
        <?php foreach ($messages as $message): ?>
            <div class="alert alert-<?php echo $message['type'] === 'error' ? 'danger' : $message['type']; ?> alert-dismissible fade show">
                <?php echo $message['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <?php include '_form.php'; ?>
        </div>
    </div>
</div>
