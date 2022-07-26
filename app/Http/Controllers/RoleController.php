<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Http\Requests\Role\CreateOrUpdateRequest;
use App\Resources\PermissionCollection;
use App\Resources\RoleCollection;
use App\Resources\Role as RoleResource;
use Spatie\Permission\Exceptions\RoleAlreadyExists;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * @author moell<moell91@foxmail.com>
     * @param Request $request
     * @return RoleCollection
     */
    public function index(Request $request)
    {
        return new RoleCollection(Role::query()->where(request_intersect(['name']))->paginate());
    }

    /**
     * @author moell<moell91@foxmail.com>
     * @param $guardName
     * @return RoleCollection
     */
    public function guardNameRoles($guardName)
    {
        return new RoleCollection(Role::query()->where('guard_name', $guardName)->get());
    }

    public function show($id)
    {
        return new RoleResource(Role::query()->findOrFail($id));
    }

    /**
     * @author moell<moell91@foxmail.com>
     * @param CreateOrUpdateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateOrUpdateRequest $request)
    {
        Role::create(request_intersect([
            'name', 'guard_name', 'description'
        ]));

        return $this->created();
    }

    /**
     * @author moell<moell91@foxmail.com>
     * @param CreateOrUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateOrUpdateRequest $request, $id)
    {
        if (Role::where(request_intersect(['name', 'guard_name']))->where('id', '!=', $id)->count()) {
            throw RoleAlreadyExists::create($request->name, $request->guard_name);
        }

        $role = Role::query()->findOrFail($id);

        $role->update(request_intersect([
            'name', 'guard_name', 'description'
        ]));

        return $this->noContent();
    }

    /**
     * @author moell<moell91@foxmail.com>
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Role::destroy($id);

        return $this->noContent();
    }

    /**
     * @author moell<moell91@foxmail.com>
     * @param $id
     * @return PermissionCollection
     */
    public function permissions($id)
    {
        $role = Role::query()->findOrFail($id);

        return new PermissionCollection($role->permissions);
    }

    /**
     * Assign permission
     *
     * @author moell<moell91@foxmail.com>
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function assignPermissions($id, Request $request)
    {
        $role = Role::query()->findOrFail($id);

        $role->syncPermissions($request->input('permissions', []));

        return $this->noContent();
    }
}