<?php
namespace App\Http\Controllers\Banktransactions;

use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use App\Models\Actionlog;
use App\Models\Agencybanking;
use App\Models\Cardauthorisation;
use App\Models\Cardbaladjust;
use App\Models\Cardchrgbackrepres;
use App\Models\Cardfinancial;
use App\Models\Cardfee;
use App\Models\Cardloadunload;
use App\Models\Mastercardfee;
use App\Models\Agencybankingfee;
use App\Models\Cardevent;
use App\Models\Txnfilesupload;
use App\Http\Requests\ImportAgencybankingRequest;
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


class BanktransactionsController extends Controller
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
        return View::make('banktransactions/import');
    }

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
                'transaction_file.*' => 'required|mimes:txt,xml,html,XML,text/xml',         
            ]);
            
            if ($validator->fails()) {
                $error = $validator->errors()->first();            
                return redirect()->to("banktransactions/import")->with("error",$error);                
            }

            $file = Input::file('transaction_file');
            $fixcol=24;
            $path = config('app.private_uploads').'/banktxn';

            $results = array();

            $date = date('Y-m-d-his');
            $fixed_filename = str_replace(' ', '-', $file->getClientOriginalName());
            $fileData=array();
            $fileData = explode(".", $fixed_filename);
            $fileName_data = explode("exp", $fileData[0]);
            $file_date = date("Y-m-d");
            if(!empty($fileName_data[1]))
            {
                $fileName_data = explode("_", $fileName_data[1]);

                if(!empty($fileName_data[0]))
                {
                    $file_date = date("Y-m-d",strtotime($fileName_data[0]));
                }
            }
            
            $filePath = $path."/".$date.'-'.$fixed_filename;

            try {
                $file->move($path, $date.'-'.$fixed_filename);

                $this->fileImportHistoryLog($date.'-'.$fixed_filename,$path,"Banktransactions",Auth::user()->id);

                $z = new \XMLReader;
                $z->open($filePath);
                $doc = new \DOMDocument;

                $xmlUnstoredTag['ApprovedAgencyBanking'] = array();
                $xmlUnstoredTag['DeclinedAgencyBanking'] = array();
                $xmlUnstoredTag['CardEvent'] = array();
                $xmlUnstoredTag['AgencyBankingFee'] = array();
                $xmlUnstoredTag['CardAuthorisation'] = array();
                $xmlUnstoredTag['CardBalAdjust'] = array();
                $xmlUnstoredTag['CardChrgBackRepRes'] = array();
                $xmlUnstoredTag['CardFinancial'] = array();
                $xmlUnstoredTag['CardFee'] = array();
                $xmlUnstoredTag['CardLoadUnload'] = array();
                $xmlUnstoredTag['MasterCardFee'] = array();
                
                while ($z->read())
                {
                    $dataArray = array();
                    $dataArray = $this->txnXmlToArrayParser($doc , $z);

                    if($dataArray['key'] == 'ApprovedAgencyBanking' && !empty($dataArray['val']))
                    {
                        $this->ApprovedAgencyBanking($dataArray['val'] , $xmlUnstoredTag , $file_date ,$fixed_filename);
                    }
                    if($dataArray['key'] == 'DeclinedAgencyBanking' && !empty($dataArray['val']))
                    {
                        $this->DeclinedAgencyBanking($dataArray['val'] , $xmlUnstoredTag , $file_date ,$fixed_filename);
                    }
                    if($dataArray['key'] == 'CardEvent' && !empty($dataArray['val']))
                    {
                        $this->CardEvent($dataArray['val'] , $xmlUnstoredTag , $file_date ,$fixed_filename);
                    }
                    if($dataArray['key'] == 'AgencyBankingFee' && !empty($dataArray['val']))
                    {
                        $this->AgencyBankingFee($dataArray['val'] , $xmlUnstoredTag , $file_date ,$fixed_filename);
                    }
                    if($dataArray['key'] == 'CardAuthorisation' && !empty($dataArray['val']))
                    {
                        $this->CardAuthorisation($dataArray['val'] , $xmlUnstoredTag , $file_date ,$fixed_filename);
                    }
                    if($dataArray['key'] == 'CardBalAdjust' && !empty($dataArray['val']))
                    {
                        $this->CardBalAdjust($dataArray['val'] , $xmlUnstoredTag , $file_date ,$fixed_filename);
                    }
                    if($dataArray['key'] == 'CardChrgBackRepRes' && !empty($dataArray['val']))
                    {
                        $this->CardChrgBackRepRes($dataArray['val'] , $xmlUnstoredTag , $file_date ,$fixed_filename);
                    }
                    if($dataArray['key'] == 'CardFinancial' && !empty($dataArray['val']))
                    {
                        $this->CardFinancial($dataArray['val'] , $xmlUnstoredTag , $file_date ,$fixed_filename);
                    }
                    if($dataArray['key'] == 'CardFee' && !empty($dataArray['val']))
                    {
                        $this->CardFee($dataArray['val'] , $xmlUnstoredTag , $file_date ,$fixed_filename);
                    }
                    if($dataArray['key'] == 'CardLoadUnload' && !empty($dataArray['val']))
                    {
                        $this->CardLoadUnload($dataArray['val'] , $xmlUnstoredTag , $file_date ,$fixed_filename);
                    }
                    if($dataArray['key'] == 'MasterCardFee' && !empty($dataArray['val']))
                    {
                        $this->MasterCardFee($dataArray['val'] , $xmlUnstoredTag , $file_date ,$fixed_filename);
                    }

                    unset($dataArray);
                }

                unset($z);
                unset($doc);

              //  $this->sendUnknownColumnEmail($xmlUnstoredTag);
                

            } catch (\Symfony\Component\HttpFoundation\File\Exception\FileException $exception) {
                $results['error']=trans('admin/banktxn/message.upload.error');
                if (config('app.debug')) {
                    $results['error'].= ' ' . $exception->getMessage();
                }
                return $results;
            }
           
            return redirect()->to("banktransactions/import")->with("success","Bank Transactions imported successfully.");
    
    }

    // public function BKP_importTransactionFile()
    // {
    //     $sftp = new SFTP(config('app.TXN_SFTP_SERVER'));

    //     if (!$sftp->login(config('app.TXN_SFTP_USERNAME'), config('app.TXN_SFTP_PASSWORD'))) {
    //         throw new Exception('Login failed');
    //     }
    //     $file_list = $sftp->nlist(config('app.TXN_SFTP_PATH'));

    //     foreach ($file_list as $key => $value) 
    //     {
    //         if($sftp->is_file(config('app.TXN_SFTP_PATH')."/".$value))
    //         {
    //             $xmlData = $sftp->get(config('app.TXN_SFTP_PATH')."/".$value);

    //             if(!empty($xmlData))
    //             {
    //                 $fileData = Txnfilesupload::where("filename",$value)->first();

    //                 if(empty($fileData) || $fileData->upload_flg == 0)
    //                 {
    //                     $path = config('app.private_uploads').'/banktxn';
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
    //                         $txnfilesObj = new Txnfilesupload();
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

    //                         $this->fileImportHistoryLog($value,$path,"Banktransactions",$user_Id);

    //                         $z = new \XMLReader;
    //                         $z->open($filePath);
    //                         $doc = new \DOMDocument;

    //                         $xmlUnstoredTag['ApprovedAgencyBanking'] = array();
    //                         $xmlUnstoredTag['DeclinedAgencyBanking'] = array();
    //                         $xmlUnstoredTag['CardEvent'] = array();
    //                         $xmlUnstoredTag['AgencyBankingFee'] = array();
    //                         $xmlUnstoredTag['CardAuthorisation'] = array();
    //                         $xmlUnstoredTag['CardBalAdjust'] = array();
    //                         $xmlUnstoredTag['CardChrgBackRepRes'] = array();
    //                         $xmlUnstoredTag['CardFinancial'] = array();
    //                         $xmlUnstoredTag['CardFee'] = array();
    //                         $xmlUnstoredTag['CardLoadUnload'] = array();
    //                         $xmlUnstoredTag['MasterCardFee'] = array();

    //                         while ($z->read())
    //                         {
    //                             $dataArray = array();
    //                             $dataArray = $this->txnXmlToArrayParser($doc , $z);

    //                             if($dataArray['key'] == 'ApprovedAgencyBanking' && !empty($dataArray['val']))
    //                             {
    //                                 $this->ApprovedAgencyBanking($dataArray['val'] , $xmlUnstoredTag , $file_date ,$value);
    //                             }
    //                             if($dataArray['key'] == 'DeclinedAgencyBanking' && !empty($dataArray['val']))
    //                             {
    //                                 $this->DeclinedAgencyBanking($dataArray['val'] , $xmlUnstoredTag , $file_date ,$value);
    //                             }
    //                             if($dataArray['key'] == 'CardEvent' && !empty($dataArray['val']))
    //                             {
    //                                 $this->CardEvent($dataArray['val'] , $xmlUnstoredTag , $file_date ,$value);
    //                             }
    //                             if($dataArray['key'] == 'AgencyBankingFee' && !empty($dataArray['val']))
    //                             {
    //                                 $this->AgencyBankingFee($dataArray['val'] , $xmlUnstoredTag , $file_date ,$value);
    //                             }
    //                             if($dataArray['key'] == 'CardAuthorisation' && !empty($dataArray['val']))
    //                             {
    //                                 $this->CardAuthorisation($dataArray['val'] , $xmlUnstoredTag , $file_date ,$value);
    //                             }
    //                             if($dataArray['key'] == 'CardBalAdjust' && !empty($dataArray['val']))
    //                             {
    //                                 $this->CardBalAdjust($dataArray['val'] , $xmlUnstoredTag , $file_date ,$value);
    //                             }
    //                             if($dataArray['key'] == 'CardChrgBackRepRes' && !empty($dataArray['val']))
    //                             {
    //                                 $this->CardChrgBackRepRes($dataArray['val'] , $xmlUnstoredTag , $file_date ,$value);
    //                             }
    //                             if($dataArray['key'] == 'CardFinancial' && !empty($dataArray['val']))
    //                             {
    //                                 $this->CardFinancial($dataArray['val'] , $xmlUnstoredTag , $file_date ,$value);
    //                             }
    //                             if($dataArray['key'] == 'CardFee' && !empty($dataArray['val']))
    //                             {
    //                                 $this->CardFee($dataArray['val'] , $xmlUnstoredTag , $file_date ,$value);
    //                             }
    //                             if($dataArray['key'] == 'CardLoadUnload' && !empty($dataArray['val']))
    //                             {
    //                                 $this->CardLoadUnload($dataArray['val'] , $xmlUnstoredTag , $file_date ,$value);
    //                             }
    //                             if($dataArray['key'] == 'MasterCardFee' && !empty($dataArray['val']))
    //                             {
    //                                 $this->MasterCardFee($dataArray['val'] , $xmlUnstoredTag , $file_date ,$value);
    //                             }

    //                             unset($dataArray);
    //                         }

    //                         unset($z);
    //                         unset($doc);

    //                         $this->sendUnknownColumnEmail($xmlUnstoredTag);


                          

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
    //     return redirect()->to("banktransactions/import")->with("success","Bank Transactions imported successfully.");
    // }

    public function importTransactionFile()
    {
        $file_list = scandir(config('app.TXN_SFTP_PATH'));

        foreach ($file_list as $key => $value) 
        {
            if(is_file(config('app.TXN_SFTP_PATH')."/".$value))
            {
                $xmlData = file_get_contents(config('app.TXN_SFTP_PATH')."/".$value);
                if(!empty($xmlData))
                {
                    $fileData = Txnfilesupload::where("filename",$value)->first();

                    if(empty($fileData) || $fileData->upload_flg == 0)
                    {
                        $path = config('app.private_uploads').'/banktxn';
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
                            $txnfilesObj = new Txnfilesupload();
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

                            $this->fileImportHistoryLog($value,$path,"Banktransactions",$user_Id);

                            $z = new \XMLReader;
                            $z->open($filePath);
                            $doc = new \DOMDocument;

                            $xmlUnstoredTag['ApprovedAgencyBanking'] = array();
                            $xmlUnstoredTag['DeclinedAgencyBanking'] = array();
                            $xmlUnstoredTag['CardEvent'] = array();
                            $xmlUnstoredTag['AgencyBankingFee'] = array();
                            $xmlUnstoredTag['CardAuthorisation'] = array();
                            $xmlUnstoredTag['CardBalAdjust'] = array();
                            $xmlUnstoredTag['CardChrgBackRepRes'] = array();
                            $xmlUnstoredTag['CardFinancial'] = array();
                            $xmlUnstoredTag['CardFee'] = array();
                            $xmlUnstoredTag['CardLoadUnload'] = array();
                            $xmlUnstoredTag['MasterCardFee'] = array();

                            while ($z->read())
                            {
                                $dataArray = array();
                                $dataArray = $this->txnXmlToArrayParser($doc , $z);

                                if($dataArray['key'] == 'ApprovedAgencyBanking' && !empty($dataArray['val']))
                                {
                                    $this->ApprovedAgencyBanking($dataArray['val'] , $xmlUnstoredTag , $file_date ,$value);
                                }
                                if($dataArray['key'] == 'DeclinedAgencyBanking' && !empty($dataArray['val']))
                                {
                                    $this->DeclinedAgencyBanking($dataArray['val'] , $xmlUnstoredTag , $file_date ,$value);
                                }
                                if($dataArray['key'] == 'CardEvent' && !empty($dataArray['val']))
                                {
                                    $this->CardEvent($dataArray['val'] , $xmlUnstoredTag , $file_date ,$value);
                                }
                                if($dataArray['key'] == 'AgencyBankingFee' && !empty($dataArray['val']))
                                {
                                    $this->AgencyBankingFee($dataArray['val'] , $xmlUnstoredTag , $file_date ,$value);
                                }
                                if($dataArray['key'] == 'CardAuthorisation' && !empty($dataArray['val']))
                                {
                                    $this->CardAuthorisation($dataArray['val'] , $xmlUnstoredTag , $file_date ,$value);
                                }
                                if($dataArray['key'] == 'CardBalAdjust' && !empty($dataArray['val']))
                                {
                                    $this->CardBalAdjust($dataArray['val'] , $xmlUnstoredTag , $file_date ,$value);
                                }
                                if($dataArray['key'] == 'CardChrgBackRepRes' && !empty($dataArray['val']))
                                {
                                    $this->CardChrgBackRepRes($dataArray['val'] , $xmlUnstoredTag , $file_date ,$value);
                                }
                                if($dataArray['key'] == 'CardFinancial' && !empty($dataArray['val']))
                                {
                                    $this->CardFinancial($dataArray['val'] , $xmlUnstoredTag , $file_date ,$value);
                                }
                                if($dataArray['key'] == 'CardFee' && !empty($dataArray['val']))
                                {
                                    $this->CardFee($dataArray['val'] , $xmlUnstoredTag , $file_date ,$value);
                                }
                                if($dataArray['key'] == 'CardLoadUnload' && !empty($dataArray['val']))
                                {
                                    $this->CardLoadUnload($dataArray['val'] , $xmlUnstoredTag , $file_date ,$value);
                                }
                                if($dataArray['key'] == 'MasterCardFee' && !empty($dataArray['val']))
                                {
                                    $this->MasterCardFee($dataArray['val'] , $xmlUnstoredTag , $file_date ,$value);
                                }

                                unset($dataArray);
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
                            

                            $this->sendUnknownColumnEmail($xmlUnstoredTag);


                          

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
        return redirect()->to("banktransactions/import")->with("success","Bank Transactions imported successfully.");
    }

    public function changeFileFlag(FileFlagRequest $request)
    {

        Txnfilesupload::whereBetween('file_date', [Input::get('start_date'),Input::get('end_date')])->update(["upload_flg" => 0]);

        return redirect()->to("banktransactions/importtransaction");
    }

    private function ApprovedAgencyBanking($outputArray , &$xmlUnstoredTag , $file_date ,$file_name = null )
    {
        if(!empty($outputArray))
        {
            $CardAuthorisation_column = array("CashType","BankingId","SettlementDate","Desc","DeclineReason","File_filedate","File_filename","Card_PAN","Card_productid","Card_product","Card_programid","Card_branchcode","AgencyAccount_no","AgencyAccount_type","AgencyAccount_sortcode","AgencyAccount_bankacc","AgencyAccount_name","External_sortcode","External_bankacc","External_name","CashCode_direction","CashCode_CashType","CashCode_CashGroup","CashAmt_value","CashAmt_currency","Fee_direction","Fee_value","Fee_currency","BillAmt_value","BillAmt_currency","BillAmt_rate","OrigTxnAmt_value","OrigTxnAmt_currency","OrigTxnAmt_partial","OrigTxnAmt_origItemId");
            $dataArray = array();
            if(isset($outputArray[0]))
            {
                $dataArray = $outputArray;
            }
            else
            {
                $dataArray[0] = $outputArray;
            }
            foreach ($dataArray as $key => $value) 
            {
                if(!empty($value['BankingId']) && !empty($value['CashType']) && !empty($value['SettlementDate']))
                {
                    $bankBalAc = Agencybanking::where("BankingId",$value['BankingId'])->first();

                    if(empty($bankBalAc))
                    {
                        $AgencyBankObj = new Agencybanking();
                        $uniqueId = Helper::generateUniqueId();

                        $AgencyBankObj->id = $uniqueId;
                        $AgencyBankObj->banking_type = "Approved";     
                        $AgencyBankObj->file_date = $file_date;     
                        $AgencyBankObj->file_name = $file_name;     

                        foreach ($value as $key1 => $value1) 
                        {
                            if(is_array($value1))
                            {
                                if(!empty($value1['@attributes']))
                                {
                                    foreach ($value1['@attributes'] as $key2 => $value2) {
                                        $colName = $key1."_".$key2;

                                        if(in_array($colName, $CardAuthorisation_column))
                                        {
                                            $AgencyBankObj->$colName = $value2;
                                        }
                                        else
                                        {
                                            if(isset($xmlUnstoredTag['ApprovedAgencyBanking']) && !in_array($colName, $xmlUnstoredTag['ApprovedAgencyBanking']))
                                                $xmlUnstoredTag['ApprovedAgencyBanking'][] = $colName;
                                        }
                                    }
                                }
                            }
                            else
                            {
                                if($key1 == 'SettlementDate' && in_array($key1, $CardAuthorisation_column))
                                {
                                    $AgencyBankObj->SettlementDate = date("Y-m-d",strtotime($value1));
                                }
                                else
                                {
                                    if(!empty($value1))
                                    {
                                        if(in_array($key1, $CardAuthorisation_column))
                                            $AgencyBankObj->$key1 = $value1;
                                        else
                                        {
                                            if(isset($xmlUnstoredTag['ApprovedAgencyBanking']) && !in_array($key1, $xmlUnstoredTag['ApprovedAgencyBanking']))
                                                $xmlUnstoredTag['ApprovedAgencyBanking'][] = $key1;
                                        }
                                    }
                                }
                            }
                        }

                        if($AgencyBankObj->External_name == "Unknown")
                        {
                            $AgencyBankObj->External_name = $AgencyBankObj->External_sortcode." ".$AgencyBankObj->External_bankacc;
                        }   
                           
                        $AgencyBankObj->save();
                    }
                    
                }
                
            }
        }
    }

    private function DeclinedAgencyBanking($outputArray , &$xmlUnstoredTag , $file_date ,$file_name = null)
    {
        if(!empty($outputArray))
        {
            $CardAuthorisation_column = array("CashType","BankingId","SettlementDate","Desc","DeclineReason","File_filedate","File_filename","Card_PAN","Card_productid","Card_product","Card_programid","Card_branchcode","AgencyAccount_no","AgencyAccount_type","AgencyAccount_sortcode","AgencyAccount_bankacc","AgencyAccount_name","External_sortcode","External_bankacc","External_name","CashCode_direction","CashCode_CashType","CashCode_CashGroup","CashAmt_value","CashAmt_currency","Fee_direction","Fee_value","Fee_currency","BillAmt_value","BillAmt_currency","BillAmt_rate","OrigTxnAmt_value","OrigTxnAmt_currency","OrigTxnAmt_partial","OrigTxnAmt_origItemId");

            $dataArray = array();
            if(isset($outputArray[0]))
            {
                $dataArray = $outputArray;
            }
            else
            {
                $dataArray[0] = $outputArray;
            }
            foreach ($dataArray as $key => $value) 
            {
                if(!empty($value['BankingId']) && !empty($value['CashType']) && !empty($value['SettlementDate']))
                {
                    $bankBalAc = Agencybanking::where("BankingId",$value['BankingId'])->first();

                    if(empty($bankBalAc))
                    {
                        $AgencyBankObj = new Agencybanking();
                        $uniqueId = Helper::generateUniqueId();

                        $AgencyBankObj->id = $uniqueId;
                        $AgencyBankObj->banking_type = "Declined";  
                        $AgencyBankObj->file_date = $file_date;
                        $AgencyBankObj->file_name = $file_name;

                        foreach ($value as $key1 => $value1) 
                        {
                            if(is_array($value1))
                            {
                                if(!empty($value1['@attributes']))
                                {
                                    foreach ($value1['@attributes'] as $key2 => $value2) {
                                        $colName = $key1."_".$key2;

                                        if(in_array($colName, $CardAuthorisation_column))
                                        {
                                            $AgencyBankObj->$colName = $value2;
                                        }
                                        else
                                        {
                                            if(isset($xmlUnstoredTag['DeclinedAgencyBanking']) && !in_array($colName, $xmlUnstoredTag['DeclinedAgencyBanking']))
                                                $xmlUnstoredTag['DeclinedAgencyBanking'][] = $colName;
                                        }
                                    }
                                }
                            }
                            else
                            {
                                if($key1 == 'SettlementDate' && in_array($key1, $CardAuthorisation_column))
                                {
                                    $AgencyBankObj->SettlementDate = date("Y-m-d",strtotime($value1));
                                }
                                else
                                {
                                    if(!empty($value1))
                                    {
                                        if(in_array($key1, $CardAuthorisation_column))
                                            $AgencyBankObj->$key1 = $value1;
                                        else
                                        {
                                            if(isset($xmlUnstoredTag['DeclinedAgencyBanking']) && !in_array($key1, $xmlUnstoredTag['DeclinedAgencyBanking']))
                                                $xmlUnstoredTag['DeclinedAgencyBanking'][] = $key1;
                                        }
                                    }
                                }
                            }
                        }

                        if($AgencyBankObj->External_name == "Unknown")
                        {
                            $AgencyBankObj->External_name = $AgencyBankObj->External_sortcode." ".$AgencyBankObj->External_bankacc;
                        } 
                        
                        $AgencyBankObj->save();
                    }
                    
                }
                
            }
        }
    }

    private function CardEvent($outputArray , &$xmlUnstoredTag , $file_date ,$file_name = null)
    {
        if(!empty($outputArray))
        {
           
            $dataArray = array();
            if(isset($outputArray[0]))
            {
                $dataArray = $outputArray;
            }
            else
            {
                $dataArray[0] = $outputArray;
            }

            foreach ($dataArray as $key => $value) 
            {
                $cardBankObj = new Cardevent();
                $uniqueId = Helper::generateUniqueId();
                $cardBankObj->id = $uniqueId;

                $cardBankObj->Card_PAN = $value['Card']['@attributes']['PAN'];
                $cardBankObj->Card_productid = @$value['Card']['@attributes']['productid'];
                $cardBankObj->Event_Type = $value['Event']['@attributes']['Type'];
                $cardBankObj->Event_Source = $value['Event']['@attributes']['Source'];
                if(!empty($value['Event']['@attributes']['ActivationDate']))
                    $cardBankObj->Event_ActivationDate = date("Y-m-d H:i:s",strtotime($value['Event']['@attributes']['ActivationDate']));
                $cardBankObj->Event_StatCode = $value['Event']['@attributes']['StatCode'];
                $cardBankObj->Event_OldStatCode = $value['Event']['@attributes']['OldStatCode'];
                if(!empty($value['Event']['@attributes']['Date']))
                    $cardBankObj->Event_Date = date("Y-m-d H:i:s",strtotime($value['Event']['@attributes']['Date']));
                $cardBankObj->file_date = $file_date;
                $cardBankObj->file_name = $file_name;
                
                $cardBankObj->save();
                
            }
        }
    }

    private function AgencyBankingFee($outputArray , &$xmlUnstoredTag , $file_date ,$file_name = null)
    {
        if(!empty($outputArray))
        {
            $CardAuthorisation_column = array("BankingFeeId","AbId","SettlementDate","Desc","Card_PAN","Card_productid","Card_product","Card_programid","Card_branchcode","AgencyAccount_no","AgencyAccount_type","AgencyAccount_sortcode","AgencyAccount_bankacc","AgencyAccount_name","Amt_direction","Amt_value","Amt_currency");
            $dataArray = array();
            if(isset($outputArray[0]))
            {
                $dataArray = $outputArray;
            }
            else
            {
                $dataArray[0] = $outputArray;
            }
            $i =0;
            foreach ($dataArray as $key => $value) 
            {
                if(!empty($value['BankingFeeId']) && !empty($value['SettlementDate']))
                {
                    $bankBalAc = Agencybankingfee::where("BankingFeeId",$value['BankingFeeId'])->first();

                    if(empty($bankBalAc))
                    {
                        $cardBankObj = new Agencybankingfee();
                        $uniqueId = Helper::generateUniqueId();
                        $cardBankObj->id = $uniqueId;
                        $cardBankObj->file_date = $file_date;
                        $cardBankObj->file_name = $file_name;
                        foreach ($value as $key1 => $value1) 
                        {
                            if(is_array($value1))
                            {
                                if(!empty($value1['@attributes']))
                                {
                                    foreach ($value1['@attributes'] as $key2 => $value2) {
                                        $colName = $key1."_".$key2;

                                        if(in_array($colName, $CardAuthorisation_column))
                                        {
                                            $cardBankObj->$colName = $value2;
                                        }
                                        else
                                        {
                                            if(isset($xmlUnstoredTag['AgencyBankingFee']) && !in_array($colName, $xmlUnstoredTag['AgencyBankingFee']))
                                                $xmlUnstoredTag['AgencyBankingFee'][] = $colName;
                                        }
                                    }
                                }
                            }
                            else
                            {
                                if($key1 == 'SettlementDate' && in_array($key1, $CardAuthorisation_column))
                                {
                                    $cardBankObj->SettlementDate = date("Y-m-d",strtotime($value1));
                                }
                                else
                                {
                                    if(!empty($value1))
                                    {
                                        if(in_array($key1, $CardAuthorisation_column))
                                            $cardBankObj->$key1 = $value1;
                                        else
                                        {
                                            if(isset($xmlUnstoredTag['AgencyBankingFee']) && !in_array($key1, $xmlUnstoredTag['AgencyBankingFee']))
                                                $xmlUnstoredTag['AgencyBankingFee'][] = $key1;
                                        }
                                    }
                                }
                            }
                        }

                        $cardBankObj->save();
                    }
                    
                }
                
            }
        }
    }

    private function CardAuthorisation($outputArray , &$xmlUnstoredTag , $file_date ,$file_name = null)
    {
        if(!empty($outputArray))
        {
            $CardAuthorisation_column = array("RecType",  "AuthId",  "LocalDate",  "SettlementDate",  "ApprCode",  "MerchCode",  "Schema",  "ReversalReason",  "Card_PAN",  "Card_product",  "Card_programId",  "Card_branchCode",  "Card_productid",  "Account_no",  "Account_type",  "TxnCode_direction",  "TxnCode_Type",  "TxnCode_Group",  "TxnCode_ProcCode",  "TxnAmt_value",  "TxnAmt_currency",  "CashbackAmt_value",  "CashbackAmt_currency",  "BillAmt_value",  "BillAmt_currency",  "BillAmt_rate",  "BillAmt_clientfxrate",  "Trace_auditno",  "Trace_origauditno",  "Trace_Retrefno",  "Term_code",  "Term_location",  "Term_street",  "Term_city",  "Term_country",  "Term_inputcapability",  "Term_authcapability",  "Txn_cardholderpresent",  "Txn_cardpresent",  "Txn_cardinputmethod",  "Txn_cardauthmethod",  "Txn_cardauthentity",  "Txn_TVR",  "MsgSource_value",  "MsgSource_domesticMaestro",  "PaddingAmt_value",  "PaddingAmt_currency",  "CommissionAmt_value",  "CommissionAmt_currency",  "Classification_RCC",  "Classification_MCC",  "Response_approved",  "Response_actioncode",  "Response_responsecode",  "OrigTxnAmt_value",  "OrigTxnAmt_currency",  "OrigTxnAmt_origItemId",  "OrigTxnAmt_partial");
            $dataArray = array();
            if(isset($outputArray[0]))
            {
                $dataArray = $outputArray;
            }
            else
            {
                $dataArray[0] = $outputArray;
            }
            $i =0;
            foreach ($dataArray as $key => $value) 
            {
                if(!empty($value['RecType']) && !empty($value['AuthId']) && !empty($value['SettlementDate']))
                {
                    $bankBalAc = Cardauthorisation::where("AuthId",$value['AuthId'])->first();

                    if(empty($bankBalAc))
                    {
                        $cardBankObj = new Cardauthorisation();
                        $uniqueId = Helper::generateUniqueId();
                        $cardBankObj->id = $uniqueId;
                        $cardBankObj->file_date = $file_date;
                        $cardBankObj->file_name = $file_name;

                        foreach ($value as $key1 => $value1) 
                        {
                            if(is_array($value1))
                            {
                                if(!empty($value1['@attributes']))
                                {
                                    foreach ($value1['@attributes'] as $key2 => $value2) {
                                        $colName = $key1."_".$key2;

                                        if(in_array($colName, $CardAuthorisation_column))
                                        {
                                            $cardBankObj->$colName = $value2;
                                        }
                                        else
                                        {
                                            if(isset($xmlUnstoredTag['CardAuthorisation']) && !in_array($colName, $xmlUnstoredTag['CardAuthorisation']))
                                                $xmlUnstoredTag['CardAuthorisation'][] = $colName;
                                        }
                                    }
                                }
                            }
                            else
                            {
                                if($key1 == 'LocalDate' && in_array($key1, $CardAuthorisation_column))
                                {
                                    $cardBankObj->LocalDate = date("Y-m-d H:i:s",strtotime($value1));
                                }
                                elseif($key1 == 'SettlementDate' && in_array($key1, $CardAuthorisation_column))
                                {
                                    $cardBankObj->SettlementDate = date("Y-m-d",strtotime($value1));
                                }
                                else
                                {
                                    if(!empty($value1))
                                    {
                                        if(in_array($key1, $CardAuthorisation_column))
                                            $cardBankObj->$key1 = $value1;
                                        else
                                        {
                                            if(isset($xmlUnstoredTag['CardAuthorisation']) && !in_array($key1, $xmlUnstoredTag['CardAuthorisation']))
                                                $xmlUnstoredTag['CardAuthorisation'][] = $key1;
                                        }
                                    }
                                }
                            }
                        }

                        $cardBankObj->save();
                    }
                    
                }
                
            }
        }        
    }
    
    private function CardBalAdjust($outputArray , &$xmlUnstoredTag , $file_date ,$file_name = null)
    {
        if(!empty($outputArray))
        {
            $CardBalAdjust_column = array("RecType","AdjustId","MessageId","LocalDate","SettlementDate","VoidedAdjustId","MerchCode","Desc","Card_PAN","Card_product","Card_programid","Card_branchcode","Card_productid","Account_no","Account_type","Amount_direction","Amount_value","Amount_currency");

            $dataArray = array();
            if(isset($outputArray[0]))
            {
                $dataArray = $outputArray;
            }
            else
            {
                $dataArray[0] = $outputArray;
            }
            $i =0;
            foreach ($dataArray as $key => $value) 
            {
                if(!empty($value['RecType']) && !empty($value['AdjustId']) && !empty($value['SettlementDate']))
                {
                    $bankBalAc = Cardbaladjust::where("AdjustId",$value['AdjustId'])->first();

                    if(empty($bankBalAc))
                    {
                        $cardBankObj = new Cardbaladjust();
                        $uniqueId = Helper::generateUniqueId();
                        $cardBankObj->id = $uniqueId;
                        $cardBankObj->file_date = $file_date;
                        $cardBankObj->file_name = $file_name;

                        foreach ($value as $key1 => $value1) 
                        {
                            if(is_array($value1))
                            {
                                if(!empty($value1['@attributes']))
                                {
                                    foreach ($value1['@attributes'] as $key2 => $value2) {
                                        $colName = $key1."_".$key2;
                                        if(in_array($colName, $CardBalAdjust_column))
                                        {
                                            $cardBankObj->$colName = $value2;
                                        }
                                        else
                                        {
                                            if(isset($xmlUnstoredTag['CardBalAdjust']) && !in_array($colName, $xmlUnstoredTag['CardBalAdjust']))
                                                $xmlUnstoredTag['CardBalAdjust'][] = $colName;
                                        }
                                    }
                                }
                            }
                            else
                            {
                                if($key1 == 'LocalDate' && in_array($key1, $CardBalAdjust_column))
                                {
                                    $cardBankObj->LocalDate = date("Y-m-d H:i:s",strtotime($value1));
                                }
                                elseif($key1 == 'SettlementDate' && in_array($key1, $CardBalAdjust_column))
                                {
                                    $cardBankObj->SettlementDate = date("Y-m-d",strtotime($value1));
                                }
                                else
                                {
                                    if(!empty($value1))
                                    {
                                        if(in_array($key1, $CardBalAdjust_column))
                                            $cardBankObj->$key1 = $value1;
                                        else
                                        {
                                            if(isset($xmlUnstoredTag['CardBalAdjust']) && !in_array($key1, $xmlUnstoredTag['CardBalAdjust']))
                                                $xmlUnstoredTag['CardBalAdjust'][] = $key1;
                                        }
                                    }
                                }
                            }
                        }

                        $cardBankObj->save();
                    }
                    
                }
                
            }
        }
    }
    
    private function CardChrgBackRepRes($outputArray , &$xmlUnstoredTag , $file_date ,$file_name = null)
    {
        if(!empty($outputArray))
        {
            $CardBalAdjust_column = array("RecordType","ChgbackRepresId","LocalDate","SettlementDate","ApprCode","MerchCode","Schema","Repeat","ARN","FIID","RIID","ReasonCode","PartialReversal","Card_PAN","Card_product","Card_programid","Card_branchcode","Card_productid","Account_no","Account_type","TxnCode_direction","TxnCode_Type","TxnCode_Group","TxnAmt_value","TxnAmt_currency","CashbackAmt_value","CashbackAmt_currency","BillAmt_value","BillAmt_currency","BillAmt_rate","Trace_auditno","Trace_origauditno","Trace_Retrefno","Term_code","Term_location","Term_street","Term_city","Term_country","Term_inputcapability","Term_authcapability","Txn_cardholderpresent","Txn_cardpresent","Txn_cardinputmethod","Txn_cardauthmethod","Txn_cardauthentity","Txn_TVR","MsgSource_value","MsgSource_domesticMaestro","SettlementAmt_value","SettlementAmt_currency","SettlementAmt_rate","SettlementAmt_date","Fee_direction","Fee_value","Fee_currency","Classification_RCC","Classification_MCC","OrigTxnAmt_value","OrigTxnAmt_currency","OrigTxnAmt_origItemId","OrigTxnAmt_partial");

            $dataArray = array();
            if(isset($outputArray[0]))
            {
                $dataArray = $outputArray;
            }
            else
            {
                $dataArray[0] = $outputArray;
            }
            $i =0;
            foreach ($dataArray as $key => $value) 
            {
                if(!empty($value['RecordType']) && !empty($value['ChgbackRepresId']) && !empty($value['SettlementDate']))
                {
                    $bankBalAc = Cardchrgbackrepres::where("ChgbackRepresId",$value['ChgbackRepresId'])->first();

                    if(empty($bankBalAc))
                    {
                        $cardBankObj = new Cardchrgbackrepres();
                        $uniqueId = Helper::generateUniqueId();
                        $cardBankObj->id = $uniqueId;
                        $cardBankObj->file_date = $file_date;
                        $cardBankObj->file_name = $file_name;

                        foreach ($value as $key1 => $value1) 
                        {
                            if(is_array($value1))
                            {
                                if(!empty($value1['@attributes']))
                                {
                                    foreach ($value1['@attributes'] as $key2 => $value2) {
                                        $colName = $key1."_".$key2;
                                        if(in_array($colName, $CardBalAdjust_column))
                                        {
                                            $cardBankObj->$colName = $value2;
                                        }
                                        else
                                        {
                                            if(isset($xmlUnstoredTag['CardChrgBackRepRes']) && !in_array($colName, $xmlUnstoredTag['CardChrgBackRepRes']))
                                                $xmlUnstoredTag['CardChrgBackRepRes'][] = $colName;
                                        }
                                    }
                                }
                            }
                            else
                            {
                                if($key1 == 'LocalDate' && in_array($key1, $CardBalAdjust_column))
                                {
                                    $cardBankObj->LocalDate = date("Y-m-d H:i:s",strtotime($value1));
                                }
                                elseif($key1 == 'SettlementDate' && in_array($key1, $CardBalAdjust_column))
                                {
                                    $cardBankObj->SettlementDate = date("Y-m-d",strtotime($value1));
                                }
                                else
                                {
                                    if(!empty($value1))
                                    {
                                        if(in_array($key1, $CardBalAdjust_column))
                                            $cardBankObj->$key1 = $value1;
                                        else
                                        {
                                            if(isset($xmlUnstoredTag['CardChrgBackRepRes']) && !in_array($key1, $xmlUnstoredTag['CardChrgBackRepRes']))
                                                $xmlUnstoredTag['CardChrgBackRepRes'][] = $key1;
                                        }
                                    }
                                }
                            }
                        }

                        $cardBankObj->save();
                    }
                    
                }
                
            }
        }
    }

    private function CardFinancial($outputArray , &$xmlUnstoredTag , $file_date ,$file_name = null)
    {
        if(!empty($outputArray))
        {
            $CardBalAdjust_column = array("RecordType","FinId","AuthId","LocalDate","SettlementDate","ApprCode","MerchCode","Schema","ARN","FIID","RIID","ReasonCode","Card_productid","Card_PAN","Card_product","Card_programid","Card_branchcode","Account_no","Account_type","TxnCode_direction","TxnCode_Type","TxnCode_Group","TxnCode_ProcCode","TxnAmt_value","TxnAmt_currency","CashbackAmt_value","CashbackAmt_currency","BillAmt_value","BillAmt_currency","BillAmt_rate","Trace_auditno","Trace_origauditno","Trace_Retrefno","Term_code","Term_location","Term_street","Term_city","Term_country","Term_inputcapability","Term_authcapability","Txn_cardholderpresent","Txn_cardpresent","Txn_cardinputmethod","Txn_cardauthmethod","Txn_cardauthentity","Txn_TVR","MsgSource_value","MsgSource_domesticMaestro","Fee_direction","Fee_value","Fee_currency","SettlementAmt_value","SettlementAmt_currency","SettlementAmt_rate","SettlementAmt_date","Classification_RCC","Classification_MCC","Response_approved","Response_actioncode","Response_responsecode","OrigTxnAmt_value","OrigTxnAmt_currency","OrigTxnAmt_origItemId","OrigTxnAmt_partial","CCAAmount_value","CCAAmount_currency","CCAAmount_included");

            $dataArray = array();
            if(isset($outputArray[0]))
            {
                $dataArray = $outputArray;
            }
            else
            {
                $dataArray[0] = $outputArray;
            }
            $i = 0;
            foreach ($dataArray as $key => $value) 
            {
                if(!empty($value['RecordType']) && !empty($value['FinId']) && !empty($value['SettlementDate']))
                {
                    $bankBalAc = Cardfinancial::where("FinId",$value['FinId'])->first();

                    if(empty($bankBalAc))
                    {
                        $cardBankObj = new Cardfinancial();
                        $uniqueId = Helper::generateUniqueId();
                        $cardBankObj->id = $uniqueId;
                        $cardBankObj->file_date = $file_date;
                        $cardBankObj->file_name = $file_name;
                        foreach ($value as $key1 => $value1) 
                        {
                            if(is_array($value1))
                            {
                                if(!empty($value1['@attributes']))
                                {
                                    foreach ($value1['@attributes'] as $key2 => $value2) {
                                        $colName = $key1."_".$key2;
                                        if(in_array($colName, $CardBalAdjust_column))
                                        {
                                            $cardBankObj->$colName = $value2;
                                        }
                                        else
                                        {
                                            if(isset($xmlUnstoredTag['CardFinancial']) && !in_array($colName, $xmlUnstoredTag['CardFinancial']))
                                                $xmlUnstoredTag['CardFinancial'][] = $colName;
                                        }
                                    }
                                }
                            }
                            else
                            {
                                if($key1 == 'LocalDate' && in_array($key1, $CardBalAdjust_column))
                                {
                                    $cardBankObj->LocalDate = date("Y-m-d H:i:s",strtotime($value1));
                                }
                                elseif($key1 == 'SettlementDate' && in_array($key1, $CardBalAdjust_column))
                                {
                                    $cardBankObj->SettlementDate = date("Y-m-d",strtotime($value1));
                                }
                                else
                                {
                                    if(!empty($value1))
                                    {
                                        if(in_array($key1, $CardBalAdjust_column))
                                            $cardBankObj->$key1 = $value1;
                                        else
                                        {
                                            if(isset($xmlUnstoredTag['CardFinancial']) && !in_array($key1, $xmlUnstoredTag['CardFinancial']))
                                                $xmlUnstoredTag['CardFinancial'][] = $key1;
                                        }
                                    }
                                }
                            }
                        }

                        $cardBankObj->save();
                    }
                    
                }
                
            }
        }
    }

    private function CardFee($outputArray , &$xmlUnstoredTag , $file_date ,$file_name = null)
    {
        if(!empty($outputArray))
        {
            $CardBalAdjust_column = array("CardFeeId","LoadUnloadId","LocalDate","SettlementDate","TxId","MerchCode","Desc","ReasonCode","FIID","Card_productid","Card_PAN","Card_product","Card_programid","Card_branchcode","Account_no","Account_type","TxnCode_direction","TxnCode_Type","TxnCode_Group","TxnCode_ProcCode","MsgSource_value","MsgSource_domesticMaestro","FeeClass_interchangeTransaction","FeeClass_type","FeeClass_code","FeeAmt_direction","FeeAmt_value","FeeAmt_currency","Amt_direction","Amt_value","Amt_currency");

            $dataArray = array();
            if(isset($outputArray[0]))
            {
                $dataArray = $outputArray;
            }
            else
            {
                $dataArray[0] = $outputArray;
            }
            $i = 0;
            foreach ($dataArray as $key => $value) 
            {
                if(!empty($value['CardFeeId']) && !empty($value['SettlementDate']))
                {
                    $bankBalAc = Cardfee::where("CardFeeId",$value['CardFeeId'])
                                        ->where("Desc",$value['Desc'])
                                        ->where("FeeClass_code",@$value['FeeClass']['@attributes']['code'])
                                        ->first();

                    if(empty($bankBalAc))
                    {
                        $cardBankObj = new Cardfee();
                        $uniqueId = Helper::generateUniqueId();
                        $cardBankObj->id = $uniqueId;
                        $cardBankObj->file_date = $file_date;
                        $cardBankObj->file_name = $file_name;
                        foreach ($value as $key1 => $value1) 
                        {
                            if(is_array($value1))
                            {
                                if(!empty($value1['@attributes']))
                                {
                                    foreach ($value1['@attributes'] as $key2 => $value2) {
                                        $colName = $key1."_".$key2;
                                        if(in_array($colName, $CardBalAdjust_column))
                                        {
                                            $cardBankObj->$colName = $value2;
                                        }
                                        else
                                        {
                                            if(isset($xmlUnstoredTag['CardFee']) && !in_array($colName, $xmlUnstoredTag['CardFee']))
                                                $xmlUnstoredTag['CardFee'][] = $colName;
                                        }
                                    }
                                }
                            }
                            else
                            {
                                if($key1 == 'LocalDate' && in_array($key1, $CardBalAdjust_column))
                                {
                                    $cardBankObj->LocalDate = date("Y-m-d H:i:s",strtotime($value1));
                                }
                                elseif($key1 == 'SettlementDate' && in_array($key1, $CardBalAdjust_column))
                                {
                                    $cardBankObj->SettlementDate = date("Y-m-d",strtotime($value1));
                                }
                                else
                                {
                                    if(!empty($value1))
                                    {
                                        if(in_array($key1, $CardBalAdjust_column))
                                        $cardBankObj->$key1 = $value1;
                                        else
                                        {
                                            if(isset($xmlUnstoredTag['CardFee']) && !in_array($key1, $xmlUnstoredTag['CardFee']))
                                                $xmlUnstoredTag['CardFee'][] = $key1;
                                        }
                                    }
                                }
                            }
                        }

                        $cardBankObj->save();
                    }
                    
                }
                
            }
        }
    }

    private function CardLoadUnload($outputArray , &$xmlUnstoredTag , $file_date ,$file_name = null)
    {
        if(!empty($outputArray))
        {
            $CardBalAdjust_column = array("RecordType","LoadUnloadId","LocalDate","SettlementDate","MessageId","MerchCode","Desc","LoadSource","LoadType","VoidedLoadUnloadId","Card_productid","Card_PAN","Card_product","Card_programid","Card_branchcode","Account_no","Account_type","Amount_direction","Amount_value","Amount_currency");

            $dataArray = array();
            if(isset($outputArray[0]))
            {
                $dataArray = $outputArray;
            }
            else
            {
                $dataArray[0] = $outputArray;
            }
            $i = 0;
            foreach ($dataArray as $key => $value) 
            {
                if(!empty($value['RecordType']) && !empty($value['LoadUnloadId']))
                {
                    $bankBalAc = Cardloadunload::where("LoadUnloadId",$value['LoadUnloadId'])->first();

                    if(empty($bankBalAc))
                    {
                        $cardBankObj = new Cardloadunload();
                        $uniqueId = Helper::generateUniqueId();
                        $cardBankObj->id = $uniqueId;
                        $cardBankObj->file_date = $file_date;
                        $cardBankObj->file_name = $file_name;
                        foreach ($value as $key1 => $value1) 
                        {
                            if(is_array($value1))
                            {
                                if(!empty($value1['@attributes']))
                                {
                                    foreach ($value1['@attributes'] as $key2 => $value2) {
                                        $colName = $key1."_".$key2;
                                        if(in_array($colName, $CardBalAdjust_column))
                                        {
                                            $cardBankObj->$colName = $value2;
                                        }
                                        else
                                        {
                                            if(isset($xmlUnstoredTag['CardLoadUnload']) && !in_array($colName, $xmlUnstoredTag['CardLoadUnload']))
                                                $xmlUnstoredTag['CardLoadUnload'][] = $colName;
                                        }
                                    }
                                }
                            }
                            else
                            {
                                if($key1 == 'LocalDate' && in_array($key1, $CardBalAdjust_column))
                                {
                                    $cardBankObj->LocalDate = date("Y-m-d H:i:s",strtotime($value1));
                                }
                                elseif($key1 == 'SettlementDate' && in_array($key1, $CardBalAdjust_column))
                                {
                                    $cardBankObj->SettlementDate = date("Y-m-d",strtotime($value1));
                                }
                                else
                                {
                                    if(!empty($value1))
                                    {
                                        if(in_array($key1, $CardBalAdjust_column))
                                            $cardBankObj->$key1 = $value1;
                                        else
                                        {
                                            if(isset($xmlUnstoredTag['CardLoadUnload']) && !in_array($key1, $xmlUnstoredTag['CardLoadUnload']))
                                                $xmlUnstoredTag['CardLoadUnload'][] = $key1;
                                        }
                                    }
                                }
                            }
                        }

                        $cardBankObj->save();
                    }
                    
                }
                
            }
        }
    }

    private function MasterCardFee($outputArray , &$xmlUnstoredTag , $file_date ,$file_name = null)
    {
        if(!empty($outputArray))
        {
            $CardBalAdjust_column = array("MastercardFeeId","MTID","Function_Code_024","Conversion_Rate_Reconciliation_009","Additional_Data_048","LocalDate","SettlementDate","Desc","ReasonCode","Data_Record_072","DE93_Txn_Dest_ID","DE94_Txn_Orig_ID","File_ID_PDS0105","FileProcessDate","FeeClass_interchangeTransaction","FeeClass_type","FeeClass_code","FeeClass_memberID","FeeAmt_direction","FeeAmt_value","FeeAmt_currency","Amt_direction","Amt_value","Amt_currency","Recon_date","Recon_cycle","Settlement_date","Settlement_cycle");

            $dataArray = array();
            if(isset($outputArray[0]))
            {
                $dataArray = $outputArray;
            }
            else
            {
                $dataArray[0] = $outputArray;
            }
            $i = 0;
            foreach ($dataArray as $key => $value) 
            {
                if(!empty($value['MastercardFeeId']) && !empty($value['SettlementDate']))
                {
                    $bankBalAc = Mastercardfee::where("MastercardFeeId",$value['MastercardFeeId'])->first();

                    if(empty($bankBalAc))
                    {
                        $cardBankObj = new Mastercardfee();
                        $uniqueId = Helper::generateUniqueId();
                        $cardBankObj->id = $uniqueId;
                        $cardBankObj->file_date = $file_date;
                        $cardBankObj->file_name = $file_name;
                        foreach ($value as $key1 => $value1) 
                        {
                            if(is_array($value1))
                            {
                                if(!empty($value1['@attributes']))
                                {
                                    foreach ($value1['@attributes'] as $key2 => $value2) {
                                        $colName = $key1."_".$key2;
                                        if(in_array($colName, $CardBalAdjust_column))
                                        {
                                            $cardBankObj->$colName = $value2;
                                        }
                                        else
                                        {
                                            if(isset($xmlUnstoredTag['MasterCardFee']) && !in_array($colName, $xmlUnstoredTag['MasterCardFee']))
                                                $xmlUnstoredTag['MasterCardFee'][] = $colName;
                                        }
                                    }
                                }
                            }
                            else
                            {
                                if($key1 == 'LocalDate' && in_array($key1, $CardBalAdjust_column))
                                {
                                    $cardBankObj->LocalDate = date("Y-m-d H:i:s",strtotime($value1));
                                }
                                elseif($key1 == 'SettlementDate' && in_array($key1, $CardBalAdjust_column))
                                {
                                    $cardBankObj->SettlementDate = date("Y-m-d",strtotime($value1));
                                }
                                else
                                {
                                    if(!empty($value1))
                                    {
                                        if(in_array($key1, $CardBalAdjust_column))
                                            $cardBankObj->$key1 = $value1;
                                        else
                                        {
                                            if(isset($xmlUnstoredTag['MasterCardFee']) && !in_array($key1, $xmlUnstoredTag['MasterCardFee']))
                                                $xmlUnstoredTag['MasterCardFee'][] = $key1;
                                        }
                                    }
                                }
                            }
                        }

                        $cardBankObj->save();
                    }
                    
                }
                
            }
        }
    }

    private function sendUnknownColumnEmail($xmlUnstoredTag)
    {
    $excel = Excel::create("Bank Transaction UnKnownColumn_".date('d-M-Y'), function($excel) use($xmlUnstoredTag) {
            $excel->sheet('Bank Transaction UnKnownColumn', function($sheet) use($xmlUnstoredTag) {

                $headerArray = array_keys($xmlUnstoredTag);
                $sheet->row(1,$headerArray);
                $char = 'A';
                foreach ($xmlUnstoredTag as $key => $value) {
                    foreach ($value as $key1 => $value1) {
                        $sheet->setCellValue($char.($key1+2), $value1);
                    }
                    $char++;
                }
                
                $sheet->row(1, function($row) {

                    // call cell manipulation methods
                    $row->setBackground('#ff9100');

                });
                
            });
        })->store('xlsx', false, true);

        $fullFilePath = $excel['full'];
        Mail::send('emails.transactionunknowncolumn', array(), function ($m) use($fullFilePath) {
    
            $m->to(['maulik@parextech.com']);
            

            $m->subject('Bank Transaction UnKnownColumn');
            $m->attach($fullFilePath);
        });
    }

    public function comparetxn()
    {
        return View::make('banktransactions/comparetxn');
    }
}
