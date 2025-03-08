<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'CAFM Platform' ?></title>
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="<?= $this->asset('css/style.css') ?>" rel="stylesheet">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <img src="<?= $this->asset('images/logo.png') ?>" alt="CAFM Logo">
            <span>CAFM Platform</span>
        </div>
        <nav>
            <ul>
                <li>
                    <a href="<?= $this->url('dashboard') ?>" class="<?= $active === 'dashboard' ? 'active' : '' ?>">
                        <i class='bx bxs-dashboard'></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="<?= $this->url('assets') ?>" class="<?= $active === 'assets' ? 'active' : '' ?>">
                        <i class='bx bxs-box'></i>
                        <span>Assets</span>
                    </a>
                </li>
                <li>
                    <a href="<?= $this->url('workorders') ?>" class="<?= $active === 'workorders' ? 'active' : '' ?>">
                        <i class='bx bxs-wrench'></i>
                        <span>Work Orders</span>
                    </a>
                </li>
                <li>
                    <a href="<?= $this->url('inventory') ?>" class="<?= $active === 'inventory' ? 'active' : '' ?>">
                        <i class='bx bxs-store'></i>
                        <span>Inventory</span>
                    </a>
                </li>
                <li>
                    <a href="<?= $this->url('documents') ?>" class="<?= $active === 'documents' ? 'active' : '' ?>">
                        <i class='bx bxs-file'></i>
                        <span>Documents</span>
                    </a>
                </li>
                <li>
                    <a href="<?= $this->url('reports') ?>" class="<?= $active === 'reports' ? 'active' : '' ?>">
                        <i class='bx bxs-report'></i>
                        <span>Reports</span>
                    </a>
                </li>
                <li>
                    <a href="<?= $this->url('settings') ?>" class="<?= $active === 'settings' ? 'active' : '' ?>">
                        <i class='bx bxs-cog'></i>
                        <span>Settings</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <header class="top-bar">
            <div class="toggle-sidebar">
                <i class='bx bx-menu'></i>
            </div>
            <div class="search-box">
                <i class='bx bx-search'></i>
                <input type="text" placeholder="Search...">
            </div>
            <div class="user-menu">
                <div class="notifications">
                    <i class='bx bx-bell'></i>
                    <span class="badge">3</span>
                </div>
                <div class="user-info">
                    <img src="<?= $this->asset('images/avatar.png') ?>" alt="User Avatar">
                    <span><?= $_SESSION['user_name'] ?? 'User' ?></span>
                    <i class='bx bx-chevron-down'></i>
                    <ul class="dropdown-menu">
                        <li><a href="<?= $this->url('profile') ?>">Profile</a></li>
                        <li><a href="<?= $this->url('settings') ?>">Settings</a></li>
                        <li><a href="<?= $this->url('logout') ?>">Logout</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="content">
            <?php if ($flash = $this->flash('success')): ?>
                <div class="alert alert-success"><?= $flash ?></div>
            <?php endif; ?>

            <?php if ($flash = $this->flash('error')): ?>
                <div class="alert alert-danger"><?= $flash ?></div>
            <?php endif; ?>

            <?= $content ?>
        </main>
    </div>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="<?= $this->asset('js/main.js') ?>"></script>
</body>
</html> 