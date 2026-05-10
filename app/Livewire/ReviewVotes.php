<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Review;
use App\Models\Vote;
use Livewire\Component;

class ReviewVotes extends Component
{
    public Review $review;

    public function mount(Review $review): void
    {
        $this->review = $review;
    }

    public function vote(string $type): void
    {
        if (!auth()->check()) {
            $this->dispatch('notify', ['message' => 'Please login to vote', 'type' => 'error']);
            return;
        }

        $userId = auth()->id();
        $existingVote = Vote::where('user_id', $userId)
            ->where('voteable_id', $this->review->id)
            ->where('voteable_type', Review::class)
            ->first();

        if ($existingVote) {
            if ($existingVote->type === $type) {
                $existingVote->delete();
            } else {
                $existingVote->update(['type' => $type]);
            }
        } else {
            Vote::create([
                'user_id' => $userId,
                'voteable_id' => $this->review->id,
                'voteable_type' => Review::class,
                'type' => $type,
            ]);
        }

        $this->review->update([
            'likes_count' => $this->review->votes()->where('type', 'like')->count(),
            'dislikes_count' => $this->review->votes()->where('type', 'dislike')->count(),
        ]);

        $this->review->refresh();
    }

    public function render()
    {
        return view('livewire.review-votes');
    }
}
