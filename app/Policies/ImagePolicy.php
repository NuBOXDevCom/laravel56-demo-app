<?php

namespace App\Policies;

use App\Models\ {
    User, Image
};
use Illuminate\Auth\Access\HandlesAuthorization;

class ImagePolicy
{
    use HandlesAuthorization;

    /**
     * @param  User $user
     * @return bool
     */
    public function before(User $user): ?bool
    {
        if ($user->role === 'admin') {
            return true;
        }
    }

    /**
     * @param User $user
     * @param Image $image
     * @return mixed
     */
    public function delete(User $user, Image $image)
    {
        return $user->id === $image->user_id;
    }
}