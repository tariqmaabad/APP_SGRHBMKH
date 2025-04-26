<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2>Modifier le Personnel</h2>
            <p class="text-muted">
                PPR: <?php echo htmlspecialchars($personnel['ppr']); ?> | 
                Nom: <?php echo htmlspecialchars($personnel['nom'] . ' ' . $personnel['prenom']); ?>
            </p>
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

<script>
// Populate grade dropdown if corps is selected
document.addEventListener('DOMContentLoaded', function() {
    const corpsSelect = document.getElementById('corps_id');
    const gradeSelect = document.getElementById('grade_id');
    const selectedGrade = "<?php echo $personnel['grade_id'] ?? ''; ?>";
    
    if (corpsSelect.value) {
        fetch(`/APP_SGRHBMKH/api/grades/${corpsSelect.value}`)
            .then(response => response.json())
            .then(grades => {
                gradeSelect.innerHTML = '<option value="">Sélectionner...</option>';
                grades.forEach(grade => {
                    const option = document.createElement('option');
                    option.value = grade.id;
                    option.textContent = grade.nom_grade;
                    if (grade.id == selectedGrade) {
                        option.selected = true;
                    }
                    gradeSelect.appendChild(option);
                });
            });
    }
});
</script>
