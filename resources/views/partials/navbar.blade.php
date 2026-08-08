<!-- HEADER / NAVBAR -->
<div class="header">
    <hr>
    <div class="d-flex align-items-center gap-3">
        <!-- Dark Mode -->
        <button class="dark-toggle" id="darkModeToggle" aria-label="Toggle dark mode">
            <i class="fas fa-moon" id="darkIcon"></i>
            <span class="toggle-label" id="darkLabel">Dark</span>
        </button>
        <!-- User -->
        <div class="admin-badge">
            <i class="fas fa-user-circle"></i>
            <span>
                @if(auth()->user()->user)
                    {{ auth()->user()->user->firstname }}
                    {{ auth()->user()->user->lastname }}
                @else
                    {{ auth()->user()->username }}
                @endif
            </span>
        </div>

        <!-- Logout -->
        <form action="{{ route('logout') }}" method="POST" style="margin:0; display:inline;">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </form>

    </div>
</div>
<style>
/* ===== HEADER / NAVBAR STYLES ===== */

.header {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fc 100%);
    padding: 16px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 2px solid rgba(102, 126, 234, 0.1);
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
    min-height: 80px;
    transition: all 0.3s ease;
    position: sticky;
    top: 0;
    z-index: 1000;
    backdrop-filter: blur(10px);
    background: rgba(255, 255, 255, 0.95);
}

/* Dark mode header */
body.dark-mode .header {
    background: linear-gradient(135deg, #1e2433 0%, #262c3a 100%);
    border-bottom-color: rgba(102, 126, 234, 0.2);
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.3);
    background: rgba(30, 36, 51, 0.95);
}

/* Header Title */
.header h2 {
    font-size: 1.6rem;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: color 0.3s;
    letter-spacing: -0.3px;
}

.header h2 i {
    font-size: 1.8rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

body.dark-mode .header h2 {
    color: #eef2f8;
}

/* Right side container */
.header .d-flex {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}

/* Dark Mode Toggle Button */
.dark-toggle {
    background: rgba(102, 126, 234, 0.08);
    border: 2px solid rgba(102, 126, 234, 0.15);
    padding: 8px 18px;
    border-radius: 50px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 0.95rem;
    font-weight: 600;
    color: #4b5563;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.dark-toggle::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(102, 126, 234, 0.1);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.dark-toggle:hover::before {
    width: 300px;
    height: 300px;
}

.dark-toggle:hover {
    background: rgba(102, 126, 234, 0.15);
    border-color: #667eea;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
}

.dark-toggle:active {
    transform: scale(0.95);
}

.dark-toggle i {
    font-size: 1.2rem;
    transition: transform 0.5s ease;
}

.dark-toggle:hover i {
    transform: rotate(20deg);
}

.dark-toggle .toggle-label {
    font-size: 0.85rem;
    letter-spacing: 0.3px;
}

/* Dark mode active state */
body.dark-mode .dark-toggle {
    background: rgba(255, 255, 255, 0.08);
    border-color: rgba(255, 255, 255, 0.15);
    color: #eef2f8;
}

body.dark-mode .dark-toggle:hover {
    background: rgba(255, 255, 255, 0.15);
    border-color: #8b9cf7;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

/* Admin Badge */
.admin-badge {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.08), rgba(118, 75, 162, 0.08));
    padding: 8px 20px;
    border-radius: 50px;
    font-weight: 600;
    color: #1f2937;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    gap: 10px;
    border: 2px solid rgba(102, 126, 234, 0.1);
    transition: all 0.3s ease;
    white-space: nowrap;
}

.admin-badge:hover {
    border-color: #667eea;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.15), rgba(118, 75, 162, 0.15));
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
}

.admin-badge i {
    font-size: 1.4rem;
    color: #667eea;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

body.dark-mode .admin-badge {
    background: rgba(255, 255, 255, 0.05);
    border-color: rgba(255, 255, 255, 0.08);
    color: #eef2f8;
}

body.dark-mode .admin-badge:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: #8b9cf7;
}

/* Logout Button */
.btn-logout {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
    font-family: 'Segoe UI', sans-serif;
    letter-spacing: 0.3px;
}

.btn-logout:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(239, 68, 68, 0.35);
    background: linear-gradient(135deg, #f87171 0%, #ef4444 100%);
}

.btn-logout:active {
    transform: scale(0.95);
}

.btn-logout i {
    font-size: 1.1rem;
    transition: transform 0.3s ease;
}

.btn-logout:hover i {
    transform: translateX(3px);
}

body.dark-mode .btn-logout {
    box-shadow: 0 4px 16px rgba(239, 68, 68, 0.3);
}

/* User Avatar Circle */
.user-avatar {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 16px;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
}

/* ===== RESPONSIVE STYLES ===== */

/* Tablets */
@media (max-width: 992px) {
    .header {
        padding: 14px 20px;
        min-height: 70px;
    }

    .header h2 {
        font-size: 1.3rem;
    }

    .header h2 i {
        font-size: 1.5rem;
    }

    .admin-badge {
        font-size: 0.85rem;
        padding: 6px 16px;
    }

    .btn-logout {
        padding: 8px 16px;
        font-size: 0.85rem;
    }
}

/* Mobile */
@media (max-width: 768px) {
    .header {
        padding: 12px 16px;
        min-height: 64px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .header h2 {
        font-size: 1.1rem;
        gap: 8px;
    }

    .header h2 i {
        font-size: 1.3rem;
    }

    .header .d-flex {
        gap: 10px;
    }

    .dark-toggle {
        padding: 6px 12px;
        font-size: 0.85rem;
    }

    .dark-toggle .toggle-label {
        display: none; /* Hide text on mobile, show only icon */
    }

    .dark-toggle i {
        font-size: 1.1rem;
    }

    .admin-badge {
        font-size: 0.8rem;
        padding: 5px 12px;
        gap: 6px;
    }

    .admin-badge i {
        font-size: 1.2rem;
    }

    .btn-logout {
        padding: 6px 14px;
        font-size: 0.8rem;
        gap: 5px;
    }

    .btn-logout span {
        display: none; /* Hide "Logout" text on very small screens */
    }

    .btn-logout i {
        font-size: 1rem;
        margin: 0;
    }

    .user-avatar {
        width: 32px;
        height: 32px;
        font-size: 13px;
    }
}

/* Small phones */
@media (max-width: 480px) {
    .header {
        padding: 10px 12px;
        min-height: 56px;
        flex-direction: column;
        align-items: stretch;
        gap: 8px;
    }

    .header h2 {
        font-size: 1rem;
        justify-content: center;
    }

    .header h2 i {
        font-size: 1.1rem;
    }

    .header .d-flex {
        justify-content: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .dark-toggle {
        padding: 5px 10px;
        font-size: 0.8rem;
    }

    .admin-badge {
        font-size: 0.75rem;
        padding: 4px 10px;
        gap: 4px;
    }

    .admin-badge i {
        font-size: 1rem;
    }

    .btn-logout {
        padding: 5px 10px;
        font-size: 0.75rem;
    }
}

/* Extra small phones */
@media (max-width: 380px) {
    .header .d-flex {
        gap: 5px;
    }

    .dark-toggle {
        padding: 4px 8px;
        font-size: 0.7rem;
        border-width: 1.5px;
    }

    .admin-badge {
        font-size: 0.7rem;
        padding: 3px 8px;
        border-width: 1.5px;
    }

    .btn-logout {
        padding: 4px 8px;
        font-size: 0.7rem;
    }
}

/* ===== ANIMATIONS ===== */

/* Header entrance animation */
.header {
    animation: slideDown 0.5s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Notification dot for dark mode toggle */
.dark-toggle .notification-dot {
    display: none;
    width: 6px;
    height: 6px;
    background: #667eea;
    border-radius: 50%;
    position: absolute;
    top: 4px;
    right: 4px;
    animation: pulse-dot 2s infinite;
}

@keyframes pulse-dot {
    0%, 100% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.5;
        transform: scale(0.8);
    }
}

/* Hover tooltip for mobile */
@media (max-width: 768px) {
    .btn-logout {
        position: relative;
    }

    .btn-logout::after {
        content: 'Logout';
        position: absolute;
        bottom: -25px;
        left: 50%;
        transform: translateX(-50%);
        background: #1f2937;
        color: white;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 10px;
        white-space: nowrap;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s;
    }

    .btn-logout:hover::after {
        opacity: 1;
    }

    body.dark-mode .btn-logout::after {
        background: #eef2f8;
        color: #1f2937;
    }
}
</style>