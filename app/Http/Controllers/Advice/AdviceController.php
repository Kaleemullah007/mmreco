<?php
namespace App\Http\Controllers\Advice;

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


use App\Models\Advicefilesupload;
use App\Models\Directdebits;
use App\Models\Advice;
use App\Models\Agencybanking;

/**
 * This controller handles all actions related to Users for
 * the Parextech Asset Management application.
 *
 * @version    v1.0
 */


class AdviceController extends Controller
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
       
        return View::make('advice/index')->with('filterColumn', $filterColumn);
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
        $result = Advice::getDatatableData($params);

        $rows = array();
        foreach ($result['data'] as $data) {
            

            $rows[] = array(
                'ab_sort_code'    => e($data->ab_sort_code),                
                'ab_account_number'   => e($data->ab_account_number),
                'code'  => e($data->code),                
                'ext_bank_sort_code'  => e($data->ext_bank_sort_code),                
                'ext_bank_acc_number'  => e($data->ext_bank_acc_number),                
                'amount_in_cent'  => e($data->amount_in_cent),                
                'actual_amount'  => number_format($data->actual_amount,2,'.',''),                
                'ext_name'  => e($data->ext_name),                                                    
                'file_date'  => e($data->file_date),   
                                                        
                'C'  => e($data->C),                                           
                'A'  => e($data->A),                                           
                'ref'  => e($data->ref),                                           
                'ab_name'  => e($data->ab_name),                                           
                'advice_number'  => e($data->advice_number),                                           
                'X'  => e($data->X),                                           
                'Y'  => e($data->Y),                                           
                'Z'  => e($data->Z),                                           
            );             
        }

        return array('total'=>$result['count'], 'rows'=>$rows);
        

    }

    // public function BKP_importAdviceFile()
    // {
    //     $sftp = new SFTP(config('app.ADVICE_SFTP_SERVER'));

    //     if (!$sftp->login(config('app.ADVICE_SFTP_USERNAME'), config('app.ADVICE_SFTP_PASSWORD'))) {
    //         throw new Exception('Login failed');
    //     }
    //     $file_list = $sftp->nlist(config('app.ADVICE_SFTP_PATH'));

    //     foreach ($file_list as $key => $value) 
    //     {
    //          if($sftp->is_file(config('app.ADVICE_SFTP_PATH')."/".$value))
    //         {
    //             $xmlData = $sftp->get(config('app.ADVICE_SFTP_PATH')."/".$value);

    //             if(!empty($xmlData))
    //             {
    //                 $fileData = Advicefilesupload::where("filename",$value)->first();
    //                 if(empty($fileData) || $fileData->upload_flg == 0)
    //                 {
    //                     $path = config('app.private_uploads').'/advice';
    //                     $filePath = $path."/".$value.".csv";
    //                     file_put_contents($filePath, $xmlData);

    //                     $file_date = date("Y-m-d");

    //                     $fileName_data = explode("_", $value);
    //                     if(strpos($fileName_data[1], 'D') !== false)
    //                     {
    //                         $fileDate = str_replace("D", "", $fileName_data[1]);
    //                         $file_date = date("Y-m-d",strtotime($fileDate));
    //                     }


    //                     if(!empty($fileData))
    //                     {
    //                         $fileData->upload_flg = 1;
    //                         $fileData->save();
    //                     }
    //                     else
    //                     {
    //                         $txnfilesObj = new Advicefilesupload();
    //                         $txnfilesObj->filename = $value.".csv";
    //                         $txnfilesObj->file_pate = $path;
    //                         $txnfilesObj->save();
    //                     }

    //                     try {

    //                         $user_Id = 0;
    //                         if(!empty(Auth::user()) && !empty(Auth::user()->id))
    //                         {
    //                             $user_Id = Auth::user()->id;
    //                         }

    //                         $this->fileImportHistoryLog($value.".csv",$path,"Advice",$user_Id);

    //                         $Reader = Excel::load($filePath)->noHeading()->all()->toArray();

    //                         foreach ($Reader as $key => $value)
    //                         {
    //                             if(!empty($value[3]) && (int)$value[7] != 0 && ($value[3] == "99" || $value[3] == "44"))
    //                             {
    //                                 $adviceObj = new Advice();
    //                                 $adviceObj->id = Helper::generateUniqueId();
    //                                 $adviceObj->ab_sort_code = $value[0];
    //                                 $adviceObj->ab_account_number = $value[1];
    //                                 $adviceObj->code = $value[3];
    //                                 $adviceObj->ext_bank_sort_code = $value[4];
    //                                 $adviceObj->ext_bank_acc_number = $value[5];
    //                                 $adviceObj->amount_in_cent = (int) $value[7];
    //                                 $adviceObj->actual_amount = ($adviceObj->amount_in_cent / 100);
    //                                 $adviceObj->ext_name = $value[8];
    //                                 $adviceObj->file_date = $file_date;
    //                                 $adviceObj->file_name = $value;

    //                                 $adviceObj->C = $value[2];
    //                                 $adviceObj->A = $value[6];
    //                                 $adviceObj->ref = $value[9];
    //                                 $adviceObj->ab_name = $value[10];
    //                                 $adviceObj->advice_number = $value[11];
    //                                 $adviceObj->X = $value[12];
    //                                 $adviceObj->Y = $value[13];
    //                                 $adviceObj->Z = $value[14];

    //                                 if($value[3] == "99")
    //                                 {
    //                                     $AgencybankingObj = new Agencybanking();
    //                                     if($adviceObj->X == "S" || $adviceObj->X == "s")
    //                                         $abData = $AgencybankingObj->fetchReturnABDataForAdvice($adviceObj);
    //                                     else
    //                                         $abData = $AgencybankingObj->fetchABDataForAdvice($adviceObj);
    //                                     if(count($abData) != 0)
    //                                     {
    //                                         $adviceObj->related_table_id = $abData->ids;
    //                                         if($abData->banking_type == "Approved")
    //                                             $adviceObj->type = "abapproved";
    //                                         else    
    //                                             $adviceObj->type = "abdeclined";

    //                                         $abData->reco_flg = "A";

    //                                         $abData->save();
    //                                     }
    //                                 }

    //                                 if($value[3] == "44")
    //                                 {
    //                                     $directDebitsObj = new Directdebits();

    //                                     $ddData = $directDebitsObj->fetchDDDataForAdvice($adviceObj);
    //                                     if(count($ddData) != 0)
    //                                     {
    //                                         $relatedId = '';

    //                                         foreach ($ddData as $key1 => $value1) 
    //                                         {
    //                                             $relatedId = $relatedId.$value1->ids.",";
    //                                         }
    //                                         $adviceObj->related_table_id = rtrim($relatedId,',');
    //                                         $adviceObj->type = "dd";
    //                                     }
    //                                 }

    //                                 if(!isset($adviceObj->type))
    //                                     $adviceObj->type = "bacs";

    //                                 $adviceObj->save();
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
    //     // return redirect()->to("fpout")->with("success","FP Out imported successfully.");
    //     echo "success";exit;
    // }

    public function importAdviceFile()
    {
        $file_list = scandir(config('app.ADVICE_SFTP_PATH'));

        foreach ($file_list as $key1 => $value1) 
        {
             if(is_file(config('app.ADVICE_SFTP_PATH')."/".$value1))
            {
                $xmlData = file_get_contents(config('app.ADVICE_SFTP_PATH')."/".$value1);

                if(!empty($xmlData))
                {
                    $fileData = Advicefilesupload::where("filename",$value1.".csv")->first();
                    if(empty($fileData) || $fileData->upload_flg == 0)
                    {
                        $path = config('app.private_uploads').'/advice';
                        $filePath = $path."/".$value1.".csv";
                        file_put_contents($filePath, $xmlData);

                        $file_date = date("Y-m-d");

                        $fileName_data = explode("_", $value1);
                        if(strpos($fileName_data[1], 'D') !== false)
                        {
                            $fileDate = str_replace("D", "", $fileName_data[1]);
                            $file_date = date("Y-m-d",strtotime($fileDate));
                        }


                        if(!empty($fileData))
                        {
                            $fileData->upload_flg = 0;
                            $fileData->save();
                        }
                        else
                        {
                            $txnfilesObj = new Advicefilesupload();
                            $txnfilesObj->upload_flg = 0;
                            $txnfilesObj->filename = $value1.".csv";
                            $txnfilesObj->file_pate = $path;
                            $txnfilesObj->save();
                        }

                        try {

                            $user_Id = 0;
                            if(!empty(Auth::user()) && !empty(Auth::user()->id))
                            {
                                $user_Id = Auth::user()->id;
                            }

                            $this->fileImportHistoryLog($value1.".csv",$path,"Advice",$user_Id);

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
                                    $adviceObj->file_name = $value1;

                                    $adviceObj->C = @$value[2];
                                    $adviceObj->A = @$value[6];
                                    $adviceObj->ref = @$value[9];
                                    $adviceObj->ab_name = @$value[10];
                                    $adviceObj->advice_number = @$value[11];
                                    $adviceObj->X = @$value[12];
                                    $adviceObj->Y = @$value[13];
                                    $adviceObj->Z = @$value[14];

                                    if($value[3] == "99")
                                    {
                                        $AgencybankingObj = new Agencybanking();
                                        if($adviceObj->X == "S" || $adviceObj->X == "s")
                                            $abData = $AgencybankingObj->fetchReturnABDataForAdvice($adviceObj);
                                        else
                                            $abData = $AgencybankingObj->fetchABDataForAdvice($adviceObj);
                                        if(count($abData) != 0)
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

                                            foreach ($ddData as $key2 => $value2) 
                                            {
                                                $relatedId = $relatedId.$value2->ids.",";
                                            }
                                            $adviceObj->related_table_id = rtrim($relatedId,',');
                                            $adviceObj->type = "dd";
                                        }
                                    }

                                    if(!isset($adviceObj->type))
                                        $adviceObj->type = "bacs";

                                    $adviceObj->save();
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
        // return redirect()->to("fpout")->with("success","FP Out imported successfully.");
        echo "success";exit;
    }

    public function generateAdviceLink()
    {
        $endDate = date("Y-m-d");
        $startDate = date("Y-m-d",strtotime("-3 day",strtotime($endDate)));

        $adviceObj = new Advice();

        $fpoutData = $adviceObj->getAdviceDataByDate($startDate , $endDate);
        foreach ($fpoutData as $key => $value) 
        {
             if($value->code == "99")
            {
                $AgencybankingObj = new Agencybanking();

                if($value->X == "S" || $value->X == "s")
                    $abData = $AgencybankingObj->fetchReturnABDataForAdvice($value);
                else
                    $abData = $AgencybankingObj->fetchABDataForAdvice($value);

                if(count($abData) != 0)
                {
                    $value->related_table_id = $abData->ids;
                    if($abData->banking_type == "Approved")
                        $value->type = "abapproved";
                    else    
                        $value->type = "abdeclined";

                    $abData->reco_flg = "A";

                    $abData->save();

                    $value->save();
                }
            }

            if($value->code == "44")
            {
                $directDebitsObj = new Directdebits();

                $ddData = $directDebitsObj->fetchDDDataForAdvice($value);
                if(count($ddData) != 0)
                {
                    $relatedId = '';
                    foreach ($ddData as $key1 => $value1) {
                        $relatedId = $relatedId.$value1->ids.",";
                    }

                    $value->related_table_id = rtrim($relatedId,',');
                    $value->type = "dd";

                    $value->save();
                }
            }
        }
        echo "success";
        exit;
    }

}
