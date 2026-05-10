<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Product;
use App\Models\Vote;
use Livewire\Component;

class ProductVotes extends Component
{
    public Product $product;

    public function mount(Product $product): void
    {
        $this->product = $product;
    }

    public function vote(string $type): void
    {
        if (!auth()->check()) {
            $this->dispatch('notify', ['message' => 'Please login to vote', 'type' => 'error']);
            return;
        }

        $userId = auth()->id();
        $existingVote = Vote::where('user_id', $userId)
            ->where('voteable_id', $this->product->id)
            ->where('voteable_type', Product::class)
            ->first();

        if ($existingVote) {
            if ($existingVote->type === $type) {
                // Remove vote
                $existingVote->delete();
            } else {
                // Change vote
                $existingVote->update(['type' => $type]);
            }
        } else {
            // New vote
            Vote::create([
                'user_id' => $userId,
                'voteable_id' => $this->product->id,
                'voteable_type' => Product::class,
                'type' => $type,
            ]);
        }

        // Update counts
        $this->product->update([
            'likes_count' => $this->product->votes()->where('type', 'like')->count(),
            'dislikes_count' => $this->product->votes()->where('type', 'dislike')->count(),
        ]);

        $this->product->refresh();
    }

    public function render()
    {
        return view('livewire.product-votes');
    }
}
