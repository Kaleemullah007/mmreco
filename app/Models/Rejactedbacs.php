<?php
namespace App\Models;

use App\Models\ParexModel;
use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use Input;

class Rejactedbacs extends ParexModel
{

   use SoftDeletes;

    protected $dates = [''];
    protected $table = 'rejacted_bacs';

    protected $rules = array(
        'Date'   => 'required',
        'Token'   => 'required',
        'Txn_Amt'   => 'required',
    );

    protected $injectUniqueIdentifier = true;
    use ValidatingTrait;

    public static function getDatatableData()
    {
        $rejactedbacs = Rejactedbacs::select('rejacted_bacs.id','rejacted_bacs.Date','rejacted_bacs.Token','rejacted_bacs.Sort_Code','rejacted_bacs.Account','rejacted_bacs.Txn_Amt');

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

        if (Input::get('sort')=='Date') {
            $sort = 'Date';
        } else {
            $sort = e(Input::get('sort'));
        }

        // For Datatable Search & Filter
        if (Input::has('filter')) 
        {
            $rejactedbacs = $rejactedbacs->TextSearch(Input::get('filter'),"filter");
        }
        else
        {
            if (Input::has('search')) {
                $rejactedbacs = $rejactedbacs->TextSearch(e(Input::get('search')),"search");
            }
        }

        $allowed_columns = ['Date','Token','Sort_Code','Account','Txn_Amt'];

        $order = Input::get('order') === 'asc' ? 'asc' : 'desc';
        $sort = in_array(Input::get('sort'), $allowed_columns) ? e(Input::get('sort')) : 'rejacted_bacs.created_at';

        $rejactedbacs = $rejactedbacs->orderBy($sort, $order);
                
        $ddCount = $rejactedbacs->count();
        // $ddCount = count($ddCount);

        if($limit != 0)
            $rejactedbacs = $rejactedbacs->skip($offset)->take($limit)->get();
        else
            $rejactedbacs = $rejactedbacs->get();

        return array(
            'data' => $rejactedbacs,
            'count' => $ddCount
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
                if(isset($filterArray['Date']) && !empty($filterArray['Date']))
                {
                    $query->where('Date', 'LIKE', '%'.$filterArray['Date'].'%');
                }

                if(isset($filterArray['Token']) && !empty($filterArray['Token']))
                {
                    $query->where('Token', 'LIKE', '%'.$filterArray['Token'].'%');
                }

                if(isset($filterArray['Sort_Code']) && !empty($filterArray['Sort_Code']))
                {
                    $query->where('Sort_Code', 'LIKE', '%'.$filterArray['Sort_Code'].'%');
                }

                if(isset($filterArray['Account']) && !empty($filterArray['Account']))
                {
                    $query->where('Account', 'LIKE', '%'.$filterArray['Account'].'%');
                }

                if(isset($filterArray['Txn_Amt']) && !empty($filterArray['Txn_Amt']))
                {
                    $query->where('Txn_Amt', 'LIKE', '%'.$filterArray['Txn_Amt'].'%');
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
                $query->where(DB::raw("rejacted_bacs.Date"), 'LIKE', "%$search%")
                    ->orWhere('rejacted_bacs.Token', 'LIKE', "%$search%")
                    ->orWhere('rejacted_bacs.Sort_Code', 'LIKE', "%$search%")             
                    ->orWhere('rejacted_bacs.Account', 'LIKE', "%$search%")                   
                    ->orWhere('rejacted_bacs.Txn_Amt', 'LIKE', "%$search%")                    
                    ;
                }
            });
        }
    }
}
