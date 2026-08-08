<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>IT Inventory</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* ----- base & reset ----- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Roboto, system-ui, sans-serif;
            transition: background-color 0.2s ease, border-color 0.2s, color 0.15s;
        }

        body {
            display: flex;
            background: #f5f6fa;
            min-height: 100vh;
            transition: background 0.2s;
        }

        .sidebar {
            width: 60px;  /* Changed from 80px to 60px - thinner */
            height: 100vh;
            background: #1d2437;
            color: #e8edf5;
            position: fixed;
            top: 0;
            left: 0;
            transition: width 0.3s ease, background 0.2s, transform 0.3s ease;
            overflow: hidden;
            z-index: 1050;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.12);
            display: flex;
            flex-direction: column;
        }
        .sidebar.expanded {
            width: 250px;
        }

        /* dark mode sidebar */
        body.dark-mode .sidebar {
            background: #0f172a;
            box-shadow: 2px 0 12px rgba(0, 0, 0, 0.5);
        }

     .logo {
            padding: 20px 12px;
            text-align: center;
            font-size: 20px;
            font-weight: 700;
            border-bottom: 1px solid rgba(255, 255, 255, 0.07);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            white-space: nowrap;
            min-height: 70px;
            position: relative;
            transition: all 0.3s ease;
        }

        .logo i {
            font-size: 26px;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .logo span {
            opacity: 0;
            transition: opacity 0.3s ease, transform 0.3s ease, width 0.3s ease;
            margin-left: 4px;
            transform: translateX(-10px);
            width: 0;
            overflow: hidden;
            display: inline-block;
        }

        /* When sidebar is expanded */
        .sidebar.expanded .logo span {
            opacity: 1;
            transform: translateX(0);
            width: auto;
        }

        /* When sidebar is collapsed - center the icon */
        .sidebar:not(.expanded) .logo {
            justify-content: center;
        }

        .sidebar:not(.expanded) .logo i {
            /* Icon is already centered because of justify-content: center */
        }

        /* For mobile - when sidebar is open */
        @media (max-width: 768px) {
            .sidebar.mobile-open .logo span {
                opacity: 1 !important;
                transform: translateX(0) !important;
            }
        }

        .sidebar ul {
            list-style: none;
            margin-top: 12px;
            padding: 0 8px;
            flex: 1;
        }
        .sidebar ul li {
            padding: 12px 14px;
            border-radius: 10px;
            margin-bottom: 4px;
            transition: background 0.2s;
            cursor: pointer;
            display: flex;
            align-items: center;
            white-space: nowrap;
        }
        .sidebar ul li:hover {
            background: #2b3550;
        }
        body.dark-mode .sidebar ul li:hover {
            background: #1e2a44;
        }
        .sidebar ul li a {
            color: #e8edf5;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 14px;
            width: 100%;
            font-size: 15px;
            font-weight: 500;
        }
        .sidebar ul li a i {
            font-size: 1.3rem;
            width: 26px;
            text-align: center;
            flex-shrink: 0;
        }
        .sidebar ul li a span {
            opacity: 0;
            transition: opacity 0.2s;
            font-weight: 500;
        }
        .sidebar.expanded ul li a span {
            opacity: 1;
        }

        /* toggle button inside sidebar */
        .sidebar-toggle-btn {
            margin: 12px auto 20px auto;
            background: rgba(255, 255, 255, 0.06);
            border: none;
            color: #cdd5e6;
            width: 44px;
            height: 44px;
            border-radius: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            cursor: pointer;
            transition: 0.2s;
            flex-shrink: 0;
        }
        .sidebar-toggle-btn:hover {
            background: #2b3550;
            color: white;
        }
        .sidebar.expanded .sidebar-toggle-btn {
            width: 90%;
            border-radius: 30px;
            background: rgba(255, 255, 255, 0.08);
        }

        .main {
    margin-left: 60px;  /* Match the new thinner sidebar */
    width: 100%;
    transition: margin-left 0.3s ease, background 0.2s;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    background: #f5f6fa;
}

@media (max-width: 768px) {
    .main {
        margin-left: 0 !important;  /* Full width on mobile */
    }
}
        /* dark mode main background */
        body.dark-mode .main {
            background: #1e2229;
        }

        /* header / navbar */
        .header {
            height: 70px;
            background: white;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 30px;
            flex-shrink: 0;
            transition: background 0.2s, box-shadow 0.2s;
        }
        body.dark-mode .header {
            background: #262c38;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }
        .header h2 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1d2437;
            transition: color 0.2s;
        }
        body.dark-mode .header h2 {
            color: #eef2f8;
        }
        .header .admin-badge {
            background: #f0f2f7;
            padding: 8px 18px;
            border-radius: 40px;
            font-weight: 500;
            color: #1d2437;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background 0.2s, color 0.2s;
        }
        body.dark-mode .header .admin-badge {
            background: #343e50;
            color: #e0e7f0;
        }
        .header .admin-badge i {
            color: #4a5a7a;
        }
        body.dark-mode .header .admin-badge i {
            color: #b0c0d8;
        }

        /* night mode toggle button (in header) */
        .dark-toggle {
            background: transparent;
            border: none;
            font-size: 1.6rem;
            color: #2d3748;
            padding: 6px 12px;
            border-radius: 30px;
            transition: 0.2s;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .dark-toggle:hover {
            background: rgba(0, 0, 0, 0.05);
        }
        body.dark-mode .dark-toggle {
            color: #f0eef7;
        }
        body.dark-mode .dark-toggle:hover {
            background: rgba(255, 255, 255, 0.06);
        }
        .dark-toggle .toggle-label {
            font-size: 0.9rem;
            font-weight: 500;
        }
        @media (max-width: 480px) {
            .dark-toggle .toggle-label {
                display: none;
            }
        }

        /* content */
        .content {
            padding: 30px;
            flex: 1;
        }
        .card {
            background: white;
            border-radius: 14px;
            padding: 24px 28px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
            border: none;
            transition: background 0.2s, box-shadow 0.2s, color 0.2s;
        }
        body.dark-mode .card {
            background: #2a313e;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.3);
            color: #e6ecf5;
        }
        body.dark-mode .card .text-secondary {
            color: #b6c2d6 !important;
        }
        body.dark-mode .card .text-muted {
            color: #a0aec0 !important;
        }
        body.dark-mode .card hr {
            border-color: #3f4a5a;
        }

        .demo-card-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 24px;
            margin-top: 20px;
        }
        .demo-card-grid .card-item {
            background: white;
            border-radius: 16px;
            padding: 22px 26px;
            flex: 1 1 180px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
            border: 1px solid #edf0f5;
            transition: background 0.2s, border 0.2s, color 0.2s;
        }
        body.dark-mode .demo-card-grid .card-item {
            background: #2d3543;
            border-color: #3f4a5a;
            color: #e6ecf5;
        }
        .demo-card-grid .card-item i {
            font-size: 2rem;
            color: #1d2437;
            opacity: 0.7;
        }
        body.dark-mode .demo-card-grid .card-item i {
            color: #b8cbff;
        }
        .demo-card-grid .card-item h5 {
            margin-top: 12px;
            font-weight: 600;
        }

        /* badge / quick actions dark */
        body.dark-mode .badge.bg-light {
            background: #343e50 !important;
            color: #e6ecf5 !important;
            border-color: #4d5a70 !important;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 68px;
            }
            .sidebar.expanded {
                width: 230px;
            }
            .main {
                margin-left: 68px;
            }
            .sidebar.expanded~.main {
                margin-left: 230px;
            }
            .header h2 {
                font-size: 1.2rem;
            }
            .content {
                padding: 20px 16px;
            }
        }
        /* Mobile: Make sidebar even thinner and add hamburger menu */
@media (max-width: 768px) {
    .sidebar {
        width: 0;
        transform: translateX(-100%);
        box-shadow: none;
    }
    
    /* When mobile menu is open, force it to expanded state */
    .sidebar.mobile-open {
        width: 250px;
        transform: translateX(0);
        box-shadow: 4px 0 20px rgba(0, 0, 0, 0.3);
    }
    
    /* Force expanded state on mobile when open */
    .sidebar.mobile-open .logo span,
    .sidebar.mobile-open ul li a span {
        opacity: 1 !important;
        display: inline-block !important;
    }
    
    /* Hide the toggle button on mobile since we use hamburger */
    .sidebar .sidebar-toggle-btn {
        display: none;
    }
    
    .main {
        margin-left: 0 !important;
    }
    
    /* Smaller text on mobile */
    .sidebar.mobile-open ul li a {
        font-size: 14px;
        gap: 12px;
    }
    
    .sidebar.mobile-open ul li a i {
        font-size: 1.1rem;
        width: 22px;
    }
    
    .sidebar.mobile-open .logo {
        font-size: 18px;
        padding: 15px 10px;
    }
}

/* Even smaller screens (phones) */
@media (max-width: 480px) {
    .sidebar.mobile-open {
        width: 220px;  /* Slightly narrower on small phones */
    }
    
    .sidebar ul li {
        padding: 8px 10px;
    }
    
    .sidebar ul li a {
        font-size: 13px;
        gap: 10px;
    }
    
    .sidebar ul li a i {
        font-size: 1rem;
        width: 20px;
    }
    
    .logo {
        padding: 12px 8px;
        font-size: 16px;
    }
    
    .logo i {
        font-size: 20px;
    }
}
/* Mobile menu button - visible only on phones */
.mobile-menu-btn {
    display: none;
    position: fixed;
    top: 15px;
    left: 15px;
    z-index: 1060;
    background: #1d2437;
    color: white;
    border: none;
    width: 44px;
    height: 44px;
    border-radius: 8px;
    font-size: 1.4rem;
    cursor: pointer;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    transition: 0.2s;
}

.mobile-menu-btn:hover {
    background: #2b3550;
}

.mobile-menu-btn:active {
    transform: scale(0.95);
}

/* Dark mode support */
body.dark-mode .mobile-menu-btn {
    background: #0f172a;
}

@media (max-width: 768px) {
    .mobile-menu-btn {
        display: flex;
        align-items: center;
        justify-content: center;
    }
}

/* When sidebar is open, shift the hamburger button */
.sidebar.mobile-open ~ .mobile-menu-btn {
    left: 260px; /* Adjust based on sidebar width */
}
    </style>
</head>
<body>


@include('partials.sidebar')

<div class="main">

@include('partials.navbar')

<div class="content">

@yield('content')

</div>

</div>




 <!-- Bootstrap JS (bundle) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

   <script>
        (function() {
            // ----- SIDEBAR TOGGLE -----
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('toggleSidebarBtn');
            const toggleIcon = document.getElementById('toggleIcon');

            // ensure initial state: small
            sidebar.classList.remove('expanded');
            toggleIcon.className = 'fas fa-chevron-right';

            function toggleSidebar() {
                sidebar.classList.toggle('expanded');
                const isExpanded = sidebar.classList.contains('expanded');
                toggleIcon.className = isExpanded ? 'fas fa-chevron-left' : 'fas fa-chevron-right';
                try {
                    localStorage.setItem('sidebarExpanded', isExpanded ? 'true' : 'false');
                } catch (e) {}
            }
            toggleBtn.addEventListener('click', toggleSidebar);

            // restore sidebar state
            try {
                const saved = localStorage.getItem('sidebarExpanded');
                if (saved === 'true') {
                    sidebar.classList.add('expanded');
                    toggleIcon.className = 'fas fa-chevron-left';
                } else if (saved === 'false') {
                    sidebar.classList.remove('expanded');
                    toggleIcon.className = 'fas fa-chevron-right';
                }
            } catch (e) {}


            // ----- DARK MODE TOGGLE (navbar) -----
            const darkToggle = document.getElementById('darkModeToggle');
            const darkIcon = document.getElementById('darkIcon');
            const darkLabel = document.getElementById('darkLabel');

            // check localStorage
            let darkMode = false;
            try {
                const stored = localStorage.getItem('darkMode');
                if (stored === 'true') darkMode = true;
                else if (stored === 'false') darkMode = false;
            } catch (e) {}

            function applyDarkMode(isDark) {
                if (isDark) {
                    document.body.classList.add('dark-mode');
                    darkIcon.className = 'fas fa-sun';
                    darkLabel.textContent = 'Light';
                } else {
                    document.body.classList.remove('dark-mode');
                    darkIcon.className = 'fas fa-moon';
                    darkLabel.textContent = 'Dark';
                }
                try {
                    localStorage.setItem('darkMode', isDark ? 'true' : 'false');
                } catch (e) {}
            }

            // initial apply
            applyDarkMode(darkMode);

            darkToggle.addEventListener('click', function() {
                const now = document.body.classList.contains('dark-mode');
                applyDarkMode(!now);
            });

            // extra: if you want to sync dark mode with system preference (optional)
            // but we keep it manual for user control.

        })();
    </script>
@yield('scripts')
</body>
</html>