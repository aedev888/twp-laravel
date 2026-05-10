<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Collection;

class ActivityToast extends Component
{
    public ?array $currentActivity = null;
    public bool $visible = false;

    protected $activities = [
        ['user' => 'Ivan_WP', 'action' => 'downloaded', 'item' => 'Advanced Elementor Pro', 'time' => '2 minutes ago'],
        ['user' => 'DesignMaster', 'action' => 'left a review ⭐⭐⭐⭐⭐ on', 'item' => 'Lumina Premium Theme', 'time' => '5 minutes ago'],
        ['user' => 'DevOps_Ru', 'action' => 'purchased a license', 'item' => 'Multi-Vendor Plugin', 'time' => '12 minutes ago'],
        ['user' => 'Alex_K', 'action' => 'downloaded an update for', 'item' => 'WooCommerce SEO Pack', 'time' => 'just now'],
        ['user' => 'Studio_24', 'action' => 'added to collection', 'item' => 'Modern Portfolio Kit', 'time' => '8 minutes ago'],
    ];

    public function mount(): void
    {
        // Start hidden
    }

    public function showNextActivity(): void
    {
        $this->currentActivity = $this->activities[array_rand($this->activities)];
        $this->visible = true;
    }

    public function hideActivity(): void
    {
        $this->visible = false;
    }

    public function render()
    {
        return view('livewire.activity-toast');
    }
}
