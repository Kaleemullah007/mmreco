<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Actionlog;
use App\Models\Fileimporthistory;
use guzzleHttp;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

     public function __construct()
    {
        view()->share('signedIn', Auth::check());
        view()->share('user', Auth::user());
    }

    public function dateFormat($date,$type)
    {
    	if($type==0){
    		return date('Y-m-d', strtotime($date));	
    	}else{
    		return date('d-m-Y', strtotime($date));	
    	}
    	
    } 

    public function trimAndencode($str)
    {
    	return trim(str_replace('&nbsp;','',e(utf8_encode($str))));
    }
    
    public static function getDistance($origins,$destinations)
    {
        $api="https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=".$origins."&destinations=".$destinations."&key=AIzaSyDXtXMd10R3jF-y7qcFmUWlE03K0AInZjk";
     
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_a = json_decode($response);
  
        $api_main = json_decode(utf8_encode($response),true);


      if(isset($api_main['rows'][0]['elements'][0]['distance']['value']))
      {

      	$distance=0;
        $distance = $api_main['rows'][0]['elements'][0]['distance']['value'];
        $mil = $distance / 1609.34;

        $mil = number_format((float)$mil, 2, '.', '');
    	  return $mil;

     }
     else
     {
    	 $mil = "";
    	 return $mil;
     }

    }

	public static function moneyFormatIndia($num)
	{
		if(!empty($num) && $num != "-")
			$num = number_format($num,2);

		$minus = false;
		if($num<0)
		{
			$minus = true;
			$num = $num * -1;
		}
		
		$explrestunits = "" ;
		$num=preg_replace('/,+/', '', $num);
		$words = explode(".", $num);
		$des="00";
		if(count($words)<=2){
			$num=$words[0];
			if(count($words)>=2){$des=$words[1];}
			if(strlen($des)<2){$des="$des";}else{$des=substr($des,0,2);}
		}
		if(strlen($num)>3){
			$lastthree = substr($num, strlen($num)-3, strlen($num));
			$restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
			$restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
			$expunit = str_split($restunits, 2);
			for($i=0; $i<sizeof($expunit); $i++){
				// creates each of the 2's group and adds a comma to the end
				if($i==0)
				{
					$explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
				}else{
					$explrestunits .= $expunit[$i].",";
				}
			}
			$thecash = $explrestunits.$lastthree;
		} else {
			$thecash = $num;
		}

		$NewNumber = $thecash.".".$des;

		if($minus)
		{
			$NewNumber = "-".$NewNumber;
		}

		return $NewNumber; // writes the final format where $currency is the currency symbol.

    }
	public static function Actionlog($row){
		
		    $logaction = new Actionlog();
            $logaction->item_id = $row['item_id'];
            $logaction->item_type = $row['item_type'];
            $logaction->target_id = $row['target_id'];
            $logaction->target_type = $row['target_type'];
			$logaction->new_data = json_encode($row['new_data']);
            $logaction->user_id = Auth::user()->id;
            $logaction->note = $row['note'];
            $logaction->logaction($row['logaction']);
			
			return "successful";
		
	}

    public function txnXmlToArrayParser($doc , $z)
    {
        $dataArrya = array();
        if($z->name == 'ApprovedAgencyBanking')
        {
            $node = simplexml_import_dom($doc->importNode($z->expand(), true));
            $dataArrya['key'] = 'ApprovedAgencyBanking'; 
            $dataArrya['val'] = json_decode(json_encode($node),true); 
            return $dataArrya;
        }
        if($z->name == 'DeclinedAgencyBanking')
        {
            $node = simplexml_import_dom($doc->importNode($z->expand(), true));
            $dataArrya['key'] = 'DeclinedAgencyBanking'; 
            $dataArrya['val'] = json_decode(json_encode($node),true);
            return $dataArrya;
        }
        if($z->name == 'CardEvent')
        {
            $node = simplexml_import_dom($doc->importNode($z->expand(), true));
            $dataArrya['key'] = 'CardEvent'; 
            $dataArrya['val'] = json_decode(json_encode($node),true);
            return $dataArrya;
        }
        if($z->name == 'AgencyBankingFee')
        {
            $node = simplexml_import_dom($doc->importNode($z->expand(), true));
            $dataArrya['key'] = 'AgencyBankingFee'; 
            $dataArrya['val'] = json_decode(json_encode($node),true);
            return $dataArrya;
        }
        if($z->name == 'CardAuthorisation')
        {
            $node = simplexml_import_dom($doc->importNode($z->expand(), true));
            $dataArrya['key'] = 'CardAuthorisation'; 
            $dataArrya['val'] = json_decode(json_encode($node),true);
            return $dataArrya;
        }
        if($z->name == 'CardBalAdjust')
        {
            $node = simplexml_import_dom($doc->importNode($z->expand(), true));
            $dataArrya['key'] = 'CardBalAdjust'; 
            $dataArrya['val'] = json_decode(json_encode($node),true);
            return $dataArrya;
        }
        if($z->name == 'CardChrgBackRepRes')
        {
            $node = simplexml_import_dom($doc->importNode($z->expand(), true));
            $dataArrya['key'] = 'CardChrgBackRepRes'; 
            $dataArrya['val'] = json_decode(json_encode($node),true);
            return $dataArrya;
        }
        if($z->name == 'CardFinancial')
        {
            $node = simplexml_import_dom($doc->importNode($z->expand(), true));
            $dataArrya['key'] = 'CardFinancial'; 
            $dataArrya['val'] = json_decode(json_encode($node),true);
            return $dataArrya;
        }
        if($z->name == 'CardFee')
        {
            $node = simplexml_import_dom($doc->importNode($z->expand(), true));
            $dataArrya['key'] = 'CardFee'; 
            $dataArrya['val'] = json_decode(json_encode($node),true);
            return $dataArrya;
        }
        if($z->name == 'CardLoadUnload')
        {
            $node = simplexml_import_dom($doc->importNode($z->expand(), true));
            $dataArrya['key'] = 'CardLoadUnload'; 
            $dataArrya['val'] = json_decode(json_encode($node),true);
            return $dataArrya;
        }
        if($z->name == 'MasterCardFee')
        {
            $node = simplexml_import_dom($doc->importNode($z->expand(), true));
            $dataArrya['key'] = 'MasterCardFee'; 
            $dataArrya['val'] = json_decode(json_encode($node),true);
            return $dataArrya;
        }
    }


    public function balXmlToArrayParser($doc , $z)
    {
        $dataArrya = array();
        if($z->name == 'ACCOUNT')
        {
            $node = simplexml_import_dom($doc->importNode($z->expand(), true));
            $dataArrya['key'] = 'ACCOUNT'; 
            $dataArrya['val'] = json_decode(json_encode($node),true); 
            return $dataArrya;
        }
    }

    public function directDebitXmlToArrayParser($doc , $z)
    {
        $dataArrya = array();
        if($z->name == 'Table1')
        {
            $node = simplexml_import_dom($doc->importNode($z->expand(), true));
            $dataArrya['key'] = 'Table1'; 
            $dataArrya['val'] = json_decode(json_encode($node),true); 
            return $dataArrya;
        }
    }

    public function fileImportHistoryLog($fileName , $path , $module_name , $userId = 0)
    {
        $fileimporthistoryObj = new Fileimporthistory;
        $fileimporthistoryObj->file_name = $fileName;
        $fileimporthistoryObj->file_path = $path;
        $fileimporthistoryObj->module_name = $module_name;
        $fileimporthistoryObj->imported_by = $userId;
        $fileimporthistoryObj->save();

    }

    public function fpoutXmlToArrayParser($doc , $z)
    {
        $dataArrya = array();
        if($z->name == 'di:FileID')
        {
            $node = simplexml_import_dom($doc->importNode($z->expand(), true));
            $dataArrya['key'] = 'FileID'; 
            $dataArrya['val'] = json_decode(json_encode($node),true); 
            return $dataArrya;
        }
        if($z->name == 'di:Date')
        {
            $node = simplexml_import_dom($doc->importNode($z->expand(), true));
            $dataArrya['key'] = 'Date'; 
            $dataArrya['val'] = json_decode(json_encode($node),true); 
            return $dataArrya;
        }
        if($z->name == 'di:PaymentIteration')
        {
            $node = simplexml_import_dom($doc->importNode($z->expand(), true));
            $dataArrya['key'] = 'PaymentIteration'; 
            $dataArrya['val'] = json_decode(json_encode($node),true); 
            return $dataArrya;
        }
    }

    public function fpoutXmlToArrayParser1($doc , $z , &$flg , &$dataArray, &$i)
    {
        // new values //
          if($z->name == 'FPSDocument')
          {

            $node = simplexml_import_dom($doc->importNode($z->expand(), true)); 
            $dt = json_decode(json_encode($node),true);
            if(!empty($dt))
            {
              $dataArray["di:FPSDocumentTitle"] = $dt['@attributes']['Title']; 
              $dataArray["di:FPSDocumentcreated"] = $dt['@attributes']['created']; 
              $dataArray["di:FPSDocumentschemaVersion"] = $dt['@attributes']['schemaVersion']; 
            }
          }

          if($z->name == 'di:SubmissionStatus')
          {

            $node = simplexml_import_dom($doc->importNode($z->expand(), true)); 
            $dt = json_decode(json_encode($node),true);
            if(!empty($dt))
              $dataArray["di:SubmissionStatus"] = $dt[0]; 
          }

          if($z->name == 'di:Currency')
          {

            $node = simplexml_import_dom($doc->importNode($z->expand(), true)); 
            $dt = json_decode(json_encode($node),true);
            if(!empty($dt))
              $dataArray["di:Currency"] = $dt[0]; 
          }

          if($z->name == 'di:FileStatus')
          {

            $node = simplexml_import_dom($doc->importNode($z->expand(), true)); 
            $dt = json_decode(json_encode($node),true);
            if(!empty($dt))
              $dataArray["di:FileStatus"] = $dt[0]; 
          }

          if($z->name == 'di:OutwardAcceptedVolume')
          {

            $node = simplexml_import_dom($doc->importNode($z->expand(), true)); 
            $dt = json_decode(json_encode($node),true);
            if(!empty($dt))
              $dataArray["di:OutwardAcceptedVolume"] = $dt[0]; 
          }

          if($z->name == 'di:OutwardAcceptedValue')
          {

            $node = simplexml_import_dom($doc->importNode($z->expand(), true)); 
            $dt = json_decode(json_encode($node),true);

            if(!empty($dt[0]))
              $dataArray["di:OutwardAcceptedValue"] = @$dt[0]; 

            if(!empty($dt))
              $dataArray["di:OutwardAcceptedValueCur"] = $dt['@attributes']['Ccy']; 
          }

          if($z->name == 'di:OutwardRejectedVolume')
          {

            $node = simplexml_import_dom($doc->importNode($z->expand(), true)); 
            $dt = json_decode(json_encode($node),true);
            if(!empty($dt))
              $dataArray["di:OutwardRejectedVolume"] = $dt[0]; 
          }

          if($z->name == 'di:OutwardRejectedValue')
          {

            $node = simplexml_import_dom($doc->importNode($z->expand(), true)); 
            $dt = json_decode(json_encode($node),true);

            if(!empty($dt[0]))
              $dataArray["di:OutwardRejectedValue"] = @$dt[0]; 

            if(!empty($dt))
              $dataArray["di:OutwardRejectedValueCur"] = $dt['@attributes']['Ccy']; 
          }
// old new //
          if($z->name == 'di:ReportTitle')
          {

            $node = simplexml_import_dom($doc->importNode($z->expand(), true)); 
            $dt = json_decode(json_encode($node),true);
            if(!empty($dt))
              $dataArray["di:ReportTitle"] = $dt[0]; 
          }
          if($z->name == 'di:CorporateID')
          {

            $node = simplexml_import_dom($doc->importNode($z->expand(), true)); 
            $dt = json_decode(json_encode($node),true);
            if(!empty($dt))
              $dataArray["di:CorporateID"] = $dt[0]; 
          }
          if($z->name == 'di:SubmissionID')
          {

            $node = simplexml_import_dom($doc->importNode($z->expand(), true)); 
            $dt = json_decode(json_encode($node),true);
            if(!empty($dt))
              $dataArray["di:SubmissionID"] = $dt[0]; 
          }

          // old values //
          if($z->name == 'di:FileID')
          {

            $node = simplexml_import_dom($doc->importNode($z->expand(), true)); 
            $dt = json_decode(json_encode($node),true);
            if(!empty($dt))
              $dataArray["di:FileID"] = $dt[0]; 
          }
          if($z->name == 'di:Date')
          {

            $node = simplexml_import_dom($doc->importNode($z->expand(), true)); 
            $dt = json_decode(json_encode($node),true);
            if(!empty($dt))
              $dataArray["di:Date"] = $dt[0]; 
          }
            
          if($z->name == 'di:PaymentIteration' && $flg == 0)
          {
              $flg = 1;
          }

          if($flg == 1)
          {
            if($z->name == 'di:Time')
            {

              $node = simplexml_import_dom($doc->importNode($z->expand(), true)); 
              $dt = json_decode(json_encode($node),true);
              if(!empty($dt))
                $dataArray["di:Time"] = $dt[0]; 
            }

            if($z->name == 'di:FPID')
            {

              $node = simplexml_import_dom($doc->importNode($z->expand(), true)); 
              $dt = json_decode(json_encode($node),true);
              if(!empty($dt))
                $dataArray["di:FPID"] = $dt[0]; 
            }
            if($z->name == 'di:Time')
            {

              $node = simplexml_import_dom($doc->importNode($z->expand(), true)); 
              $dt = json_decode(json_encode($node),true);
              if(!empty($dt))
                $dataArray["di:Time"] = $dt[0]; 
            }
            if($z->name == 'di:OrigCustomerSortCode')
            {

              $node = simplexml_import_dom($doc->importNode($z->expand(), true)); 
              $dt = json_decode(json_encode($node),true);
              if(!empty($dt))
                $dataArray["di:OrigCustomerSortCode"] = $dt[0]; 
            }
            if($z->name == 'di:OrigCustomerAccountNumber')
            {

              $node = simplexml_import_dom($doc->importNode($z->expand(), true)); 
              $dt = json_decode(json_encode($node),true);
              if(!empty($dt))
                $dataArray["di:OrigCustomerAccountNumber"] = $dt[0]; 
            }
            if($z->name == 'di:BeneficiaryCreditInstitution')
            {

              $node = simplexml_import_dom($doc->importNode($z->expand(), true)); 
              $dt = json_decode(json_encode($node),true);
              if(!empty($dt))
                $dataArray["di:BeneficiaryCreditInstitution"] = $dt[0]; 
            }
            if($z->name == 'di:BeneficiaryCustomerAccountNumber')
            {

              $node = simplexml_import_dom($doc->importNode($z->expand(), true)); 
              $dt = json_decode(json_encode($node),true);
              if(!empty($dt))
                $dataArray["di:BeneficiaryCustomerAccountNumber"] = $dt[0]; 
            }
            if($z->name == 'di:Amount')
            {
              $node = simplexml_import_dom($doc->importNode($z->expand(), true)); 
              $dt = json_decode(json_encode($node),true);
              if(!empty($dt) && isset($dt[0]))
                $dataArray["di:Amount"] = $dt[0]; 
            }
            if($z->name == 'di:Accepted')
            {
              $node = simplexml_import_dom($doc->importNode($z->expand(), true)); 
              $dt = json_decode(json_encode($node),true);
              if(!empty($dt) && isset($dt[0]))
                $dataArray["di:Accepted"] = $dt[0]; 
            }
            if($z->name == 'di:QualifierCode')
            {
              $node = simplexml_import_dom($doc->importNode($z->expand(), true)); 
              $dt = json_decode(json_encode($node),true);
              if(!empty($dt) && isset($dt[0]))
                $dataArray["di:QualifierCode"] = $dt[0]; 
            }
            if($z->name == 'di:ProcessedAsynchronously')
            {
              $node = simplexml_import_dom($doc->importNode($z->expand(), true)); 
              $dt = json_decode(json_encode($node),true);
              if(!empty($dt) && isset($dt[0]))
                $dataArray["di:ProcessedAsynchronously"] = $dt[0]; 
            }
            if($z->name == 'di:ReferenceInformation')
            {
              $node = simplexml_import_dom($doc->importNode($z->expand(), true)); 
              $dt = json_decode(json_encode($node),true);
              if(!empty($dt) && isset($dt[0]))
                $dataArray["di:ReferenceInformation"] = $dt[0]; 
            }
            if($z->name == 'di:OrigCustomerAccountName')
            {
              $node = simplexml_import_dom($doc->importNode($z->expand(), true)); 
              $dt = json_decode(json_encode($node),true);
              if(!empty($dt) && isset($dt[0]))
                $dataArray["di:OrigCustomerAccountName"] = $dt[0]; 
            }
          }
          if($z->name == 'di:OrigCustomerAccountName' && $flg == 1)
          {
              $flg = 2;
          }

          if($flg == 2)
          {
              return $dataArray;
          }

          return 1;

    }
    
}
