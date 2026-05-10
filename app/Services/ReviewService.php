<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class ReviewService
{
    /**
     * Create or update a review for a product.
     */
    public function storeReview(User $user, Product $product, array $data): Review
    {
        $review = Review::updateOrCreate(
            [
                'user_id'    => $user->id,
                'product_id' => $product->id,
            ],
            [
                'content'      => $data['content'],
                'rating'       => $data['rating'],
                'is_published' => $data['is_published'] ?? true, // Default to true, or handle moderation logic
            ]
        );

        $this->clearProductReviewCache($product);

        return $review;
    }

    /**
     * Delete a review.
     */
    public function deleteReview(Review $review): bool
    {
        $product = $review->product;
        $deleted = $review->delete();

        if ($deleted && $product) {
            $this->clearProductReviewCache($product);
        }

        return $deleted;
    }

    /**
     * Toggle the publication status of a review.
     */
    public function togglePublication(Review $review): Review
    {
        $review->update([
            'is_published' => !$review->is_published,
        ]);

        $this->clearProductReviewCache($review->product);

        return $review;
    }

    /**
     * Clear relevant caches when reviews change.
     */
    protected function clearProductReviewCache(Product $product): void
    {
        // Placeholder for future cache clearing logic (e.g. if we cache average rating)
        Cache::forget("product.{$product->id}.rating");
    }
}
