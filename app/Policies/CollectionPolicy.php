<?php

namespace App\Policies;

use App\Models\Collection;
use App\Models\User;

class CollectionPolicy
{
    /**
     * Determine whether any visitor can view collection listings.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether any visitor can view a collection.
     */
    public function view(?User $user, Collection $collection): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create collections via API.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin;
    }

    /**
     * Determine whether the user can update collections or attach media/video.
     */
    public function update(User $user, ?Collection $collection = null): bool
    {
        return $user->isAdmin;
    }

    /**
     * Determine whether the user can delete collections or images.
     */
    public function delete(User $user, Collection $collection): bool
    {
        return $user->isAdmin;
    }
}
