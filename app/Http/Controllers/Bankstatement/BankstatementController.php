<?php
namespace App\Http\Controllers\Bankstatement;

use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use App\Models\Actionlog;
use App\Models\Bankmaster;
use App\Models\Bankstatement;
use App\Http\Requests\ImportBankStatementRequest ;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Auth;
use Config;
use Crypt;
use DB;
use HTML;
use Illuminate\Support\Facades\Log;
use Input;
use Lang;
use League\Csv\Reader;
use Mail;
use Redirect;
use Response;
use Str;
use Symfony\Component\HttpFoundation\JsonResponse;
use URL;
use View;
use Illuminate\Http\Request;
use Gate;
use File;
use Excel;
use Validator;

/**
 * This controller handles all actions related to Users for
 * the Parextech Asset Management application.
 *
 * @version    v1.0
 */


class BankstatementController extends Controller
{


    /**
    * Returns a view that invokes the ajax tables which actually contains
    * the content for the users listing, which is generated in getDatatable().
    *
    * @author [A. Gianotto] [<snipe@snipe.net>]
    * @see BankstatementController::getDatatable() method that generates the JSON response
    * @since [v1.0]
    * @return View
    */
    public function getIndex()
    {
// echo phpversion();
// die();
        if( Input::has('start_date') || Input::has('end_date') ){
            $validator = Validator::make(Input::all(), [                
                'end_date' => 'date|after_or_equal:start_date',
                'start_date' => 'date|before_or_equal:end_date',        
            ]);
            if ($validator->fails()) {            
                $error = $validator->errors()->first();                  
                return Redirect::back()->withInput(Input::all())->with("error",$error);                
            }    
        }    
        $bankstatementExtraFlg = Helper::bankStatementExtraFlg();
    	$Bankmaster = Bankmaster::where('status','=','Active')->pluck('name','id');
        $Bankmaster->prepend('Select Bank Master' , '');        
        $filterColumn = array();
            
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));        
        $filterColumn[]=array("filter" => array("type" => "input"));        
        $filterColumn[]=array("filter" => array("type" => "input"));        
        $filterColumn[]=array("filter" => array("type" => "input"));        
        $filterColumn[]=array("filter" => array("type" => "input"));        
        $filterColumn[]=array("filter" => array("type" => "input"));        
        $filterColumn[]=array("filter" => array("type" => "input"));        
        $filterColumn[]=array("filter" => array("type" => "input"));        
        $filterColumn[]=array("filter" => array("type" => "input"));        

        return View::make('bankstatement/index',compact('Bankmaster'))->with('filterColumn', $filterColumn)->with('bankstatementExtraFlg',$bankstatementExtraFlg);	
    }

    /**
    * Return JSON response with a list of rejected bank statement details for the getIndex() view.
    *
    * @author [A. Gianotto] [<snipe@snipe.net>]
    * @since [v1.6]
    * @see RejectedbacsController::getIndex() method that consumed this JSON response
    * @return string JSON
    */
    public function getDatatable()
    {   
        $bankstatementExtraFlg = Helper::bankStatementExtraFlg();
        $params = array();
        if(Input::has('start_date') ){
            $params['start_date'] = $this->dateFormat(Input::get('start_date'),0);    
        }
        if(Input::has('end_date')){
            $params['end_date'] = $this->dateFormat(Input::get('end_date'),0);    
        }
        $result = Bankstatement::getDatatableData($params);               
        
        $rows = array();
      //  dd($result['data']);
        foreach ($result['data'] as $data) {
                // echo "<pre>";
                // print_r($data->agencybankings[0]);
                // die();
            $recoFlgHtml = '';
            $recoSattelDate = '';
            $bankingType = '';
            $bankingPan = '';
            if($data->reco_flg == 'Y')
            {
               
                if(!empty($data->agencybankings[0]))
                {
                    if($data->date > $data->agencybankings['0']->SettlementDate)
                    {
                        $recoFlgHtml = '<span class="recoYesYellow">'.e($data->reco_flg).'</span>';
                    }
                    else
                    {
                        $recoFlgHtml = '<span class="recoYes">'.e($data->reco_flg).'</span>';                        
                    }
                    $recoSattelDate = $data->agencybankings['0']->SettlementDate;
                    $bankingType = $data->agencybankings['0']->banking_type;
                    $bankingPan = $data->agencybankings['0']->Card_PAN;
                }
                else
                {
                    $recoFlgHtml = '<span class="recoYes">'.e($data->reco_flg).'</span>';
                }

            }
            else
            {
                $recoFlgHtml = '<span class="">'.e($data->reco_flg).'</span>';
            }

            $extra_flagsEdit = '';
            if(!empty($data->extra_flags))
            {
                $extraFlg = $bankstatementExtraFlg[$data->extra_flags];

                $extra_flagsEdit = '<span  data-toggle="tooltip" data-original-title="'.e($extraFlg).'" data-placement="left"> <a class="extra_flagsEdit" data-name="extra_flags" data-type = "select" data-url="'.route('setBankStatementExtraFlg').'" data-value="'.e($data->extra_flags).'" data-title="Set Flag" data-pk="'.$data->ids.'" style="display:block; white-space:nowrap; word-break:break-word;"> ' .e($extraFlg).'</a> <span>';
            }
            else
            {
                $extra_flagsEdit = '<span  data-toggle="tooltip" data-original-title="'.e($data->extra_flags).'" data-placement="left"> <a class="extra_flagsEdit" data-name="extra_flags" data-type = "select" data-url="'.route('setBankStatementExtraFlg').'" data-title="Set Flag" data-value="'.e($data->extra_flags).'" data-pk="'.$data->ids.'" style="display:block; white-space:nowrap; word-break:break-word;">-</a> <span>';
            }
            $chkBtn = "";
            if($data->reco_flg == 'N')
            {
                $chkBtn = '<input type="checkbox" name="selectchk" id="selectchk-'.$data->ids.'" class="selectchk" value="'.$data->ids.'" >';
            }
            
            $nestedData = array();            
            $nestedData['chkBtn'] = $chkBtn;
            $nestedData['reco_date'] = $recoSattelDate;
            $nestedData['name'] = $data->name;
            $nestedData['date'] = $data->date;
            $nestedData['description'] = $data->description;
            $nestedData['type'] = $data->type;
            $nestedData['debit'] = round($data->debit,2);
            $nestedData['credit'] = round($data->credit,2);
            $nestedData['bal'] = $data->bal;
            $nestedData['extra_flags'] = $extra_flagsEdit;
            $nestedData['reco_flg'] = $recoFlgHtml;
            $nestedData['created_at'] = $this->dateFormat($data->created_at,1);
            $nestedData['bankingType'] = $bankingType;
            $nestedData['bankingPan'] = $bankingPan;
            $rows[] = $nestedData;

        }

        return array('total'=>$result['count'], 'rows'=>$rows);                
    }

    public function setBankStatementExtraFlg(Request $request)
    {
        if(!empty($request->get('value')) && !empty($request->get('pk')) )
        {
            Bankstatement::where("id",$request->get('pk'))->update(["extra_flags" => $request->get('value') , "reco_flg" => "Y"]);
        }
        else if(!empty($request->get('pk')) && empty($request->get('value')))
        {
            Bankstatement::where("id",$request->get('pk'))->update(["extra_flags" => NULL , "reco_flg" => "N"]);   
        }
        return json_encode(array("success" => "success"));
    }

    /**
    * Return user import view
    *
    * @author [A. Gianotto] [<snipe@snipe.net>]
    * @since [v1.0]
    * @return View
    */
    public function getImport()
    {
        $bankList = Bankmaster::where('status','Active')->pluck('name', 'id')->toArray();
        $bankName = '';
        if(!empty($bankList))
        {
            $bankName = current($bankList);
        }
        return View::make('bankstatement/import', compact('bankList','bankName'));
    }

    /**
    * Handle user import file
    *
    * @author [A. Gianotto] [<snipe@snipe.net>]
    * @since [v1.0]
    * @return Redirect
    */
    // public function postImport(Request $request)
    // {
    //         $validator = Validator::make($request->all(), [
    //             'user_import_csv' => 'required',
    //             'bank_master_id' => 'required',         
    //         ]);

    //         if ($validator->fails()) {
    //             $error = $validator->errors()->first();            
    //             return redirect()->to("bankstatement/import")->with("error",'The statement file field is required.');                
    //         }

    //         $file = Input::file('user_import_csv');
    //         $fixcol=24;
    //         $path = config('app.private_uploads').'/bankstmt';

    //         $results = array();
    //         $fileType = $file->getMimeType();
    //             if (!in_array($file->getMimeType(), array(
    //                 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    //                 'application/vnd.ms-excel',
    //                 'application/vnd.ms-office',
    //                 'text/csv',
    //                 'text/plain',
    //                 'text/comma-separated-values',
    //                 'text/tsv'))) {
                    
    //                 return redirect()->to("bankstatement/import")->with("error",'File type must be Excel');  
    //             }

    //             $date = date('Y-m-d-his');
    //             $fixed_filename = str_replace(' ', '-', $file->getClientOriginalName());
    //             $fileData=array();
    //             $fileData = explode(".", $fixed_filename);
    //             $startAfer = 1;


    //             if($fileData[1] == 'csv' || $fileData[1] == 'CSV' || $fileType == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
    //               $startAfer = 0;

    //             $filePath = $path."/".$date.'-'.$fixed_filename;


    //             try {
    //                 $file->move($path, $date.'-'.$fixed_filename);

    //                 $this->fileImportHistoryLog($date.'-'.$fixed_filename,$path,"Bankstatement",Auth::user()->id);


    //                 $csvfilename="bankStmt-".date('Y-m-d-his').".csv";
    //                 $path1 = storage_path();
    //                 $path1  = str_replace('storage', '', $path1);

    //                 $path1 = $path1.'public/uploads/bankstmt';
    //                 $csvfile = fopen($path1."/".$csvfilename, 'w');
                    
    //                 $Reader = Excel::load($filePath)->noHeading()->all()->toArray();
                    
    //                 // $Reader = new SpreadsheetReader($filePath );
    //                 // $Reader -> ChangeSheet(0);
    //                 $headers=[
    //                     // strtolower to prevent Excel from trying to open it as a SYLK file
    //                    // strtolower(trans('general.id')),
    //                     "Date",
    //                     "Description",
    //                     "Type",
    //                     "Debit",
    //                     "Credit",
    //                     "Balance",
    //                     "Status",
    //                 ];
    //                 if(!empty($Reader))
    //                 {
    //                     fputcsv($csvfile, $headers);
    //                 }
    //                 $formData = Helper::bankFormatArray(Input::get('bankName'));
    //                 $dateStrCmp = $formData['date'];
    //                 $arrayTemp=array();
    //                 $temp=false;
    //                 $arrayTemp2= array();

    //                 if(is_array($Reader[0][0]))
    //                 {
    //                     $Reader = $Reader[0];
    //                 }

    //                 foreach ($Reader as $key => $value) {
                    
    //                     if($temp==false)
    //                     {   
    //                         $datecheck=0;
    //                         $balance=0;
    //                         foreach ($value as $key1 => $value1) {
    //                                 strchr($value1,$dateStrCmp)?$datecheck=1:'';
                                                 
    //                             //strchr($value1,'Bal')?$balance=1:'';                  
    //                             if($datecheck==1)
    //                             {
    //                                 for($i=0;$i<count($value);$i++)
    //                                 {
    //                                     if(empty($value[$i]))
    //                                         $value[$i]='temp';
    //                                     $arrayTemp[$i]=$value[$i];
    //                                 }
    //                                 $temp=true;
    //                             }
    //                         }
    //                     }   
    //                     if($temp==true){
    //                             $arr_empty = true;
    //                             foreach ($value as $arr) {
    //                                 $arr=trim($arr);
    //                                 !empty($arr)?$arr_empty = false:'';
    //                             }
    //                             if($arr_empty==false)
    //                             {
    //                                 for($i=0;$i<count($value);$i++)
    //                                 {
    //                                     $arrayTemp2[$arrayTemp[$i]][]=$value[$i];
    //                                 }
    //                             }else{
    //                             goto a;
    //                             }                       
    //                         }
    //                 }
    //                 a:

    //                 unset($arrayTemp2['temp']);
    //                 $fields=array();
    //                 foreach ($arrayTemp2 as $key => $value) {
    //                     $fields[]=$key; 
    //                     $i=0;
    //                     foreach ($value as $key1 => $value1) {
    //                     $tempFinal[$i][$key]=$value1;
    //                     $i++;
    //                     }
                    
    //                 }
    //                 foreach ($tempFinal as $key => $value) {
    //                     $arr_empty = true;
    //                         foreach ($value as $arr) {
    //                             $arr=trim($arr);
    //                             if (!empty($arr)) {
    //                                 $arr_empty = false;
    //                             }
    //                         }
    //                         if($arr_empty==true)
    //                         {
    //                             unset($tempFinal[$key]);
    //                         }
    //                 }

    //                 $actualData = array_values($tempFinal);
    //                 $formData = Helper::bankFormatArray(Input::get('bankName'));
    //                 $statementArray = array();
    //                 if(!empty($formData) && !empty($actualData))
    //                 {
    //                     foreach ($actualData as $key0 => $value) {
                    
    //                         foreach ($value as $key => $value1) {
                                
    //                             if($key==$formData['date'] && $formData['date']!='')
    //                                 $statementArray[$key0]['date']=$value1;

    //                             else if($key==$formData['description'] && $formData['description']!='')
    //                                 $statementArray[$key0]['description']=$value1;

    //                             else if($key==$formData['bnkType'] && $formData['bnkType']!='')
    //                                 $statementArray[$key0]['bnkType']=$value1;

    //                             else if($key==$formData['Amount'] && $formData['Amount']!='')
    //                                 $statementArray[$key0]['bal']=$value1;

    //                             if($formData['type']=='DEBIT/CREDIT')
    //                             {
    //                                 if($key==$formData['Debit'] && $formData['Debit']!='')
    //                                 {
    //                                     if($value1!='')
    //                                         $statementArray[$key0]['Debit']=$value1;
    //                                     else
    //                                         $statementArray[$key0]['Debit']='';
    //                                 }
    //                                 if($key==$formData['Credit'] && $formData['Credit']!='')
    //                                 {
    //                                     if($value1!='')
    //                                         $statementArray[$key0]['Credit']=$value1;
    //                                     else
    //                                         $statementArray[$key0]['Credit']='';
    //                                 }
    //                             }else if($formData['type']=='DR|CR')
    //                             {
    //                                 if($key==$formData['DR|CR'] && $formData['DR|CR']!='')
    //                                 {
    //                                     if(trim($key)=='DR' || trim($key)=='dr' || trim($key)=='Dr')
    //                                     {
    //                                         $statementArray[$key0]['Debit']=trim($value[$formData['Amount']]);
    //                                         $statementArray[$key0]['Credit']='';
    //                                     }
    //                                     else if (trim($key)=='CR' || trim($key)=='cr' || trim($key)=='Cr') {
    //                                         $statementArray[$key0]['Debit']='';
    //                                         $statementArray[$key0]['Credit']=trim($value[$formData['Amount']]);
    //                                     }   
    //                                 }
    //                             }   
    //                         }
    //                     }
    //                 }

    //                 $DataInsertedFlg = array();
    //                 foreach ($statementArray as $key => $value) {
    //                     $resultArray = array();
    //                     if($key != 0)
    //                     {
    //                         $resultArray = $value;
    //                         if(Input::get('bank_master_id') != '' && $value['date']!='' && $value['description'] != '')
    //                         {

    //                             $bnkDate = date('Y-m-d',strtotime(str_replace("/",'-',$value['date'])));
                                
    //                             // if(!isset($DataInsertedFlg[$bnkDate]))
    //                             // {
    //                             //     $bankStmtData = Bankstatement::where('date',$bnkDate)->first();
    //                             //     if(!empty($bankStmtData))
    //                             //         $DataInsertedFlg[$bnkDate] = 1;
    //                             //     else
    //                             //         $DataInsertedFlg[$bnkDate] = 0;
    //                             // }  

    //                             $bnkStmtObj = new Bankstatement();
    //                             $uniqueId = Helper::generateUniqueId();
    //                             $bnkStmtObj->id = $uniqueId;
    //                             $bnkStmtObj->bank_master_id = Input::get('bank_master_id');
    //                             $bnkStmtObj->date = $bnkDate;
    //                             $bnkStmtObj->description = $value['description'];
                                
    //                             if(!empty($value['Debit']))
    //                             {
    //                                 $bnkStmtObj->debit = $value['Debit'];
    //                             }

    //                             if(!empty($value['Credit']))
    //                             {
    //                                 $bnkStmtObj->credit = $value['Credit'];
    //                             }
    //                             $bnkStmtObj->bal = $value['bal'];
    //                             $bnkStmtObj->type = $value['bnkType'];

    //                             if($bnkStmtObj->save())
    //                             {
    //                                 $resultArray['status'] = "success";
    //                             }
    //                             else
    //                             {
    //                                 $resultArray['status'] = "error";
    //                             }

    //                         }
    //                         else
    //                         {
    //                             $resultArray['status'] = "Required fields error.";
    //                         }
    //                     }
    //                     fputcsv($csvfile, $resultArray); 
    //                 }
                    
    //                 fclose($csvfile); 

    //             } catch (\Symfony\Component\HttpFoundation\File\Exception\FileException $exception) {
    //                 $results['error']=trans('admin/bankstmt/message.upload.error');
    //                 if (config('app.debug')) {
    //                     $results['error'].= ' ' . $exception->getMessage();
    //                 }
    //                 return $results;
    //             }
    //             $response = array();
    //        $response['filePath'] = config('app.url').'/uploads/bankstmt/'.$csvfilename;
    //        $response['fileName'] = $csvfilename;
         

    //        $headers = array(
    //     'Content-Type' => 'text/csv',
    //     );

    //     return Response::download($path1."/".$csvfilename,$csvfilename, $headers)->deleteFileAfterSend(true);
    
    // }

    public function duplicationCheck(Request $request)
    {
        $responseData = array();
       $validator = Validator::make($request->all(), [
            'user_import_csv' => 'required',
            'bank_master_id' => 'required',         
        ]);

        if ($validator->fails()) {
            $responseData['code'] = "error";
            $responseData['msg'] = "The statement file field is required.";

            echo json_encode($responseData);
            exit;
        }

        $file = Input::file('user_import_csv');
        $fixcol=24;
        $path = config('app.private_uploads').'/bankstmt';

        $results = array();
        $fileType = $file->getMimeType();
        if (!in_array($file->getMimeType(), array(
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-excel',
            'application/vnd.ms-office',
            'text/csv',
            'text/plain',
            'text/comma-separated-values',
            'text/tsv'))) {
            
            $responseData['code'] = "error";
            $responseData['msg'] = "File type must be Excel";

            echo json_encode($responseData);
            exit;

        }

        $date = date('Y-m-d-his');
        $fixed_filename = str_replace(' ', '-', $file->getClientOriginalName());
        $fileData=array();
        $fileData = explode(".", $fixed_filename);
        $startAfer = 1;


        if($fileData[1] == 'csv' || $fileData[1] == 'CSV' || $fileType == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
          $startAfer = 0;

        $filePath = $path."/".$date.'-'.$fixed_filename;
        try {
                $file->move($path, $date.'-'.$fixed_filename);

                $this->fileImportHistoryLog($date.'-'.$fixed_filename,$path,"Bankstatement",Auth::user()->id);

                $Reader = Excel::load($filePath)->noHeading()->all()->toArray();
                
                $formData = Helper::bankFormatArray(Input::get('bankName'));
                $dateStrCmp = $formData['date'];
                $arrayTemp=array();
                $temp=false;
                $arrayTemp2= array();

                if(is_array($Reader[0][0]))
                {
                    $Reader = $Reader[0];
                }
                foreach ($Reader as $key => $value) {
                    
                    if($temp==false)
                    {   
                        $datecheck=0;
                        $balance=0;
                        foreach ($value as $key1 => $value1) {
                                strchr($value1,$dateStrCmp)?$datecheck=1:'';
                                             
                            //strchr($value1,'Bal')?$balance=1:'';                  
                            if($datecheck==1)
                            {
                                for($i=0;$i<count($value);$i++)
                                {
                                    if(empty($value[$i]))
                                        $value[$i]='temp';
                                    $arrayTemp[$i]=$value[$i];
                                }
                                $temp=true;
                            }
                        }
                    }   
                    if($temp==true){
                            $arr_empty = true;
                            foreach ($value as $arr) {
                                $arr=trim($arr);
                                !empty($arr)?$arr_empty = false:'';
                            }
                            if($arr_empty==false)
                            {
                                for($i=0;$i<count($value);$i++)
                                {
                                    $arrayTemp2[$arrayTemp[$i]][]=$value[$i];
                                }
                            }else{
                            goto a;
                            }                       
                        }
                    }
                    a:

                    unset($arrayTemp2['temp']);
                    $fields=array();
                    foreach ($arrayTemp2 as $key => $value) {
                        $fields[]=$key; 
                        $i=0;
                        foreach ($value as $key1 => $value1) {
                        $tempFinal[$i][$key]=$value1;
                        $i++;
                        }
                    
                    }
                    foreach ($tempFinal as $key => $value) {
                        $arr_empty = true;
                            foreach ($value as $arr) {
                                $arr=trim($arr);
                                if (!empty($arr)) {
                                    $arr_empty = false;
                                }
                            }
                            if($arr_empty==true)
                            {
                                unset($tempFinal[$key]);
                            }
                    }

                    $actualData = array_values($tempFinal);
                    $formData = Helper::bankFormatArray(Input::get('bankName'));
                    $statementArray = array();
                    $uniQueDateArray = array();

                    if(!empty($formData) && !empty($actualData))
                    {
                        foreach ($actualData as $key0 => $value) {
                    
                            foreach ($value as $key => $value1) {
                                
                                if($key==$formData['date'] && $formData['date']!='')
                                {
                                    if($key0 != 0 && !empty($value1))
                                        $uniQueDateArray[date('Y-m-d',strtotime(str_replace("/",'-',$value1)))] = date('Y-m-d',strtotime(str_replace("/",'-',$value1)));
                                    
                                    $statementArray[$key0]['date']=$value1;
                                }

                                else if($key==$formData['description'] && $formData['description']!='')
                                    $statementArray[$key0]['description']=$value1;

                                else if($key==$formData['bnkType'] && $formData['bnkType']!='')
                                    $statementArray[$key0]['bnkType']=$value1;

                                else if($key==$formData['Amount'] && $formData['Amount']!='')
                                    $statementArray[$key0]['bal']=$value1;

                                if($formData['type']=='DEBIT/CREDIT')
                                {
                                    if($key==$formData['Debit'] && $formData['Debit']!='')
                                    {
                                        if($value1!='')
                                            $statementArray[$key0]['Debit']=$value1;
                                        else
                                            $statementArray[$key0]['Debit']='';
                                    }
                                    if($key==$formData['Credit'] && $formData['Credit']!='')
                                    {
                                        if($value1!='')
                                            $statementArray[$key0]['Credit']=$value1;
                                        else
                                            $statementArray[$key0]['Credit']='';
                                    }
                                }else if($formData['type']=='DR|CR')
                                {
                                    if($key==$formData['DR|CR'] && $formData['DR|CR']!='')
                                    {
                                        if(trim($key)=='DR' || trim($key)=='dr' || trim($key)=='Dr')
                                        {
                                            $statementArray[$key0]['Debit']=trim($value[$formData['Amount']]);
                                            $statementArray[$key0]['Credit']='';
                                        }
                                        else if (trim($key)=='CR' || trim($key)=='cr' || trim($key)=='Cr') {
                                            $statementArray[$key0]['Debit']='';
                                            $statementArray[$key0]['Credit']=trim($value[$formData['Amount']]);
                                        }   
                                    }
                                }   
                            }
                        }
                    }

                    $dupCheckBstDate = array();
                    $dataStoreFlg = "nodata"; 
                    $appendFlg = false;
                    $overWriteFlg = false;
                    foreach ($uniQueDateArray as $key => $value) {
                       $bankStmtData = Bankstatement::join("txn_mapping_int","txn_mapping_int.bank_statement_id","=","bank_statement.id")->where("bank_statement.date",$value)->first();
                       if(!empty($bankStmtData))
                       {
                            $dupCheckBstDate[$value] = $value." - Record already exists and reconciled.";
                            $appendFlg = true;
                       }
                       else
                       {
                            $bankStmtData1 = Bankstatement::where("bank_statement.date",$value)->first();
                            if(!empty($bankStmtData1))
                            {
                                $dupCheckBstDate[$value] = $value." - Record already exists.";
                                $overWriteFlg = true;
                            }
                            else
                            {
                                $dupCheckBstDate[$value] = $value." - New Entry.";
                            }
                       }

                    }

                    if($appendFlg)
                    {
                        $dataStoreFlg = "append";
                    }
                    else if($overWriteFlg)
                    {
                        $dataStoreFlg = "overwrite";
                    }
                    else
                    {
                        $dataStoreFlg = "new";
                    }

                    $request->session()->put('statementArray', json_encode($statementArray));
                    $request->session()->put('uniQueDateArray', json_encode($uniQueDateArray));
                    $request->session()->save();
                    $responseData['code'] = "success";
                    $responseData['msg'] = "";
                    $responseData['dupCheckBstDate'] = $dupCheckBstDate;
                    $responseData['dataStoreFlg'] = $dataStoreFlg;

                    echo json_encode($responseData);
                    exit;

        }
        catch (\Symfony\Component\HttpFoundation\File\Exception\FileException $exception) {
            $results['error']=trans('admin/bankstmt/message.upload.error');
            if (config('app.debug')) {
                $results['error'].= ' ' . $exception->getMessage();
            }
            return $results;
        }

    }

    public function submitbstdata(Request $request)
    {
        $responseArray = array();
        $statementArray = array();
        $statementArray = $request->session()->get('statementArray');
        $statementArray = json_decode($statementArray ,true);

        if(!empty($statementArray) && !empty($request->get('bank_master_id')) && !empty($request->get('bstprocess')))
        {
            $csvfilename="bankStmt-".date('Y-m-d-his').".csv";
            $path1 = storage_path();
            $path1  = str_replace('storage', '', $path1);

            $path1 = $path1.'public/uploads/bankstmt';
            $csvfile = fopen($path1."/".$csvfilename, 'w');
            $headers=[
                        // strtolower to prevent Excel from trying to open it as a SYLK file
                       // strtolower(trans('general.id')),
                        "Date",
                        "Description",
                        "Type",
                        "Debit",
                        "Credit",
                        "Balance",
                        "Status",
                    ];
            if(!empty($statementArray))
            {
                fputcsv($csvfile, $headers);
            }

            if($request->get('bstprocess') == "overwrite")
            {
                $uniQueDateArray = $request->session()->get('uniQueDateArray');
                $uniQueDateArray = json_decode($uniQueDateArray ,true);
                $uniQueDateArray = array_values($uniQueDateArray);

                Bankstatement::whereIn("date",$uniQueDateArray)->delete();
            }

            $DataInsertedFlg = array();
            foreach ($statementArray as $key => $value) {
                $resultArray = array();
                if($key != 0)
                {
                    $resultArray = $value;
                    
                    if(is_array($value['date']))
                        $resultArray['date'] = $value['date']['date'];

                    if($request->get('bank_master_id') != '' && $value['date']!='' && $value['description'] != '')
                    {
                        if(is_array($value['date']))
                            $bnkDate = date('Y-m-d',strtotime(str_replace("/",'-',$value['date']['date'])));
                        else
                            $bnkDate = date('Y-m-d',strtotime(str_replace("/",'-',$value['date'])));
                        
                        // if(!isset($DataInsertedFlg[$bnkDate]))
                        // {
                        //     $bankStmtData = Bankstatement::where('date',$bnkDate)->first();
                        //     if(!empty($bankStmtData))
                        //         $DataInsertedFlg[$bnkDate] = 1;
                        //     else
                        //         $DataInsertedFlg[$bnkDate] = 0;
                        // }  

                        $bnkStmtObj = new Bankstatement();
                        $uniqueId = Helper::generateUniqueId();
                        $bnkStmtObj->id = $uniqueId;
                        $bnkStmtObj->bank_master_id = $request->get('bank_master_id');
                        $bnkStmtObj->date = $bnkDate;
                        $bnkStmtObj->description = $value['description'];
                        
                        if(!empty($value['Debit']))
                        {
                            $bnkStmtObj->debit = $value['Debit'];
                        }

                        if(!empty($value['Credit']))
                        {
                            $bnkStmtObj->credit = $value['Credit'];
                        }
                        $bnkStmtObj->bal = $value['bal'];
                        $bnkStmtObj->type = $value['bnkType'];

                        if($bnkStmtObj->save())
                        {
                            $resultArray['status'] = "success";
                        }
                        else
                        {
                            $resultArray['status'] = "error";
                        }

                    }
                    else
                    {
                        $resultArray['status'] = "Required fields error.";
                    }
                }
                fputcsv($csvfile, $resultArray); 
            }
            
            fclose($csvfile); 

            $responseArray['code'] = 'success';
            $responseArray['msg'] = 'BstInserted Successfully';
            $responseArray['path'] = config('app.url').'/uploads/bankstmt/'.$csvfilename;

            echo json_encode($responseArray);
            exit;

        }
        else
        {
            $responseArray['code'] = 'error';
            $responseArray['msg'] = 'Something wromg please try again.';

            echo json_encode($responseArray);
            exit;
        }

    }

    public function setbstflag(Request $request)
    {
        if(!empty($request->get('bstIds')) && !empty($request->get('extra_flg')))
        {
            Bankstatement::whereIn("id",$request->get('bstIds'))->update(["extra_flags"=>$request->get('extra_flg'),"reco_flg"=>"Y"]);
        }
        echo "success";
        exit;
    }
}
