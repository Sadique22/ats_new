<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Tymon\JWTAuth\Contracts\JWTSubject;
use DB;

class User extends Authenticatable implements JWTSubject,MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email','contact','user_type', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier() {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() {
        return [];
    }
    
    public function getTotalStudents()
    {
     return DB::table('users')->where('user_type',3)->count();
    }

    public function getTotalTeachers()
    {
     return DB::table('users')->where('user_type',2)->count();
    }

    public function getTotalParents()
    {
     return DB::table('users')->where('user_type',4)->count();
    }

    public function getAllRegUsers()
    {
     return DB::table('users')->whereIn('user_type',[2, 3, 4])->count();
    }

    public function getTotalClasses()
    {
     return DB::table('class_details')->count();
    }

    protected function checkToken($token)
    {
    return User::where('remember_token', $token)->value('id');
    }

    protected function getUserType($id)
    {
     return (new UserRole)->where('ur_id', $id)->value('user_type');
    }

    protected function getAdminId()
    {
     return (new User)->where('user_type', 1)->value('id');
    }

    protected function getAdminEmail()
    {
     return (new User)->where('user_type', 1)->value('email');
    }

    protected function getUserName($id)
    {
     return (new User)->where('id', $id)->value('name');
    }

    public function getAllRegStudents()
    {
     return DB::table('users')->select('users.id','users.name','users.email','users.email_verified_at','tbl_userrole.user_type','tbl_userrole.ur_id')
            ->join('tbl_userrole', 'users.id', 'tbl_userrole.ur_id')
            ->where('email_verified_at', '!=', NULL)
            ->whereIn('tbl_userrole.user_type',[3, 4])
            ->orderBy('users.id','desc')
            ->get();
    }

    protected function getUsers()
    {
     return DB::table('users')->select('users.id','users.name','users.email','users.contact','users.email_verified_at','users.credit_points', 'tbl_userrole.user_type','tbl_userrole.ur_id')
        ->join('tbl_userrole', 'users.id', 'tbl_userrole.ur_id')->whereIn('tbl_userrole.user_type',[2, 3, 4])
        ->orderBy('users.id','desc')->paginate(10);
    } 

    protected function verifiedUsers()
    {
     return DB::table('users')->select('users.id','users.name','users.email','users.contact','users.email_verified_at', 'tbl_userrole.user_type','tbl_userrole.ur_id')
        ->join('tbl_userrole', 'users.id', 'tbl_userrole.ur_id')
        ->whereIn('tbl_userrole.user_type',[2, 3, 4])
        ->where('users.email_verified_at','!=','null')
        ->orderBy('users.id','desc')->get();
    }

    protected function UserData($id)
    {
     return DB::table('users')->where('id', $id)->get();
    }

    protected function UserAdditionalinfo($id)
    {
     return DB::table('users')->select('users.id','users.name','users.email','users.contact','users.qualification','users.occupation','users.gender')
        ->where('users.id',$id)
        ->get();
    }

    protected function UserFieldofExpertise($id){
       return DB::table('user_additional_info')->select('user_additional_info.field_of_expertise','user_additional_info.uai_id')
        ->where('user_additional_info.user_id',$id)
        ->get(); 
    }

    protected function UserFieldofInterest($id){
       return DB::table('user_additional_info')->select('user_additional_info.field_of_interest','user_additional_info.uai_id')
        ->where('user_additional_info.user_id',$id)
        ->get(); 
    }

    protected function getAdditionalData($id)
    {
     return DB::table('user_additional_info')->select('user_additional_info.*')
        ->where('user_additional_info.user_id',$id)
        ->get();
    }

}
