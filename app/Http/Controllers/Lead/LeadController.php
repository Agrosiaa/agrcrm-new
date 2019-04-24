<?php

namespace App\Http\Controllers\Lead;

use App\CustomerNumberStatus;
use App\CustomerNumberStatusDetails;
use App\Http\Middleware\User;
use Box\Spout\Common\Type;
use Box\Spout\Reader\ReaderFactory;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LeadController extends Controller
{
    public function manage(Request $request){
        try{
            $user = Auth::user();
            return view('backend.Lead.manage')->with(compact('user'));
        }catch(\Exception $exception){
            $data =[
                'action' => 'get Lead manage page',
                'exception' => $exception->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$exception->getMessage());
        }
    }
    public function exportCustomerView(Request $request){
        try{
            return view('backend.Lead.customerExcel.logistic-Import-Excel');
        }catch(\Exception $exception){
            $data =[
                'action' => 'export excel view',
                'exception' => $exception->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$exception->getMessage());
        }
    }
    public function exportCustomerSheet(Request $request){
        try{
            $user = Auth::user();
            $reader = ReaderFactory::create(Type::XLSX); // for XLSX files
            $reader->open($request->file('excel_file'));
            $sheetIndex = 1;
            foreach($reader->getSheetIterator() as $sheet){
                if($sheetIndex==1){
                    $rowIndex = 1;
                    foreach ($sheet->getRowIterator() as $rows) {
                        /* Create Array To data Insert */
                        if($rowIndex > 1){
                            Log::info($rows);
                            if($rows[0] == null){
                                $message = "Please Insert Number";
                                $request->session()->flash('error', $message);
                                return redirect('leads/export-for-logistic');
                            }else{
//                                $users = User::where();
                                $customerData['customer_number_status_id'] = CustomerNumberStatus::where('slug','new')->pluck('id');
                                $customerData['user_id'] = 2;
                                $customerData['number'] = $rows[0];
                                CustomerNumberStatusDetails::create($customerData);
                            }
                        }
                        $rowIndex++;
                    }
                }
                $sheetIndex++;
            }
            $reader->close();
            $message = "File uploaded successfully";
            $request->session()->flash('success', $message);
            return redirect('leads/export-customer-number');
        }catch(\Exception $exception){
            $data =[
                'action' => 'export excel upload',
                'exception' => $exception->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$exception->getMessage());
        }
    }
}
