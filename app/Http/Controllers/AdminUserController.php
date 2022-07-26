<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Auth;
use Illuminate\Http\Response;
use App\AdminUserFactory;
use App\Http\Requests\AdminUser\CreateOrUpdateRequest;
use App\Resources\AdminUser as AdminUserResource;
use App\Resources\AdminUserCollection;
use App\Resources\RoleCollection;

class AdminUserController extends Controller
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $adminUserModel;

    public function __construct()
    {
        $this->adminUserModel = AdminUserFactory::adminUser();
    }

    /**
     * @author dq
     * @param Request $request
     * @return AdminUserCollection
     */
    public function index(Request $request)
    {
        return new AdminUserCollection($this->adminUserModel->where(request_intersect(['name', 'username']))->paginate());
    }

    /**
     * @author dq
     * @param $id
     * @return AdminUserResource
     */
    public function show($id)
    {
        return new AdminUserResource($this->adminUserModel->findOrFail($id));
    }

    /**
     * @author dq
     * @param CreateOrUpdateRequest $request
     * @return Response
     */
    public function store(CreateOrUpdateRequest $request)
    {
        $data = request_intersect([
            'name', 'username', 'password'
        ]);
        $data['status'] = $request->status ? true : false;
        $data['password'] = bcrypt($data['password']);

        $this->adminUserModel->create($data);

        return $this->created();
    }

    /**
     * @author dq
     * @param CreateOrUpdateRequest $request
     * @param $id
     * @return Response
     */
    public function update(CreateOrUpdateRequest $request, $id)
    {
        $adminUser = $this->adminUserModel->findOrFail($id);

        $data = $request->only([
            'name', 'status'
        ]);

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $adminUser->fill($data);
        $adminUser->save();

        return $this->noContent();
    }

    /**
     * @author dq
     * @param $id
     * @return Response
     */
    public function destroy($id)
    {
        $adminUser = $this->adminUserModel->findOrFail($id);

        $adminUser->delete();

        return $this->noContent();
    }

    /**
     * @author moell<moell91@foxmail.com>
     * @param $id
     * @param $provider
     * @return RoleCollection
     */
    public function roles($id, $provider)
    {
        $user = $this->getGuardModel($provider)->findOrFail($id);

        return new RoleCollection($user->roles);
    }

    /**
     * @param $id
     * @param $guard
     * @param Request $request
     * @return Response
     *@author moell<moell91@foxmail.com>
     */
    public function assignRoles($id, $guard, Request $request)
    {
        $user = $this->getGuardModel($guard)->findOrFail($id);

        $user->syncRoles($request->input('roles', []));

        return $this->noContent();
    }

    /**
     * @param $guard
     * @return Illuminate\Foundation\Auth\User
     */
    private function getGuardModel($guard)
    {
        return app(config('snack.guards.' . $guard . '.model'));
    }
}
