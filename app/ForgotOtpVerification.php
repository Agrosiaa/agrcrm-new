<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForgotOtpVerification extends Model
{
    protected $table = 'forgot_otp_verification';

    protected $fillable = ['mobile','otp','message_count','is_verified','role_id','created_at','updated_at'];
}
