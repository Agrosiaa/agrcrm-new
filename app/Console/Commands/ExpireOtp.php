<?php

namespace App\Console\Commands;

use App\OtpVerification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ExpireOtp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'custom:expire-otp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Expired OTP';

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
        try{
            $mobileNos = OtpVerification::where('is_verified',false)->get();
            if(!$mobileNos->isEmpty()){
                $currentTime = Carbon::now();
                foreach($mobileNos as $mobileNo){
                    $otpCreatedTime = $mobileNo->created_at;
                    $timeDifference = $otpCreatedTime->diffInMinutes($currentTime);
                    if($timeDifference>3){
                        $mobileNo->delete();
                    }
                }
                $message = 'success';
            }else{
                $message = 'Empty list';
            }
        }catch (\Exception $e){
            $errorLog = [
                'action'=>'Delete Expired OTP',
                'exception'=>$e->getMessage()
            ];
            Log::critical(json_encode($errorLog));
            $message = $e->getMessage();
        }
        $this->info($message);
    }
}
