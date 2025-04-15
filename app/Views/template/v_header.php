<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= !empty($_SESSION['menu']) && $_SESSION['menu'] ? ucfirst($_SESSION['menu']) : 'StorageRoom' ?></title>
    <?= $this->include('template/v_import') ?>
</head>
<style>
    ::-webkit-scrollbar {
        width: 0;
        height: 0;
    }

    body {
        background: linear-gradient(217deg, rgba(182,235,255,.5), rgba(182,235,255,0) 70.71%),
                            linear-gradient(127deg, rgba(137,142,255,.5), rgba(137,142,255,0) 70.71%),
                            linear-gradient(336deg, rgba(216,175,255,.5), rgba(216,175,255,0) 70.71%);
        background-repeat: no-repeat;
        background-size: cover;
        background-attachment: fixed;
    }

    @media screen and (max-width: 850px) {
        .mobile-navbar {
            display: block !important;
            position: sticky;
            top: 0;
            z-index: 1000;
            width: 100%;
        }
        
        /* Hide desktop navbar on mobile */
        .flex-fill > .bg-white.shadow-sm.z-2 {
            display: none !important;
        }
        
        /* Adjust content padding to accommodate navbar */
        .flex-fill > .p-3.z-1 {
            padding-top: 0.5rem !important;
        }
        
        .nav-link.active {
            color: #676df0 !important;
        }
        
        #mobileNavContent {
            max-height: calc(100vh - 60px);
            overflow-y: auto;
        }
    }
</style>
<body>
    <div class="mobile-navbar d-none">
        <nav class="navbar navbar-expand-lg bg-white shadow-sm py-2 px-3">
            <div class="container-fluid">
                <a class="navbar-brand" href="<?= base_url('/') ?>">
                    <span style="font-size: 1.3rem; color: #676df0;"><span class="fw-semibold">Storage</span>Room</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mobileNavContent" aria-controls="mobileNavContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="bx bx-menu fs-4"></i>
                </button>
                <div class="collapse navbar-collapse mt-2" id="mobileNavContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <!-- Main Menu Items -->
                        <li class="nav-item">
                            <a class="nav-link <?= !empty($_SESSION['menu']) && $_SESSION['menu'] == 'dashboard' ? 'active fw-bold' : '' ?>" href="<?= base_url('/dashboard') ?>">
                                <i class="bx bxs-dashboard me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= !empty($_SESSION['menu']) && $_SESSION['menu'] == 'product' ? 'active fw-bold' : '' ?>" href="<?= base_url('/product') ?>">
                                <i class="bx bxs-package me-2"></i>Product
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= !empty($_SESSION['menu']) && $_SESSION['menu'] == 'storage' ? 'active fw-bold' : '' ?>" href="<?= base_url('/storage') ?>">
                                <i class="bx bxs-layer me-2"></i>Storage
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= !empty($_SESSION['menu']) && $_SESSION['menu'] == 'location' ? 'active fw-bold' : '' ?>" href="<?= base_url('/location') ?>">
                                <i class="bx bxs-customize me-2"></i>Location
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= !empty($_SESSION['menu']) && $_SESSION['menu'] == 'history' ? 'active fw-bold' : '' ?>" href="<?= base_url('/history') ?>">
                                <i class="bx bxs-report me-2"></i>History
                            </a>
                        </li>
                        
                        <?php if (strtolower($_SESSION['role']) == 'admin') :?>
                        <!-- Settings Menu Items for Admin -->
                        <li class="nav-item mt-3 border-bottom pb-1">
                            <span class="text-muted fw-semibold">Settings</span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= !empty($_SESSION['menu']) && $_SESSION['menu'] == 'users' ? 'active fw-bold' : '' ?>" href="<?= base_url('/users') ?>">
                                <i class="bx bxs-user me-2"></i>Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= !empty($_SESSION['menu']) && $_SESSION['menu'] == 'role' ? 'active fw-bold' : '' ?>" href="<?= base_url('/role') ?>">
                                <i class="bx bxs-group me-2"></i>Role
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= !empty($_SESSION['menu']) && $_SESSION['menu'] == 'category' ? 'active fw-bold' : '' ?>" href="<?= base_url('/category') ?>">
                                <i class="bx bxs-category-alt me-2"></i>Category
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= !empty($_SESSION['menu']) && $_SESSION['menu'] == 'type' ? 'active fw-bold' : '' ?>" href="<?= base_url('/type') ?>">
                                <i class="bx bx-copy me-2"></i>Type
                            </a>
                        </li>
                        <?php endif ?>
                        
                        <!-- Logout Option -->
                        <li class="nav-item border rounded-3 px-3 mt-4">
                            <a class="nav-link text-danger" href="#" id="mobile-logout-btn">
                                <i class="bx bx-power-off me-2"></i>Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <div class="vw-100 d-flex m-0 flex-nowrap">
        <!-- SIDEBAR -->
        <?= $this->include('template/v_sidebar')  ?>
        <div class="flex-fill">
            <!-- NAVBAR -->
            <div class="bg-white shadow-sm z-2 py-2 px-3 d-flex justify-content-between align-items-center">
                <div>
                    <span class="fs-5 fw-semibold"><?= !empty($menu_title) && $menu_title ? ucfirst($menu_title) : '' ?></span>
                </div>
                <div>
                    <span id="logout-btn" class="fs-4" style="cursor: pointer;"><i class="bx bx-power-off"></i></span>
                </div>
            </div>
            <!-- CONTENT -->
            <div class="p-3 z-1">