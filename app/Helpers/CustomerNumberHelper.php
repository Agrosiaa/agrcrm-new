<?php

/**
 * Created by PhpStorm.
 * User: ganesh
 * Date: 20/4/19
 * Time: 4:30 PM
 */
namespace App\Helpers;
use App\CustomerNumberStatus;
use App\CustomerNumberStatusDetails;

class CustomerNumberHelper
{
    static function orderCount()
    {
        try {
            $totalRecords = array();
            $new = CustomerNumberStatus::where('slug', 'new')->pluck('id');
            $callBack = CustomerNumberStatus::where('slug', 'call-back')->pluck('id');
            $complete = CustomerNumberStatus::where('slug', 'complete')->pluck('id');
            $failed = CustomerNumberStatus::where('slug', 'failed')->pluck('id');
            $totalRecords['new'] = CustomerNumberStatusDetails::where('customer_number_status_id', $new)->count();
            $totalRecords['call-back'] = CustomerNumberStatusDetails::where('customer_number_status_id', $callBack)->count();
            $totalRecords['complete'] = CustomerNumberStatusDetails::where('customer_number_status_id', $complete)->count();
            $totalRecords['failed'] = CustomerNumberStatusDetails::where('customer_number_status_id', $failed)->count();
            return $totalRecords;
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
    }
}