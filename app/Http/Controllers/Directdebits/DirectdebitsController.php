<?php
namespace App\Http\Controllers\Directdebits;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Models\Directdebits;
use DB;
use Input;
use View;
use Carbon\Carbon;
use Validator;
use Auth;
use App\Models\Fpoutfilesupload;
use App\Models\Fpout;
use App\Models\Agencybanking;
use App\Models\Advicefilesupload;
use App\Models\Advice;
use Excel;
use Redirect;
use Response;
/**
 * This controller handles all actions related to Direct Debits for
 * the Parextech Asset Management application.
 *
 * @version    v1.0
 */

class DirectdebitsController extends Controller
{

    /**
    * Returns a view that invokes the ajax tables which actually contains
    * the content for the direct debit listing, which is generated in getDatatable().
    *
    * @author [A. Gianotto] [<snipe@snipe.net>]
    * @see DirectdebitsController::getDatatable() method that generates the JSON response
    * @since [v1.0]
    * @return View
    */
    public function getIndex()
    {
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

    	$filterColumn = array();
       
        // $filterColumn[]=array("filter" => array("type" => "input"));
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

        return View::make('directdebits/index')->with('filterColumn', $filterColumn);
    }

    /**
    * Return JSON response with a list of direct debit details for the getIndex() view.
    *
    * @author [A. Gianotto] [<snipe@snipe.net>]
    * @since [v1.6]
    * @see DirectdebitsController::getIndex() method that consumed this JSON response
    * @return string JSON
    */
    public function getDatatable(Request $request, $status = null)
    {
        $params = array();
        if(Input::has('start_date') ){
            $params['start_date'] = $this->dateFormat(Input::get('start_date'),0);    
        }
        if(Input::has('end_date')){
            $params['end_date'] = $this->dateFormat(Input::get('end_date'),0);    
        }

        $result = Directdebits::getDatatableData($params);
        
        $rows = array();
        foreach ($result['data'] as $directdebit) {
            $actions = '<nobr>';
                
            // if (Gate::allows('users.edit')) {
                $actions .= '<a href="' . route('update/user',
                        $directdebit->id) . '" class="btn btn-warning btn-sm" data-original-title="Edit User" data-tooltip="tooltip"><i class="fa fa-pencil icon-white"></i></a> ';
                
            // }

            // if (Gate::allows('users.delete')) {
                
                $actions .= '<a data-html="false" class="btn delete-asset btn-danger btn-sm" data-toggle="modal" href="' . route('delete/user',
                            $directdebit->id) . '" data-content="Are you sure you wish to delete direct debit entry?" data-title="Delete ' . htmlspecialchars($directdebit->SUN.'-'.$directdebit->Sun_Name) . '?" onClick="return false;" data-original-title="Delete Direct Debit" data-tooltip="tooltip"><i class="fa fa-trash icon-white"></i></a> ';                
            // } else {
            //     $actions.='';
            // }

            $actions .= '</nobr>';

            $rows[] = array(
                'id'    => $directdebit->id,                
                'Processing_Date'   => e($directdebit->Processing_Date),
                'Due_Date'  => e($directdebit->Due_Date),
                'SUN'   => e($directdebit->SUN),
                'Sun_Name'  => e($directdebit->Sun_Name),
                'Trans_Code'    => e($directdebit->Trans_Code),
                'DReference'    => e($directdebit->DReference),
                'diban' => e($directdebit->diban),
                'status'    => e($directdebit->status),
                'amount'    => round(e($directdebit->amount),2),
                'Token_Number'  => e($directdebit->Token_Number),
                'actions'   => ($actions) ? $actions : '',                                                
            );
        }

        return array('total'=>$result['count'], 'rows'=>$rows);                
    }

    /**
    * Returns a view that displays the direct debit creation form.
    *
    * @author [A. Gianotto] [<snipe@snipe.net>]
    * @since [v1.0]
    * @return View
    */
    public function getCreate()
    {
        return View::make('directdebits/edit')
            ->with('Directdebits', new Directdebits)
        ;
    }

    /**
    * Delete a direct debit
    *
    * @author [A. Gianotto] [<snipe@snipe.net>]
    * @since [v1.0]
    * @param  int  $id
    * @return Redirect
    */
    public function getDelete($id = null)
    {
        try {
            // Get Direct Debit information
            $directdebit = Directdebits::find($id);
            $directdebit->delete();
            $success = trans('admin/directdebits/message.success.delete');
            return redirect()->route('users')->with('success', $success);

        } catch (UserNotFoundException $e) {
            return redirect()->route('directdebits')->with('error', trans('admin/directdebits/message.direct_debit_not_found', compact('id')));
        }
    }

    /**
    * Return direct debit import view
    *
    * @author [A. Gianotto] [<snipe@snipe.net>]
    * @since [v1.0]
    * @return View
    */
    public function getImport()
    {
        return View::make('directdebits/import');
    }

    /**
    * Handle direct debit import file
    *
    * @author [A. Gianotto] [<snipe@snipe.net>]
    * @since [v1.0]
    * @return Redirect
    */
    public function postImport(Request $request)
    {                        
        $validator = Validator::make($request->all(), [
            // 'direct_debits_file.*' => 'required|mimes:txt,xml,html,csv',         
            'importType' => 'required',         
        ]);
        if ($validator->fails()) {            
            $error = $validator->errors()->first();            
            return redirect()->to("directdebits/import")->with("error",$error);                
        }
        if($request->get('importType') == "dd")
        {
            $this->importDDFile($request);
        }
        else if($request->get('importType') == "fpout")
        {
            $this->importFpoutFile($request);
        }
        else if($request->get('importType') == "adv")
        {
            $this->importAdvFile($request);
        }
        else
        {
            return redirect()->to("directdebits/import")->with("error","Something wrong please try again");
        }
        
        return redirect()->to("directdebits/import");
    }

    private function importAdvFile($request)
    {
        $files = Input::file('direct_debits_file');
        
        foreach ($files as $key => $file) 
        {
            $path = config('app.private_uploads').'/advice';

            $results = array();
            $date = date('Y-m-d-his');
            
            $fixed_filename =  $file->getClientOriginalName();
            
            $filePath = $path."/".$date.'-'.$fixed_filename.".csv";

            try {
                $file_date = date("Y-m-d");
                $fileName_data = explode("_", $fixed_filename);
                if(strpos($fileName_data[1], 'D') !== false)
                {
                    $fileDate = str_replace("D", "", $fileName_data[1]);
                    $file_date = date("Y-m-d",strtotime($fileDate));
                }

                $file->move($path, $date.'-'.$fixed_filename.".csv");

                $this->fileImportHistoryLog($date.'-'.$fixed_filename.".csv",$path,"Advice",Auth::user()->id);

                $Reader = Excel::load($filePath)->noHeading()->all()->toArray();

                foreach ($Reader as $key => $value)
                {
                    if(!empty($value[3]) && (int)$value[7] != 0 && ($value[3] == "99" || $value[3] == "44"))
                    {
                        $adviceObj = new Advice();
                        $adviceObj->id = Helper::generateUniqueId();
                        $adviceObj->ab_sort_code = $value[0];
                        $adviceObj->ab_account_number = $value[1];
                        $adviceObj->code = $value[3];
                        $adviceObj->ext_bank_sort_code = $value[4];
                        $adviceObj->ext_bank_acc_number = $value[5];
                        $adviceObj->amount_in_cent = (int) $value[7];
                        $adviceObj->actual_amount = ($adviceObj->amount_in_cent / 100);
                        $adviceObj->ext_name = $value[8];
                        $adviceObj->file_date = $file_date;
                        $adviceObj->file_name = $fixed_filename;

                        $adviceObj->C = $value[2];
                        $adviceObj->A = $value[6];
                        $adviceObj->ref = $value[9];
                        $adviceObj->ab_name = $value[10];
                        $adviceObj->advice_number = $value[11];
                        $adviceObj->X = $value[12];
                        $adviceObj->Y = $value[13];
                        $adviceObj->Z = $value[14];

                        if($value[3] == "99")
                        {
                            $AgencybankingObj = new Agencybanking();

                            if($adviceObj->X == "S" || $adviceObj->X == "s")
                                $abData = $AgencybankingObj->fetchReturnABDataForAdvice($adviceObj);
                            else
                                $abData = $AgencybankingObj->fetchABDataForAdvice($adviceObj);

                            if(!empty($abData))
                            {
                                $adviceObj->related_table_id = $abData->ids;
                                if($abData->banking_type == "Approved")
                                    $adviceObj->type = "abapproved";
                                else    
                                    $adviceObj->type = "abdeclined";

                                $abData->reco_flg = "A";

                                $abData->save();
                            }
                        }

                        if($value[3] == "44")
                        {
                            $directDebitsObj = new Directdebits();

                            $ddData = $directDebitsObj->fetchDDDataForAdvice($adviceObj);
                            if(count($ddData) != 0)
                            {
                                $relatedId = '';

                                foreach ($ddData as $key1 => $value1) 
                                {
                                    $relatedId = $relatedId.$value1->ids.",";
                                }
                                $adviceObj->related_table_id = $relatedId;
                                $adviceObj->type = "dd";
                            }
                        }

                        if(!isset($adviceObj->type))
                            $adviceObj->type = "bacs";

                        $adviceObj->save();
                    }
                    
                }

            } catch (\Symfony\Component\HttpFoundation\File\Exception\FileException $exception) {
                $results['error']=trans('admin/directdebits/message.upload.error');
                if (config('app.debug')) {
                    $results['error'].= ' ' . $exception->getMessage();
                }
                return $results;
            }
        }
        return redirect()->to("directdebits/import")->with("success","Advice file imported successfully.");
    }

    private function importFpoutFile($request)
    {
        $files = Input::file('direct_debits_file');

        foreach ($files as $key => $file) 
        {
            $path = config('app.private_uploads').'/fpout';
            $results = array();
            $date = date('Y-m-d-his');
            
            $fixed_filename =  $file->getClientOriginalName();
            
            $filePath = $path."/".$date.'-'.$fixed_filename;

            try {
                 
                $file->move($path, $date.'-'.$fixed_filename);
                $this->fileImportHistoryLog($date.'-'.$fixed_filename,$path,"Fpout",Auth::user()->id);

                $z = new \XMLReader;
                $z->open($filePath);
                $doc = new \DOMDocument;
                $dataArray = array();
                $flg = 0;
                $i = 0;
                while ($z->read())
                {
                    $dataArray1 = $this->fpoutXmlToArrayParser1($doc , $z , $flg , $dataArray , $i);
                    if($flg == 2)
                    {
                        $dataArray1['di:FPID'] = trim($dataArray1['di:FPID']);

                        $fpOutDataExixst = Fpout::where("FPID",$dataArray1['di:FPID'])->first();
                       
                        if(!empty($fpOutDataExixst))
                        {
                            $fpOutDataExixst->FileID = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $dataArray1['di:FileID'])));
                            $fpOutDataExixst->file_date = $dataArray1['di:Date'];
                            $fpOutDataExixst->FPID = $dataArray1['di:FPID'];
                            $fpOutDataExixst->OrigCustomerSortCode = $dataArray1['di:OrigCustomerSortCode'];
                            $fpOutDataExixst->OrigCustomerAccountNumber = $dataArray1['di:OrigCustomerAccountNumber'];
                            $fpOutDataExixst->BeneficiaryCreditInstitution = $dataArray1['di:BeneficiaryCreditInstitution'];
                            $fpOutDataExixst->BeneficiaryCustomerAccountNumber = $dataArray1['di:BeneficiaryCustomerAccountNumber'];
                            $fpOutDataExixst->Amount = $dataArray1['di:Amount'];
                            $fpOutDataExixst->Accepted = $dataArray1['di:Accepted'];
                            $fpOutDataExixst->ProcessedAsynchronously = $dataArray1['di:ProcessedAsynchronously'];
                            $fpOutDataExixst->ReferenceInformation = @$dataArray1['di:ReferenceInformation'];
                            $fpOutDataExixst->OrigCustomerAccountName = $dataArray1['di:OrigCustomerAccountName'];

                            $fpOutDataExixst->ReportTitle = @$dataArray1['di:ReportTitle'];
                            $fpOutDataExixst->CorporateID = @$dataArray1['di:CorporateID'];
                            $fpOutDataExixst->SubmissionID = @$dataArray1['di:SubmissionID'];

                            $fpOutDataExixst->FPSDocumentTitle = @$dataArray1['di:FPSDocumentTitle'];
                            $fpOutDataExixst->FPSDocumentcreated = @$dataArray1['di:FPSDocumentcreated'];
                            $fpOutDataExixst->FPSDocumentschemaVersion = @$dataArray1['di:FPSDocumentschemaVersion'];
                            $fpOutDataExixst->SubmissionStatus = @$dataArray1['di:SubmissionStatus'];
                            $fpOutDataExixst->Currency = @$dataArray1['di:Currency'];
                            $fpOutDataExixst->FileStatus = @$dataArray1['di:FileStatus'];
                            $fpOutDataExixst->OutwardAcceptedVolume = @$dataArray1['di:OutwardAcceptedVolume'];
                            $fpOutDataExixst->OutwardAcceptedValue = @$dataArray1['di:OutwardAcceptedValue'];
                            $fpOutDataExixst->OutwardAcceptedValueCur = @$dataArray1['di:OutwardAcceptedValueCur'];
                            $fpOutDataExixst->OutwardRejectedVolume = @$dataArray1['di:OutwardRejectedVolume'];
                            $fpOutDataExixst->OutwardRejectedValue = @$dataArray1['di:OutwardRejectedValue'];
                            $fpOutDataExixst->OutwardRejectedValueCur = @$dataArray1['di:OutwardRejectedValueCur'];
                            $fpOutDataExixst->Time = @$dataArray1['di:Time'];
                            
                            $fpOutDataExixst->file_name = $fixed_filename;
                          
                            $fpOutDataExixst->save();
                          /*
                          * if record is  there and there is no agencybanking_id than you have to create a new agencybanking object
                          *and save a new record in agencybanking
                          * a new record in agencybanking is saved only and only if there is no agencybanking_id in case of fpout record alreay exists
                          */
                            if(empty($fpOutDataExixst->agencybanking_Id))
                            {
                               
                                     $AgencybankingObj = new Agencybanking();

                                     /*It is "Approved case" when the refrenceinformation in fpouts data xml file is not "RTN" or "rtn"
                                       in case of "Approved case" data entered with reco_flag "F"
                                       in case of "Declined case" data entered with fpout_dec_reco_flag "Y"
                                     */
        
                                if($fpOutDataExixst->ReferenceInformation != 'RTN' && $fpOutDataExixst->ReferenceInformation != 'rtn' )
                                {
                                    $abData = $AgencybankingObj->fetchABDataForFPOut($fpOutDataExixst,"Approved");
                                  
                                    if(!empty($abData))
                                    {
                                        $fpOutDataExixst->agencybanking_Id = $abData->ids;
                                        $fpOutDataExixst->ab_type = $abData->banking_type;
                                        $abData->reco_flg = "F";
                                        $abData->fp_out_date = $fpOutDataExixst->file_date;
                                        $abData->save();
                                        $fpOutDataExixst->save();
                                    }
                                }
                                else
                                {   
                                    $abData = $AgencybankingObj->fetchABDDataForFPOut($fpOutDataExixst,"Declined");
                                  
                                    if(!empty($abData))
                                    {
                                     
                                        $fpOutDataExixst->agencybanking_Id = $abData->ids;
                                        $fpOutDataExixst->ab_type = $abData->banking_type;

                                        $abData->fpout_dec_reco_flag = "Y";
                                        $abData->fp_out_date = $fpOutDataExixst->file_date;

                                        $abData->save();
                                        $fpOutDataExixst->save();
                                    }
                                }
                            }
                        }
                        else
                        {
                            /*
                            *Here any record will be saved with new record in fpout and agencybanking and while saving the record in fpout table
                            *may be agencybanking_Id in fpout will saved as null
                            */
                            $obj = new Fpout;
                            $obj->id = Helper::generateUniqueId();
                            $obj->FileID = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $dataArray1['di:FileID'])));
                            $obj->file_date = $dataArray1['di:Date'];
                            $obj->FPID = $dataArray1['di:FPID'];
                            $obj->OrigCustomerSortCode = $dataArray1['di:OrigCustomerSortCode'];
                            $obj->OrigCustomerAccountNumber = $dataArray1['di:OrigCustomerAccountNumber'];
                            $obj->BeneficiaryCreditInstitution = $dataArray1['di:BeneficiaryCreditInstitution'];
                            $obj->BeneficiaryCustomerAccountNumber = $dataArray1['di:BeneficiaryCustomerAccountNumber'];
                            $obj->Amount = $dataArray1['di:Amount'];
                            $obj->Accepted = $dataArray1['di:Accepted'];
                            $obj->ProcessedAsynchronously = $dataArray1['di:ProcessedAsynchronously'];
                            $obj->ReferenceInformation = @$dataArray1['di:ReferenceInformation'];
                            $obj->OrigCustomerAccountName = $dataArray1['di:OrigCustomerAccountName'];

                            $obj->ReportTitle = @$dataArray1['di:ReportTitle'];
                            $obj->CorporateID = @$dataArray1['di:CorporateID'];
                            $obj->SubmissionID = @$dataArray1['di:SubmissionID'];

                            $obj->FPSDocumentTitle = @$dataArray1['di:FPSDocumentTitle'];
                            $obj->FPSDocumentcreated = @$dataArray1['di:FPSDocumentcreated'];
                            $obj->FPSDocumentschemaVersion = @$dataArray1['di:FPSDocumentschemaVersion'];
                            $obj->SubmissionStatus = @$dataArray1['di:SubmissionStatus'];
                            $obj->Currency = @$dataArray1['di:Currency'];
                            $obj->FileStatus = @$dataArray1['di:FileStatus'];
                            $obj->OutwardAcceptedVolume = @$dataArray1['di:OutwardAcceptedVolume'];
                            $obj->OutwardAcceptedValue = @$dataArray1['di:OutwardAcceptedValue'];
                            $obj->OutwardAcceptedValueCur = @$dataArray1['di:OutwardAcceptedValueCur'];
                            $obj->OutwardRejectedVolume = @$dataArray1['di:OutwardRejectedVolume'];
                            $obj->OutwardRejectedValue = @$dataArray1['di:OutwardRejectedValue'];
                            $obj->OutwardRejectedValueCur = @$dataArray1['di:OutwardRejectedValueCur'];
                            $obj->Time = @$dataArray1['di:Time'];
                            
                            $obj->file_name = $fixed_filename;

                            $AgencybankingObj = new Agencybanking();

                            if($obj->ReferenceInformation != 'RTN' && $obj->ReferenceInformation != 'rtn' )
                            {
                                $abData = $AgencybankingObj->fetchABDataForFPOut($obj,"Approved");

                                if(!empty($abData))
                                {
                                 
                                  $obj->agencybanking_Id = $abData->ids;
                               
                                    $obj->ab_type = $abData->banking_type;

                                    $abData->reco_flg = "F";
                                    $abData->fp_out_date = $obj->file_date;

                                    $abData->save();
                                }
                            }
                            else
                            {   
                                $abData = $AgencybankingObj->fetchABDDataForFPOut($obj,"Declined");
                                if(!empty($abData))
                                {
                                   
                                 $obj->agencybanking_Id = $abData->ids;
                               
                                    $obj->ab_type = $abData->banking_type;

                                    $abData->fpout_dec_reco_flag = "Y";
                                    $abData->fp_out_date = $obj->file_date;

                                    $abData->save();
                                }
                            }
                      
                            $obj->save();
                        }
                            $flg = 0;
                            $i = 0;
                    }
                    
                }                     

             } catch (\Symfony\Component\HttpFoundation\File\Exception\FileException $exception) {
                $results['error']=trans('admin/directdebits/message.upload.error');
                if (config('app.debug')) {
                    $results['error'].= ' ' . $exception->getMessage();
                }
                return $results;
            }
        }

        return redirect()->to("directdebits/import")->with("success","Fpout imported successfully.");

    }

    private function importDDFile($request)
    {
        $files = Input::file('direct_debits_file'); 

        foreach ($files as $key => $file) 
        {
            $path = config('app.private_uploads').'/directdebit';

            $results = array();
            $date = date('Y-m-d-his');
            
            $fixed_filename = str_replace(' ', '-', $file->getClientOriginalName());
            
            $filePath = $path."/".$date.'-'.$fixed_filename;

            try {
                $file->move($path, $date.'-'.$fixed_filename);

                $this->fileImportHistoryLog($date.'-'.$fixed_filename,$path,"Directdebits",Auth::user()->id);

                $z = new \XMLReader;            
                $z->open($filePath);
                $doc = new \DOMDocument;

                while ($z->read())
                {
                    $dataArray = array();
                    $dataArray = $this->directDebitXmlToArrayParser($doc , $z);
                    
                    if($dataArray['key'] == 'Table1' && !empty($dataArray['val']))
                    {
                        $this->storeDirectDebitData($dataArray['val']);                    
                    }
                } 

            } catch (\Symfony\Component\HttpFoundation\File\Exception\FileException $exception) {
                $results['error']=trans('admin/directdebits/message.upload.error');
                if (config('app.debug')) {
                    $results['error'].= ' ' . $exception->getMessage();
                }
                return $results;
            }
       
       }
        return redirect()->to("directdebits/import")->with("success",trans('admin/directdebits/message.success.import'));
    }

    private function storeDirectDebitData($outputArray)
    {
        // TEXT9=SUN
        // TEXT15=Sun_Name
        // TEXT11=Trans_Code
        // TEXT6=DReference
        // TEXT13=diban
        // TEXT17=status
        // TEXT23=Processing_Date
        // TEXT25=Due_Date
        // TEXT27=amount
        $Processing_Date = Carbon::parse($outputArray['TEXT23'])->format('Y-m-d');
        $SUN = $outputArray['TEXT9'];         
        // $ddChecks = Directdebits::where('Processing_Date','=',$Processing_Date)
        //     ->where('SUN','=',$SUN)
        //     ->first()
        //     ;
        // if(empty($ddChecks)){            
            $ddChecks = new Directdebits();
        // }                

        $ddChecks->Processing_Date = $Processing_Date;
        $ddChecks->Due_Date = Carbon::parse($outputArray['TEXT25'])->format('Y-m-d');
        $ddChecks->SUN = $SUN;
        $ddChecks->Sun_Name = $outputArray['TEXT15'];
        $ddChecks->Trans_Code = $outputArray['TEXT11'];
        $ddChecks->DReference = $outputArray['TEXT6'];
        $ddChecks->diban = $outputArray['TEXT13'];
        $ddChecks->status = $outputArray['TEXT17'];
        $ddChecks->amount = floatval(ltrim($outputArray['TEXT27'], '$'));
        $ddChecks->Token_Number = '';        

        $ddChecks->save();
    }
}
