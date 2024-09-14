<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;



class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRolesAndAbilities;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    use SoftDeletes;
    protected $fillable = [
        'first_name','middle_name','last_name','email','password','verification_token','company_id','account_type','parent_id','country','user_code','csv','referral_code','is_email_verified'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

  


    public function profile(){
        return $this->hasOne(Profile::class, 'user_id');
    }
    public function candidate(){
        return $this->hasOne(CandidateDetail::class, 'user_id');
    }

    public function business(){
        return $this->hasOne(BusinessDetail::class, 'user_id');
    }
    public function Parent(){
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function verificationstaff(){
        return $this->hasOne(VerificationStaff::class, 'user_id');
    }
    public function verificationhr(){
        return $this->hasMany(Verification::class, 'hr_id');
    }
    public function userAccess(){
    //    return $data= $this->hasOneThrough(UserAccessMaster::class, IndividualUserAccess::class, 'user_id','access_id','access_id','id');            
        //dd($data);
        return $this->belongsToMany(UserAccessMaster::class, IndividualUserAccess::class, 'user_id', 'access_id')->select('title');
        
    }
    public function chkUserAccess($acc_id)
    {                     
         if($this->account_type=='hr'){
            $user_access=IndividualUserAccess::where([['user_id',$this->id],['access_id',$acc_id]])->first();
            if($user_access){
                return true;
            }
            return false;
         }
       return  true;
        //return $user_access;
        // //dd($user_access);
        // if($user_access==Null)
        // {
        //     return $status=0;
        // }
        // else{
        //     return $status=$user_access->user_id;
        // }
        
    }
    

}
