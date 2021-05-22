<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:menu-admin', ['only' => ['show', 'store', 'update', 'delete']]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show()
    {
        $users = User::all();

        $roles = Role::all();

        return view('utente.utente', [ 'users'=>$users, 'roles'=>$roles ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm_password',
            'roles' => 'required'
        ]);

        try {
            $name = $request['name'];
            $email = $request['email'];
            $password = $request['password'];
            $roles = $request['roles'];

            $password = Hash::make($password);

            $user = new User();
            $user->name = $name;
            $user->email = $email;
            $user->password = $password;
            $user->save();

            $user->assignRole($roles);

            request()->session()->flash('status', 'Utente inserito correttamente');
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
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'same:confirm_password',
            'roles' => 'required'
        ]);

        try {
            $input = $request->all();
            if (!empty($input['password'])) {
                $input['password'] = Hash::make($input['password']);
            } else {
                $password = Arr::except($input, array('password'));
            }

            $user = User::findOrFail($input['id']);
            $user->name = ($input['name']);
            $user->email = ($input['email']);
            if(!empty($input['password'])) {
                $user->password = ($input['password']);
            }
            $user->update();

            DB::table('model_has_roles')->where('model_id', $input['id'])->delete();

            $user->assignRole(request()->input('roles'));

            request()->session()->flash('status', 'Utente modificato correttamente');
        } catch (\Exception $e) {
            return new JsonResponse(['errors' => $e->errorInfo[2]]);
        }

        return new JsonResponse(['success' => '1']);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function getUserById($id) {
        /** @var User $user */
        $user = User::find($id);

        $roles = $user->roles()->allRelatedIds();

        return new JsonResponse(['user' => $user, 'editRoles' => $roles]);
    }
}
