<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfiloController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show()
    {
        $datiUtente = Auth::user();

        return view('profilo.profilo', [ 'data' => $datiUtente ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function updateNameMail(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email'
        ]);

        try {
            $input = $request->all();
            $user = User::findOrFail(Auth::id());
            $user->name = ($input['name']);
            $user->email = ($input['email']);
            $user->update();

            request()->session()->flash('status', 'Nome/E-mail modificati correttamente');
        } catch (\Exception $e) {
            return new JsonResponse(['errors' => $e->errorInfo[2]]);
        }

        return new JsonResponse(['success' => '1']);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function updatePassword(Request $request) {
        $request->validate([
            'password' => 'same:confirm_password',
        ]);

        try {
            $input = $request->all();
            if (!empty($input['password'])) {
                $input['password'] = Hash::make($input['password']);
            } else {
                $password = Arr::except($input, array('password'));
            }

            $user = User::findOrFail(Auth::id());
            if(!empty($input['password'])) {
                $user->password = ($input['password']);
            }
            $user->update();

            request()->session()->flash('status', 'Utente modificato correttamente');
        } catch (\Exception $e) {
            return new JsonResponse(['errors' => $e->errorInfo[2]]);
        }

        return new JsonResponse(['success' => '1']);
    }
}
