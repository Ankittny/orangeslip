<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
 
use Carbon\Carbon;
use DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Database\Seeder;

class ExpiredSubscriptionCheck extends Command
{

	use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expired_subscription_check';

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
        		
        		
        		
        $query= DB::table('subscriptions')
        // ->orderBy('id','DESC')
        ->where('status','=',1)
        ->where(function($q)  {  
        $q->where('remain_qty','<=',0);            
        $q->orWhere('expire_date','<=',Carbon::now()->toDateTimeString());      
        })
        ->update(['status'=>2]);
		
    }
    
    
}
