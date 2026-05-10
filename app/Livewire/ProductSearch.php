<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Product;
use App\Models\SearchLog;
use Livewire\Component;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class ProductSearch extends Component
{
    public string $query = '';
    public bool $showDropdown = false;
    protected ?string $lastLoggedQuery = null;

    public function updatedQuery(): void
    {
        $this->showDropdown = strlen($this->query) >= 2;
    }

    public function resetSearch(): void
    {
        $this->query = '';
        $this->showDropdown = false;
    }

    public function render()
    {
        $results = new Collection();

        if (strlen($this->query) >= 2) {
            $results = Product::where('status', 'published')
                ->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->query . '%')
                      ->orWhere('description', 'like', '%' . $this->query . '%');
                })
                ->with('taxonomies')
                ->limit(6)
                ->get();
            
            // Log search if query is long enough and different from last logged
            if (strlen($this->query) >= 3) {
                $this->logSearch($results->count());
            }
        }

        return view('livewire.product-search', [
            'results' => $results,
        ]);
    }

    protected function logSearch(int $count): void
    {
        // Simple throttling: don't log the exact same query in the same session twice in a row
        if (session('last_search_query') === $this->query) {
            return;
        }

        SearchLog::create([
            'query' => $this->query,
            'user_id' => Auth::id(),
            'results_count' => $count,
            'ip_address' => request()->ip(),
        ]);

        session(['last_search_query' => $this->query]);
    }
}
