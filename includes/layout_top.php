<?php
// Attend les variables suivantes, déjà définies par la page appelante :
// $pageTitle (string), $activePage (string : 'dashboard' | 'documents'),
// $user (array), $userInitial (string)
?>
<!doctype html>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <title><?= htmlspecialchars($pageTitle) ?> | OIC</title>
    <link rel="shortcut icon" href="assets/img/logo-oic.jpg" type="image/x-icon">

    <!-- Theme init : évite le flash d'un mauvais thème au chargement (repris du template AdminLTE) -->
    <script>
      (() => {
        'use strict';
        const root = document.documentElement;
        const STORAGE_KEY = 'lte-theme';
        let stored = null;
        try {
          stored = localStorage.getItem(STORAGE_KEY);
        } catch {
          // localStorage indisponible (navigation privée, iframe...)
        }
        let resolved = 'light';
        if (stored === 'dark' || stored === 'light') {
          resolved = stored;
        } else if (globalThis.matchMedia('(prefers-color-scheme: dark)').matches) {
          resolved = 'dark';
        }
        root.setAttribute('data-bs-theme', resolved);
        root.style.colorScheme = resolved;
      })();
    </script>

    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      crossorigin="anonymous"
      media="print"
      onload="this.media = 'all'"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
      crossorigin="anonymous"
    />
    <!-- Bootstrap Icons hébergées en local (assets/vendor), plus de dépendance à un CDN -->
    <link rel="stylesheet" href="assets/vendor/bootstrap-icons/bootstrap-icons.min.css" />
    <!-- CSS AdminLTE copié dans assets/vendor (le répertoire adminlte/ sera supprimé en fin de projet) -->
    <link rel="stylesheet" href="assets/vendor/adminlte/css/adminlte.css" />
    <!-- Surcharges de la charte graphique OIC -->
    <link rel="stylesheet" href="assets/css/dashboard-theme.css" />
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">

        <!--begin::Header-->
        <nav class="app-header navbar navbar-expand bg-body">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button" aria-label="Afficher/masquer le menu">
                            <i class="bi bi-list"></i>
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav ms-auto">
                    <!--begin::Color Mode Toggle-->
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" id="bd-theme" aria-label="Changer de thème" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-sun-fill" data-lte-theme-icon="light"></i>
                            <i class="bi bi-moon-fill d-none" data-lte-theme-icon="dark"></i>
                            <i class="bi bi-circle-half d-none" data-lte-theme-icon="auto"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="bd-theme" style="--bs-dropdown-min-width: 8rem">
                            <li>
                                <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">
                                    <i class="bi bi-sun-fill me-2"></i> Clair
                                    <i class="bi bi-check-lg ms-auto d-none"></i>
                                </button>
                            </li>
                            <li>
                                <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
                                    <i class="bi bi-moon-fill me-2"></i> Sombre
                                    <i class="bi bi-check-lg ms-auto d-none"></i>
                                </button>
                            </li>
                            <li>
                                <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="auto" aria-pressed="true">
                                    <i class="bi bi-circle-half me-2"></i> Auto
                                    <i class="bi bi-check-lg ms-auto d-none"></i>
                                </button>
                            </li>
                        </ul>
                    </li>
                    <!--end::Color Mode Toggle-->

                    <!--begin::User Menu Dropdown-->
                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <span class="user-image rounded-circle shadow bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width: 2rem; height: 2rem;">
                                <?= htmlspecialchars($userInitial) ?>
                            </span>
                            <span class="d-none d-md-inline"><?= htmlspecialchars($user['username']) ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <li class="user-header text-bg-primary">
                                <span class="rounded-circle shadow bg-white text-primary d-inline-flex align-items-center justify-content-center fw-bold" style="width: 90px; height: 90px; font-size: 2.5rem;">
                                    <?= htmlspecialchars($userInitial) ?>
                                </span>
                                <p>
                                    <?= htmlspecialchars($user['username']) ?>
                                    <small><?= htmlspecialchars($user['company_name']) ?></small>
                                </p>
                            </li>
                            <li class="user-footer">
                                <a href="controllers/logout.php" class="btn btn-outline-danger w-100">
                                    <i class="bi bi-box-arrow-right me-1"></i> Déconnexion
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!--end::User Menu Dropdown-->
                </ul>
            </div>
        </nav>
        <!--end::Header-->

        <!--begin::Sidebar-->
        <aside class="app-sidebar shadow" data-bs-theme="dark">
            <div class="sidebar-brand">
                <a href="dashboard.php" class="brand-link">
                    <img src="assets/img/logo-oic.jpg" alt="Logo OIC" class="brand-image opacity-75 shadow rounded" />
                    <span class="brand-text fw-light"> <b>OIC</b> </span>
                </a>
            </div>

            <div class="sidebar-wrapper">
                <nav class="mt-2" aria-label="Navigation principale">
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" data-accordion="false">
                        <li class="nav-item">
                            <a href="dashboard.php" class="nav-link<?= $activePage === 'dashboard' ? ' active' : '' ?>">
                                <i class="nav-icon bi bi-speedometer"></i>
                                <p>Tableau de bord</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="documents.php" class="nav-link<?= $activePage === 'documents' ? ' active' : '' ?>">
                                <i class="nav-icon bi bi-folder"></i>
                                <p>Documents</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="controllers/logout.php" class="nav-link">
                                <i class="nav-icon bi bi-box-arrow-right"></i>
                                <p>Déconnexion</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>
        <!--end::Sidebar-->

        <!--begin::App Main-->
        <main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h1 class="mb-0 fs-3"><?= htmlspecialchars($pageTitle) ?></h1>
                        </div>
                        <div class="col-sm-6">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb float-sm-end">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Accueil</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($pageTitle) ?></li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <div class="app-content">
                <div class="container-fluid">
