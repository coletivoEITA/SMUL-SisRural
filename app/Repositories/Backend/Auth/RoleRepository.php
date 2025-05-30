<?php

namespace App\Repositories\Backend\Auth;

use App\Events\Backend\Auth\Role\RoleCreated;
use App\Events\Backend\Auth\Role\RoleUpdated;
use App\Exceptions\GeneralException;
use App\Models\Auth\Role;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class RoleRepository.
 */
class RoleRepository extends BaseRepository
{
    /**
     * RoleRepository constructor.
     *
     * @param Role $model
     */
    public function __construct(Role $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $data
     *
     * @return Role
     * @throws \Throwable
     * @throws GeneralException
     */
    public function create(array $data): Role
    {
        // Make sure it doesn't already exist
        if ($this->roleExists($data['name'])) {
            throw new GeneralException('A role already exists with the name ' . e($data['name']));
        }

        if (!isset($data['permissions']) || !\count($data['permissions'])) {
            $data['permissions'] = [];
        }

        //See if the role must contain a permission as per config
        if (config('access.roles.role_must_contain_permission') && \count($data['permissions']) === 0) {
            throw new GeneralException(__('exceptions.backend.access.roles.needs_permission'));
        }

        return DB::transaction(function () use ($data) {
            $role = $this->model::create(['name' => strtolower($data['name'])]);

            if ($role) {
                $role->givePermissionTo($data['permissions']);

                event(new RoleCreated($role));

                return $role;
            }

            throw new GeneralException(trans('exceptions.backend.access.roles.create_error'));
        });
    }

    /**
     * @param Role $role
     * @param array $data
     *
     * @return mixed
     * @throws \Throwable
     * @throws GeneralException
     */
    public function update(Role $role, array $data)
    {
        if ($role->isAdmin()) {
            throw new GeneralException('You can not edit the administrator role.');
        }

        // If the name is changing make sure it doesn't already exist
        if ($role->name !== strtolower($data['name'])) {
            if ($this->roleExists($data['name'])) {
                throw new GeneralException('A role already exists with the name ' . $data['name']);
            }
        }

        if (!isset($data['permissions']) || !\count($data['permissions'])) {
            $data['permissions'] = [];
        }

        //See if the role must contain a permission as per config
        if (config('access.roles.role_must_contain_permission') && \count($data['permissions']) === 0) {
            throw new GeneralException(__('exceptions.backend.access.roles.needs_permission'));
        }

        return DB::transaction(function () use ($role, $data) {
            if ($role->update([
                'name' => strtolower($data['name']),
            ])) {
                $role->syncPermissions($data['permissions']);

                event(new RoleUpdated($role));

                return $role;
            }

            throw new GeneralException(trans('exceptions.backend.access.roles.update_error'));
        });
    }

    /**
     * @param $name
     *
     * @return bool
     */
    protected function roleExists($name): bool
    {
        return $this->model
            ->where('name', strtolower($name))
            ->count() > 0;
    }

    /**
     * @deprecated Bem provável que esse método não é mais utilizado, rever futuramente.
     */
    public function visible()
    {
        $whereRoles = [];
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            //Admin
            $whereRoles = ['admin', 'dominio'];
        } else if ($user->hasRole('dominio')) {
            //Domínio
            $whereRoles = ['dominio'];
        } else if ($user->hasRole('unid_operacional')) {
            //Unidade Operacional
            $whereRoles = ['unid_operacional'];
        } else if ($user->hasRole('tecnico')) {
            //Tecnico
            $whereRoles = ['tecnico'];
        }

        return $this->model->whereIn('name', $whereRoles);
    }
}
