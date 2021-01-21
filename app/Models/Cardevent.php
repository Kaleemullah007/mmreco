<?php
namespace App\Models;

use App\Models\ParexModel;
use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Input;
use DB;
use Carbon\Carbon;

class Cardevent extends ParexModel
{

   //use SoftDeletes;

    protected $dates = [''];
    protected $table = 'cardevent';

    protected $rules = array(
    );

    protected $injectUniqueIdentifier = true;
    use ValidatingTrait;

    public static function getDatatableData($params)
    {
        $cardevent = Cardevent::select('cardevent.*');             
        
        if(!isset($params['start_date']) && !isset($params['end_date'])){            
            $dt = Carbon::now();            
            $params['start_date'] = date('Y-m-d', strtotime($dt->subDays(15)));
            $params['end_date'] = date("Y-m-d");            
        }
        $cardevent = $cardevent->whereBetween('file_date' ,[$params['start_date'], $params['end_date']]);

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
            $cardevent = $cardevent->TextSearch(Input::get('filter'),"filter");
        }
        else
        {
            if (Input::has('search')) {
                $cardevent = $cardevent->TextSearch(e(Input::get('search')),"search");
            }
        }

        $allowed_columns = ['Card_PAN','Event_Type','Event_ActivationDate','Event_StatCode','Event_Date','created_at','file_date'];

        $order = Input::get('order') === 'asc' ? 'asc' : 'desc';
        $sort = in_array(Input::get('sort'), $allowed_columns) ? e(Input::get('sort')) : 'cardevent.created_at';

        $cardevent = $cardevent->orderBy($sort, $order);
        
        $ceCount = $cardevent->count();
        // $ceCount = count($ceCount);

        if($limit != 0){            
            $cardevent = $cardevent->skip($offset)->take($limit)->get();
        }
        else{   
            $cardevent = $cardevent->get();
        }
     
        return array(
            'data' => $cardevent,
            'count' => $ceCount
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
                if(isset($filterArray['Card_PAN']) && !empty($filterArray['Card_PAN']))
                {
                    $query->where('Card_PAN', 'LIKE', '%'.$filterArray['Card_PAN'].'%');
                }

                if(isset($filterArray['Event_Type']) && !empty($filterArray['Event_Type']))
                {
                    $query->where('Event_Type', 'LIKE', '%'.$filterArray['Event_Type'].'%');
                }

                if(isset($filterArray['Event_ActivationDate']) && !empty($filterArray['Event_ActivationDate']))
                {
                    $query->where('Event_ActivationDate', 'LIKE', '%'.$filterArray['Event_ActivationDate'].'%');
                }

                if(isset($filterArray['Event_StatCode']) && !empty($filterArray['Event_StatCode']))
                {
                    $query->where('Event_StatCode', 'LIKE', '%'.$filterArray['Event_StatCode'].'%');
                }

                if(isset($filterArray['Event_Date']) && !empty($filterArray['Event_Date']))
                {
                    $query->where('Event_Date', 'LIKE', '%'.$filterArray['Event_Date'].'%');
                }

                if(isset($filterArray['created_at']) && !empty($filterArray['created_at']))
                {
                    $query->where('created_at', 'LIKE', '%'.$filterArray['created_at'].'%');
                }

                if(isset($filterArray['file_date']) && !empty($filterArray['file_date']))
                {
                    $query->where('file_date', 'LIKE', '%'.$filterArray['file_date'].'%');
                }

                if(isset($filterArray['file_name']) && !empty($filterArray['file_name']))
                {
                    $query->where('file_name', 'LIKE', '%'.$filterArray['file_name'].'%');
                }

                if(isset($filterArray['Card_productid']) && !empty($filterArray['Card_productid']))
                {
                    $query->where('Card_productid', 'LIKE', '%'.$filterArray['Card_productid'].'%');
                }

                if(isset($filterArray['Event_Source']) && !empty($filterArray['Event_Source']))
                {
                    $query->where('Event_Source', 'LIKE', '%'.$filterArray['Event_Source'].'%');
                }

                if(isset($filterArray['Event_OldStatCode']) && !empty($filterArray['Event_OldStatCode']))
                {
                    $query->where('Event_OldStatCode', 'LIKE', '%'.$filterArray['Event_OldStatCode'].'%');
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
                    $query->where('cardevent.Card_PAN', 'LIKE',"%{$search}%")
                        ->orWhere('cardevent.Event_Type', 'LIKE',"%{$search}%")
                        ->orWhere('cardevent.Event_ActivationDate', 'LIKE',"%{$search}%")
                        ->orWhere('cardevent.Event_StatCode', 'LIKE',"%{$search}%")
                        ->orWhere('cardevent.Event_Date', 'LIKE',"%{$search}%")                        
                        ->orWhere('cardevent.created_at', 'LIKE',"%{$search}%")

                        ->orWhere('cardevent.file_name', 'LIKE',"%{$search}%")
                        ->orWhere('cardevent.Card_productid', 'LIKE',"%{$search}%")
                        ->orWhere('cardevent.Event_Source', 'LIKE',"%{$search}%")
                        ->orWhere('cardevent.Event_OldStatCode', 'LIKE',"%{$search}%")
                        
                        ->orWhere('cardevent.file_date', 'LIKE',"%{$search}%");
                }              
            });
        }
    }
}
