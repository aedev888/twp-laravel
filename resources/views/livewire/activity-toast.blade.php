<div 
    x-data="{ show: @entangle('visible') }"
    x-init="
        setInterval(() => {
            if (!show) {
                $wire.showNextActivity();
                setTimeout(() => { $wire.hideActivity(); }, 6000);
            }
        }, 25000);
    "
    x-show="show"
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0 translate-y-10 scale-90"
    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
    x-transition:leave="transition ease-in duration-500"
    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
    x-transition:leave-end="opacity-0 translate-y-10 scale-90"
    style="position: fixed; bottom: 2rem; left: 2rem; z-index: 9999; display: none;"
    :style="{ display: show ? 'block' : 'none' }"
>
    @if($currentActivity)
    <div style="background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(20px); border: 1px solid var(--glass-border); padding: 1.25rem; border-radius: 1.5rem; display: flex; align-items: center; gap: 1.25rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); min-width: 320px;">
        <div style="width: 48px; height: 48px; background: var(--primary-gradient); border-radius: 1rem; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.25rem;">
            <i class="fa-solid fa-bolt"></i>
        </div>
        <div style="flex: 1;">
            <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.25rem;">
                <b style="color: white;">@ {{ $currentActivity['user'] }}</b> {{ $currentActivity['action'] }}
            </div>
            <div style="font-weight: 700; color: var(--primary); font-size: 0.95rem; line-height: 1.3;">
                {{ $currentActivity['item'] }}
            </div>
            <div style="font-size: 0.7rem; color: var(--text-muted); margin-top: 0.4rem; display: flex; align-items: center; gap: 0.4rem;">
                <i class="fa-solid fa-clock"></i> {{ $currentActivity['time'] }}
            </div>
        </div>
        <button @click="show = false" style="background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 0.5rem;">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
    @endif
</div>
