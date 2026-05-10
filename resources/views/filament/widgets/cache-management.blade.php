<x-filament-widgets::widget>
    @php
        $health = $this->getSystemHealth();
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- System Health Section -->
        <x-filament::section icon="heroicon-o-heart" icon-color="danger">
            <x-slot name="heading">Состояние системы</x-slot>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Версия PHP</p>
                    <p class="text-lg font-semibold">{{ $health['php_version'] }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Laravel</p>
                    <p class="text-lg font-semibold">v{{ $health['laravel_version'] }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Место на диске</p>
                    <div class="flex items-center gap-2">
                        <div class="flex-1 h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-full bg-{{ $health['disk_usage'] > 80 ? 'danger' : 'success' }}-500" style="width: {{ $health['disk_usage'] }}%"></div>
                        </div>
                        <span class="text-sm font-medium">{{ $health['disk_usage'] }}%</span>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">{{ $health['disk_free'] }} доступно</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Режим отладки</p>
                    <x-filament::badge color="{{ $health['debug_mode'] === 'Включен' ? 'warning' : 'success' }}">
                        {{ $health['debug_mode'] }}
                    </x-filament::badge>
                </div>
            </div>
        </x-filament::section>

        <!-- Cache Management Section -->
        <x-filament::section icon="heroicon-o-cpu-chip" icon-color="primary">
            <x-slot name="heading">Управление кешем</x-slot>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Принудительное обновление данных и представлений приложения.</p>

            <div class="grid grid-cols-2 gap-3">
                <x-filament::button 
                    color="gray" 
                    icon="heroicon-m-eye"
                    wire:click="clearCache('view')"
                    size="sm"
                >
                    Представления
                </x-filament::button>

                <x-filament::button 
                    color="gray" 
                    icon="heroicon-m-cog-6-tooth"
                    wire:click="clearCache('config')"
                    size="sm"
                >
                    Конфиг
                </x-filament::button>

                <x-filament::button 
                    color="gray" 
                    icon="heroicon-m-server"
                    wire:click="clearCache('cache')"
                    size="sm"
                >
                    Кеш приложения
                </x-filament::button>

                <x-filament::button 
                    color="warning" 
                    icon="heroicon-m-trash"
                    wire:click="clearCache('all')"
                    size="sm"
                    class="col-span-2"
                >
                    Очистить всё
                </x-filament::button>
            </div>
        </x-filament::section>
    </div>
</x-filament-widgets::widget>
