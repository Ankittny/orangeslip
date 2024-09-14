<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
 

use App\Jobs\User;
use App\Jobs\Profile;

use Carbon\Carbon;
use DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Database\Seeder;

class ClearDatabase extends Command
{

	use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear_database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        		
        		
        		
        

        //php artisan db:seed --class=AdminSeeder --force

        		
        // profiles		
        		
        // users		
        		        				
		DB::table('users')->truncate();
		DB::table('profiles')->truncate();

		DB::table('assigned_roles')->truncate();
		DB::table('permissions')->truncate();
        
		DB::table('business_review_details')->truncate();
		DB::table('candidate_bulk_data')->truncate();
		DB::table('candidate_change_logs')->truncate();
		DB::table('candidate_details')->truncate();
		DB::table('candidate_documents')->truncate();
		DB::table('candidate_education_details')->truncate();
		DB::table('candidate_follow_up')->truncate();
		DB::table('candidate_other_details')->truncate();
		DB::table('candidate_professional_details')->truncate();
		DB::table('checked_offer_letters')->truncate();
		DB::table('contact_us')->truncate();
		DB::table('deposits')->truncate();
		DB::table('disputes')->truncate();
		DB::table('enroll_companies')->truncate();
		DB::table('failed_jobs')->truncate();
		DB::table('individual_user_access')->truncate();
		DB::table('lead_follow_up')->truncate();
		DB::table('login_info')->truncate();
		DB::table('offer_letters')->truncate();
		DB::table('otp')->truncate();
		DB::table('password_resets')->truncate();
		DB::table('reallotment_candidate')->truncate();
		DB::table('business_details')->truncate();
		DB::table('verification_staffs')->truncate();
		DB::table('verification_rejected_requests')->truncate();
		DB::table('verification_documents')->truncate();
		DB::table('verifications')->truncate();
		DB::table('transactions')->truncate();
		DB::table('staff_enroll')->truncate();
		DB::table('reschedule')->truncate();
		DB::table('request_log')->truncate();
	
        $this->call('db:seed', [
            '--class' => 'UserTableSeeder', '--force'
         ]);	
		
    }
    
    
}
