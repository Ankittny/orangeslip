<?php
/**
 * Created by PhpStorm.
 * User: aivie
 * Date: 20/7/22
 * Time: 12:10 PM
 */

namespace App\Repository\Candidate;

use DB;
use App\Candidate;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class CandidateRepository implements ICandidateRepository
{
    protected $candidate;

    public function __construct(Candidate $candidate)
    {
        $this->candidate = $candidate;
    }

    public function __call($method, $args)
    {
        return call_user_func_array([$this->candidate, $method], $args);
    }

    public function createOrUpdate(array $data, $id = null)
    {
       
		
		$profile = new Profile();   

        $profile->rank_id = 1;
        
        $profile->gender = $data['gender'];		
        $profile->country_id = $data['country'];
        $profile->income_available_after = $data['income_available_after'];
        $profile->mobile_no = $data['phone'];     
        $profile->skype = isset($data['skype'])?$data['skype']:'';     
        $profile->taxid = isset($data['taxid'])?$data['skype']:'';   

        $user = DB::transaction(function() use ($profile, $data){
			
			$user = $this->user->create([
                'first_name' => $data['firstname'],
                'last_name' => $data['lastname'],
                'email' => strtolower($data['email']),
                'username' => strtolower($data['username']),
                'password' => bcrypt($data['password']),
                'verification_token' => str_random(4),
            ]);
            
            $sponsor = $this->user->where('username', $data['sponsor'])->first();
      
			//$parent = $this->user->where('username', $data['parent'])->first();
			
			$profile->sponsor_id = $sponsor->id;
            
			$parent = $sponsor;
			
			$profile->parent_id = $parent->id;
			$profile->level = $parent->profile->level + 1;
             

            $parent->push();

            $verification = new Verification();
            $verification->user_id = $user->id;
            $verification->save();

            $wallet = new Wallet;
            $wallet->user_id = $user->id;
            $wallet->save();

            $business = new Business;
            $business->user_id = $user->id;
            $business->save();

            $user->profile()->save($profile);

            return $user;
        });
        return $user;
    }

}
