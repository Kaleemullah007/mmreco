<?php
namespace App\Http\Controllers\Autocomparetxn;

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

use App\Models\Agencybanking;
use App\Models\Agencybankingfee;
use App\Models\Cardauthorisation;
use App\Models\Cardbaladjust;
use App\Models\Cardchrgbackrepres;
use App\Models\Cardevent;
use App\Models\Cardfee;
use App\Models\Cardfinancial;
use App\Models\Cardloadunload;
use App\Models\Mastercardfee;
use App\Models\Bankstatement;
use App\Models\Bankbalance;
use App\Models\Fpout;
use App\Models\Advice;
use App\Models\Directdebits;
use App\Models\Txnmappingint;

use App\Models\FszNotification;
use App\Models\FszNotificationEntry;
/**
 * This controller handles all actions related to Users for
 * the Parextech Asset Management application.
 *
 * @version    v1.0
 */


class AutocomparetxnController extends Controller
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
    public function getDatatable()
    {
    }

    public function getReAutoCompareTxn()
    {
        return View::make('autocomparetxn/recompare');
    }

    public function postReAutoCompareTxn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required',         
        ]);
        
        if ($validator->fails()) {

            $error = $validator->errors()->first();            
            return redirect()->to("autocomparetxn/recompare")->with("error",$error);                
        }

        $this->autoMapTxnData($request->get('start_date'));
        return redirect()->to("autocomparetxn/recompare")->with("success","Record re-calculated");  
    }

    public function generateAutoMapTxnData()
    {
        $reportDate = "2017-11-16";
        $this->autoMapTxnData($reportDate);
        echo "success";exit;
    }

    public function autoMapTxnData($reportDate)
    {
        $user_Id = 0;
        if(!empty(Auth::user()) && !empty(Auth::user()->id))
        {
            $user_Id = Auth::user()->id;
        }

        $startDate = date("Y-m-d",strtotime("-6 day",strtotime($reportDate)));
        $endDate = $reportDate;

        $bankStmentObj = new Bankstatement();
        $bankStmentData = $bankStmentObj->getBankStatementData($startDate,$endDate);
        $matchFlag = false;

       // pr($bankStmentData,1);
$idsss = [];
       foreach ($bankStmentData as $key => $value) {
$idsss[] = $value->id;
       }


       if(in_array('c37bda8c-f229-4576-a40a-e51235be5c96',$idsss)){
echo "Found";
    }
    else{
        echo "not Found";
    }
die();


         $matchData = array();
        foreach ($bankStmentData as $key => $value) {
         
            if(!$matchFlag)
            {
                $matchData = $this->oneToOneExcetMatchWithOutDateNotifications($value);  

                if($matchData['found'] == 'yes')
                {
                    $matchFlag = true;
                }
            }
            if(!$matchFlag && strpos($value->description, 'BACS') !== false)
            {
                $matchData = $this->oneToOneDDRMetchWithOutDate($value);

                if($matchData['found'] == 'yes')
                {
                    $matchFlag = true;
                }
            }

            if(!$matchFlag && strpos($value->description, 'STO') !== false)
            {
            
                $matchData = $this->oneToManySTOMetchWithDate($value);

                if($matchData['found'] == 'yes')
                {
                    $matchFlag = true;
                }
            }

            if(!$matchFlag && strpos($value->description, 'ADV') !== false)
            {
                $matchData = $this->oneToManyADVMetchWithDate($value);

                if($matchData['found'] == 'yes')
                {
                    $matchFlag = true;
                }
            }

            if(!empty($matchData) && $matchData['found'] == 'yes')
            {
                $value->reco_flg = "Y";
                $value->save();
                foreach ($matchData['data'] as $key1 => $value1) {
                    $TxnmappingintObj = new Txnmappingint();

                    $TxnmappingintObj->id = Helper::generateUniqueId();
                    $TxnmappingintObj->bank_statement_id = $value->bstId;
                    $TxnmappingintObj->txn_type = $value1->int_type;
                    $TxnmappingintObj->txn_table_id = $value1->ids;
                    $TxnmappingintObj->coding = $matchData['coding'];
                    $TxnmappingintObj->comment = $matchData['comment'];
                    $TxnmappingintObj->created_by = $user_Id;

                    $TxnmappingintObj->save();

                    $value1->reco_flg = "Y";
                    $value1->reco_date = $value->date;
                    $value1->save();

                }
            }
            $matchFlag = false;    
        }
        
       
    }
    private function oneToOneExcetMatchWithOutDateNotifications($bankStmt){
        if(strpos($bankStmt->description, 'BGCFrom:') !== false)
        {
           $descCmp = explode("BGCFrom:", $bankStmt->description);
        }
        elseif(strpos($bankStmt->description, 'STOFrom:') !== false){
           $descCmp = explode("STOFrom:", $bankStmt->description);
        }
        else
        {
          $descCmp = explode("From:", $bankStmt->description);
        }
       $bankstatement_type= Helper::getRmtInf($bankStmt->type);
       if($bankstatement_type =="fasterpaymentreturn"){
             $myBankstatementDesc = explode("From:", $bankStmt->description);
             $BankstatementDesc=Helper::getRmtInf($myBankstatementDesc[0]);
             $BankstatementDesc2 = explode(" ", trim(@$myBankstatementDesc[1]));
             //triming "-" from the sortcode
             $BankstatementDesc2[0] = str_replace("-", "", $BankstatementDesc2[0]); 
             //getrtting the full account number from bank statement
            $BankstatemtnAccountNumber=@$BankstatementDesc2[0].@$BankstatementDesc2[1];
             
       }
        //GETTING sortcode and account number in @desCmp[1];
        //IN $desCamp[0] comes the name which is to be matched with agency banking external_name 
       // dd($descCmp[0]);
        $descCmp2 = explode(" ", trim(@$descCmp[1]));
        //triming "-" from the sortcode
        $descCmp2[0] = str_replace("-", "", $descCmp2[0]); 
        //getrtting the full account number from bank statement
        $accountNumberNotifications=@$descCmp2[0].@$descCmp2[1];
        $amtMatch = 0;
        if(!empty($bankStmt->credit))
        {
            $amtMatch = $bankStmt->credit;
        }  
        $AddtlRmtInf_ref    = Helper::getRmtInf($descCmp[0]);
        $credit_debit_indicator="CRDT";
        $startDate =  date("Y-m-d",strtotime("0 day",strtotime($bankStmt->date)));
        $endDate   =  date("Y-m-d",strtotime($bankStmt->date));    
        if(empty($BankstatementDesc))
        {
        $entries = FszNotificationEntry::select('fsz_notification_enteries.*','fsz_notification_enteries.id as notify_id')
        ->whereBetween('booking_date', [$startDate, $endDate])
        ->where('credit_debit_indicator',$credit_debit_indicator)
        ->where('amount',$amtMatch)
        ->where('DbtrAcct_Id',$accountNumberNotifications)
        ->where('AddtlRmtInf_ref',$AddtlRmtInf_ref)
        ->where('reco_flg',"N")
        ->first();
        }
        else
        {    
       $entries = FszNotificationEntry::select('fsz_notification_enteries.*','fsz_notification_enteries.id as notify_id')
       ->whereBetween('booking_date', [$startDate, $endDate])
       ->where('credit_debit_indicator',$credit_debit_indicator)
       ->where('amount',$amtMatch)
       ->where('DbtrAcct_Id',$BankstatemtnAccountNumber)
       ->where('bankstatement_desc',$BankstatementDesc)
       ->where('reco_flg',"N")
       ->first();
        }
       // return $entries;
        if(!empty($entries))
         {   
            $getAgencyBankData =$this->oneToOneExcetMatchWithOutDateAB($entries,$bankStmt);

               $returnData= array();
                   if(!empty($getAgencyBankData))
                   {
                       $returnData['data'][0] =  $getAgencyBankData;
                       if($getAgencyBankData->banking_type == "Approved")
                       {
                           $returnData['type'] = "AB Approved";
                           $returnData['coding'] = "AB Approved one to one";
                           $returnData['comment'] = "AB Approved one to one with date";
                       }
                       else
                       {
                           $returnData['type'] = "AB Declined";
                           $returnData['coding'] = "contra AB Declined/Not Loaded on Card";
                           $returnData['comment'] = "AB Declined one to one with date";
                       }
            
                       $returnData['found'] = 'yes';
                   }
                   else
                   {
                       $returnData['found'] = 'no';
                   }
               return $returnData;
            }
        }

    /**********************************************************************************
After Matching with notification now the matched data will be again matched with agency banking-CODE BY AMJAD

*****************************************************/

private function oneToOneExcetMatchWithOutDateAB($notificationsMatchedData,$bankstatement,$paypalcode=null){

   DB::table('fsz_notification_enteries')->where('id',$notificationsMatchedData->id)->update(['reco_flg'=>"Y"]);
    //=============================================================
    if(strpos($bankstatement->description, 'BGCFrom:') !== false)
    $descCmp = explode("BGCFrom:", $bankstatement->description);
    else
    $descCmp = explode("STOFrom:", $bankstatement->description);
    
    $isPayPal = false;
    
    if(strpos($bankstatement->description, 'PAYPAL') !== false)
    {
    $isPayPal = true;
    }
    //GETTING sortcode and account number in @desCmp[1];
    //IN desCamp[0] comes the name which is to be matched with agency banking external_name
    $descCmp2 = explode(" ", trim(@$descCmp[1]));
    //triming "-" from the sortcode
    $descCmp2[0] = str_replace("-", "", $descCmp2[0]);
    $amtMatch = 0;
    if(!empty($bankstatement->credit))
    {
    $amtMatch = $bankstatement->credit;
    }
    else
    {
    $amtMatch = $bankstatement->debit;
    }
    
    //=============================================================================
        $bankstatement_type= Helper::getRmtInf($bankstatement->type);
        
        //DEBITOR ACCOUNT OPERATION===========================
        $debitor_Account = $notificationsMatchedData->DbtrAcct_Id;
        $debitor_Account_sortcode= substr($debitor_Account, 0, 6);     
        $debitor_Account_bankacc = substr($debitor_Account, 6, 14);       
        //CREDITOR ACCOUNT OPERATION===================
        $creditor_Account = $notificationsMatchedData->CdtrAcct_Id;
        $creditor_Account_sortcode= substr($creditor_Account, 0, 6);     
        $creditor_Account_bankacc = substr($creditor_Account, 6, 14); 
        //==================================================== 
        if($bankstatement_type =="fasterpaymentreturn"){   
            $cdtr_Nm = $notificationsMatchedData->Cdtr_Nm;
            $creditor_Nm_sortcode= substr($cdtr_Nm, 0, 6);     
            $creditor_Nm_bankacc = substr($cdtr_Nm, 6, 14);  
            $getAgencyBankData = Agencybanking::select("agencybanking.*","agencybanking.id as ids","agencybanking.banking_type  AS int_type")
            ->where('agencybanking.CashAmt_value',$notificationsMatchedData->amount)
            ->where('agencybanking.External_sortcode',$debitor_Account_sortcode)
            ->where('agencybanking.External_bankacc',$debitor_Account_bankacc)
            ->where( function( $query)  use ($creditor_Account_sortcode,$creditor_Account_bankacc){
                $query->where('agencybanking.AgencyAccount_sortcode',$creditor_Account_sortcode)
                     ->where('agencybanking.AgencyAccount_bankacc',$creditor_Account_bankacc);
            })->orWhere(function($query) use ($creditor_Nm_sortcode,$creditor_Nm_bankacc){
                $query->where('agencybanking.AgencyAccount_sortcode',$creditor_Nm_sortcode)
                ->where('agencybanking.AgencyAccount_bankacc',$creditor_Nm_bankacc);   
            })      
            ->whereRaw("MATCH(agencybanking.Desc) AGAINST ('$notificationsMatchedData->AddtlTxInf')")
            ->where("agencybanking.CashType","RCP")
            ->where("agencybanking.CashCode_CashType","fpy")
            ->where('reco_flg','N')
            ->get();  

    
        }
        else
        {   
           
            $getAgencyBankData = Agencybanking::select("agencybanking.*","agencybanking.id as ids","agencybanking.banking_type  AS int_type")
            ->where('agencybanking.CashAmt_value',$notificationsMatchedData->amount)
            ->where('agencybanking.External_sortcode',$debitor_Account_sortcode)
            ->where('agencybanking.External_bankacc',$debitor_Account_bankacc)  
            ->where('agencybanking.AgencyAccount_sortcode',$creditor_Account_sortcode)
            ->where('agencybanking.AgencyAccount_bankacc',$creditor_Account_bankacc)
            ->whereRaw("MATCH(agencybanking.Desc) AGAINST ('$notificationsMatchedData->AddtlTxInf')")
            ->where("agencybanking.CashType","RCP")
            ->where("agencybanking.CashCode_CashType","fpy")
            ->where('reco_flg','N')
            ->get(); 
            
        }
    
        $dataArray = array();
        foreach ($getAgencyBankData as $key => $value) 
        {
            $dataArray=$value;
        }
    
        return $dataArray;
    }
    
    /*========================================================================================*/
    private function oneToOneExcetMatchWithDate($bankStmt)
    {
        $returnData = array();
        if(strpos($bankStmt->description, 'BGCFrom:') !== false || strpos($bankStmt->description, 'STOFrom:') !== false)
        {
            $agencyBankingObj = new Agencybanking();
            $getAgencyBankData = $agencyBankingObj->getOneToOneMatchWithDate($bankStmt);

            if(!empty($getAgencyBankData))
            {
                $returnData['data'][0] =  $getAgencyBankData;
                if($getAgencyBankData->banking_type == "Approved")
                {
                    $returnData['type'] = "AB Approved";
                    $returnData['coding'] = "AB Approved one to one";
                    $returnData['comment'] = "AB Approved one to one with date";
                }
                else
                {
                    $returnData['type'] = "AB Declined";
                    $returnData['coding'] = "contra AB Declined/Not Loaded on Card";
                    $returnData['comment'] = "AB Declined one to one with date";
                }

                $returnData['found'] = 'yes';
            }
            else
            {
                $returnData['found'] = 'no';
            }
        }
        else
        {
            $returnData['found'] = 'no';
        }

        return $returnData;
    }

    private function oneToOneExcetMatchWithOutDate($bankStmt)
    {
        $returnData = array();
        if(strpos($bankStmt->description, 'BGCFrom:') !== false || strpos($bankStmt->description, 'STOFrom:') !== false)
        {
            $agencyBankingObj = new Agencybanking();
            $getAgencyBankData = $agencyBankingObj->getOneToOneMatchWithOutDate($bankStmt);

            if(!empty($getAgencyBankData))
            {
                $returnData['data'][0] =  $getAgencyBankData;
                if($getAgencyBankData->banking_type == "Approved")
                {
                    $returnData['type'] = "AB Approved";
                    $returnData['coding'] = "AB Approved one to one";
                    $returnData['comment'] = "AB Approved one to one with out date";
                }
                else
                {
                    $returnData['type'] = "AB Declined";
                    $returnData['coding'] = "contra AB Declined/Not Loaded on Card";
                    $returnData['comment'] = "AB Declined one to one with out date";
                }

                $returnData['found'] = 'yes';
            }
            else
            {
                $returnData['found'] = 'no';
            }
        }
        else
        {
            $returnData['found'] = 'no';
        }

        return $returnData;
    }

    private function oneToOneDDRMetchWithOutDate($bankStmt)
    {
        $returnData = array();
        $btDesc = strtolower(str_replace(' ','',$bankStmt->description));

        if($btDesc == "bacsddr")
        {
            $agencyBankingObj = new Agencybanking();
            $getAgencyBankData = $agencyBankingObj->oneToOneDDRMetchWithOutDate($bankStmt);

            if(!empty($getAgencyBankData))
            {
                $returnData['found'] = 'yes';
                $returnData['type'] = 'BACS DDR';
                $returnData['data'][0] = $getAgencyBankData;
                $returnData['coding'] = 'Contra';
                $returnData['comment'] = "BACS DDR";
            }
            else
            {
                $returnData['found'] = 'no';
            }

        }
        else
        {
            $returnData['found'] = 'no';
        }

    }

    private function oneToManySTOMetchWithDate($bankStmt)
    {
        $returnData = array();
        if(strpos($bankStmt->description, 'STO') !== false)
        {
            $FpoutObj = new Fpout();
            $getFpoutData = $FpoutObj->getFpoutData($bankStmt);
            $linkedFlag = true;
            if(count($getFpoutData) != 0)
            {
                foreach ($getFpoutData as $key => $value)
                {
                    if(empty($value->agencybanking_Id))
                        $linkedFlag = false;
                       
                }
                //where there is empty agencybanking_Id then you will not go inside the if
                if($linkedFlag)
                {
                    $returnData['found'] = 'yes';
                    $returnData['type'] = 'Fpout';
                    $i = 0;
                    $contra = false;
                    $returnData['data'] = $getFpoutData;              
                    // foreach ($getFpoutData as $key => $value) 
                    // {
                    //     $endDate = $bankStmt->date;     
                    //     $startDate = date("Y-m-d",strtotime("-3 day",strtotime($bankStmt->date)));

                    //     $agencyBankingObj = new Agencybanking();
                    //     $abData = $agencyBankingObj->getAbDataByFpout($value , $startDate , $endDate);
                    //     if(!empty($abData))
                    //     {
                    //         if($value->ReferenceInformation == "RTN")
                    //             $contra = true;

                    //         $returnData['data'][$i++] = $abData;

                    //         $value->reco_flg  = "Y";
                    //         $value->save();
                    //     }
                    // }
                    if($contra)
                        $returnData['coding'] = 'Contra';
                    else
                        $returnData['coding'] = 'AB Approved Multiple';

                    $returnData['comment'] = "Fpout batch";
                }
                else
                {
                    $returnData['found'] = 'no';
                }

            }
            else
            {
                $returnData['found'] = 'no';
            }
        }
        else
        {
            $returnData['found'] = 'no';
        }
        return $returnData;
    }
//====Amjad Coded function=====================================
//     private function oneToManySTOMetchWithDate($bankStmt)
//     {
//         $returnData = array();
//         if(strpos($bankStmt->description, 'STO') !== false)
//         {
//             $FpoutObj = new Fpout();
//             $getFpoutData = $FpoutObj->getFpoutData($bankStmt);
//             if(count($getFpoutData) != 0)
//             {                 
//                     $returnData['found'] = 'yes';
//                     $returnData['type'] = 'Fpout';
//                     $i = 0;
//                     $contra = false;
//                     $returnData['data'] = $getFpoutData;           
//                     foreach ($getFpoutData as $key => $value) 
//                     {
//                         $endDate = $bankStmt->date;     
//                         $startDate = date("Y-m-d",strtotime("-3 day",strtotime($bankStmt->date)));

//                         $agencyBankingObj = new Agencybanking();
//                         $abData = $agencyBankingObj->getAbDataByFpout($value , $startDate , $endDate);
//                         if(!empty($abData))
//                         {
//                             if($value->ReferenceInformation == "RTN")
//                                 $contra = true;

//                             $returnData['data'][$i++] = $abData;

//                             $value->reco_flg  = "Y";
//                             $value->save();
//                         }
//                     }
//                     if($contra)
//                         $returnData['coding'] = 'Contra';
//                     else
//                         $returnData['coding'] = 'AB Approved Multiple';

//                     $returnData['comment'] = "Fpout batch";
//                 }      
//         else
//         {
//             $returnData['found'] = 'no';
//         }
     
//        return $returnData;
//     }
// }
//======================================================
    private function oneToManyADVMetchWithDate($bankStmt)
    {
        $returnData = array();
        if(strpos($bankStmt->description, 'ADV') !== false)
        {
            $AdviceObj = new Advice();
            $getAdviceAbData = $AdviceObj->getAdviceAbData($bankStmt);
            $getAdviceDDData = $AdviceObj->getDDAbData($bankStmt);

            $resultArray = array();
            $i = 0 ;
            if(count($getAdviceAbData) != 0)
            {
               foreach ($getAdviceAbData as $key => $value) {
                   $resultArray[$i++] = $value;
               }
            }
            if(count($getAdviceDDData) != 0)
            {
                foreach ($getAdviceDDData as $key => $value) {
                   $resultArray[$i++] = $value;
                }
            }

            if(count($resultArray) != 0)
            {
                $returnData['found'] = 'yes';
                $returnData['type'] = 'Advice';
                $i = 0;
                $contra = false;
                $returnData['data'] = $resultArray;
                
                $returnData['coding'] = 'Advice';

                $returnData['comment'] = "Advice";
            }
            else
            {
                $returnData['found'] = 'no';
            }
        }
        else
        {
            $returnData['found'] = 'no';
        }
        return $returnData;
    }

    public function autoComparedTransaction(Request $request)
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

        return View::make('autocomparetxn/comparedtxn')->with('filterColumn', $filterColumn);
    }

    public function autoComparedTransactionTable()
    {
        $params = array();
        if(Input::has('start_date') ){
            $params['start_date'] = $this->dateFormat(Input::get('start_date'),0);    
        }
        if(Input::has('end_date')){
            $params['end_date'] = $this->dateFormat(Input::get('end_date'),0);    
        }
        $result = Txnmappingint::getAutoComparedData($params);               
        
        $rows = array();
        foreach ($result['data'] as $data) {

            $recoFlgHtml = '';
            $recoSattelDate = '';
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
            $action = '';
            $action = "<button type='button' class='btn  btn-sm bg-blue' onclick='fetchRelatedData(\"".$data->ids."\")'><span class='fa fa-list'></span> </button>&nbsp;";

            $action .= "<button type='button' onclick='removeMatchTxn(\"".$data->ids."\")'  class='btn btn-sm bg-red' data-original-title='Remove transactions match' data-tooltip='tooltip'><span class='fa fa-remove'></span> </button>";

            
            $chkBtn = '<input type="checkbox" name="selectchk" id="selectchk-'.$data->ids.'" class="selectchk" value="'.$data->ids.'" onchange="changeChkBox(this.id, this.value)">';
            $txntype = "Auto";
            if($data->coding == "Manual Compare")
            {
                $txntype = "Manual";
            }

            $nestedData = array(); 
            $nestedData['txncmptype'] = $txntype;  
            $nestedData['chkBtn'] = $chkBtn;           
            $nestedData['action'] = $action;           
            $nestedData['reco_date'] = $recoSattelDate;
            $nestedData['name'] = $data->name;
            $nestedData['date'] = $data->date;
            $nestedData['description'] = $data->description;
            $nestedData['type'] = $data->type;
            $nestedData['debit'] = round($data->debit,2);
            $nestedData['credit'] = round($data->credit,2);
            $nestedData['bal'] = $data->bal;
            $nestedData['created_at'] = $this->dateFormat($data->created_at,1);
            $rows[] = $nestedData;

        }

        return array('total'=>$result['count'], 'rows'=>$rows);         
    }

    public function unMatchTransaction(Request $request)
    {
        if(!empty($request->get('bstId')))
        {
            $bstIntData = Txnmappingint::fetchAllTxnByBstId($request->get('bstId'));

            $bstData = Bankstatement::where("id",$request->get('bstId'))->first();

            $bstData->reco_flg = "N";
            $bstData->save();

            foreach ($bstIntData as $key => $value) {
                if($value->txn_type == "FP_Out" || $value->txn_type == "Fpout")
                {
                    Fpout::where("id",$value->txn_table_id)->update([
                        "reco_flg" => "N",
                        "reco_date" => NULL
                    ]);
                }

                if($value->txn_type == "AB Approved" || $value->txn_type == "AB Declined" || $value->txn_type == "DDR_Bacs" || $value->txn_type == "Agencybanking" )
                {
                    Agencybanking::where("id",$value->txn_table_id)->update([
                        "reco_flg" => "N",
                        "reco_date" => NULL
                    ]);
                }

                if($value->txn_type == "DD")
                {
                    Directdebits::where("id",$value->txn_table_id)->update([
                        "reco_flg" => "N",
                        "reco_date" => NULL
                    ]);
                }
            }

            Txnmappingint::where("bank_statement_id",$request->get('bstId'))->delete();

            echo "success";  
        }
        else
        {
            echo "error";
        }
        exit;

    }

    public function deletebulktxn(Request $request)
    {
        if(!empty($request->get('deleteId')))
        {
            $bstIds = $request->get('deleteId');
            foreach ($bstIds as $key1 => $value1) 
            {
                
                $bstIntData = Txnmappingint::fetchAllTxnByBstId($value1);

                $bstData = Bankstatement::where("id",$value1)->first();

                $bstData->reco_flg = "N";
                $bstData->save();

                foreach ($bstIntData as $key => $value) {
                    if($value->txn_type == "FP_Out" || $value->txn_type == "Fpout")
                    {
                        Fpout::where("id",$value->txn_table_id)->update([
                            "reco_flg" => "N",
                            "reco_date" => NULL
                        ]);
                    }

                    if($value->txn_type == "AB Approved" || $value->txn_type == "AB Declined" || $value->txn_type == "DDR_Bacs" || $value->txn_type == "Agencybanking")
                    {
                        Agencybanking::where("id",$value->txn_table_id)->update([
                            "reco_flg" => "N",
                            "reco_date" => NULL
                        ]);
                    }

                    if($value->txn_type == "DD")
                    {
                        Directdebits::where("id",$value->txn_table_id)->update([
                            "reco_flg" => "N",
                            "reco_date" => NULL
                        ]);
                    }
                }

                Txnmappingint::where("bank_statement_id",$value1)->delete();
            }
            echo "success";  
        }
        else
        {
            echo "error";
        }
        exit;

    }

    public function fetchRelatedData(Request $request)
    {
        if(!empty(Input::get('bstId')))
        {
            $agencybankingApprovedData = array();
            $agencybankingApprovedData = Agencybanking::select("agencybanking.*","agencybanking.id as ids" , DB::raw("IF(agencybanking.banking_type='Approved','AB Approved','AB Declined') AS txn_data_type"))
                ->join("txn_mapping_int","txn_mapping_int.txn_table_id","=","agencybanking.id")
                ->where("txn_mapping_int.bank_statement_id",Input::get('bstId'))
                ->where(function($query){
                        $query->where("txn_mapping_int.txn_type","AB Approved");
                        $query->orWhere("txn_mapping_int.txn_type","AB Declined");
                        $query->orWhere("txn_mapping_int.txn_type","DDR_Bacs");
                        $query->orWhere("txn_mapping_int.txn_type","Agencybanking");
                        $query->orWhere("txn_mapping_int.txn_type","Approved");
                        $query->orWhere("txn_mapping_int.txn_type","Declined");
                })
                ->get();
            $fp_outData = array();
            $fp_outData = Fpout::select("fp_out.*","fp_out.id as ids")
                         ->join("txn_mapping_int","txn_mapping_int.txn_table_id","=","fp_out.id")
                        ->where(function($query){
                            $query->where("txn_mapping_int.txn_type","FP_Out");
                            $query->orWhere("txn_mapping_int.txn_type","Fpout");
                        })
                        ->where("txn_mapping_int.bank_statement_id",Input::get('bstId'))
                        ->get();

            $advData = array();
            $advData = Directdebits::select("direct_debits.*","direct_debits.id as ids")
                        ->join("txn_mapping_int","txn_mapping_int.txn_table_id","=","direct_debits.id")
                        ->where("txn_mapping_int.txn_type","DD")
                        ->where("txn_mapping_int.bank_statement_id",Input::get('bstId'))
                        ->get();

            return View::make('autocomparetxn/relatedtxndata')
                ->with('agencybankingApprovedData', $agencybankingApprovedData)
                ->with('fp_outData', $fp_outData)
                ->with('advData', $advData)
                ;
        }
        else
        {
            echo "error";
            exit;
        }
    }

    public function exportall(Request $request)
    {
        if(!empty($request->get('exp_start_date')) && !empty($request->get('exp_end_date')))
        {
            $start_date = $request->get('exp_start_date');
            $end_date = $request->get('exp_end_date');

            $bankstatement = Bankstatement::select('bank_statement.*','bank_master.name','bank_statement.id as ids' , 'txn_mapping_int.id as txnIntId','txn_mapping_int.coding')
            ->join('txn_mapping_int', 'txn_mapping_int.bank_statement_id', '=', 'bank_statement.id')
            ->join('bank_master', 'bank_statement.bank_master_id', '=', 'bank_master.id')
            ->whereBetween('bank_statement.date' ,[$request->get('exp_start_date'), $request->get('exp_end_date')])
            ->groupby("bank_statement.id")
            ->with('autoCmpagencybankings','autoCmpfpout','autoCmpAdv')
            ->get()
            ->toArray()
            ;

            $excel = Excel::create("ComparedTxn Data ".$start_date." to ".$end_date, function($excel) use($bankstatement) {

            $excel->sheet('Monthly Invoice Report', function($sheet) use($bankstatement) {

               
                $sheet->setCellValue('A1', 'TxnCmp Type');
                $sheet->setCellValue('B1', 'Bank Master Name');
                $sheet->setCellValue('C1', 'Date');
                $sheet->setCellValue('D1', 'Description');
                $sheet->setCellValue('E1', 'Type');
                $sheet->setCellValue('F1', 'Debit');
                $sheet->setCellValue('G1', 'Credit');
                $sheet->setCellValue('H1', 'Sattelment Date');
                $sheet->setCellValue('I1', 'Date');
                $sheet->setCellValue('J1', 'Type');
                $sheet->setCellValue('K1', 'Description');
                $sheet->setCellValue('L1', 'Amount');
                $sheet->cells('A1:L1', function ($cells) {
                    $cells->setFontweight("bold");
                    $cells->setAlignment('center');
                   
                   });
            
          
                $row_number = 2;
                foreach ($bankstatement as $key => $value) 
                {
                    $recoSattelDate = "";

                    if(!empty($value['auto_cmpagencybankings'][0]))
                    {
                        $recoSattelDate = $value['auto_cmpagencybankings']['0']['SettlementDate'];    
                    }

                    $txntype = "Auto";
                    if($value['coding'] == "Manual Compare")
                    {
                        $txntype = "Manual";
                    }

                    $sheet->setCellValue('A'.$row_number, $txntype);
                    $sheet->setCellValue('B'.$row_number, $value['name']);
                    $sheet->setCellValue('C'.$row_number, $value['date']);
                    $sheet->setCellValue('D'.$row_number, $value['description']);
                    $sheet->setCellValue('E'.$row_number, $value['type']);
                    $sheet->setCellValue('F'.$row_number,  round($value['debit'],2));
                    $sheet->setCellValue('G'.$row_number, round($value['credit'],2));
                    $sheet->setCellValue('H'.$row_number, $recoSattelDate);
                    $row_number++;

                    if(!empty($value['auto_cmpagencybankings']))
                    {
                        foreach ($value['auto_cmpagencybankings'] as $key1 => $value1) 
                        {
                            $sheet->setCellValue('I'.$row_number, $value1['SettlementDate']);
                            $sheet->setCellValue('J'.$row_number, "AB ".$value1['banking_type']);
                            $sheet->setCellValue('K'.$row_number, $value1['External_name']." ".$value1['External_sortcode']." ".$value1['External_bankacc']);
                            $sheet->setCellValue('L'.$row_number, number_format($value1['CashAmt_value'],2,'.',''));
                            $row_number++;
                        }
                    }
                    
                    if(!empty($value['auto_cmpfpout']))
                    {
                        foreach ($value['auto_cmpfpout'] as $key1 => $value1) 
                        {
                            $sheet->setCellValue('I'.$row_number, $value1['file_date']);
                            $sheet->setCellValue('J'.$row_number, "Faster Payment Out");
                            $sheet->setCellValue('K'.$row_number, $value1['OrigCustomerSortCode']." ".$value1['OrigCustomerAccountNumber']);
                            $sheet->setCellValue('L'.$row_number, number_format($value1['Amount'],2,'.',''));
                            $row_number++;
                        }
                    }
                    if(!empty($value['auto_cmp_adv']))
                    {
                        foreach ($value['auto_cmp_adv'] as $key1 => $value1) 
                        {
                            $sheet->setCellValue('I'.$row_number, $value1['Due_Date']);
                            $sheet->setCellValue('J'.$row_number, "DD");
                            $sheet->setCellValue('K'.$row_number, $value1['diban']);
                            $sheet->setCellValue('L'.$row_number, number_format($value1['amount'],2,'.',''));
                            $row_number++;
                        }
                    }
                }
                
                $sheet->setBorder('A1:L'.($row_number-1),'thin');

            });

     

        })->store('xls', false, true);

            $fullFilePath = $excel['full'];
            return Response::download($fullFilePath);
        }
        
    }
}
