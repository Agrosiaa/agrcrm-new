<?php

namespace App\Http\Controllers\Report;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Ixudra\Curl\Facades\Curl;
use Maatwebsite\Excel\Facades\Excel;

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

    public function generateReport(Request $request){
        try{
            $data = array();
            $orderData = Curl::to(env('BASE_URL')."/report-data")
                ->withData( array( 'report' => $request->report, 'from_date' => $request->from_date,'to_date' => $request->to_date))->asJson()->get();
            foreach ($orderData as $key => $orderDatum){
                $data[$key] = (array)$orderDatum;
            }
            switch($request->report) {
                case 'sales-orders':
                    $curr_date = Carbon::now();
                    Excel::create("Sales_Order_Report"."_".$curr_date, function($excel) use($data) {
                        $excel->getDefaultStyle()
                            ->getAlignment()
                            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $excel->sheet("Sales Order Report", function($sheet) use($data) {
                            $sheet->setAutoSize(true);
                            $sheet->setAllBorders('thin');
                            $sheet->cells('A1:J1', function ($cells) {
                                $cells->setAlignment('center');
                                $cells->setFontWeight('bold');
                                $cells->setFontSize(13);
                            });
                            // $sheet->protect('password');
                            $sheet->fromArray($data);
                            for( $intRowNumber = 1; $intRowNumber <= count($data) + 1; $intRowNumber++){
                                $sheet->setSize('A' . $intRowNumber, 25, 18);
                                $sheet->setSize('B' . $intRowNumber, 20, 18);
                                $sheet->setSize('C' . $intRowNumber, 20, 18);
                                $sheet->setSize('D' . $intRowNumber, 20, 18);
                                $sheet->setSize('E' . $intRowNumber, 20, 18);
                                $sheet->setSize('F' . $intRowNumber, 20, 18);
                                $sheet->setSize('G' . $intRowNumber, 20, 18);
                                $sheet->setSize('H' . $intRowNumber, 25, 18);
                                $sheet->setSize('I' . $intRowNumber, 20, 18);
                                $sheet->setSize('J' . $intRowNumber, 20, 18);
                                //$sheet->setSize('K' . $intRowNumber, 22, 18);
                            }
                        });
                    })->export('xls');
                    break;
            }
        }catch(\Exception $e){
            $errorLog = [
                'request' => $request->all(),
                'action' => 'Agrosiaa Report',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($errorLog));
            abort(500,$e->getMessage());
        }
    }

    public function getStructuredOrderId($orderId)
    {
        return str_pad($orderId, 9, "0", STR_PAD_LEFT);
    }

}
