<?php

namespace App\Policies;

use App\Models\BoardRow;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BoardRowPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, BoardRow $boardRow): bool
    {
        return $boardRow->board->user_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Board $board): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BoardRow $boardRow): bool
    {
        return $boardRow->board->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BoardRow $boardRow): bool
    {
        return $boardRow->board->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, BoardRow $boardRow): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, BoardRow $boardRow): bool
    {
        //
    }
}
