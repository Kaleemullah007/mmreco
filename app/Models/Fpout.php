<?php
namespace App\Models;

use App\Models\ParexModel;
use App\Models\Agencybanking;
use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Input;
use DB;
use Carbon\Carbon;

class Fpout extends ParexModel
{

   //use SoftDeletes;

    protected $dates = [''];
    protected $table = 'fp_out';

    protected $rules = array(
       
    );

    protected $injectUniqueIdentifier = true;
    use ValidatingTrait;

    public static function getDatatableData($params)
    {
        $fpout = Fpout::select('fp_out.*');            
        
        if(!isset($params['start_date']) && !isset($params['end_date'])){            
            $dt = Carbon::now();            
            $params['start_date'] = date('Y-m-d', strtotime($dt->subDays(15)));
            $params['end_date'] = date("Y-m-d");            
        }
        $fpout = $fpout->whereBetween('file_date' ,[$params['start_date'], $params['end_date']]);

        if (Input::has('offset')) {
            $offset = e(Input::get('offset'));
        } else {
            $offset = 0;
        }

        if (Input::has('limit')) {
            $limit = e(Input::get('limit'));
        } else {
            $limit = 50;
        }

        $sort = e(Input::get('sort'));        

        // For Datatable Search & Filter
        if (Input::has('filter')) 
        {
            $fpout = $fpout->TextSearch(Input::get('filter'),"filter");
        }
        else
        {
            if (Input::has('search')) {
                $fpout = $fpout->TextSearch(e(Input::get('search')),"search");
            }
        }

        $allowed_columns = ['FileID','FPID','OrigCustomerSortCode','OrigCustomerAccountNumber','BeneficiaryCreditInstitution','BeneficiaryCustomerAccountNumber','Amount','ProcessedAsynchronously','ReferenceInformation','OrigCustomerAccountName','created_at','file_date','FPSDocumentTitle','FPSDocumentcreated','FPSDocumentschemaVersion','SubmissionStatus','Currency','FileStatus','OutwardAcceptedVolume','OutwardAcceptedValue','OutwardAcceptedValueCur','OutwardRejectedVolume','OutwardRejectedValue','OutwardRejectedValueCur','Time'];

        $order = Input::get('order') === 'asc' ? 'asc' : 'desc';
        $sort = in_array(Input::get('sort'), $allowed_columns) ? e(Input::get('sort')) : 'fp_out.created_at';

        $fpout = $fpout->orderBy($sort, $order);
        
        $fpCount = $fpout->count();
        // $fpCount = count($fpCount);

        if($limit != 0){            
            $fpout = $fpout->skip($offset)->take($limit)->get();
        }
        else{   
            $fpout = $fpout->get();
        }
     
        return array(
            'data' => $fpout,
            'count' => $fpCount
        );
    }

    public static function getFpoutCmpDatatableData($params)
    {
        $fpout = Fpout::select('fp_out.*','fp_out.id as ids');            
        
        if(!isset($params['start_date']) && !isset($params['end_date'])){            
            $dt = Carbon::now();            
            $params['start_date'] = date('Y-m-d', strtotime($dt->subDays(15)));
            $params['end_date'] = date("Y-m-d");            
        }
        $fpout = $fpout->whereBetween('file_date' ,[$params['start_date'], $params['end_date']])->where("reco_flg","N");

        if (Input::has('offset')) {
            $offset = e(Input::get('offset'));
        } else {
            $offset = 0;
        }

        if (Input::has('limit')) {
            $limit = e(Input::get('limit'));
        } else {
            $limit = 50;
        }

        $sort = e(Input::get('sort'));        

        // For Datatable Search & Filter
        if (Input::has('filter')) 
        {
            $fpout = $fpout->TextSearch(Input::get('filter'),"filter");
        }
        else
        {
            if (Input::has('search')) {
                $fpout = $fpout->TextSearch(e(Input::get('search')),"search");
            }
        }

        $allowed_columns = ['FileID','FPID','OrigCustomerSortCode','OrigCustomerAccountNumber','BeneficiaryCreditInstitution','BeneficiaryCustomerAccountNumber','Amount','ProcessedAsynchronously','ReferenceInformation','OrigCustomerAccountName','created_at','file_date'];

        $order = Input::get('order') === 'asc' ? 'asc' : 'desc';
        $sort = in_array(Input::get('sort'), $allowed_columns) ? e(Input::get('sort')) : 'fp_out.created_at';

        $fpout = $fpout->orderBy($sort, $order);
        
        $fpCount = $fpout->count();
        // $fpCount = count($fpCount);

        if($limit != 0){            
            $fpout = $fpout->skip($offset)->take($limit)->get();
        }
        else{   
            $fpout = $fpout->get();
        }
     
        return array(
            'data' => $fpout,
            'count' => $fpCount
        );
    }

    /**
     * Query builder scope to search on text
     *
     * @param  Illuminate\Database\Query\Builder  $query  Query builder instance
     * @param  text                              $search      Search term
     *
     * @return Illuminate\Database\Query\Builder          Modified query builder
     */
    public function scopeTextsearch($query, $search, $type)
    {
        if($type == "filter")
        {
            $filterArray = json_decode($search,true);
            return $query->where(function ($query) use ($filterArray) 
            {
                if(isset($filterArray['FileID']) && !empty($filterArray['FileID']))
                {
                    $query->where('FileID', 'LIKE', '%'.$filterArray['FileID'].'%');
                }

                if(isset($filterArray['FPID']) && !empty($filterArray['FPID']))
                {
                    $query->where('FPID', 'LIKE', '%'.$filterArray['FPID'].'%');
                }

                if(isset($filterArray['OrigCustomerSortCode']) && !empty($filterArray['OrigCustomerSortCode']))
                {
                    $query->where('OrigCustomerSortCode', 'LIKE', '%'.$filterArray['OrigCustomerSortCode'].'%');
                }

                if(isset($filterArray['OrigCustomerAccountNumber']) && !empty($filterArray['OrigCustomerAccountNumber']))
                {
                    $query->where('OrigCustomerAccountNumber', 'LIKE', '%'.$filterArray['OrigCustomerAccountNumber'].'%');
                }

                if(isset($filterArray['BeneficiaryCreditInstitution']) && !empty($filterArray['BeneficiaryCreditInstitution']))
                {
                    $query->where('BeneficiaryCreditInstitution', 'LIKE', '%'.$filterArray['BeneficiaryCreditInstitution'].'%');
                }

                if(isset($filterArray['BeneficiaryCustomerAccountNumber']) && !empty($filterArray['BeneficiaryCustomerAccountNumber']))
                {
                    $query->where('BeneficiaryCustomerAccountNumber', 'LIKE', '%'.$filterArray['BeneficiaryCustomerAccountNumber'].'%');
                }

                if(isset($filterArray['Amount']) && !empty($filterArray['Amount']))
                {
                    $query->where('Amount', 'LIKE', '%'.$filterArray['Amount'].'%');
                }

                if(isset($filterArray['ProcessedAsynchronously']) && !empty($filterArray['ProcessedAsynchronously']))
                {
                    $query->where('ProcessedAsynchronously', 'LIKE', '%'.$filterArray['ProcessedAsynchronously'].'%');
                }

                if(isset($filterArray['ReferenceInformation']) && !empty($filterArray['ReferenceInformation']))
                {
                    $query->where('ReferenceInformation', 'LIKE', '%'.$filterArray['ReferenceInformation'].'%');
                }
                
                if(isset($filterArray['OrigCustomerAccountName']) && !empty($filterArray['OrigCustomerAccountName']))
                {
                    $query->where('OrigCustomerAccountName', 'LIKE', '%'.$filterArray['OrigCustomerAccountName'].'%');
                }

                if(isset($filterArray['created_at']) && !empty($filterArray['created_at']))
                {
                    $query->where('created_at', 'LIKE', '%'.$filterArray['created_at'].'%');
                }

                if(isset($filterArray['file_date']) && !empty($filterArray['file_date']))
                {
                    $query->where('file_date', 'LIKE', '%'.$filterArray['file_date'].'%');
                }

                if(isset($filterArray['ReportTitle']) && !empty($filterArray['ReportTitle']))
                {
                    $query->where('ReportTitle', 'LIKE', '%'.$filterArray['ReportTitle'].'%');
                }

                if(isset($filterArray['CorporateID']) && !empty($filterArray['CorporateID']))
                {
                    $query->where('CorporateID', 'LIKE', '%'.$filterArray['CorporateID'].'%');
                }

                if(isset($filterArray['SubmissionID']) && !empty($filterArray['SubmissionID']))
                {
                    $query->where('SubmissionID', 'LIKE', '%'.$filterArray['SubmissionID'].'%');
                }

                if(isset($filterArray['FPSDocumentTitle']) && !empty($filterArray['FPSDocumentTitle']))
                {
                    $query->where('FPSDocumentTitle', 'LIKE', '%'.$filterArray['FPSDocumentTitle'].'%');
                }

                if(isset($filterArray['FPSDocumentcreated']) && !empty($filterArray['FPSDocumentcreated']))
                {
                    $query->where('FPSDocumentcreated', 'LIKE', '%'.$filterArray['FPSDocumentcreated'].'%');
                }

                if(isset($filterArray['FPSDocumentschemaVersion']) && !empty($filterArray['FPSDocumentschemaVersion']))
                {
                    $query->where('FPSDocumentschemaVersion', 'LIKE', '%'.$filterArray['FPSDocumentschemaVersion'].'%');
                }

                if(isset($filterArray['SubmissionStatus']) && !empty($filterArray['SubmissionStatus']))
                {
                    $query->where('SubmissionStatus', 'LIKE', '%'.$filterArray['SubmissionStatus'].'%');
                }

                if(isset($filterArray['Currency']) && !empty($filterArray['Currency']))
                {
                    $query->where('Currency', 'LIKE', '%'.$filterArray['Currency'].'%');
                }

                if(isset($filterArray['FileStatus']) && !empty($filterArray['FileStatus']))
                {
                    $query->where('FileStatus', 'LIKE', '%'.$filterArray['FileStatus'].'%');
                }

                if(isset($filterArray['OutwardAcceptedVolume']) && !empty($filterArray['OutwardAcceptedVolume']))
                {
                    $query->where('OutwardAcceptedVolume', 'LIKE', '%'.$filterArray['OutwardAcceptedVolume'].'%');
                }

                if(isset($filterArray['OutwardAcceptedValue']) && !empty($filterArray['OutwardAcceptedValue']))
                {
                    $query->where('OutwardAcceptedValue', 'LIKE', '%'.$filterArray['OutwardAcceptedValue'].'%');
                }

                if(isset($filterArray['OutwardAcceptedValueCur']) && !empty($filterArray['OutwardAcceptedValueCur']))
                {
                    $query->where('OutwardAcceptedValueCur', 'LIKE', '%'.$filterArray['OutwardAcceptedValueCur'].'%');
                }

                if(isset($filterArray['OutwardRejectedVolume']) && !empty($filterArray['OutwardRejectedVolume']))
                {
                    $query->where('OutwardRejectedVolume', 'LIKE', '%'.$filterArray['OutwardRejectedVolume'].'%');
                }

                if(isset($filterArray['OutwardRejectedValue']) && !empty($filterArray['OutwardRejectedValue']))
                {
                    $query->where('OutwardRejectedValue', 'LIKE', '%'.$filterArray['OutwardRejectedValue'].'%');
                }

                if(isset($filterArray['OutwardRejectedValueCur']) && !empty($filterArray['OutwardRejectedValueCur']))
                {
                    $query->where('OutwardRejectedValueCur', 'LIKE', '%'.$filterArray['OutwardRejectedValueCur'].'%');
                }

                if(isset($filterArray['Time']) && !empty($filterArray['Time']))
                {
                    $query->where('Time', 'LIKE', '%'.$filterArray['Time'].'%');
                }

            });
        }
        else
        {
            $search = explode('+', $search);
            return $query->where(function ($query) use ($search) 
            {
                foreach ($search as $search) 
                {                            
                    $query->where('fp_out.FileID', 'LIKE',"%{$search}%")
                        ->orWhere('fp_out.FPID', 'LIKE',"%{$search}%")
                        ->orWhere('fp_out.OrigCustomerSortCode', 'LIKE',"%{$search}%")
                        ->orWhere('fp_out.OrigCustomerAccountNumber', 'LIKE',"%{$search}%")
                        ->orWhere('fp_out.BeneficiaryCreditInstitution', 'LIKE',"%{$search}%")
                        ->orWhere('fp_out.BeneficiaryCustomerAccountNumber', 'LIKE',"%{$search}%")
                        ->orWhere('fp_out.Amount', 'LIKE',"%{$search}%")
                        ->orWhere('fp_out.ProcessedAsynchronously', 'LIKE',"%{$search}%")
                        ->orWhere('fp_out.ReferenceInformation', 'LIKE',"%{$search}%")
                        ->orWhere('fp_out.OrigCustomerAccountName', 'LIKE',"%{$search}%")
                        ->orWhere('fp_out.created_at', 'LIKE',"%{$search}%")
                        ->orWhere('fp_out.ReportTitle', 'LIKE',"%{$search}%")
                        ->orWhere('fp_out.CorporateID', 'LIKE',"%{$search}%")
                        ->orWhere('fp_out.SubmissionID', 'LIKE',"%{$search}%")

                        ->orWhere('fp_out.FPSDocumentTitle', 'LIKE',"%{$search}%")
                        ->orWhere('fp_out.FPSDocumentcreated', 'LIKE',"%{$search}%")
                        ->orWhere('fp_out.FPSDocumentschemaVersion', 'LIKE',"%{$search}%")
                        ->orWhere('fp_out.SubmissionStatus', 'LIKE',"%{$search}%")
                        ->orWhere('fp_out.Currency', 'LIKE',"%{$search}%")
                        ->orWhere('fp_out.FileStatus', 'LIKE',"%{$search}%")
                        ->orWhere('fp_out.OutwardAcceptedVolume', 'LIKE',"%{$search}%")
                        ->orWhere('fp_out.OutwardAcceptedValue', 'LIKE',"%{$search}%")
                        ->orWhere('fp_out.OutwardAcceptedValueCur', 'LIKE',"%{$search}%")
                        ->orWhere('fp_out.OutwardRejectedVolume', 'LIKE',"%{$search}%")
                        ->orWhere('fp_out.OutwardRejectedValue', 'LIKE',"%{$search}%")
                        ->orWhere('fp_out.OutwardRejectedValueCur', 'LIKE',"%{$search}%")
                        ->orWhere('fp_out.Time', 'LIKE',"%{$search}%")
                        
                        ->orWhere('fp_out.file_date', 'LIKE',"%{$search}%");
                }                     
            });
        }
    }

    public function getFpoutData($bankStmt)
    {
        $whereDt = '';
        if(strpos($bankStmt->description, 'STO') !== false)
        {
            $descCmp = explode("STO", $bankStmt->description);
            $whereDt = str_replace(' ','',$descCmp[0]);
        }
        
       
        $startDate = $bankStmt->date;
        $endDate = $bankStmt->date;

        $dayName = date("D",strtotime($bankStmt->date));
        if($dayName == 'Mon')
            $startDate = date("Y-m-d",strtotime("-3 day",strtotime($bankStmt->date)));
        else
            $startDate = date("Y-m-d",strtotime("-1 day",strtotime($bankStmt->date)));
       //kumar was using get and you are using first
        $fpoutData = Fpout::select("fp_out.*","fp_out.id as ids",DB::raw(" 'FP_Out' as int_type"))->whereRaw("REPLACE(FileID,' ','') = '$whereDt' ")->whereBetween("file_date",[$startDate,$endDate])->get();

        // $fpoutData = Agencybanking::select("agencybanking.*","agencybanking.id as ids","fp_out.id as fp_out_id",DB::raw("IF(agencybanking.banking_type='Approved','AB Approved','AB Declined') AS int_type"))->join("fp_out","fp_out.agencybanking_Id","=","agencybanking.id")->whereRaw("REPLACE(FileID,' ','') = '$whereDt' ")->where("fp_out.file_date",$bankStmt->date)->get();

        return $fpoutData;
    }

    public function getFpoutDataByDate($startDate , $endDate)
    {
        $fpoutData = Fpout::whereBetween("file_date",[$startDate,$endDate])->whereNull("agencybanking_Id")
                    ->where("reco_flg","N")
                    // ->where("ReferenceInformation","RTN")
                    ->get();

        return $fpoutData;
    }
}
