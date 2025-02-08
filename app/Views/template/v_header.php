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
</style>
<body>
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