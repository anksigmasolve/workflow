<?php

namespace App\Models;

use App\Models\City;
use App\Models\State;
use App\Models\{Country, UserDetail};
use App\Helpers\CommonHelper;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

//add the namespace

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasRoles, SoftDeletes;
    protected $guard_name = 'web'; // added
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'login_type', 'login_type_token',
        'last_name', 'email', 'gender', 'role_type',
        'phone_number', 'race_banner', 'race_logo',
        'mobile_number', 'dial_code_for_phone_number', 'dial_code_for_mobile_number',
        'fcm_token',
        'app_version',
        'device_type',
        'password',
        'reset_password_token',
        'profile_photo',
        'date_of_birth',
        'status',
        'is_emailverified',
        'email_verified_at',
        'otp',
        'otp_generated_at',
        'is_otpverified',
        'otp_verified_at',
        'volunteer_address_line_1',
        'volunteer_address_line_2',
        'volunteer_type',
        'address',
        'apartment',
        'website_url',
        'zipcode',
        'state_id',
        'city_id',
        'country_id',
        'role_id',
        'creator_id',
        'updator_id',
        'organization_verified_at',
        'is_organization_verified',
        'organization_verified_name',
        'basic_verification',
        'basic_verified_at',
        'criminal_verification',
        'criminal_verified_at',
        'licence_verification',
        'licence_verified_at',
        'is_organization_verified',
        'organization_verified_at',
        'authenticate_access_code',
        'organization_verified_name',
        'criminal_verification_payment',
        'basic_verification_payment',
        'licence_verification_payment',
        'organization_type',
        'ssn_licence_criminal_verified_at',
        'ssn_licence_criminal_verification',
        'ssn_criminal_verified_at',
        'ssn_criminal_verification',
        'basic_verified_at',
        'basic_verification',
        'ssn_licence_verified_at',
        'ssn_licence_verification'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roleName()
    {
        $roleQuery = Role::select('name')->where('status', 0)->where('id', $this->role_id)->first();
        return $roleQuery['name'] ?? '';
    }

    public function usersName($id)
    {
        $username_query = self::select('name')->where('status', 0)->where('id', $id)->first();
        return $username_query['name'] ?? '';
    }

    public function Role()
    {
        return $this->hasOne('Spatie\Permission\Models\Role', 'id', 'role_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function educational()
    {
        return $this->hasMany(UserEducationalLevel::class, 'user_id', 'id');
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'creator_id', 'id');
    }

    public function keySkill()
    {
        return $this->hasMany(UserKeySkill::class, 'user_id', 'id');
    }


    public function state()
    {
        return $this->belongsTo(State::class, 'state_id', 'id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function userDetail()
    {
        return $this->belongsTo(UserDetail::class, 'id', 'user_id');
    }

    public function interest()
    {
        return $this->hasMany(UserInterest::class, 'user_id', 'id');
    }

    public function myProfileLink()
    {
        return route('vendor.profile', [
            'p1' => CommonHelper::slugify(isset($this->state_id) ? $this->state->name . ', ' . $this->zipcode : "" . ', ' . $this->zipcode),
            'p2' => CommonHelper::slugify($this?->role?->name ?? 'NA'),
            'p3' => CommonHelper::slugify($this->name ?? 'NA'),
            'id' => Crypt::encryptString('MansiR' . $this->id),
        ]);
    }
}
