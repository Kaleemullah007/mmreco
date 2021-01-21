<?php
namespace App\Http\Controllers\Bankbalance;

use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use App\Models\Actionlog;
use App\Models\Bankbalance;
use App\Models\Bankbalancecard;
use App\Models\Balfilesupload;
use App\Models\Fileimporthistory;
use App\Http\Requests\ImportBankBalanceRequest;
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
use Validator;

/**
 * This controller handles all actions related to Users for
 * the Parextech Asset Management application.
 *
 * @version    v1.0
 */


class BankbalanceController extends Controller
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
        if( Input::has('from') || Input::has('to') ){
            $validator = Validator::make(Input::all(), [                
                'to' => 'date|after_or_equal:from',
                'from' => 'date|before_or_equal:to',        
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

    	 return View::make('bankbalance/index')->with('filterColumn', $filterColumn);;	
    }

    public function postBankBalance(Request $request)
    {
        // pr($request->all(),1);
        $params = array();
        if(Input::has('from') ){
            $params['start_date'] = $this->dateFormat(Input::get('from'),0);    
        }
        if(Input::has('to')){
            $params['end_date'] = $this->dateFormat(Input::get('to'),0);    
        }
        $result = Bankbalance::getDatatableData($params);    
        $rows = array();

        foreach ($result['data'] as $data)
        {                

            $nestedData['accno'] = $data->accno;
            $nestedData['currcode'] = $data->currcode;
            $nestedData['acctype'] = $data->acctype;
            $nestedData['sortcode'] = $data->sortcode;
            $nestedData['bankacc'] = $data->bankacc;
            $nestedData['feeband'] = $data->feeband;
            $nestedData['finamt'] = $data->finamt;
            $nestedData['blkamt'] = $data->blkamt;
            $nestedData['amtavl'] = $data->amtavl;
            $nestedData['bankbal_date'] = $this->dateFormat($data->bankbal_date,1);//$data->created_at;
            $nestedData['created_at'] = $this->dateFormat($data->created_at,1);//$data->created_at;
            $nestedData['pan'] = $data->pan;
            $nestedData['virtual'] = $data->virtual;
            $nestedData['primary'] = $data->primary;
            $nestedData['crdproduct'] = $data->crdproduct;
            $nestedData['programid'] = $data->programid;
            $nestedData['custcode'] = $data->custcode;
            $nestedData['statcode'] = $data->statcode;
            $nestedData['expdate'] = $data->expdate;
            $nestedData['crdaccno'] = $data->crdaccno;
            $nestedData['crdcurrcode'] = $data->crdcurrcode;
            $nestedData['productid'] = $data->productid;
            $nestedData['file_name'] = $data->file_name;


            $rows[] = $nestedData;

        }
          
        return array('total'=>$result['count'], 'rows'=>$rows);          
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
        return View::make('bankbalance/import');
    }

  //   public function postBankBalance(Request $request)
  //   {
  //   	$columns = array( 
  //                           0 =>'id', 
  //                           1 =>'accno',
  //                           2=> 'currcode',
  //                           3=> 'acctype',
  //                           4=> 'sortcode',
  //                           5=> 'bankacc',
  //                           6=> 'feeband',
  //                           7=> 'finamt',
  //                           8=> 'blkamt',
  //                           9=> 'amtavl',
  //                           10=> 'created_at',
  //                       );
  //       $startDate = $this->dateFormat($request->input('startDate'),0).' 00:00:00';
  //       // $startDate = '2017-05-05';
  //       $endDate = $this->dateFormat($request->input('endDate'),0)." 23:59:59";
  //       $minAmount = $request->input('minAmount');
  //       $maxAmount = $request->input('maxAmount');
        
		// if(!empty($startDate) && !empty($endDate))
  //           $bankbalance = Bankbalance::whereBetween('bankbal_date' ,[$startDate, $endDate]);
  //       if(!empty($minAmount) && !empty($maxAmount))
  //           $bankbalance = Bankbalance::whereBetween('amtavl' ,[$minAmount, $maxAmount]);
  //   	$totalData=$bankbalance->count();
		// $totalFiltered = $totalData; 

  //       $limit = $request->input('length');
  //       $start = $request->input('start');
  //       $order = $columns[$request->input('order.0.column')];
  //       $dir = $request->input('order.0.dir');
            
  //       $bankbalance = Bankbalance::select('bank_balance.*','bank_balance.id as bank_balance_id');
  //       if(empty($request->input('search.value')))
  //       {            
		// 	if(!empty($startDate) && !empty($endDate))
  //             $bankbalance=  $bankbalance->whereBetween('bankbal_date' ,[$startDate, $endDate]);
  //   		if(!empty($minAmount) && !empty($maxAmount))
  //             $bankbalance=  $bankbalance->whereBetween('amtavl' ,[$minAmount, $maxAmount]);
        	
  //       	$bankbalance=$bankbalance->offset($start)
  //               ->limit($limit)
  //               ->orderBy($order,$dir)
  //               ->get();
  //       }
  //       else {
  //           $search = $request->input('search.value'); 
  //           $bankbalance =  $bankbalance->where(function($query) use($search)
		//     {
		//     	$query->orwhere('id','LIKE',"%{$search}%")
  //               ->orWhere('accno', 'LIKE',"%{$search}%")
  //               ->orWhere('currcode', 'LIKE',"%{$search}%")
  //               ->orWhere('acctype', 'LIKE',"%{$search}%")
  //               ->orWhere('sortcode', 'LIKE',"%{$search}%")
  //               ->orWhere('bankacc', 'LIKE',"%{$search}%")
  //               ->orWhere('feeband', 'LIKE',"%{$search}%")
  //               ->orWhere('finamt', 'LIKE',"%{$search}%")
  //               ->orWhere('blkamt', 'LIKE',"%{$search}%")
  //               ->orWhere('amtavl', 'LIKE',"%{$search}%")
  //               ->orWhere('created_at', 'LIKE',"%{$search}%");
  //           });
		// 	if(!empty($startDate) && !empty($endDate))
	 //            $bankbalance->whereBetween('created_at' ,[$startDate, $endDate]);
		// 	if(!empty($minAmount) && !empty($maxAmount))
		// 	    $bankbalance->whereBetween('amtavla' ,[$minAmount, $maxAmount]);
  //           $totalFiltered= $bankbalance->count();
  //       	$bankbalance = $bankbalance
  //               ->offset($start)
  //               ->limit($limit)
  //               ->orderBy($order,$dir)
  //               ->get();
		// }

  //       $data1 = array();
  //       $i=1;        
  //       if(!empty($bankbalance))
  //       {
  //           foreach ($bankbalance as $data)
  //           {                
  //               $nestedData['id'] = '<input type="hidden" value="'.$data->bank_balance_id.'">'.$i++;
  //               $nestedData['accno'] = $data->accno;
  //               $nestedData['currcode'] = $data->currcode;
  //               $nestedData['acctype'] = $data->acctype;
  //               $nestedData['sortcode'] = $data->sortcode;
  //               $nestedData['bankacc'] = $data->bankacc;
  //               $nestedData['feeband'] = $data->feeband;
  //               $nestedData['finamt'] = $data->finamt;
  //               $nestedData['blkamt'] = $data->blkamt;
  //               $nestedData['amtavl'] = $data->amtavl;
  //               $nestedData['created_at'] = $this->dateFormat($data->created_at,1);//$data->created_at;
  //               $data1[] = $nestedData;

  //           }
  //       }
          
  //       $json_data = array(
  //                   "draw"            => intval($request->input('draw')),  
  //                   "recordsTotal"    => intval($totalData),  
  //                   "recordsFiltered" => intval($totalFiltered), 
  //                   "data"            => $data1   
  //                   );
            
  //       echo json_encode($json_data); 
  //   }

    /**
    * Handle user import file
    *
    * @author [A. Gianotto] [<snipe@snipe.net>]
    * @since [v1.0]
    * @return Redirect
    */
    public function postImport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'balance_file.*' => 'required|mimes:txt,xml,html,XML,text/xml',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();                   
            return redirect()->to("bankbalance/import")->with("error",$error);                
        }

        $file = Input::file('balance_file');
        $fixcol=24;
        $path = config('app.private_uploads').'/bankbal';

        $results = array();

        $date = date('Y-m-d-his');
        $fileName = $file->getClientOriginalName();
        $fileName_data = explode(".", $fileName);
     //   Helper::pr($fileName_data);
        $fileName_data = explode("exp", $fileName_data[0]);
       // Helper::pr($fileName_data);
        $bankbal_date = date("Y-m-d");

        if(!empty($fileName_data[1]))
        {
            $fileName_data = explode("_", $fileName_data[1]);

            if(!empty($fileName_data[0]))
            {
                $bankbal_date = date("Y-m-d",strtotime($fileName_data[0]));
            }
        }
      //  Helper::pr($bankbal_date,1);
        $fixed_filename = str_replace(' ', '-', $file->getClientOriginalName());
        $fileData=array();
        $fileData = explode(".", $fixed_filename);

        $filePath = $path."/".$date.'-'.$fixed_filename;


        try {
            $file->move($path, $date.'-'.$fixed_filename);
            
            $this->fileImportHistoryLog($date.'-'.$fixed_filename , $path , "Cardbalance" , Auth::user()->id);

            $z = new \XMLReader;
            $z->open($filePath);
            $doc = new \DOMDocument;

            while ($z->read())
            {
                $dataArray = array();
                $dataArray = $this->balXmlToArrayParser($doc , $z);

                if($dataArray['key'] == 'ACCOUNT' && !empty($dataArray['val']))
                {
                    $accId = $this->storeAccountData($dataArray['val'] , $bankbal_date,$fixed_filename);
                    if(!empty($accId))
                    {
                        $this->storeCardData($dataArray['val'] , $accId);
                    }
                }
            }    

        } catch (\Symfony\Component\HttpFoundation\File\Exception\FileException $exception) {
            $results['error']=trans('admin/bankbal/message.upload.error');
            if (config('app.debug')) {
                $results['error'].= ' ' . $exception->getMessage();
            }
            return $results;
        }
       
        return redirect()->to("bankbalance/import")->with("success","Bank Balance imported successfully.");
    
    }

    public function getBankBalanceCard(Request $request){        
        $bank_balance_id = $request->input('bank_balance_id');
        $data = array();
        if($bank_balance_id != '' || $bank_balance_id != null){
            $data = Bankbalancecard::getBankBalanceCardById($bank_balance_id);
        }        
        echo json_encode(array('data'=>$data));
    }

    // public function BKP_importBalanceFile()
    // {
    //     $sftp = new SFTP(config('app.BAL_SFTP_SERVER'));

    //     if (!$sftp->login(config('app.BAL_SFTP_USERNAME'), config('app.BAL_SFTP_PASSWORD'))) {
    //         throw new Exception('Login failed');
    //     }
    //     $file_list = $sftp->nlist(config('app.BAL_SFTP_PATH'));
    //     sort($file_list);
        
    //     foreach ($file_list as $key => $value) 
    //     {
    //          if($sftp->is_file(config('app.BAL_SFTP_PATH')."/".$value))
    //         {
    //             $xmlData = $sftp->get(config('app.BAL_SFTP_PATH')."/".$value);

    //             if(!empty($xmlData))
    //             {
    //                 $fileData = Balfilesupload::where("filename",$value)->first();
    //                 if(empty($fileData) || $fileData->upload_flg == 0)
    //                 {
    //                     $path = config('app.private_uploads').'/bankbal';
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
    //                         $fileData->file_date = $file_date;
    //                         $fileData->save();
    //                     }
    //                     else
    //                     {
    //                         $txnfilesObj = new Balfilesupload();
    //                         $txnfilesObj->filename = $value;
    //                         $txnfilesObj->file_pate = $path;
    //                         $txnfilesObj->file_date = $file_date;
    //                         $txnfilesObj->save();
    //                     }

    //                     try {

    //                         $user_Id = 0;
    //                         if(!empty(Auth::user()) && !empty(Auth::user()->id))
    //                         {
    //                             $user_Id = Auth::user()->id;
    //                         }

    //                         $this->fileImportHistoryLog($value,$path,"Cardbalance",$user_Id);

    //                         $fileName_data = explode(".", $value);
    //                         $fileName_data = explode("exp", $fileName_data[0]);
    //                         $bankbal_date = date("Y-m-d");

    //                         if(!empty($fileName_data[1]))
    //                         {
    //                             $fileName_data = explode("_", $fileName_data[1]);

    //                             if(!empty($fileName_data[0]))
    //                             {
    //                                 $bankbal_date = date("Y-m-d",strtotime($fileName_data[0]));
    //                             }
    //                         }

    //                         $z = new \XMLReader;
    //                         $z->open($filePath);
    //                         $doc = new \DOMDocument;
    //                         while ($z->read())
    //                         {
    //                             $dataArray = array();
    //                             $dataArray = $this->balXmlToArrayParser($doc , $z);

    //                             if($dataArray['key'] == 'ACCOUNT' && !empty($dataArray['val']))
    //                             {
    //                                 $accId = $this->storeAccountData($dataArray['val'] , $bankbal_date,$value);
    //                                 if(!empty($accId))
    //                                 {
    //                                     $this->storeCardData($dataArray['val'] , $accId);
    //                                 }
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
    //     return redirect()->to("bankbalance/import")->with("success","Bank Balance imported successfully.");
    // }

    public function importBalanceFile()
    {
        $file_list = scandir(config('app.BAL_SFTP_PATH'));
        sort($file_list);

        foreach ($file_list as $key => $value) 
        {
             if(is_file(config('app.BAL_SFTP_PATH')."/".$value))
            {
                $xmlData = file_get_contents(config('app.BAL_SFTP_PATH')."/".$value);

                if(!empty($xmlData))
                {
                    $fileData = Balfilesupload::where("filename",$value)->first();
                    if(empty($fileData) || $fileData->upload_flg == 0)
                    {
                        $path = config('app.private_uploads').'/bankbal';
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
                            $fileData->file_date = $file_date;
                            $fileData->save();
                        }
                        else
                        {
                            $txnfilesObj = new Balfilesupload();
                            $txnfilesObj->upload_flg = 0;
                            $txnfilesObj->filename = $value;
                            $txnfilesObj->file_pate = $path;
                            $txnfilesObj->file_date = $file_date;
                            $txnfilesObj->save();
                        }

                        try {

                            $user_Id = 0;
                            if(!empty(Auth::user()) && !empty(Auth::user()->id))
                            {
                                $user_Id = Auth::user()->id;
                            }

                            $this->fileImportHistoryLog($value,$path,"Cardbalance",$user_Id);

                            $fileName_data = explode(".", $value);
                            $fileName_data = explode("exp", $fileName_data[0]);
                            $bankbal_date = date("Y-m-d");

                            if(!empty($fileName_data[1]))
                            {
                                $fileName_data = explode("_", $fileName_data[1]);

                                if(!empty($fileName_data[0]))
                                {
                                    $bankbal_date = date("Y-m-d",strtotime($fileName_data[0]));
                                }
                            }

                            $z = new \XMLReader;
                            $z->open($filePath);
                            $doc = new \DOMDocument;
                            while ($z->read())
                            {
                                $dataArray = array();
                                $dataArray = $this->balXmlToArrayParser($doc , $z);

                                if($dataArray['key'] == 'ACCOUNT' && !empty($dataArray['val']))
                                {
                                    $accId = $this->storeAccountData($dataArray['val'] , $bankbal_date,$value);
                                    if(!empty($accId))
                                    {
                                        $this->storeCardData($dataArray['val'] , $accId);
                                    }
                                }
                            }

                            unset($z);
                            unset($doc);
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
        return redirect()->to("bankbalance/import")->with("success","Bank Balance imported successfully.");
    }

    public function changeFileFlag(FileFlagRequest $request)
    {

        Balfilesupload::whereBetween('file_date', [Input::get('start_date'),Input::get('end_date')])->update(["upload_flg" => 0]);

        return redirect()->to("bankbalance/importbalance");
    }

    private function storeAccountData($outputArray,$bankbal_date,$file_name=NULL)
    {
        if(!empty($outputArray))
        {
            if(!empty($outputArray['ACCNO']))
            {
                $bankBalAc = Bankbalance::select("*","bank_balance.id as balId")->where("accno",$outputArray['ACCNO'])->where("bankbal_date",$bankbal_date)->first();

                if(empty($bankBalAc))
                {
                    $bankBalObj = new Bankbalance();
                    $uniqueId = Helper::generateUniqueId();

                    $bankBalObj->id = $uniqueId;
                    $bankBalObj->accno = $outputArray['ACCNO'];
                    $bankBalObj->currcode = @$outputArray['CURRCODE'];
                    $bankBalObj->acctype = @$outputArray['ACCTYPE'];
                    $bankBalObj->sortcode = @$outputArray['SORTCODE'];
                    $bankBalObj->bankacc = @$outputArray['BANKACC'];
                    $bankBalObj->feeband = @$outputArray['FEEBAND'];
                    $bankBalObj->payment = @$outputArray['PAYMENT'];
                    $bankBalObj->finamt = @$outputArray['FINAMT'];
                    $bankBalObj->blkamt = @$outputArray['BLKAMT'];
                    $bankBalObj->amtavl = @$outputArray['AMTAVL'];
                    $bankBalObj->bankbal_date = $bankbal_date;
                    $bankBalObj->file_name = $file_name;

                    if(!empty($outputArray['CARD']))
                    {
                      if(!empty($outputArray['CARD'][0]))
                      {
                        $bankBalObj->bank_pan = $outputArray['CARD'][0]['PAN'];
                      }
                      else
                      {
                        $bankBalObj->bank_pan = $outputArray['CARD']['PAN'];
                      }
                    }

                    if($bankBalObj->save())
                    {
                        return $uniqueId;
                    }
                    else
                    {
                        return false;
                    }

                    
                }
                else
                {
                    $bankBalAc->accno = $outputArray['ACCNO'];
                    $bankBalAc->currcode = @$outputArray['CURRCODE'];
                    $bankBalAc->acctype = @$outputArray['ACCTYPE'];
                    $bankBalAc->sortcode = @$outputArray['SORTCODE'];
                    $bankBalAc->bankacc = @$outputArray['BANKACC'];
                    $bankBalAc->feeband = @$outputArray['FEEBAND'];
                    $bankBalAc->payment = @$outputArray['PAYMENT'];
                    $bankBalAc->finamt = @$outputArray['FINAMT'];
                    $bankBalAc->blkamt = @$outputArray['BLKAMT'];
                    $bankBalAc->amtavl = @$outputArray['AMTAVL'];
                    $bankBalAc->bankbal_date = $bankbal_date;
                    $bankBalAc->file_name = $file_name;

                    if(!empty($outputArray['CARD']))
                    {
                      if(!empty($outputArray['CARD'][0]))
                      {
                        $bankBalAc->bank_pan = $outputArray['CARD'][0]['PAN'];
                      }
                      else
                      {
                        $bankBalAc->bank_pan = $outputArray['CARD']['PAN'];
                      }
                    }
                    
                    $bankBalAc->save();

                    return @$bankBalAc->balId;
                }
                
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    private function storeCardData($outputArray,$accountId)
    {
        if(!empty($outputArray['CARD']))
        {
            $outputArray = $outputArray['CARD'];
            if(!empty($outputArray[0]))
            {
                if(!empty($accountId))
                {
                    Bankbalancecard::where("bank_balance_id","=",$accountId)->delete();
                    foreach ($outputArray as $key => $value) 
                    {
                        if(!empty($value['PAN']))
                        {
                            $obj = new Bankbalancecard();
                            $uniqueId = Helper::generateUniqueId();

                            $obj->id = $uniqueId;
                            $obj->pan = $value['PAN'];
                            $obj->virtual = @$value['VIRTUAL'];
                            $obj->primary = @$value['PRIMARY'];
                            $obj->crdproduct = @$value['CRDPRODUCT'];
                            $obj->programid = @$value['PROGRAMID'];
                            $obj->custcode = @$value['CUSTCODE'];
                            $obj->statcode = @$value['STATCODE'];
                            $obj->expdate = @$value['EXPDATE'];
                            $obj->crdaccno = @$value['CRDACCNO'];
                            $obj->crdcurrcode = @$value['CRDCURRCODE'];
                            $obj->productid = @$value['PRODUCTID'];
                            $obj->bank_balance_id = $accountId;

                            $obj->save();
                        }
                    }
                }   
            }
            else
            {
                if(!empty($accountId))
                {
                    if(!empty($outputArray['PAN']))
                    {
                        Bankbalancecard::where("bank_balance_id","=",$accountId)->delete();
                        $obj = new Bankbalancecard();
                        $uniqueId = Helper::generateUniqueId();

                        $obj->id = $uniqueId;
                        $obj->pan = $outputArray['PAN'];
                        $obj->virtual = @$outputArray['VIRTUAL'];
                        $obj->primary = @$outputArray['PRIMARY'];
                        $obj->crdproduct = @$outputArray['CRDPRODUCT'];
                        $obj->programid = @$outputArray['PROGRAMID'];
                        $obj->custcode = @$outputArray['CUSTCODE'];
                        $obj->statcode = @$outputArray['STATCODE'];
                        $obj->expdate = @$outputArray['EXPDATE'];
                        $obj->crdaccno = @$outputArray['CRDACCNO'];
                        $obj->crdcurrcode = @$outputArray['CRDCURRCODE'];
                        $obj->productid = @$outputArray['PRODUCTID'];
                        $obj->bank_balance_id = $accountId;

                        $obj->save();
                    }
                }
            }
        }
    }
}
