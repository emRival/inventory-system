<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ItemUnit;
use Illuminate\Auth\Access\HandlesAuthorization;

class ItemUnitPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_returned::item');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ItemUnit $itemUnit): bool
    {
        return $user->can('view_returned::item');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_returned::item');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ItemUnit $itemUnit): bool
    {
        return $user->can('update_returned::item');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ItemUnit $itemUnit): bool
    {
        return $user->can('delete_returned::item');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_returned::item');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, ItemUnit $itemUnit): bool
    {
        return $user->can('force_delete_returned::item');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_returned::item');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, ItemUnit $itemUnit): bool
    {
        return $user->can('restore_returned::item');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_returned::item');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, ItemUnit $itemUnit): bool
    {
        return $user->can('replicate_returned::item');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_returned::item');
    }
}
