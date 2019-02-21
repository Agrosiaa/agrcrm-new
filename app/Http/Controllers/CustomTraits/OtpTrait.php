<?php
namespace App\Http\Controllers\CustomTraits;

use App\Http\Requests\Web\Seller\MobileNoRequest;
use App\OtpVerification;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Helpers\LanguageHelper;

trait OtpTrait{

    public function checkMobile(MobileNoRequest $request){
        try{
            $currentLanguage = LanguageHelper::currentLanguage();
            $mobileExists = User::where('mobile',$request->mobile)->first();
            $verificationData = array(
                'mobile' => $request->mobile,
                'is_verified' => true
            );
            $mobileExistsInOtptableVerified = OtpVerification::where($verificationData)->first();
            if($mobileExistsInOtptableVerified!=null){
                $status =202;
                $message = trans('message.Mobile_number_already_verified_no_need_to_enter_OTP');
                $data = ['mobile'=>$request->mobile,'otp'=>$mobileExistsInOtptableVerified->otp,'message_count'=>$mobileExistsInOtptableVerified->message_count,'is_verified'=>$mobileExistsInOtptableVerified->is_verified,'created_at'=>$mobileExistsInOtptableVerified->created_at];
            }else{
                if($mobileExists==null){
                    $mobileExistsInOtptable = OtpVerification::where('mobile',$request->mobile)->first();
                    if($mobileExistsInOtptable!=null){
                        //Send sms with same otp if user requested it again
                        if($mobileExistsInOtptable->message_count==3){
                            //Write A Cron Job To Delete it after 3.15 Minutes
                            $optCreatedTime = $mobileExistsInOtptable->created_at;
                            $currentTime = Carbon::now();
                            $waitingTime = 300-$currentTime->diffInSeconds($optCreatedTime);
                            if($waitingTime<0){
                                $data = array(
                                    'mobile'=>$request->mobile,
                                );
                                $otpData = OtpVerification::where($data)->first();
                                $otpData->delete();
                                goto creteOtp;
                            }else{
                                $status =403;
                                $message = trans('message.waiting_time_message');;
                                $data = ['mobile'=>$request->mobile,'otp'=>$mobileExistsInOtptable->otp,'message_count'=>$mobileExistsInOtptable->message_count,'is_verified'=>$mobileExistsInOtptable->is_verified,'created_at'=>$mobileExistsInOtptable->created_at,'waitingTime'=>$waitingTime];
                            }
                        }else{
                            $count = $mobileExistsInOtptable->message_count + 1;
                            $mobileExistsInOtptable->update(array('message_count'=>$count));
                            $message = trans('message.otp_sent_on_registered_no_message');
                            $sms = $mobileExistsInOtptable->otp.' is your OTP. Validity is for 3 minutes only. Welcome to Agrosiaa.';
                            $sendSMS = $this->sendSms($request->mobile,$sms);
                            $data = ['mobile'=>$request->mobile,'otp'=>$mobileExistsInOtptable->otp,'message_count'=>$count,'sms_gateway_output'=>$sendSMS,'is_verified'=>$mobileExistsInOtptable->is_verified,'created_at'=>$mobileExistsInOtptable->created_at,'waitingTime'=>0];
                            $status = 200;
                        }
                    }else{
                        creteOtp:
                        $otp = $this->createOtp();
                        if($otp!=null){
                            $carbon = Carbon::now();
                            $role = Role::where('slug','seller')->first();
                            $sms = $otp.' is your OTP. Validity is for 3 minutes only. Welcome to Agrosiaa.';
                            $sendSMS = $this->sendSms($request->mobile,$sms);
                            $data = [
                                'mobile'=>$request->mobile,
                                'message_count'=>1,//change to 0 & update it after sms sent
                                'is_verified'=>0,
                                'otp'=>$otp,
                                'role_id'=>$role->id,
                                'created_at'=>$carbon,
                                'updated_at'=>$carbon
                            ];
                            $otpData = OtpVerification::create($data);
                            $message = trans('message.otp_sent_on_registered_no_message');
                            $data = ['mobile'=>$request->mobile,'otp'=>$otp,'message_count'=>1,'sms_gateway_output'=>$sendSMS,'is_verified'=>$otpData->is_verified,'created_at'=>$otpData->created_at,'waitingTime'=>0];
                            $status = 200;
                        }else{
                            $message = 'something went wrong';
                            $data = null;
                            $status = 500;
                        }
                    }
                }else{
                    $status =401;
                    $data = null;
                    $message = trans('message.mobile_already_registered');
                }
            }
        }catch (\Exception $e){
            $errorLog = [
                'request'=>$request->all(),
                'action'=>'check mobile no',
                'exception'=>$e->getMessage()
            ];
            Log::critical(json_encode($errorLog));
            $message = 'something went wrong';
            $data = null;
            $status = 500;
        }
        $response = [compact('message','data')];
        return response()->json($response,$status);
    }

    public function checkMobileUser(MobileNoRequest $request){
        try{
            $currentLanguage = LanguageHelper::currentLanguage();
            $mobileExists = User::where('mobile',$request->mobile)->first();
            $verificationData = array(
                'mobile' => $request->mobile,
                'is_verified' => true
            );
            $mobileExistsInOtptableVerified = OtpVerification::where($verificationData)->first();
            if($mobileExistsInOtptableVerified!=null){
                $status =202;
                $message = ($currentLanguage == 'en') ? "Mobile number is already verified, no need to enter OTP" : 'मोबाइल नंबर आधीच सत्यापित आहे ओटीपी प्रविष्ट करण्याची गरज नाही';
                $data = ['mobile'=>$request->mobile,'otp'=>$mobileExistsInOtptableVerified->otp,'message_count'=>$mobileExistsInOtptableVerified->message_count,'is_verified'=>$mobileExistsInOtptableVerified->is_verified,'created_at'=>$mobileExistsInOtptableVerified->created_at];
            }else{
                if($mobileExists==null){
                    $mobileExistsInOtptable = OtpVerification::where('mobile',$request->mobile)->first();
                    if($mobileExistsInOtptable!=null){
                        //Send sms with same otp if user requested it again
                        if($mobileExistsInOtptable->message_count==3){
                            //Write A Cron Job To Delete it after 3.15 Minutes
                            $optCreatedTime = $mobileExistsInOtptable->created_at;
                            $currentTime = Carbon::now();
                            $waitingTime = 300-$currentTime->diffInSeconds($optCreatedTime);
                            if($waitingTime<0){
                                $data = array(
                                    'mobile'=>$request->mobile,
                                );
                                $otpData = OtpVerification::where($data)->first();
                                $otpData->delete();
                                goto creteOtp;
                            }else{
                                $status =403;
                                $message = ($currentLanguage == 'en') ? "Sorry you will have to wait till below timer reaches to 0, to generate new OTP else enter OTP you have received!" : "क्षमस्व आपणास नवीन ओटीपी निर्माण होईपर्यंत प्रतीक्षा करावी लागणार आहे किव्वा टाइमर 0 पर्यंत पोहोचेल तोपर्यंत अन्यथा आपल्याला मिळालेली ओटीपी प्रविष्ट करा ";
                                $data = ['mobile'=>$request->mobile,'otp'=>$mobileExistsInOtptable->otp,'message_count'=>$mobileExistsInOtptable->message_count,'is_verified'=>$mobileExistsInOtptable->is_verified,'created_at'=>$mobileExistsInOtptable->created_at,'waitingTime'=>$waitingTime];
                            }
                        }else{
                            if($mobileExistsInOtptable->message_count >= 1){
                                $status = 203;
                            }else{
                                $status = 200;
                            }
                            $count = $mobileExistsInOtptable->message_count + 1;
                            $mobileExistsInOtptable->update(array('message_count'=>$count));
                            $message = trans('message.otp_sent_on_registered_no_message');
                            $sms = $mobileExistsInOtptable->otp.' is your OTP. Validity is for 3 minutes only. Welcome to Agrosiaa.';
                            $sendSMS = $this->sendSms($request->mobile,$sms);
                            $data = ['mobile'=>$request->mobile,'otp'=>$mobileExistsInOtptable->otp,'message_count'=>$count,'sms_gateway_output'=>$sendSMS,'is_verified'=>$mobileExistsInOtptable->is_verified,'created_at'=>$mobileExistsInOtptable->created_at,'waitingTime'=>300];

                        }
                    }else{
                        creteOtp:
                        $otp = $this->createOtp();
                        if($otp!=null){
                            $carbon = Carbon::now();
                            $role = Role::where('slug','customer')->first();
                            $sms = $otp.' is your OTP. Validity is for 3 minutes only. Welcome to Agrosiaa.';
                            $sendSMS = $this->sendSms($request->mobile,$sms);
                            $data = [
                                'mobile'=>$request->mobile,
                                'message_count'=>1,//change to 0 & update it after sms sent
                                'is_verified'=>0,
                                'otp'=>$otp,
                                'role_id'=>$role->id,
                                'created_at'=>$carbon,
                                'updated_at'=>$carbon
                            ];
                            $otpData = OtpVerification::create($data);
                            $message = trans('message.otp_sent_on_registered_no_message');
                            $data = ['mobile'=>$request->mobile,'otp'=>$otp,'message_count'=>1,'sms_gateway_output'=>$sendSMS,'is_verified'=>$otpData->is_verified,'created_at'=>$otpData->created_at,'waitingTime'=>300];
                            $status = 200;
                        }else{
                            $message = 'something went wrong';
                            $data = null;
                            $status = 500;
                        }
                    }
                }else{
                    if($mobileExists->role->slug!='customer'){
                        $status = 401;
                        $data = null;
                        $message = "Unauthorized";
                    }else{
                        $status =201;
                        $data = null;
                        $message = ($currentLanguage == 'en') ? 'Account already exists! Please enter password to login' : 'खाते आधीपासून अस्तित्वात आहे! लॉग इन करण्यासाठी संकेतशब्द प्रविष्ट करा';
                    }
                }
            }
        }catch (\Exception $e){
            $errorLog = [
                'request'=>$request->all(),
                'action'=>'check mobile no customer',
                'exception'=>$e->getMessage()
            ];
            Log::critical(json_encode($errorLog));
            $message = 'something went wrong';
            $data = null;
            $status = 500;
        }
        $response = compact('message','data');
        return response()->json($response,$status);
    }

    public function validateOtp(Request $request){
        try{
            $currentLanguage = LanguageHelper::currentLanguage();
            $data = array(
                'mobile'=>$request->mobile,
                'otp'=>$request->otp
            );
            $mobileWithOtpExists = OtpVerification::where($data)->first();
            if($mobileWithOtpExists==null){
                $status = 403;
                $message = ($currentLanguage == 'en') ? "Entered OTP is either Invalid or Expired!" : "हा ओ टी पी अवैध किंवा कालबाह्य एकतर आहे";
                $data = null;
            }else{
                $optCreatedTime = $mobileWithOtpExists->created_at;
                $currentTime = Carbon::now();
                $waitingTime = 300-$currentTime->diffInSeconds($optCreatedTime);
                if($waitingTime<0){
                    $data = array(
                        'mobile'=>$request->mobile,
                    );
                    $otpData = OtpVerification::where($data)->first();
                    $otpData->delete();
                    $status = 403;
                    $message = ($currentLanguage == 'en') ? "Entered OTP is either Invalid or Expired!" : "हा ओ टी पी अवैध किंवा कालबाह्य एकतर आहे";
                    $data = null;
                }else{
                    $mobileWithOtpExists->update(array('is_verified'=>TRUE));
                    $message = 'Success';
                    $status=200;
                    $data = ['mobile'=>$mobileWithOtpExists->mobile,'otp'=>$mobileWithOtpExists->otp,'message_count'=>$mobileWithOtpExists->message_count,'is_verified'=>$mobileWithOtpExists->is_verified,'created_at'=>$mobileWithOtpExists->created_at];
                }
            }
        }catch(\Exception $e){
            $errorLog = [
                'request'=>$request->all(),
                'action'=>'validate OTP',
                'exception'=>$e->getMessage()
            ];
            Log::critical(json_encode($errorLog));
            $message = 'something went wrong';
            $data = null;
            $status = 500;
        }
        $response = [compact('message','data')];
        return response()->json($response,$status);
    }

    public function createOtp($length = 6){
        try{
            $chars = '0123456789';
            $time = date("Y-m-d H:i:s");
            $unixTimeStamp = strtotime($time);
            $chars = $unixTimeStamp.$chars;
            $result = '';
            for ($p = 0; $p < $length; $p++)
            {
                $result .= ($p%2) ? $chars[mt_rand(1, 10)] : $chars[mt_rand(11, strlen($chars))];
            }
            return $result;
        }catch (\Exception $e){
            $errorLog = [
                'action'=>'create OTP',
                'exception'=>$e->getMessage()
            ];
            Log::critical(json_encode($errorLog));
            return null;
        }
    }
    public function sendSms($mobile,$otp){
        try{
            // create curl resource
            $ch = curl_init();
            $smsUsername = env('SMS_USERNAME');
            $smsPassword = env('SMS_PASSWORD');
            $otp = urlencode($otp);
            curl_setopt_array($ch, array(
                CURLOPT_URL => "http://www.smsgateway.center/SMSApi/rest/send?userId=".$smsUsername."&password=".$smsPassword."&senderId=AGRSIA&sendMethod=simpleMsg&msgType=text&mobile=".$mobile."&msg=".$otp."&duplicateCheck=true&format=json",
                CURLOPT_RETURNTRANSFER => 1
            ));
            // execute
            $output = curl_exec($ch);
            // free
            curl_close($ch);
            return $output;
        }catch (\Exception $e){
            $errorLog = [
                'mobile'=>$mobile,
                'otp'=>$otp,
                'action'=>'Send SMS OTP',
                'exception'=>$e->getMessage()
            ];
            Log::critical(json_encode($errorLog));
            return null;
        }
    }

    public function sendMessage($mobile,$message){
        try{
            // create curl resource
            $ch = curl_init();
            $smsUsername = env('SMS_USERNAME');
            $smsPassword = env('SMS_PASSWORD');
            $message = urlencode($message);
            curl_setopt_array($ch, array(
                CURLOPT_URL => "http://www.smsgateway.center/SMSApi/rest/send?userId=".$smsUsername."&password=".$smsPassword."&senderId=AGRSIA&sendMethod=simpleMsg&msgType=text&mobile=".$mobile."&msg=".$message."&duplicateCheck=true&format=json",
                CURLOPT_RETURNTRANSFER => 1
            ));
            // execute
            $output = curl_exec($ch);
            // free
            curl_close($ch);
            return $output;
        }catch (\Exception $e){
            $errorLog = [
                'mobile'=>$mobile,
                'message'=>$message,
                'action'=>'Send message through SMS.',
                'exception'=>$e->getMessage()
            ];
            Log::critical(json_encode($errorLog));
            return null;
        }
    }

}