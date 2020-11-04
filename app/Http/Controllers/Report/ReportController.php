<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
class ReportController extends Controller
{
    public function view(Request $request){
        try{
            $thisYear = date('Y');
            $nextYear = $thisYear + 1;
            return view('backend.report.view')->with(compact('thisYear','nextYear'));
        }catch(\Exception $exception){
            $data =[
                'action' => 'get report view page',
                'exception' => $exception->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$exception->getMessage());
        }
    }

}
