<div id="sidebar-container">
    <div id="sidebar" class="bg-white shadow z-3 <?= $_COOKIE['sidebar-status'] == 'open' ? 'sidebar-open' : 'sidebar-close'?> position-fixed d-flex flex-column justify-content-between" style="height: 100vh;">
        <div>
            <div class="d-flex gap-2 p-3 justify-content-between align-items-center">
                <span onclick="if ($('#sidebar').hasClass('sidebar-open')) window.location.href = '<?= base_url('/') ?>'" class="title" style="width: 0; font-size: 1.3rem; color: #676df0;"><span class="fw-semibold">Storage</span>Room</span>
                <span class="fs-5 sidebar-toggle" style="cursor: pointer;" onclick="openSidebar()"><i class="bx bx-arrow-to-left"></i></span>
            </div>
            <div class="mt-3 mx-3" style="overflow-y: hidden;">
                <!-- Menu Group -->
                <div class="px-1 mt-3 rounded-3 text-secondary menu-group" style="cursor: default; font-size: .9rem;">
                    <span>Main</span>
                </div>
                <!-- Menu -->
                <div onclick="<?= !empty($_SESSION['menu']) && $_SESSION['menu'] == 'dashboard' ? '' : "window.location.href = '". base_url('/dashboard') ."'" ?>" class="menu <?= !empty($_SESSION['menu']) && $_SESSION['menu'] == 'dashboard' ? 'menu-active' : '' ?> my-2 p-2 rounded-3 text-secondary fw-semibold d-flex align-items-center" style="cursor: pointer;">
                    <i class="bx bxs-dashboard fs-5 me-2"></i>
                    <span>Dashboard</span>
                </div>
                <div onclick="<?= !empty($_SESSION['menu']) && $_SESSION['menu'] == 'product' ? '' : "window.location.href = '". base_url('/product') ."'" ?>" class="menu <?= !empty($_SESSION['menu']) && $_SESSION['menu'] == 'product' ? 'menu-active' : '' ?> my-2 p-2 rounded-3 text-secondary fw-semibold d-flex align-items-center" style="cursor: pointer;">
                    <i class="bx bxs-package fs-5 me-2"></i>
                    <span>Product</span>
                </div>
                <div onclick="<?= !empty($_SESSION['menu']) && $_SESSION['menu'] == 'storage' ? '' : "window.location.href = '". base_url('/storage') ."'" ?>" class="menu <?= !empty($_SESSION['menu']) && $_SESSION['menu'] == 'storage' ? 'menu-active' : '' ?> my-2 p-2 rounded-3 text-secondary fw-semibold d-flex align-items-center" style="cursor: pointer;">
                    <i class="bx bxs-layer fs-5 me-2"></i>
                    <span>Storage</span>
                </div>
                <div onclick="<?= !empty($_SESSION['menu']) && $_SESSION['menu'] == 'location' ? '' : "window.location.href = '". base_url('/location') ."'" ?>" class="menu <?= !empty($_SESSION['menu']) && $_SESSION['menu'] == 'location' ? 'menu-active' : '' ?> my-2 p-2 rounded-3 text-secondary fw-semibold d-flex align-items-center" style="cursor: pointer;">
                    <i class="bx bxs-customize fs-5 me-2"></i>
                    <span>Location</span>
                </div>
                <div onclick="<?= !empty($_SESSION['menu']) && $_SESSION['menu'] == 'history' ? '' : "window.location.href = '". base_url('/history') ."'" ?>" class="menu <?= !empty($_SESSION['menu']) && $_SESSION['menu'] == 'history' ? 'menu-active' : '' ?> my-2 p-2 rounded-3 text-secondary fw-semibold d-flex align-items-center" style="cursor: pointer;">
                    <i class="bx bxs-report fs-5 me-2"></i>
                    <span>History</span>
                </div>
                <!-- Menu Group -->
                <div class="px-1 mt-3 rounded-3 text-secondary menu-group" style="cursor: default; font-size: .9rem;">
                    <span>Settings</span>
                </div>
                <!-- Menu -->
                <div onclick="<?= !empty($_SESSION['menu']) && $_SESSION['menu'] == 'users' ? '' : "window.location.href = '". base_url('/users') ."'" ?>" class="menu <?= !empty($_SESSION['menu']) && $_SESSION['menu'] == 'users' ? 'menu-active' : '' ?> my-2 p-2 rounded-3 text-secondary fw-semibold d-flex align-items-center" style="cursor: pointer;">
                    <i class="bx bxs-user fs-5 me-2"></i>
                    <span>Users</span>
                </div>
                <div onclick="<?= !empty($_SESSION['menu']) && $_SESSION['menu'] == 'role' ? '' : "window.location.href = '". base_url('/role') ."'" ?>" class="menu <?= !empty($_SESSION['menu']) && $_SESSION['menu'] == 'role' ? 'menu-active' : '' ?> my-2 p-2 rounded-3 text-secondary fw-semibold d-flex align-items-center" style="cursor: pointer;">
                    <i class="bx bxs-group fs-5 me-2"></i>
                    <span>Role</span>
                </div>
                <div onclick="<?= !empty($_SESSION['menu']) && $_SESSION['menu'] == 'category' ? '' : "window.location.href = '". base_url('/category') ."'" ?>" class="menu <?= !empty($_SESSION['menu']) && $_SESSION['menu'] == 'category' ? 'menu-active' : '' ?> my-2 p-2 rounded-3 text-secondary fw-semibold d-flex align-items-center" style="cursor: pointer;">
                    <i class="bx bxs-category-alt fs-5 me-2"></i>
                    <span>Category</span>
                </div>
            </div>
        </div>
        <div id="copyright" class="d-flex justify-content-center w-100 mt-5 mb-3" style="text-wrap: nowrap;">
            <span class="text-secondary" style="font-size: .75rem;">Copyright &#169; 2025 Secondnyot</span>
        </div>
    </div>
</div>
<style>
    #sidebar-container {
        transition: width .7s cubic-bezier(0.25, 0.1, 0.25, 1);
    }

    #sidebar {
        transition: width .7s cubic-bezier(0.25, 0.1, 0.25, 1);
        overflow-x: hidden;
    }

    #sidebar-container:has(#sidebar.sidebar-open) {
        width: 240px;
    }

    #sidebar-container:has(#sidebar.sidebar-close) {
        width: 48px;
    }

    #sidebar.sidebar-open {
        width: 240px;
    }

    #sidebar.sidebar-close {
        width: 48px;
    }
    
    #sidebar.sidebar-open .title,
    #sidebar.sidebar-open #copyright {
        transition: all .5s .3s ease-in-out;
        opacity: 1;
    }

    #sidebar.sidebar-close .title,
    #sidebar.sidebar-close #copyright {
        /* transition: all .3s ease-in-out; */
        opacity: 0;
    }

    #sidebar.sidebar-close .menu-group {
        display: none;
    }

    #sidebar.sidebar-close div:has(>.title) {
        gap: 0 !important;
    }

    #sidebar .sidebar-toggle {
        transition: all .5s ease-in-out;
    }

    #sidebar.sidebar-close .sidebar-toggle {
        transform: rotate(180deg) translateX(3px);
    }

    #sidebar.sidebar-close div:has(>.menu) {
        /* padding: 0 !important; */
        margin: 5px !important;
    }

    #sidebar.sidebar-close .menu i {
        margin-right: 0 !important;
    }

    #sidebar.sidebar-close .menu span {
        display: none;
    }

    #sidebar .menu.menu-active {
        background-color: #676df0 !important;
        color: white !important;
    }

    .custom-tooltip {
        --bs-tooltip-bg: rgb(137,142,255);
        --bs-tooltip-color: white;
    }
</style>
<script>
    $(document).ready(function () {
        $('#sidebar .menu').each(function (i, e) {
            $(e).attr('data-bs-toggle', 'tooltip')
            $(e).attr('data-bs-custom-class', 'custom-tooltip')
            $(e).attr('data-bs-placement', 'right')
            $(e).attr('data-bs-title', $('span', e).text())
        })

        var tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        var tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

        if (Cookies.get('sidebar-status') == 'open') {
            $('#sidebar .menu').each(function (i, e) {
                bootstrap.Tooltip.getInstance($(e)).dispose()
            })
            $('#sidebar .title').css('cursor', 'pointer')
        } else if (Cookies.get('sidebar-status') == 'close') {
            $('#sidebar .title').css('cursor', 'default')
        }

    })
    
    function openSidebar() {
        e = "#sidebar"
        $(e).toggleClass('sidebar-open')
        $(e).toggleClass('sidebar-close')
    
        if ($('#sidebar').hasClass('sidebar-close')) {
            Cookies.set('sidebar-status', 'close')
            $('.title', e).css('cursor', 'default')
        } else if ($('#sidebar').hasClass('sidebar-open')) {
            Cookies.set('sidebar-status', 'open')
            $('.title', e).css('cursor', 'pointer')
        }
    
        $('.menu', e).each(function (i, e) {
            let tooltipInstance = bootstrap.Tooltip.getInstance(e)
            if ($('#sidebar').hasClass('sidebar-close')) {
                if (!tooltipInstance) new bootstrap.Tooltip(e);
            } else if ($('#sidebar').hasClass('sidebar-open')) {
                if (tooltipInstance) tooltipInstance.dispose();
            }
        })
    }
</script>