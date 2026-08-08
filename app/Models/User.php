<?php 

namespace App\Models;


use Illuminate\Support\Facades\DB;
use Database\Factories\UserFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


#[Fillable(['idno', 'username', 'password', 'role', 'is_active'])]
#[Hidden(['password', 'remember_token'])]


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }


    public function getEmployeeProfileAttribute()
    {
        if (!$this->idno) {
            return null;
        }

        return DB::connection('hris')
        ->table('employee_profile')
        ->where('idno', $this->idno)
        ->select(
            'idno',
            'lastname',
            'firstname',
            'middlename'
        )
        ->first();
    }
}