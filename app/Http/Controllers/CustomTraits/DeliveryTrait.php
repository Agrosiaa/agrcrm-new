<?php
namespace App\Http\Controllers\CustomTraits;


use App\Cart;
use App\Holidays;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use DatePeriod;

trait DeliveryTrait{
    public function getNormalDeliveryDate($datetime,$deliveryType){
        try{
            $holidaysList = Holidays::lists('date')->toArray();
            $workingCount = 0;
            $holidayCount = 0;
            $time = date($datetime);
            $time = strtotime($time);
            $time = date('H:i:s',$time);
            $datetime = date('Y-m-d',  strtotime($datetime));
            if($deliveryType == "Fast"){
                $deliveryPeriod = 2;
            }else{
                $deliveryPeriod = 9;
            }
            if($time > "11:59:00" && date('D',strtotime($datetime)) != 'Sun' && !in_array($datetime,$holidaysList)){
                $deliveryPeriod++;
            }
            while($workingCount != $deliveryPeriod){
                if(date('D',strtotime($datetime))=='Sun' || in_array($datetime,$holidaysList)){
                    $datetime =  strtotime($datetime);
                    $datetime = date('Y-m-d',  strtotime("+1 day", $datetime));
                    $holidayCount++;
                }else{
                    $datetime =  strtotime($datetime);
                    $datetime = date('Y-m-d',  strtotime("+1 day", $datetime));
                    $workingCount++;
                }
            }
            $deliveryDate = DeliveryTrait::addWorkingDays($holidaysList,$datetime);
            return $deliveryDate;
        }catch(\Exception $e){
            $data = [
                'input_params' => null,
                'dateTime' => $datetime,
                'action' => 'calculate normal delivery date',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }
    public function addWorkingDays($holidaysList,$deliveryDate){
        $flag = 0;
        while($flag != 1){
            if(date('D',strtotime($deliveryDate))=='Sun' || in_array($deliveryDate,$holidaysList)){
                $deliveryDate = date('Y-m-d', strtotime($deliveryDate . " + 1 day"));
            }else{
                $flag = 1 ;
            }
        }
        return $deliveryDate;
    }
    //The function returns the no. of business days between two dates
    public function getWorkingDays($startDate,$endDate){
        $start = new \DateTime($startDate);
        $end = new \DateTime($endDate);
        $oneday = new \DateInterval("P1D");
        $workingDaysCount = 0;
        /* Iterate from $start up to $end+1 day, one day in each iteration.
           We add one day to the $end date, because the DatePeriod only iterates up to,
           not including, the end date. */
        foreach(new DatePeriod($start, $oneday, $end->add($oneday)) as $day) {
            $day_num = $day->format("N"); /* 'N' number days 1 (mon) to 7 (sun) */
            if($day_num <= 6) { /* weekday */
                $workingDaysCount++;
            }
        }
        return $workingDaysCount;
    }

    public function getFastDispatchDate($datetime){
        try{
            $time = date($datetime);
            $strToTime = strtotime($time);
            $time = date('H:i:s',$strToTime);
            $year = date('Y',$strToTime);
            $month = date('m',$strToTime);
            $day = date('d',$strToTime);
            $hours = date('H',$strToTime);
            $minutes = date('i',$strToTime);
            $holidaysList = Holidays::lists('date')->toArray();
            if($time > "10:00:00" && $time < "15:59:00"){
                $datetime = date('Y-m-d',strtotime($datetime));
                if(date('D',strtotime($datetime))=='Sun' || in_array($datetime,$holidaysList)){
                    $flag = 0;
                    while($flag != 1){
                        $datetime = date('Y-m-d', strtotime($datetime . " + 1 day"));
                        if(date('D',strtotime($datetime))!='Sun' && (!in_array($datetime,$holidaysList))){
                            $flag = 1 ;
                        }
                    }
                    $dispatchDateTime = $datetime." 11:59:00 ";
                }else{
                    $dt = Carbon::create($year,$month, $day, $hours,$minutes);
                    $dt->toDateTimeString();
                    $dt->addHours(2);
                    $dispatchDateTime = $dt->toDateTimeString();
                }
            }elseif($time > "16:00:00" && $time < "23:59:00"){
                $date = strtotime($datetime);
                $dispatchDateTime = date('Y-m-d', strtotime("+1 day", $date));
                if(date('D',strtotime($dispatchDateTime))=='Sun' || in_array($dispatchDateTime,$holidaysList)){
                    $flag = 0;
                    while($flag != 1){
                        $dispatchDateTime = date('Y-m-d', strtotime($dispatchDateTime . " + 1 day"));
                        if(date('D',strtotime($dispatchDateTime))!='Sun' && (!in_array($dispatchDateTime,$holidaysList))){
                            $flag = 1 ;
                        }
                    }
                }
                $dispatchDateTime = $dispatchDateTime ." 11:59:00 ";
            }else{
                $datetime = date('Y-m-d', strtotime($datetime));
                if(date('D',strtotime($datetime))=='Sun' || in_array($datetime,$holidaysList)){
                    $flag = 0;
                    while($flag != 1){
                        $datetime = date('Y-m-d', strtotime($datetime . " + 1 day"));
                        if(date('D',strtotime($datetime))!='Sun' && (!in_array($datetime,$holidaysList))){
                            $flag = 1 ;
                        }
                    }
                }
                $dispatchDateTime = $datetime." 11:59:00 ";
            }
            return $dispatchDateTime;
        }catch(\Exception $e){
            $data = [
                'input_params' => null,
                'dateTime' => $datetime,
                'action' => 'calculate fast dispatch date',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }

    public function getFastPickUpDate($datetime){
        try{
            $holidaysList = Holidays::lists('date')->toArray();
            $time = date($datetime);
            $strToTime = strtotime($time);
            $year = date('Y',$strToTime);
            $month = date('m',$strToTime);
            $day = date('d',$strToTime);
            $hours = date('H',$strToTime);
            $minutes = date('i',$strToTime);
            $dt = Carbon::create($year,$month, $day, $hours,$minutes);
            $dt->toDateTimeString();
            $dt->addHours(4);
            $hours = $dt->hour;
            $minutes = $dt->minute;
            $time = "$hours:$minutes";
            $workingCount = 0;
            $holidayCount =0;
            if($time > "18:00"){
                while($workingCount != 1){
                    if(date('D',strtotime($datetime))=='Sun' || in_array($datetime,$holidaysList)){
                        $datetime =  strtotime($datetime);
                        $datetime = date('Y-m-d',  strtotime("+1 day", $datetime));
                        $holidayCount++;
                    }else{
                        $datetime =  strtotime($datetime);
                        $datetime = date('Y-m-d',  strtotime("+1 day", $datetime));
                        $workingCount++;
                    }
                }
                $PickUpDateTime = DeliveryTrait::addWorkingDays($holidaysList,$datetime);
                $PickUpDateTime = $PickUpDateTime ."  10:00:00 ";
            }else{
                $PickUpDateTime = $dt->toDateTimeString();
            }
            return $PickUpDateTime;
        }catch(\Exception $e){
            $data = [
                'input_params' => null,
                'dateTime' => $datetime,
                'action' => 'calculate fast dispatch date',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }

    public function getPickUpDate($datetime,$deliveryType){
        try{
            if($deliveryType == "normal"){
                $holidaysList = Holidays::lists('date')->toArray();
                $workingCount = 0;
                $deliveryPeriod = 1;
                $holidayCount = 0;
                while($workingCount != $deliveryPeriod){
                    if(date('D',strtotime($datetime))=='Sun' || in_array($datetime,$holidaysList)){
                        $datetime =  strtotime($datetime);
                        $datetime = date('Y-m-d',  strtotime("+1 day", $datetime));
                        $holidayCount++;
                    }else{
                        $datetime =  strtotime($datetime);
                        $datetime = date('Y-m-d',  strtotime("+1 day", $datetime));
                        $workingCount++;
                    }
                }
                $dispatchDate = DeliveryTrait::addWorkingDays($holidaysList,$datetime);
                $dispatchDate = $dispatchDate ." 11:59:00";
            }else {
                $dispatchDate = $this->getFastPickUpDate($datetime);
            }
            return $dispatchDate;
        }catch(\Exception $e){
            $data = [
                'input_params' => null,
                'dateTime' => $datetime,
                'action' => 'calculate order Pick Up Date',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }


    public function getReturnDate($datetime){
        try{
            $date = strtotime($datetime);
            $deliveryDateExcludingSundayAndHolidaysDate = date('Y-m-d', strtotime("+2 day", $date));
            $days = 3;
            // This will return working days
            $workingDaysCount = DeliveryTrait::getWorkingDays($datetime,$deliveryDateExcludingSundayAndHolidaysDate);
            $numberOfSundays = $days - $workingDaysCount;
            if($numberOfSundays<0){
                $numberOfSundays = 0;
            }
            $deliveryDateExcludingHolidays = date('Y-m-d', strtotime($deliveryDateExcludingSundayAndHolidaysDate . " + $numberOfSundays day"));
            $holidaysList = Holidays::lists('date');
            $holidayCount = 0;
            foreach($holidaysList as $holiday){
                $day = date('D',$holiday);
                if($day!='Sun'){
                    if($datetime <= $holiday && $deliveryDateExcludingHolidays >= $holiday){
                        $holidayCount = $holidayCount + 1;
                    }
                }
            }
            $deliveryDate = date('Y-m-d', strtotime($deliveryDateExcludingHolidays . " + $holidayCount day"));
            $flag = 0;
            $holidaysList = $holidaysList->toArray();
            while($flag != 1){
                if(date('D',strtotime($deliveryDate))=='Sun' || in_array($deliveryDate,$holidaysList)){
                    $deliveryDate = date('Y-m-d', strtotime($deliveryDate . " + 1 day"));
                }else{
                    $flag = 1 ;
                }
            }
            return $deliveryDate;
        }catch(\Exception $e){
            $data = [
                'input_params' => null,
                'dateTime' => $datetime,
                'action' => 'getReturnDate',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }
}