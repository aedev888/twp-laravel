<nav class="glass-nav" id="mainNav" x-data="{ scrolled: false, mobileMenu: false }" @scroll.window="scrolled = (window.pageYOffset > 20)" :class="{ 'scrolled': scrolled }">
    <div style="display: flex; align-items: center; gap: 2rem; flex: 1; justify-content: space-between;">
        <a href="{{ url('/') }}" wire:navigate class="logo">LUMINA<span>CMS</span></a>
        
        <div class="search-container desktop-only" style="margin: 0; max-width: 600px;">
            @livewire('product-search')
        </div>

        <div class="mobile-nav-toggle" @click="mobileMenu = true">
            <i class="fa-solid fa-bars"></i>
        </div>
    </div>

    <div class="nav-links desktop-only">
        @auth
            @if(auth()->user()->role === \App\Enums\UserRole::Admin)
                <a href="/admin" class="btn" style="background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); padding: 0.4rem 1rem; font-size: 0.8rem;">
                    <i class="fa-solid fa-screwdriver-wrench"></i> Admin Panel
                </a>
            @endif

            <div x-data="{ open: false }" style="position: relative; margin-left: 1.5rem;">
                <button @click="open = !open" @click.away="open = false" style="background: none; border: none; display: flex; align-items: center; gap: 0.5rem; color: var(--text); cursor: pointer; font-weight: 600;">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=6366f1&color=fff" style="width: 32px; height: 32px; border-radius: 50%;">
                    <i class="fa-solid fa-chevron-down" :class="{ 'rotate-180': open }" style="font-size: 0.7rem; transition: transform 0.3s;"></i>
                </button>
                
                <div x-show="open" x-cloak 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     style="position: absolute; top: calc(100% + 1rem); right: 0; background: #0f172a; border: 1px solid var(--glass-border); border-radius: 1rem; width: 220px; box-shadow: 0 20px 40px rgba(0,0,0,0.4); padding: 0.5rem; z-index: 1100;">
                    <div style="padding: 0.75rem 1rem; border-bottom: 1px solid var(--glass-border); margin-bottom: 0.5rem;">
                        <div style="font-weight: 800; font-size: 0.9rem;">{{ auth()->user()->name }}</div>
                        <div style="font-size: 0.7rem; color: var(--text-muted);">{{ auth()->user()->email }}</div>
                    </div>
                    <a href="{{ route('dashboard') }}" class="dropdown-item"><i class="fa-solid fa-gauge"></i> Dashboard</a>
                    <a href="{{ route('checkout') }}" class="dropdown-item"><i class="fa-solid fa-crown"></i> Go Premium</a>
                    <hr style="border: 0; border-top: 1px solid var(--glass-border); margin: 0.5rem 0;">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item" style="width: 100%; text-align: left; color: #f87171;"><i class="fa-solid fa-sign-out"></i> Logout</button>
                    </form>
                </div>
            </div>
        @else
            <a href="{{ route('login') }}" wire:navigate>Login</a>
            <a href="{{ route('register') }}" wire:navigate class="btn btn-primary" style="padding: 0.5rem 1.25rem; font-size: 0.875rem; margin-left: 1rem;">Register</a>
        @endauth
    </div>

    <!-- Mobile Navigation Drawer -->
    <div class="drawer-overlay" :class="{ 'active': mobileMenu }" @click="mobileMenu = false"></div>
    <div class="mobile-drawer" :class="{ 'active': mobileMenu }">
        <div class="mobile-drawer-header">
            <a href="{{ url('/') }}" class="logo" style="font-size: 1.5rem;">LUMINA<span>CMS</span></a>
            <button class="close-drawer" @click="mobileMenu = false"><i class="fa-solid fa-xmark"></i></button>
        </div>
        
        <div class="mobile-search">
            @livewire('product-search')
        </div>

        <div class="mobile-nav-group">
            <div class="sidebar-nav-title">Menu</div>
            @include('layouts.sidebar')
        </div>

        <div class="mobile-nav-group" style="margin-top: 2rem;">
            @auth
                <div class="sidebar-nav-title">Account</div>
                <a href="{{ route('dashboard') }}" class="sidebar-nav-item"><i class="fa-solid fa-gauge"></i> Dashboard</a>
                <form action="{{ route('logout') }}" method="POST" style="width: 100%;">
                    @csrf
                    <button type="submit" class="sidebar-nav-item" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer;">
                        <i class="fa-solid fa-sign-out"></i> Logout
                    </button>
                </form>
            @else
                <div class="sidebar-nav-title">Auth</div>
                <a href="{{ route('login') }}" class="sidebar-nav-item"><i class="fa-solid fa-sign-in"></i> Login</a>
                <a href="{{ route('register') }}" class="sidebar-nav-item"><i class="fa-solid fa-user-plus"></i> Register</a>
            @endauth
        </div>
    </div>
</nav>

<style>
[x-cloak] { display: none !important; }

.glass-nav {
    height: var(--header-height);
}

.search-container {
    flex: 1;
    max-width: 600px;
}
</style>
