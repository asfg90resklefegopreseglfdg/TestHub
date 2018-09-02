<?php

namespace App\Repositories\Eloquent;

use App\User;

class UserRepository
{

    /**
     * @param string $slug
     * @param User $user
     * @return User
     */
    public function getUserForPublishIfRegistered(string $slug, User $user): User
    {
        User::where('id', '=', $user->id)
            ->with(['tests' => function ($query) use ($slug) {
                $query->select(['id', 'user_id', 'slug'])->whereSlug($slug);
            }])->firstOrFail(['id', 'email']);
    }

    /**
     * @param string $uniqueId
     * @return bool
     */
    public function existsUniqueId(string $uniqueId): bool
    {
        return User::where('unique_id', $uniqueId)->count();
    }
}