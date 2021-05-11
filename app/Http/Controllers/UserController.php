<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show()
    {
        $users = User::all();

        return view('user.user', [ 'data'=>$users ]);
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
            'password' => 'required|same:confirm-password',
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
        request()->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);

        try {
            $input = request()->all();
            if (!empty($input['password'])) {
                $input['password'] = Hash::make($input['password']);
            } else {
                $password = Arr::except($input, array('password'));
            }

            $user = User::findOrFail($input['id']);
            $user->update($input);
            DB::table('model_has_roles')->where('model_id', $input['id'])->delete();

            $user->assignRole(request()->input('roles'));

            request()->session()->flash('status', 'Utente modificato correttamente');
        } catch (\Exception $e) {
            return new JsonResponse(['errors' => $e->errorInfo[2]]);
        }

        return new JsonResponse(['success' => '1']);
    }

    /**
     * @param $userId
     * @return JsonResponse
     */
    public function delete($userId) {
        try {
            $user = User::findOrFail($userId);

            $user->delete();

            request()->session()->flash('status', 'Tipo Macchina eliminato correttamente');
        } catch (QueryException $e) {
            return new JsonResponse(['errors' => $e->errorInfo[2]]);
        }

        return new JsonResponse(['success' => '1']);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getUserById($id) {
        $user = User::find($id);
        return response()->json($user);
    }
}
