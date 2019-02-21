<?php
namespace App\Http\Controllers\CustomTraits;



use App\PostOffice;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Ixudra\Curl\Facades\Curl;

trait PostOfficeTrait{
    public function getPostOffices(Request $request){
        try{
            $status = 200;
            $postOfficeName = trim($request['office_name']);
            $taluka = str_replace('-', ' ', trim($request['taluka']));
            if($postOfficeName == "" || $postOfficeName == null){
                $postOffices = null;
            }
            else{
                $postOffices = PostOffice::where('office_name','ILIKE','%'.$postOfficeName.'%')->where('taluka',$taluka)->get();
            }

        }catch (\Exception $e){
            $status = 500;
            $data = [
                'input_params' => $request->all(),
                'action' => 'get post offices',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
        }
        return response()->json($postOffices,$status);
    }

    public function getTaluka(Request $request,$name){
        try{
            $status = 200;
            $talukas = PostOffice::select('taluka')->where('district',$name)->distinct()->orderBy('taluka','asc')->get();
            return response()->json($talukas,$status);
        }catch (\Exception $e){
            abort(500,$e->getMessage());
        }
    }

    public function getPincode(Request $request){
        try {
            $status = 200;
            $requestPincode = trim($request['pincode']);
            if ($requestPincode == "" || $requestPincode == null) {
                $pincode = null;
            } else {
                $postOfficeData = PostOffice::where('pincode',$requestPincode)->get();
                if(count($postOfficeData) > 0){
                    $pincode = array();
                    foreach($postOfficeData as $data){
                        if(array_key_exists($requestPincode,$pincode)){
                            $pincode[$requestPincode]['post_offices'] .= '<option value="'.$data->office_name.'">'.$data->office_name.'</option>';
                        }else{
                            $pincode[$requestPincode] = array();
                            $pincode[$requestPincode]['pincode'] = $requestPincode;
                            $pincode[$requestPincode]['post_offices'] = '<option value="'.$data->office_name.'">'.$data->office_name.'</option>';
                            $pincode[$requestPincode]['state'] = $data->state;
                        }
                    }
                }else{
                    $pincodeData = Curl::to('http://postalpincode.in/api/pincode/'.$requestPincode)->get();
                    $pincodeData = json_decode($pincodeData);
                    if($pincodeData->PostOffice != null){
                        $pincode = array();
                        foreach($pincodeData->PostOffice as $data){
                            if(array_key_exists($requestPincode,$pincode)){
                                $pincode[$requestPincode]['post_offices'] .= '<option value="'.$data->Name.'">'.$data->Name.'</option>';
                            }else{
                                $pincode[$requestPincode] = array();
                                $pincode[$requestPincode]['pincode'] = $requestPincode;
                                $pincode[$requestPincode]['post_offices'] = '<option value="'.$data->Name.'">'.$data->Name.'</option>';
                                $pincode[$requestPincode]['state'] = $data->State;
                            }
                        }
                    }else{
                        $pincode = null;
                    }
                }
            }
        }catch (\Exception $e){
            $status = 500;
            $pincode = null;
            $data = [
                'input_params' => $request->all(),
                'action' => 'get pincode',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
        }
        return response()->json($pincode,$status);
    }

    public function getPostOfficeInfo(Request $request,$postOffice,$pincode){
        try{
            $status = 200;
            $postOffice = trim(str_replace("%20"," ",$postOffice));
            $postOfficeData = PostOffice::where('office_name','ilike', $postOffice)->where('pincode',$pincode)->select('taluka','district')->first();
            if(($postOfficeData) != null || count($postOfficeData) > 0){
                $response = $postOfficeData->toArray();
            }else{
                $postOfficeResponse = Curl::to('http://postalpincode.in/api/postoffice/'.(str_replace(" ","%20",$postOffice)))->get();
                $postOfficeResponse = json_decode($postOfficeResponse);
                $response = array();
                if($postOfficeResponse->PostOffice != null){
                    foreach($postOfficeResponse->PostOffice as $postOffice){
                        $response = [
                            'taluka' => $postOffice->Taluk,
                            'district' => $postOffice->District
                        ];
                    }
                }
            }
        }catch (\Exception $e){
            $status = 500;
            $response = null;
            $data = [
                'input_params' => $request->all(),
                'action' => 'Get post office info',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
        }
        return response()->json($response,$status);
    }
}

