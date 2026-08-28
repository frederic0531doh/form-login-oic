<?php
require __DIR__ . '/includes/auth.php';

$pageTitle = 'Tableau de bord';
$activePage = 'dashboard';
require __DIR__ . '/includes/layout_top.php';
?>

                    <div class="card mb-4">
                        <div class="card-body d-flex align-items-center gap-3">
                            <span class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 64px; height: 64px; font-size: 1.75rem;">
                                <?= htmlspecialchars($userInitial) ?>
                            </span>
                            <div>
                                <h2 class="h4 mb-1">Bienvenue, <?= htmlspecialchars($user['username']) ?> !</h2>
                                <p class="mb-0 text-secondary">Heureux de vous revoir sur votre espace personnel OIC.</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon text-bg-primary shadow-sm"><i class="bi bi-building"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Raison sociale</span>
                                    <span class="info-box-number"><?= htmlspecialchars($user['company_name']) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon text-bg-success shadow-sm"><i class="bi bi-person-badge"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Nom d'utilisateur</span>
                                    <span class="info-box-number"><?= htmlspecialchars($user['username']) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon text-bg-warning shadow-sm"><i class="bi bi-envelope"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Adresse mail</span>
                                    <span class="info-box-number"><?= htmlspecialchars($user['email']) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon text-bg-danger shadow-sm"><i class="bi bi-hash"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Identifiant</span>
                                    <span class="info-box-number">#<?= htmlspecialchars($user['id_user']) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
