<?php
namespace App\Http\Controllers\Fpout;

use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use App\Models\Actionlog;
use App\Http\Requests\FileFlagRequest;
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
use SFTP;
use PDO;
use Validator;


use App\Models\Fpoutfilesupload;
use App\Models\Fpout;
use App\Models\Agencybanking;

/**
 * This controller handles all actions related to Users for
 * the Parextech Asset Management application.
 *
 * @version    v1.0
 */


class FpoutController extends Controller
{


    /**
    * Returns a view that invokes the ajax tables which actually contains
    * the content for the users listing, which is generated in getDatatable().
    *
    * @author [A. Gianotto] [<snipe@snipe.net>]
    * @see BankbalanceController::getDatatable() method that generates the JSON response
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
        $filterColumn[]=array("filter" => array("type" => "input"));        
        $filterColumn[]=array("filter" => array("type" => "input"));        

        return View::make('fpout/index')->with('filterColumn', $filterColumn);     
    }

    public function getDatatable(Request $request)
    {
        $params = array();
        if(Input::has('start_date') ){
            $params['start_date'] = $this->dateFormat(Input::get('start_date'),0);    
        }
        if(Input::has('end_date')){
            $params['end_date'] = $this->dateFormat(Input::get('end_date'),0);    
        }
        $result = Fpout::getDatatableData($params);
        
        $rows = array();
        foreach ($result['data'] as $data) {

            $rows[] = array(
                'FileID'    => e($data->FileID),                
                'FPID'   => e($data->FPID),
                'OrigCustomerSortCode'  => e($data->OrigCustomerSortCode),                
                'OrigCustomerAccountNumber'  => e($data->OrigCustomerAccountNumber),                
                'BeneficiaryCreditInstitution'    => e($data->BeneficiaryCreditInstitution),                
                'BeneficiaryCustomerAccountNumber'    => e($data->BeneficiaryCustomerAccountNumber),                
                'Amount'  => round(e($data->Amount),2),              
                'ProcessedAsynchronously'  => e($data->ProcessedAsynchronously),                                           
                'ReferenceInformation'  => e($data->ReferenceInformation),             
                'OrigCustomerAccountName'  => round(e($data->OrigCustomerAccountName),2),      
                'created_at'  => date('Y-m-d',strtotime(e($data->created_at))),                                           
                'file_date'  => e($data->file_date), 
                                                          
                'ReportTitle'  => e($data->ReportTitle),                                           
                'CorporateID'  => e($data->CorporateID),                                           
                'SubmissionID'  => e($data->SubmissionID),     

                'FPSDocumentTitle'  => e($data->FPSDocumentTitle),                                           
                'FPSDocumentcreated'  => e($data->FPSDocumentcreated),                                           
                'FPSDocumentschemaVersion'  => e($data->FPSDocumentschemaVersion),                                           
                'SubmissionStatus'  => e($data->SubmissionStatus),                                           
                'Currency'  => e($data->Currency),                                           
                'FileStatus'  => e($data->FileStatus),                                           
                'OutwardAcceptedVolume'  => e($data->OutwardAcceptedVolume),                                           
                'OutwardAcceptedValue'  => e($data->OutwardAcceptedValue),                                           
                'OutwardAcceptedValueCur'  => e($data->OutwardAcceptedValueCur),                                           
                'OutwardRejectedVolume'  => e($data->OutwardRejectedVolume),                                           
                'OutwardRejectedValue'  => e($data->OutwardRejectedValue),                                           
                'OutwardRejectedValueCur'  => e($data->OutwardRejectedValueCur),                                           
                'Time'  => e($data->Time),                                           
            );             
        }

        return array('total'=>$result['count'], 'rows'=>$rows); 
    }

    // public function BKP_importFpoutFile()
    // {
    //     $sftp = new SFTP(config('app.FPOUT_SFTP_SERVER'));

    //     if (!$sftp->login(config('app.FPOUT_SFTP_USERNAME'), config('app.FPOUT_SFTP_PASSWORD'))) {
    //         throw new Exception('Login failed');
    //     }
    //     $file_list = $sftp->nlist(config('app.FPOUT_SFTP_PATH'));

    //     foreach ($file_list as $key => $value) 
    //     {
    //          if($sftp->is_file(config('app.FPOUT_SFTP_PATH')."/".$value))
    //         {
    //             $xmlData = $sftp->get(config('app.FPOUT_SFTP_PATH')."/".$value);

    //             if(!empty($xmlData))
    //             {
    //                 $fileData = Fpoutfilesupload::where("filename",$value)->first();
    //                 if(empty($fileData) || $fileData->upload_flg == 0)
    //                 {
    //                     $path = config('app.private_uploads').'/fpout';
    //                     $filePath = $path."/".$value;
    //                     file_put_contents($filePath, $xmlData);

    //                     $fileName_data = explode(".", $value);
    //                     $fileName_data = explode("exp", $fileName_data[0]);
    //                     $file_date = date("Y-m-d");

    //                     if(!empty($fileName_data[1]))
    //                     {
    //                         $fileName_data = explode("_", $fileName_data[1]);

    //                         if(!empty($fileName_data[0]))
    //                         {
    //                             $file_date = date("Y-m-d",strtotime($fileName_data[0]));
    //                         }
    //                     }


    //                     if(!empty($fileData))
    //                     {
    //                         $fileData->upload_flg = 1;
    //                         $fileData->save();
    //                     }
    //                     else
    //                     {
    //                         $txnfilesObj = new Fpoutfilesupload();
    //                         $txnfilesObj->filename = $value;
    //                         $txnfilesObj->file_pate = $path;
    //                         $txnfilesObj->save();
    //                     }

    //                     try {

    //                         $user_Id = 0;
    //                         if(!empty(Auth::user()) && !empty(Auth::user()->id))
    //                         {
    //                             $user_Id = Auth::user()->id;
    //                         }

    //                         $this->fileImportHistoryLog($value,$path,"Fpout",$user_Id);
    //                         $z = new \XMLReader;
    //                         $z->open($filePath);
    //                         $doc = new \DOMDocument;
    //                             $dataArray = array();
    //                             $flg = 0;
    //                             $i = 0;
    //                         while ($z->read())
    //                         {
    //                             $dataArray1 = $this->fpoutXmlToArrayParser1($doc , $z , $flg , $dataArray , $i);

    //                             if($flg == 2)
    //                             {
    //                                 $dataArray1['di:FPID'] = trim($dataArray1['di:FPID']);

    //                                 $fpOutDataExixst = Fpout::where("FPID",$dataArray1['di:FPID'])->first();

    //                                 if(!empty($fpOutDataExixst))
    //                                 {
    //                                     $fpOutDataExixst->FileID = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $dataArray1['di:FileID'])));
    //                                     $fpOutDataExixst->file_date = $dataArray1['di:Date'];
    //                                     $fpOutDataExixst->FPID = $dataArray1['di:FPID'];
    //                                     $fpOutDataExixst->OrigCustomerSortCode = $dataArray1['di:OrigCustomerSortCode'];
    //                                     $fpOutDataExixst->OrigCustomerAccountNumber = $dataArray1['di:OrigCustomerAccountNumber'];
    //                                     $fpOutDataExixst->BeneficiaryCreditInstitution = $dataArray1['di:BeneficiaryCreditInstitution'];
    //                                     $fpOutDataExixst->BeneficiaryCustomerAccountNumber = $dataArray1['di:BeneficiaryCustomerAccountNumber'];
    //                                     $fpOutDataExixst->Amount = $dataArray1['di:Amount'];
    //                                     $fpOutDataExixst->Accepted = $dataArray1['di:Accepted'];
    //                                     $fpOutDataExixst->ProcessedAsynchronously = $dataArray1['di:ProcessedAsynchronously'];
    //                                     $fpOutDataExixst->ReferenceInformation = @$dataArray1['di:ReferenceInformation'];
    //                                     $fpOutDataExixst->OrigCustomerAccountName = $dataArray1['di:OrigCustomerAccountName'];

    //                                     $fpOutDataExixst->ReportTitle = @$dataArray1['di:ReportTitle'];
    //                                     $fpOutDataExixst->CorporateID = @$dataArray1['di:CorporateID'];
    //                                     $fpOutDataExixst->SubmissionID = @$dataArray1['di:SubmissionID'];

    //                                     $fpOutDataExixst->FPSDocumentTitle = @$dataArray1['di:FPSDocumentTitle'];
    //                                     $fpOutDataExixst->FPSDocumentcreated = @$dataArray1['di:FPSDocumentcreated'];
    //                                     $fpOutDataExixst->FPSDocumentschemaVersion = @$dataArray1['di:FPSDocumentschemaVersion'];
    //                                     $fpOutDataExixst->SubmissionStatus = @$dataArray1['di:SubmissionStatus'];
    //                                     $fpOutDataExixst->Currency = @$dataArray1['di:Currency'];
    //                                     $fpOutDataExixst->FileStatus = @$dataArray1['di:FileStatus'];
    //                                     $fpOutDataExixst->OutwardAcceptedVolume = @$dataArray1['di:OutwardAcceptedVolume'];
    //                                     $fpOutDataExixst->OutwardAcceptedValue = @$dataArray1['di:OutwardAcceptedValue'];
    //                                     $fpOutDataExixst->OutwardAcceptedValueCur = @$dataArray1['di:OutwardAcceptedValueCur'];
    //                                     $fpOutDataExixst->OutwardRejectedVolume = @$dataArray1['di:OutwardRejectedVolume'];
    //                                     $fpOutDataExixst->OutwardRejectedValue = @$dataArray1['di:OutwardRejectedValue'];
    //                                     $fpOutDataExixst->OutwardRejectedValueCur = @$dataArray1['di:OutwardRejectedValueCur'];
    //                                     $fpOutDataExixst->Time = @$dataArray1['di:Time'];
                                        
    //                                     $fpOutDataExixst->file_name = $fixed_filename;

    //                                     $fpOutDataExixst->save();
    //                                 }
    //                                 else
    //                                 {

    //                                     $obj = new Fpout;
    //                                     $obj->id = Helper::generateUniqueId();
    //                                     $obj->FileID = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $dataArray1['di:FileID'])));
    //                                     $obj->file_date = $dataArray1['di:Date'];
    //                                     $obj->FPID = $dataArray1['di:FPID'];
    //                                     $obj->OrigCustomerSortCode = $dataArray1['di:OrigCustomerSortCode'];
    //                                     $obj->OrigCustomerAccountNumber = $dataArray1['di:OrigCustomerAccountNumber'];
    //                                     $obj->BeneficiaryCreditInstitution = $dataArray1['di:BeneficiaryCreditInstitution'];
    //                                     $obj->BeneficiaryCustomerAccountNumber = $dataArray1['di:BeneficiaryCustomerAccountNumber'];
    //                                     $obj->Amount = $dataArray1['di:Amount'];
    //                                     $obj->Accepted = $dataArray1['di:Accepted'];
    //                                     $obj->ProcessedAsynchronously = $dataArray1['di:ProcessedAsynchronously'];
    //                                     $obj->ReferenceInformation = @$dataArray1['di:ReferenceInformation'];
    //                                     $obj->OrigCustomerAccountName = $dataArray1['di:OrigCustomerAccountName'];

    //                                     $obj->ReportTitle = @$dataArray1['di:ReportTitle'];
    //                                     $obj->CorporateID = @$dataArray1['di:CorporateID'];
    //                                     $obj->SubmissionID = @$dataArray1['di:SubmissionID'];

    //                                     $obj->FPSDocumentTitle = @$dataArray1['di:FPSDocumentTitle'];
    //                                     $obj->FPSDocumentcreated = @$dataArray1['di:FPSDocumentcreated'];
    //                                     $obj->FPSDocumentschemaVersion = @$dataArray1['di:FPSDocumentschemaVersion'];
    //                                     $obj->SubmissionStatus = @$dataArray1['di:SubmissionStatus'];
    //                                     $obj->Currency = @$dataArray1['di:Currency'];
    //                                     $obj->FileStatus = @$dataArray1['di:FileStatus'];
    //                                     $obj->OutwardAcceptedVolume = @$dataArray1['di:OutwardAcceptedVolume'];
    //                                     $obj->OutwardAcceptedValue = @$dataArray1['di:OutwardAcceptedValue'];
    //                                     $obj->OutwardAcceptedValueCur = @$dataArray1['di:OutwardAcceptedValueCur'];
    //                                     $obj->OutwardRejectedVolume = @$dataArray1['di:OutwardRejectedVolume'];
    //                                     $obj->OutwardRejectedValue = @$dataArray1['di:OutwardRejectedValue'];
    //                                     $obj->OutwardRejectedValueCur = @$dataArray1['di:OutwardRejectedValueCur'];
    //                                     $obj->Time = @$dataArray1['di:Time'];

    //                                     $obj->file_name = $value;

    //                                     $AgencybankingObj = new Agencybanking();

    //                                     if($obj->ReferenceInformation != 'RTN' && $obj->ReferenceInformation != 'rtn' )
    //                                     {
    //                                         $abData = $AgencybankingObj->fetchABDataForFPOut($obj,"Approved");

    //                                         if(count($abData) != 0)
    //                                         {
    //                                             $obj->agencybanking_Id = $abData->ids;
    //                                             $obj->ab_type = $abData->banking_type;

    //                                             $abData->reco_flg = "F";
    //                                             $abData->fp_out_date = $obj->file_date;

    //                                             $abData->save();
    //                                         }
    //                                     }
    //                                     else
    //                                     {   
    //                                         $abData = $AgencybankingObj->fetchABDDataForFPOut($obj,"Declined");
    //                                         if(count($abData) != 0)
    //                                         {
    //                                             $obj->agencybanking_Id = $abData->ids;
    //                                             $obj->ab_type = $abData->banking_type;

    //                                             $abData->fpout_dec_reco_flag = "Y";
    //                                             $abData->fp_out_date = $obj->file_date;

    //                                             $abData->save();
    //                                         }
    //                                     }
                                        

    //                                     $obj->save();
    //                                 }
    //                                     $flg = 0;
    //                                     $i = 0;
    //                             }
                                
    //                         }   
                            
    //                     } catch (\Symfony\Component\HttpFoundation\File\Exception\FileException $exception) {
    //                         $results['error']=trans('admin/banktxn/message.upload.error');
    //                         if (config('app.debug')) {
    //                             $results['error'].= ' ' . $exception->getMessage();
    //                         }
    //                         return $results;
    //                     }
    //                 }
    //             }
    //         }
    //     }
    //     return redirect()->to("fpout")->with("success","FP Out imported successfully.");
    // }

    public function importFpoutFile()
    {
        $file_list = scandir(config('app.FPOUT_SFTP_PATH'));
        foreach ($file_list as $key => $value) 
        {
             if(is_file(config('app.FPOUT_SFTP_PATH')."/".$value))
            {
                $xmlData = file_get_contents(config('app.FPOUT_SFTP_PATH')."/".$value);
                if(!empty($xmlData))
                {
                    $fileData = Fpoutfilesupload::where("filename",$value)->first();
                    if(empty($fileData) || $fileData->upload_flg == 0)
                    {
                        $path = config('app.private_uploads').'/fpout';
                        $filePath = $path."/".$value;
                        file_put_contents($filePath, $xmlData);

                        $fileName_data = explode(".", $value);
                        $fileName_data = explode("exp", $fileName_data[0]);
                        $file_date = date("Y-m-d");

                        if(!empty($fileName_data[1]))
                        {
                            $fileName_data = explode("_", $fileName_data[1]);

                            if(!empty($fileName_data[0]))
                            {
                                $file_date = date("Y-m-d",strtotime($fileName_data[0]));
                            }
                        }


                        if(!empty($fileData))
                        {
                            $fileData->upload_flg = 0;
                            $fileData->save();
                        }
                        else
                        {
                            $txnfilesObj = new Fpoutfilesupload();
                            $txnfilesObj->upload_flg = 0;
                            $txnfilesObj->filename = $value;
                            $txnfilesObj->file_pate = $path;
                            $txnfilesObj->save();
                        }

                        try {

                            $user_Id = 0;
                            if(!empty(Auth::user()) && !empty(Auth::user()->id))
                            {
                                $user_Id = Auth::user()->id;
                            }

                            $this->fileImportHistoryLog($value,$path,"Fpout",$user_Id);
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

                                        if(empty($fpOutDataExixst->agencybanking_Id))
                                        {
                                             $AgencybankingObj = new Agencybanking();
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

                                        $obj->file_name = $value;

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

                            if(!empty($fileData))
                            {
                                $fileData->upload_flg = 1;
                                $fileData->save();
                            }
                            else
                            {
                                $txnfilesObj->upload_flg = 1;
                                $txnfilesObj->save();
                            }   
                            
                        } catch (\Symfony\Component\HttpFoundation\File\Exception\FileException $exception) {
                            $results['error']=trans('admin/banktxn/message.upload.error');
                            if (config('app.debug')) {
                                $results['error'].= ' ' . $exception->getMessage();
                            }
                            return $results;
                        }
                    }
                }
            }
        }
        return redirect()->to("fpout")->with("success","FP Out imported successfully.");
    }

    public function generateFpoutAbLink()
    {
        // $endDate = date("Y-m-d");
        // $startDate = date("Y-m-d",strtotime("-3 day",strtotime($endDate)));

        $endDate = "2018-03-31";
        $startDate = "2018-03-01";

        $fpoutObj = new Fpout();

        $fpoutData = $fpoutObj->getFpoutDataByDate($startDate , $endDate);
          // pr($fpoutData,1);
        foreach ($fpoutData as $key => $value) 
        {
            if($value->ReferenceInformation != 'RTN' && $value->ReferenceInformation != 'rtn' )
            {
                $AgencybankingObj = new Agencybanking();
                $abData = $AgencybankingObj->fetchABDataForFPOut($value,"Approved");

                if(!empty($abData))
                {
                    $value->agencybanking_Id = $abData->ids;
                    $value->ab_type = $abData->banking_type;

                    $abData->reco_flg = "F";
                    $abData->fp_out_date = $value->file_date;

                    $abData->save();

                    $value->save();
                }
            }
            else
            {   
                $AgencybankingObj = new Agencybanking();
                $abData = $AgencybankingObj->fetchABDDataForFPOut($value,"Declined");

                if(!empty($abData))
                {
                    $value->agencybanking_Id = $abData->ids;
                    $value->ab_type = $abData->banking_type;

                    $abData->fpout_dec_reco_flag = "Y";
                    $abData->fp_out_date = $value->file_date;

                    $abData->save();
                    $value->save();
                }
            }
        }
    }

}
