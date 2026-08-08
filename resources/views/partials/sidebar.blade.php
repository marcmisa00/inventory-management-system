 <!-- ========== SIDEBAR ========== -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
  <button class="mobile-menu-btn" onclick="toggleMobileSidebar()">
    <i class="fas fa-bars"></i>
</button>
    <div class="sidebar" id="sidebar">
        <div class="logo">
            <i class="fas fa-laptop-code"></i>
            <span>IT</span>
        </div>

        <ul>
            <li>
                <a href="{{ url('/') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('assets.index') }}">
                    <i class="fas fa-boxes"></i>
                    <span>Stocks</span>
                </a>
            </li>
            <li>
                <a href="{{ route('purchaseTracker.index') }}">
                    <i class="fas fa-clipboard-list"></i>
                    <span>Purchase Tracker</span>
                </a>
            </li>
            <li>
                <a href="{{ route('user-monitoring.index') }}">
                    <i class="fa-solid fa-book"></i>
                    <span>Pc User Monitoring</span>
                </a>
            </li>
            <li>
                <a href="{{ route('users.index') }}">
                    <i class="fas fa-users"></i>
                    <span>Employees</span>
                </a>
            </li>
        </ul>

        <!-- toggle button (inside sidebar) -->
        <button class="sidebar-toggle-btn" id="toggleSidebarBtn" aria-label="Toggle sidebar">
            <i class="fas fa-chevron-right" id="toggleIcon"></i>
        </button>
    </div>

    <script>
    function toggleMobileSidebar() {
    const sidebar = document.getElementById('sidebar');
    
    // Toggle mobile-open class
    sidebar.classList.toggle('mobile-open');
    
    // When opening on mobile, ALWAYS expand to show names
    if (sidebar.classList.contains('mobile-open')) {
        sidebar.classList.add('expanded');
    } else {
        // When closing, remove expanded if on mobile
        if (window.innerWidth <= 768) {
            sidebar.classList.remove('expanded');
        }
    }
}

// Close sidebar when clicking outside on mobile
document.addEventListener('click', function(event) {
    const sidebar = document.getElementById('sidebar');
    const menuBtn = document.querySelector('.mobile-menu-btn');
    
    if (window.innerWidth <= 768) {
        const isClickInside = sidebar.contains(event.target) || menuBtn.contains(event.target);
        if (!isClickInside && sidebar.classList.contains('mobile-open')) {
            sidebar.classList.remove('mobile-open');
        }
    }
});

// Close sidebar when a link is clicked (mobile)
document.querySelectorAll('.sidebar a').forEach(link => {
    link.addEventListener('click', function() {
        if (window.innerWidth <= 768) {
            document.getElementById('sidebar').classList.remove('mobile-open');
        }
    });
});
        </script>
