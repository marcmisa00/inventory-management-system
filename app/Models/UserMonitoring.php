<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserMonitoring extends Model
{
    use HasFactory;

    protected $table = 'user_monitoring';

    protected $fillable = [
        'pc_name',
        'serial_number',
        'idno',
        'department',
        'job_title',
        'location',
        'set_up',
        'address',
        'company',
        'motherboard',
        'processor',
        'hdd',
        'ssd',
        'ram',
        'psu',
        'cpuf',
        'monitor',
        'keyboard',
        'mouse',
        'avr',
        'binaural',
        'monaural',
        'magic_jack',
        'headset',
        'camera',
        'dialpad',
        'ups',
        'telephone_set',
        'ip_address',
        'vpn',
        'operating_system',
        'product_key',
        'microsoft_office',
        'office_serial_key',
        'microsoft_account',
        'delivery_date',
        'pc_cost',
        'store',
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'pc_cost' => 'decimal:2',
    ];

    // Get user details from HRIS
    public function getUserAttribute()
    {
        if ($this->idno) {
            return DB::connection('hris')
                ->table('employee_profile')
                ->where('idno', $this->idno)
                ->select('lastname', 'firstname', 'middlename')
                ->first();
        }
        return null;
    }

    public function getUserLastnameAttribute()
    {
        $user = $this->getUserAttribute();
        return $user ? $user->lastname : null;
    }

    public function getUserFirstnameAttribute()
    {
        $user = $this->getUserAttribute();
        return $user ? $user->firstname : null;
    }

    public function getUserMiddlenameAttribute()
    {
        $user = $this->getUserAttribute();
        return $user ? $user->middlename : null;
    }

    public function getFullNameAttribute()
    {
        $user = $this->getUserAttribute();
        if ($user) {
            return $user->lastname . ', ' . $user->firstname . 
                   ($user->middlename ? ' ' . substr($user->middlename, 0, 1) . '.' : '');
        }
        return 'Unassigned';
    }
}