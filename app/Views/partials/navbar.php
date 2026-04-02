<header class="navbar">
    <div class="navbar-left">
        <button class="menu-toggle" onclick="toggleSidebar()" aria-label="Toggle Menu">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>
        <h1 class="page-title"><?= $pageTitle ?? 'Dashboard' ?></h1>
    </div>
    
    <div class="navbar-right">
        <button class="navbar-action" aria-label="Search">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
        </button>
        
        <button class="navbar-action" aria-label="Notifications">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
            </svg>
            <span class="notification-badge">3</span>
        </button>
        
        <div class="user-profile" onclick="toggleUserMenu()">
            <div class="user-avatar">
                <?= strtoupper(substr($_SESSION['user']['name'] ?? 'U', 0, 1)) ?>
            </div>
            <div class="user-info">
                <div class="user-name"><?= htmlspecialchars($_SESSION['user']['name'] ?? 'User') ?></div>
                <div class="user-role"><?= ucfirst($_SESSION['user']['role'] ?? 'User') ?></div>
            </div>
        </div>
    </div>
</header>

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
}

function toggleUserMenu() {
    // User menu toggle functionality
}
</script>
