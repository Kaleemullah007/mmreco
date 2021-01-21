<?php
namespace App\Http\Controllers\Rejectedbacs;

use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use App\Models\Rejactedbacs;
use Illuminate\Http\Request;
use App\Http\Requests\SaveRejectedBacsRequest;
use App\Http\Requests\UpdateRejectedBacsRequest;
use DB;
use Input;
use Redirect;
use View;
use Carbon\Carbon;
use Validator;
use Response;
use Excel;

/**
 * This controller handles all actions related to Rejected bank syayement for
 * the Parextech Asset Management application.
 *
 * @version    v1.0
 */

class RejectedbacsController extends Controller
{

    /**
    * Returns a view that invokes the ajax tables which actually contains
    * the content for the rejected banks statement listing, which is generated in getDatatable().
    *
    * @author [A. Gianotto] [<snipe@snipe.net>]
    * @see RejectedbacsController::getDatatable() method that generates the JSON response
    * @since [v1.0]
    * @return View
    */
    public function getIndex()
    {
    	$filterColumn = array();
       
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));        

        return View::make('rejectedbacs/index')->with('filterColumn', $filterColumn);
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
        $result = Rejactedbacs::getDatatableData();               
        
        $rows = array();
        foreach ($result['data'] as $rejactedbac) {
            $actions = '<nobr>';
                
            // if (Gate::allows('users.edit')) {
                $actions .= '<a href="' . route('update/rejectedbacs',
                        $rejactedbac->id) . '" class="btn btn-warning btn-sm" data-original-title="Edit" data-tooltip="tooltip"><i class="fa fa-pencil icon-white"></i></a> ';
                
            // }

            // if (Gate::allows('users.delete')) {
                
                $actions .= '<a data-html="false" class="btn delete-asset btn-danger btn-sm" data-toggle="modal" href="' . route('delete/rejectedbacs',
                            $rejactedbac->id) . '" data-content="Are you sure you wish to delete rejected BACS entry?" data-title="Delete ' . htmlspecialchars($rejactedbac->Sort_Code) . '?" onClick="return false;" data-original-title="Delete" data-tooltip="tooltip"><i class="fa fa-trash icon-white"></i></a> ';                
            // } else {
            //     $actions.='';
            // }

            $actions .= '</nobr>';

            $rows[] = array(
                'id'    => $rejactedbac->id,                
                'Date'   => e($rejactedbac->Date),
                'Token'  => e($rejactedbac->Token),
                'Sort_Code'   => e($rejactedbac->Sort_Code),
                'Account'  => e($rejactedbac->Account),                
                'Txn_Amt'    => round(e($rejactedbac->Txn_Amt),2),                
                'actions'   => ($actions) ? $actions : '',                                                
            );

        }

        return array('total'=>$result['count'], 'rows'=>$rows);                
    }

    /**
    * Returns a view that displays the rejected bank statement creation form.
    *
    * @author [A. Gianotto] [<snipe@snipe.net>]
    * @since [v1.0]
    * @return View
    */
    public function getCreate()
    {        
        return View::make('rejectedbacs/edit')
            ->with('rejactedbacs', new Rejactedbacs)
        ;
    }

    /**
    * Validate and store the new rejected bank statement data, or return an error.
    *
    * @author [A. Gianotto] [<snipe@snipe.net>]
    * @since [v1.0]
    * @return Redirect
    */
    public function postCreate(SaveRejectedBacsRequest $request)
    {           
        $rejactedbacs = new Rejactedbacs;

        $date = Carbon::parse($request->input('Date'))->format('Y-m-d');

        $rejactedbacs->Date = $date;
        $rejactedbacs->Token = e($request->input('Token'));
        $rejactedbacs->Sort_Code = e($request->input('Sort_Code'));        
        $rejactedbacs->Account = e($request->input('Account'));
        $rejactedbacs->Txn_Amt = e($request->input('Txn_Amt'));
        $rejactedbacs->Bacs_Return = e($request->input('Bacs_Return'));   
        $rejactedbacs->Txn_Code = e($request->input('Txn_Code'));   
        $rejactedbacs->Error_Code = e($request->input('Error_Code'));   
        $rejactedbacs->File_Description = e($request->input('File_Description'));   
        $rejactedbacs->Failure_Reason = e($request->input('Failure_Reason'));   
        $rejactedbacs->BNK_BankAccountNumbersRef = e($request->input('BNK_BankAccountNumbersRef'));   
        $rejactedbacs->BNK_IncomingOutgoingBankFilesRef = e($request->input('BNK_IncomingOutgoingBankFilesRef'));   
        $rejactedbacs->PANT = e($request->input('PANT'));   
        $rejactedbacs->PublicToken = e($request->input('PublicToken'));   
        $rejactedbacs->rej_bacs_id = e($request->input('rej_bacs_id'));   
        $rejactedbacs->TransactionStatus = e($request->input('TransactionStatus'));   
        $rejactedbacs->BNKTransID = e($request->input('BNKTransID'));   
        $rejactedbacs->DestAccName_BACS = e($request->input('DestAccName_BACS'));   
        $rejactedbacs->IssuerID = e($request->input('IssuerID'));   
        $rejactedbacs->Institution = e($request->input('Institution'));   
        $rejactedbacs->ActionCode = e($request->input('ActionCode'));   
        $rejactedbacs->RecordType = e($request->input('RecordType'));   

        if ($rejactedbacs->save()) 
        {
            return redirect::route('rejectedbacs')->with('success', trans('admin/rejectedbacs/message.success.create'));
        }
        return redirect()->back()->withInput()->withErrors($rejactedbacs->getErrors());
    }

    public function getEdit($id = null)
    {
        try 
        {
           
            $rejactedbacs = Rejactedbacs::find($id);
            if(empty($rejactedbacs)){
                $error = trans('admin/rejectedbacs/message.direct_debit_not_found', compact('id'));
                // Redirect to the rejected bank statement page
                return redirect()->route('rejectedbacs')->with('error', $error);
            }
            
        } 
        catch (Exception $e) {
            $error = trans('admin/rejectedbacs/message.direct_debit_not_found', compact('id'));
            // Redirect to the rejected bank statement page
            return redirect()->route('rejectedbacs')->with('error', $error);
        }

        // Show the page
        return View::make('rejectedbacs/edit', compact('rejactedbacs'));
    }

    /**
    * Validate and save edited rejected bank statement data from edit form.
    *
    * @author [A. Gianotto] [<snipe@snipe.net>]
    * @since [v1.0]
    * @param  int  $id
    * @return Redirect
    */
    public function postEdit(UpdateRejectedBacsRequest $request, $id = null)
    {

        $rejactedbacs = Rejactedbacs::find($id);

        $date = Carbon::parse($request->input('Date'))->format('Y-m-d');    
           
        $rejactedbacs->Date = $date;
        $rejactedbacs->Token = e($request->input('Token'));
        $rejactedbacs->Sort_Code = e($request->input('Sort_Code'));        
        $rejactedbacs->Account = e($request->input('Account'));
        $rejactedbacs->Txn_Amt = e($request->input('Txn_Amt'));
        $rejactedbacs->Bacs_Return = e($request->input('Bacs_Return'));   
        $rejactedbacs->Txn_Code = e($request->input('Txn_Code'));   
        $rejactedbacs->Error_Code = e($request->input('Error_Code'));   
        $rejactedbacs->File_Description = e($request->input('File_Description'));   
        $rejactedbacs->Failure_Reason = e($request->input('Failure_Reason'));   
        $rejactedbacs->BNK_BankAccountNumbersRef = e($request->input('BNK_BankAccountNumbersRef'));   
        $rejactedbacs->BNK_IncomingOutgoingBankFilesRef = e($request->input('BNK_IncomingOutgoingBankFilesRef'));   
        $rejactedbacs->PANT = e($request->input('PANT'));   
        $rejactedbacs->PublicToken = e($request->input('PublicToken'));   
        $rejactedbacs->rej_bacs_id = e($request->input('rej_bacs_id'));   
        $rejactedbacs->TransactionStatus = e($request->input('TransactionStatus'));   
        $rejactedbacs->BNKTransID = e($request->input('BNKTransID'));   
        $rejactedbacs->DestAccName_BACS = e($request->input('DestAccName_BACS'));   
        $rejactedbacs->IssuerID = e($request->input('IssuerID'));   
        $rejactedbacs->Institution = e($request->input('Institution'));   
        $rejactedbacs->ActionCode = e($request->input('ActionCode'));   
        $rejactedbacs->RecordType = e($request->input('RecordType'));     
          
        if ($rejactedbacs->save()) 
        {       
            // Prepare the success message
            $success = trans('admin/rejectedbacs/message.success.update');

            // Redirect to the rejected bank statement page
            return redirect()->route('rejectedbacs')->with('success', $success);
        }
        return redirect()->back()->withInput()->withErrors($user->getErrors());
    }

    /**
    * Delete a rejected bank statement entry.
    *
    * @author [A. Gianotto] [<snipe@snipe.net>]
    * @since [v1.0]
    * @param  int  $id
    * @return Redirect
    */
    public function getDelete($id = null)
    {
        try {
            // Get rejected bank statement information
            $rejactedbacs = Rejactedbacs::find($id);
            $rejactedbacs->delete();
            $success = trans('admin/rejectedbacs/message.success.delete');            
            return redirect()->route('rejectedbacs')->with('success', $success);

        } catch (Exception $e) {
            return redirect()->route('rejectedbacs')->with('error', trans('admin/rejectedbacs/message.direct_debit_not_found', compact('id')));
        }
    }

    public function getImport()
    {
        return View::make('rejectedbacs/import');
    }

    public function postImport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'files' => 'required|mimes:xls,xlsx',         
        ]);
        if ($validator->fails()) {            
            $error = $validator->errors()->first();            
            return redirect()->to("rejectedbacs/import")->with("error",$error);                
        }
        $file = Input::file('files');
            $fixcol=24;
            $path = config('app.private_uploads').'/rejectedbacs';

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
                    $results['error']='File type must be CSV';
                    return $results;
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
                    $csvfilename="rejectedBacs-".date('Y-m-d-his').".csv";
                    $path1 = storage_path();
                    $path1  = str_replace('storage', '', $path1);

                    $path1 = $path1.'public/uploads/rejectedbacs';
                    $csvfile = fopen($path1."/".$csvfilename, 'w');
                    
                    $Reader = Excel::load($filePath)->noHeading()->all()->toArray();

                    $dataArray = array();
                    if(isset($Reader[0][0][0]))
                        $dataArray = $Reader[0];
                    else
                        $dataArray = $Reader;
                    // $Reader = new SpreadsheetReader($filePath );
                    // $Reader -> ChangeSheet(0);
                    $headers=[
                        // strtolower to prevent Excel from trying to open it as a SYLK file
                       // strtolower(trans('general.id')),
                        "Date",
                        "Token",
                        "Sort Code",
                        "Account",
                        "Txn Amt",
                        "Return",
                        "Return (BS)",
                        "Txn Code",
                        "Error Code",
                        "File Description",
                        "Failure Reason",
                        "BNK_BankAccountNumbersRef",
                        "BNK_IncomingOutgoingBankFilesRef",
                        "PANT",
                        "PublicToken",
                        "ID",
                        "TransactionStatus",
                        "BNKTransID",
                        "DestAccName_BACS",
                        "IssuerID",
                        "Institution",
                        "ActionCode",
                        "RecordType",
                        "Status"
                    ];
                    if(!empty($Reader))
                    {
                        fputcsv($csvfile, $headers);
                    }

                    foreach($dataArray[0] as $k1=>$v1)
                    {

                      for($i=0;$i<=22;$i++)
                      {
                        if(!isset($v1[$i]))
                        {
                          $v1[$i] = "";
                        }
                      }
                     
                      if($k1 != 0)
                      {
                        
                           $values = [
                          
                                $v1[0],
                                trim($v1[1]),
                                trim($v1[2]),
                                trim($v1[3]),
                                trim($v1[4]),
                                trim($v1[5]),
                                trim($v1[6]),
                                trim($v1[7]),
                                trim($v1[8]),
                                trim($v1[9]),
                                trim($v1[10]),
                                trim($v1[11]),
                                trim($v1[12]),
                                trim($v1[13]),
                                trim($v1[14]),
                                trim($v1[15]),
                                trim($v1[16]),
                                trim($v1[17]),
                                trim($v1[18]),
                                trim($v1[19]),
                                trim($v1[20]),
                                trim($v1[21]),
                                trim($v1[22]),
                                
                      
                           ];
                            $RejactedbacsObj = new Rejactedbacs();

                            if(!empty($v1[0]))
                                $RejactedbacsObj->Date = date("Y-m-d",strtotime($v1[0]));
                            
                            $RejactedbacsObj->Token = $v1[1];
                            $RejactedbacsObj->Sort_Code = $v1[2];
                            $RejactedbacsObj->Account = $v1[3];
                            $RejactedbacsObj->Txn_Amt = $v1[4];
                            $RejactedbacsObj->return_b = $v1[5];
                            $RejactedbacsObj->Bacs_Return = $v1[6];
                            $RejactedbacsObj->Txn_Code = $v1[7];
                            $RejactedbacsObj->Error_Code = $v1[8];
                            $RejactedbacsObj->File_Description = $v1[9];
                            $RejactedbacsObj->Failure_Reason = $v1[10];
                            $RejactedbacsObj->BNK_BankAccountNumbersRef = $v1[11];
                            $RejactedbacsObj->BNK_IncomingOutgoingBankFilesRef = $v1[12];
                            $RejactedbacsObj->PANT = $v1[13];
                            $RejactedbacsObj->PublicToken = $v1[14];
                            $RejactedbacsObj->rej_bacs_id = $v1[15];
                            $RejactedbacsObj->TransactionStatus = $v1[16];
                            $RejactedbacsObj->BNKTransID = $v1[17];
                            $RejactedbacsObj->DestAccName_BACS = $v1[18];
                            $RejactedbacsObj->IssuerID = $v1[19];
                            $RejactedbacsObj->Institution = $v1[20];
                            $RejactedbacsObj->ActionCode = $v1[21];
                            $RejactedbacsObj->RecordType = $v1[22];

                           if($RejactedbacsObj->save())
                           {
                                $values[] ="Success";          
                           }
                           else
                           {
                               $values[] ="required field error";
                           }
                        
                        fputcsv($csvfile, $values); 
                      }
                      
                    }
                    fclose($csvfile); 

                } catch (\Symfony\Component\HttpFoundation\File\Exception\FileException $exception) {
                    $results['error']="error in file upload";
                    if (config('app.debug')) {
                        $results['error'].= ' ' . $exception->getMessage();
                    }
                    return $results;
                }
                $response = array();
           $response['filePath'] = config('app.url').'/uploads/rejectedbacs/'.$csvfilename;
           $response['fileName'] = $csvfilename;

            return Response::download($path1."/".$csvfilename,$csvfilename, $headers)->deleteFileAfterSend(true);

           // return redirect()->to("rejectedbacs/import")->with("response",$response);
        
    }
}
