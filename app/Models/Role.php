<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Role extends \Spatie\Permission\Models\Role
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();

        static::deleting(function($role) {
            $userRole = DB::table('model_has_roles')
                ->where('role_id', $role->id)
                ->where('model_type', 'App\Models\User')
                ->first();

            if (!empty($userRole)) {
                throw new \Exception(sprintf('Impossibile eliminare il Ruolo %s, Ruolo assegnato ad un Utente attivo', $role->name));
            }
        });
    }


}
