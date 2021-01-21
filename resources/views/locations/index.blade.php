@extends('layouts/default')

{{-- Page title --}}
@section('title')
{{ trans('general.locations') }}
@parent
@stop

@section('header_right')
<a href="{{ route('create/location') }}" class="btn btn-primary pull-right">
  {{ trans('general.create') }}</a>
 
@stop
{{-- Page content --}}
@section('content')
@include('notifications')

<div class="row">
  	<div class="col-md-12">
    	<div class="box box-default">
      		<div class="box-body">
        		<div class="table-responsive">
        			<div class="col-md-8" style="margin-top:10px;">
                  <div class="col-md-1">
                          <a href="javascript:void(0)" onclick="resetAllTableData();" class="btn btn-danger " data-original-title="Reset Search" data-tooltip="tooltip" data-placement="top"><i class="fa fa-refresh"></i></a>
                  </div>
              </div>
          <table
          name="locations"
          class="table table-striped snipe-table"
          id="table"
          data-height="600"
          data-url="{{ route('api.locations.list') }}"
          data-cookie="true"
          data-click-to-select="true"
          data-cookie-id-table="locationsTable-{{ config('version.hash_version') }}">
            <thead>
              <tr>
              	<th data-switchable="false" data-searchable="false" data-sortable="false" data-field="actions">{{ trans('table.actions') }}</th>
                <th data-sortable="true" data-field="id" data-visible="false">{{ trans('general.id') }}</th>
                <th data-sortable="true" data-field="name">{{ trans('admin/locations/table.name') }}</th>
               
                
                
                <!--<th data-searchable="true" data-sortable="true" data-field="currency">{{ App\Models\Setting::first()->default_currency }}</th>-->
                <th data-searchable="true" data-sortable="true" data-field="address">{{ trans('admin/locations/table.address') }}</th>
                <th data-searchable="true" data-sortable="true" data-field="city">{{ trans('admin/locations/table.city') }}
                </th>
                <th data-searchable="true" data-sortable="true" data-field="state">
                 {{ trans('admin/locations/table.state') }}
                </th>
                  <th data-searchable="true" data-sortable="true" data-field="zip">
                      {{ trans('admin/locations/table.zip') }}
                  </th>
               
                <th data-searchable="true" data-sortable="true" data-field="contact_person">
                    {{ trans('general.contact_person_name') }}
                </th>
                <th data-searchable="true" data-sortable="true" data-field="contact_number">
                    {{ trans('general.contact_no') }}
                </th>
                <th data-sortable="true" data-field="created_at">Created</th>
                <!--<th data-searchable="true" data-sortable="true" data-field="country">
                {{ trans('admin/locations/table.country') }}</th>-->
                
              </tr>
            </thead>
          </table>
        </div>
      </div>
  </div>
</div>
@stop

@section('moar_scripts')
@include ('partials.bootstrap-table', ['exportFile' => 'locations-export', 'search' => true])
<script type="text/javascript">
function resetAllTableData()
{
     $.removeCookie('locationsTable-.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/admin/settings' });
     $.removeCookie('locationsTable-.bs.table.sortName', { path: '/{{config('app.project_name') }}/public/admin/settings' });
     $.removeCookie('locationsTable-.bs.table.sortOrder', { path: '/{{config('app.project_name') }}/public/admin/settings' });
     $.removeCookie('locationsTable-.bs.table.pageNumber', { path: '/{{config('app.project_name') }}/public/admin/settings' });
     $.removeCookie('locationsTable-.bs.table.pageList', { path: '/{{config('app.project_name') }}/public/admin/settings' });
 
     window.location.href="{{ url('admin/settings/locations') }}";
}
</script>

@stop
