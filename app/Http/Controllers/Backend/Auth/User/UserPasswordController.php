<?php

namespace App\Http\Controllers\Backend\Auth\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Auth\User\ManageUserRequest;
use App\Http\Requests\Backend\Auth\User\UpdateUserPasswordRequest;
use App\Models\Auth\User;
use App\Repositories\Backend\Auth\UserRepository;

/**
 * Class UserPasswordController.
 */
class UserPasswordController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param ManageUserRequest $request
     * @param User $user
     *
     * @return mixed
     */
    public function edit(ManageUserRequest $request, User $user)
    {
        return view('backend.auth.user.change-password')
            ->withUser($user);
    }

    /**
     * @param UpdateUserPasswordRequest $request
     * @param User $user
     *
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     */
    public function update(UpdateUserPasswordRequest $request, User $user)
    {
        $this->userRepository->updatePassword($user, $request->only('password'));

        return redirect()->route('admin.auth.user.index')->withFlashSuccess(__('alerts.backend.users.updated_password'));
    }
}
