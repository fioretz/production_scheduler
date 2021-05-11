<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function show()
    {
        $roles = Role::all();

        $permissions = Permission::all();

        return view('ruolo.ruolo', [ 'roles'=>$roles, 'permissions'=>$permissions ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);

        try {
            $name = $request['name'];
            $permission = $request['permission'];

            $role = new Role();
            $role->name = $name;
            $role->save();

            $role->syncPermissions($permission);

            request()->session()->flash('status', 'Ruolo inserito correttamente');
        } catch (\Exception $e) {
            return new JsonResponse(['errors' => $e->errorInfo[2]]);
        }

        return new JsonResponse(['success' => '1']);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request) {
        request()->validate([
            'name' => 'required',
        ]);

        try {
            $input = request()->all();
            $role = Role::findOrFail($input['id']);
            $role->update($input);

            $role->syncPermissions(request()->input('permission'));

            request()->session()->flash('status', 'Ruolo modificato correttamente');
        } catch (\Exception $e) {
            return new JsonResponse(['errors' => $e->errorInfo[2]]);
        }

        return new JsonResponse(['success' => '1']);
    }

    public function delete($roleId) {
        try {
            $role = Role::findOrFail($roleId);

            $role->delete();

            request()->session()->flash('status', 'Ruolo eliminato correttamente');
        } catch (QueryException $e) {
            return new JsonResponse(['errors' => $e->errorInfo[2]]);
        }

        return new JsonResponse(['success' => '1']);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function getRoleById($id) {
        $role = Role::find($id);

        $permissions = $role->getAllPermissions();

        return new JsonResponse(['role' => $role, 'editPermissions' => $permissions]);
    }

}
