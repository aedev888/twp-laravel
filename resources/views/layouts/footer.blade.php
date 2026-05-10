<footer class="main-footer">
    <div class="footer-grid">
        <div class="footer-col">
            <div class="logo" style="margin-bottom: 1.5rem;">LUMINA<span>CMS</span></div>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 2rem;">
                The ultimate marketplace for high-performance WordPress themes and strictly-typed plugins. Elevate your development workflow.
            </p>
            <div style="display: flex; gap: 1.5rem; font-size: 1.25rem;">
                <a href="#" style="color: var(--text-muted); transition: 0.3s;" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='inherit'"><i class="fa-brands fa-telegram"></i></a>
                <a href="#" style="color: var(--text-muted); transition: 0.3s;" onmouseover="this.style.color='#1da1f2'" onmouseout="this.style.color='inherit'"><i class="fa-brands fa-x-twitter"></i></a>
                <a href="#" style="color: var(--text-muted); transition: 0.3s;" onmouseover="this.style.color='#333'" onmouseout="this.style.color='inherit'"><i class="fa-brands fa-github"></i></a>
            </div>
        </div>
        
        <div class="footer-col">
            <h4>Marketplace</h4>
            <ul>
                <li><a href="{{ url('/category/themes') }}" wire:navigate>Premium Themes</a></li>
                <li><a href="{{ url('/category/plugins') }}" wire:navigate>Typed Plugins</a></li>
                <li><a href="{{ url('/category/elements') }}" wire:navigate>UI Elements</a></li>
                <li><a href="{{ url('/') }}" wire:navigate>New Releases</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4>Company</h4>
            <ul>
                <li><a href="#" wire:navigate>About Us</a></li>
                <li><a href="#" wire:navigate>Terms of Service</a></li>
                <li><a href="#" wire:navigate>Privacy Policy</a></li>
                <li><a href="#" wire:navigate>Contact Support</a></li>
            </ul>
        </div>

        <div class="footer-col" x-data="{ subscribed: false }">
            <div class="newsletter-box">
                <h4>Stay Updated</h4>
                <p x-show="!subscribed" style="font-size: 0.85rem; color: var(--text-muted);">Get notified about new assets and major updates directly in your inbox.</p>
                <div x-show="subscribed" x-cloak style="color: #10b981; font-weight: 700; padding: 1rem 0;">
                    <i class="fa-solid fa-circle-check"></i> Thank you! You've been subscribed.
                </div>
                <form class="newsletter-form" x-show="!subscribed" @submit.prevent="subscribed = true" style="flex-wrap: wrap; gap: 0.75rem;">
                    <input type="email" class="newsletter-input" placeholder="Your email address" required style="min-width: 200px; flex: 1;">
                    <button type="submit" class="btn btn-primary newsletter-submit-btn" style="min-width: 45px;">
                        <i class="fa-solid fa-paper-plane"></i> <span class="mobile-only" style="margin-left: 0.5rem;">Subscribe</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div style="text-align: center; padding-top: 3rem; border-top: 1px solid var(--glass-border); color: var(--text-muted); font-size: 0.8rem;">
        &copy; {{ date('Y') }} Lumina Marketplace. Built with Laravel 13 & Filament.
    </div>
</footer>
