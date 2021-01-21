<?php

namespace App\Http\Controllers\FszNotificationsImport;

use App\Http\Controllers\Controller;

use App\Helpers\Helper;

use App\Models\FszNotificationFile;
use App\Models\FszNotification;
use App\Models\FszNotificationEntry;

use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SimpleXMLElement;
use View;

// use Illuminate\Support\Facades\Session;

class FszNotificationsImportController extends Controller
{
    public function getImport()
    {
        return view('notifications/import');
    }

 

    public function showEntries(Request $request)
    {
       
        // $entries=FszNotification::with('FszNotificationEntries')->whereHas('FszNotificationEntries', function($q) use ($request){ 
        //     $q->whereBetween('booking_date',[$request->fromdate, $request->todate]);})->take(10)->get();
           //dd($entries);
            if($request !="")
            {
            $entries = DB::table('fsz_notifications')
                ->join('fsz_notification_enteries', 'fsz_notifications.id', '=', 'fsz_notification_enteries.fsz_notification_id')
                ->select('fsz_notifications.*', 'fsz_notification_enteries.*')
                ->whereBetween('fsz_notification_enteries.booking_date', [$request->fromdate, $request->todate])
                ->paginate(15);
                return View::make('notifications/view')->with('entries', $entries);
            }
            else
            {
                
                return view('notifications/view');
            }
    }
    public function postImport(Request $request)
    {
        /**************************************************** *
        *Following are the main todos here in this coding
        1-First we move the files and save them in database with status 0
        2-Then from database we get the imported files with status 0 from the db and save them with status 1
        3-Then we get the imported files name from db and and then from uploaded directory and save the file contents and after saving the files contents the status of the file in database will be 2.
        4-if there is status 1 , it means files is uploaded to the directory but its contents are not imported properly

        *******************************************************/
        date_default_timezone_set('Asia/Karachi');
        if ($request->hasFile('notification_import_xml')) {
            $myfile = $request->file('notification_import_xml');
        
            foreach ($myfile as $file) {
                //importing multiple files
                $fileName = $file->getClientOriginalName();
                $destinationPath = 'notifications_uploads';
                $path = $file->move($destinationPath, $fileName);
                
                $upload = new FszNotificationFile();
                $upload->file = $fileName;
               // $upload->file_path = public_path() . '/notifications_uploads';
                $upload->status = 0;
                $upload->user_id = Auth::id();
                $upload->save();
            }   
            $this->ImportData();   
            Session::flash('success', "File Imported Successfully.");
            return view('notifications/import');

        } else {
            Session::flash('error', "File uploads failed, Choose a file");
            return view('notifications/import');
        }

    }

    public function ImportData()
    {
        $files =FszNotificationFile::where('status',0)->get(); 

        foreach ($files as $importfile) 
        {
            $importfile->status=1;
            $importfile->save();
            $this->ImportFileData($importfile);
            $importfile->status=2;
            $importfile->save();
        }
    }

    private function ImportFileData($importfile)
    {
        
        $string = file_get_contents(public_path("notifications_uploads/".$importfile->file));
        $xml = new SimpleXMLElement($string);
        if(isset($xml->BkToCstmrDbtCdtNtfctnV01))
        {
            $obj               =    array();
            // notification info
            $ndata             =    $xml->BkToCstmrDbtCdtNtfctnV01->Ntfctn;          
            $obj['importfile_name']     =    $importfile->file;
            $obj['notification_id']   =    (string)$ndata->Id;
            $obj['created_time']      =    (string) $ndata->CreDtTm;
            $obj['party_account']     =    intval($ndata->Acct->Id->PrtryAcct->Id);
            $obj['currency']          =    (string)$ndata->Acct->Ccy;          
            // TxsSummry
            $obj['entries']            =    (string)$ndata->TxsSummry->TtlNtries->NbOfNtries;
            $obj['entries_sum']        =    (string)$ndata->TxsSummry->TtlNtries->Sum;
            
            $obj['credit_entries']     =    (string)$ndata->TxsSummry->TtlCdtNtries->NbOfNtries;
            $obj['credit_entries_sum'] =    (string)$ndata->TxsSummry->TtlCdtNtries->Sum; 

            $obj['debit_entries']      =    (string)$ndata->TxsSummry->TtlDbtNtries->NbOfNtries;
            $obj['debit_entries_sum']  =    (string)$ndata->TxsSummry->TtlDbtNtries->Sum;            
         
            $notification_obj = FszNotification::firstOrCreate($obj);

            foreach($ndata->Ntry as $nte)
            {
                $entery = $this->addNotificationEntery($notification_obj,$nte,$importfile);            
            }
            //after creating the entery we will import the data according to our requirment
            $code = FszNotificationEntry::select('id','AddtlRmtInf_ref','booking_transaction_code','Dbtr_Nm')
            ->where('booking_transaction_code','like','%rtn%')->get();
        //    echo "<pre>"; 
        //     print_r($code);
        //     die();
            // Here we will get the first part of the description      
            foreach($code as $mycode)
            {
                if(strpos($mycode->AddtlRmtInf_ref,'paypalcode')!==false){                 
                $mycode_with_digits = substr($mycode->AddtlRmtInf_ref,0,12);
                $mycode_after_digits = substr($mycode->AddtlRmtInf_ref,14);
                $bankstatement_desc_istpart =$mycode_with_digits.$mycode_after_digits;                
                }
                elseif($mycode->AddtlRmtInf_ref=="noref")
                {                   
                   $bankstatement_desc_istpart  = Helper::getRmtInf($mycode->Dbtr_Nm);
                }
                else
                {
                    $bankstatement_desc_istpart=$mycode->AddtlRmtInf_ref;  
                }
             
              $booking_transaction_code=explode(" ",$mycode->booking_transaction_code);
              if(isset($booking_transaction_code[4])){
              $btcode=$booking_transaction_code[4]; 
            
               if($btcode==1114)
                {
                    $bankstatement_desc_2ndpart = Helper::getRmtInf("UNPPayee bank response: Sort Code / Account Number Unknown");                
                }
             elseif($btcode == 1160)
                {
                  $bankstatement_desc_2ndpart = Helper::getRmtInf("UNPPayee bank response: Account Closed");
                }
              elseif($btcode == 1161)
                {
                  $bankstatement_desc_2ndpart = Helper::getRmtInf("UNPPayee bank response: Account unable to receive credits");
                }
              elseif($btcode == 1162)
                {
                  $bankstatement_desc_2ndpart = Helper::getRmtInf("UNPPayee bank response: Account Name doesn't match Account Number");
                }
              elseif($btcode==1163)
                {
                 $bankstatement_desc_2ndpart = Helper::getRmtInf("UNPPayee bank response: Account Name doesn't match Account Number");  
                }
             elseif($btcode==1164)
                {
                  $bankstatement_desc_2ndpart = Helper::getRmtInf("UNPPayee bank response: Incorrect Reference Info");    
                }
             elseif($btcode==1169)
                {
                  $bankstatement_desc_2ndpart = Helper::getRmtInf("UNPPayee bank response: Incorrect Reference Info");  
                }
             }
             if(isset($bankstatement_desc_2ndpart))
             {
                $bankstatement_desc_from_notification = $bankstatement_desc_istpart.$bankstatement_desc_2ndpart;
             }
             else{
                $bankstatement_desc_from_notification = $bankstatement_desc_istpart; 
             }
            
                FszNotificationEntry::where('booking_transaction_code','like','%rtn%')->where('id',$mycode->id)->update(['bankstatement_desc'=>$bankstatement_desc_from_notification]);
            }
            
        }    
    }

    // notification_enteries
    private function addNotificationEntery($notification_obj,$nte,$importfile)
    {  
        $obj                                    =     array(); 
        // genernal info
        $obj['fsz_notification_id']             =     $notification_obj->id;
        $obj['importfile_name']                 =    $importfile->file;
        $obj['amount']                          =     (string)$nte->Amt;
        $obj['credit_debit_indicator']          =     (string)$nte->CdtDbtInd;
        $obj['status']                          =     (string)$nte->Sts;
        $obj['booking_date']                    =     (string)$nte->BookgDt->Dt;
        $obj['booking_transaction_code']        =     (string)$nte->BkTxCd->Prtry->Cd;

        // TxDtls
        $obj['EndToEndId']                      =     (string)$nte->TxDtls->Refs->EndToEndId;
        $obj['TxId']                            =     (string)$nte->TxDtls->Refs->TxId;

        $obj['Dbtr_Nm']                         =     (string)$nte->TxDtls->RltdPties->Dbtr->Nm;
        
        if(isset($nte->TxDtls->RltdPties->Dbtr->PstlAdr))
        {
            $obj['Dbtr_AdrLine']                 =     (string)$nte->TxDtls->RltdPties->Dbtr->PstlAdr->AdrLine;
            $obj['Dbtr_Ctry']                    =     (string)$nte->TxDtls->RltdPties->Dbtr->PstlAdr->Ctry;
        }
        else
        {
            $obj['Dbtr_AdrLine']                 =   ""; 
            $obj['Dbtr_Ctry']                    =    "";    
        }

        if(isset($nte->TxDtls->RltdPties->DbtrAcct))
        {
            $obj['DbtrAcct_Id']                     =     (string)$nte->TxDtls->RltdPties->DbtrAcct->Id->PrtryAcct->Id;
        }
        else
        {
            $obj['DbtrAcct_Id']                     =  "";
        }

        $obj['Cdtr_Nm']                             =     (string)$nte->TxDtls->RltdPties->Cdtr->Nm;
  
        if(isset($nte->TxDtls->RltdPties->Cdtr->PstlAdr))
        {
            $obj['Cdtr_AdrLine']                    =     (string)$nte->TxDtls->RltdPties->Cdtr->PstlAdr->AdrLine;
            $obj['Cdtr_Ctry']                      =     (string)$nte->TxDtls->RltdPties->Cdtr->PstlAdr->Ctry;
        }
        else
        {
            $obj['Cdtr_AdrLine']                   =   ""; 
            $obj['Cdtr_Ctry']                      =    "";    
        }        
       if(isset($nte->TxDtls->RltdPties->CdtrAcct->Id->PrtryAcct))
       {
        $obj['CdtrAcct_Id']                    =     (string)$nte->TxDtls->RltdPties->CdtrAcct->Id->PrtryAcct->Id;
       }
       else
       {
        $obj['CdtrAcct_Id']                    =  "";
       }
        $obj['Prtry_Tp']                      =     (string)$nte->TxDtls->RltdPties->Prtry->Tp;
        $Strd                                 =     $nte->TxDtls->RmtInf->Strd[sizeof($nte->TxDtls->RmtInf->Strd)-1];
        $obj['AddtlRmtInf']                   =     (string)$Strd->AddtlRmtInf;

        $obj['TxDtTm']                        =      (string)$nte->TxDtls->RltdDts->TxDtTm;
        $obj['AddtlTxInf']                    =      (string)$nte->TxDtls->AddtlTxInf;
        $obj['AddtlNtryInf']                  =      (string)$nte->AddtlNtryInf;
        $obj['AddtlRmtInf_ref']               =   Helper::getRmtInf($obj['AddtlRmtInf']);

        return FszNotificationEntry::firstOrCreate($obj);





    }

    
}
