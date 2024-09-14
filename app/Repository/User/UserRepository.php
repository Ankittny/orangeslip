<?php

namespace App\Repository\User;

use App\Models\User;
use App\Models\Profile;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class UserRepository
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function __call($method, $args)
    {
        return call_user_func_array([$this->user, $method], $args);
    }

    public function createOrUpdate($data = [], $id = null)
    {
       				
        if($id) {

            $userData = [
                'first_name' => $data['first_name'],
                'middle_name' => $data['middle_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email']
            ];

            $profileData = [
                'address' => $data['address']
                
               
               
               
            ];

            if(isset($data['password'])) {
                $user['password'] = bcrypt($data['password']);
            }

            $user = $this->user->find($id);

            return DB::transaction(function() use ($user, $userData, $profileData){

                $user->update($userData);
                $user->profile()->update($profileData);
                return $user;
            });

        } else {
			
			
            
            $user = [               
                'email' => $data['email'],
                'password' => bcrypt($data['password'])
            ];

            
           
            $profile['address'] = isset($data['address'])?$data['address']:null;
            
            //$profile['verification_token'] = str_random(60);
           
			

            return DB::transaction(function() use ($user, $profile){
                $user = $this->user->create($user);

                $user->profile()->save(new Profile($profile));

                return $user;
            });
        }
    }
}
