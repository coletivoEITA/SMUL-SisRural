<?php

namespace App\Repositories\Backend\Auth;

use App\Events\Backend\Auth\User\UserSocialDeleted;
use App\Exceptions\GeneralException;
use App\Models\Auth\SocialAccount;
use App\Models\Auth\User;

/**
 * Class SocialRepository.
 */
class SocialRepository
{
    /**
     * @param User $user
     * @param SocialAccount $social
     *
     * @return bool
     * @throws GeneralException
     */
    public function delete(User $user, SocialAccount $social)
    {
        if ($user->providers()->whereId($social->id)->delete()) {
            event(new UserSocialDeleted($user, $social));

            return true;
        }

        throw new GeneralException(__('exceptions.backend.access.users.social_delete_error'));
    }
}
