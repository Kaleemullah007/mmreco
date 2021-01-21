@extends('layouts/default')

{{-- Page title --}}
@section('title')
{{ trans('admin/bankstmt/general.bankstmt_import') }}
@parent
@stop

@section('header_right')

@stop

{{-- Page content --}}
@section('content')
@include('notifications')

<div class="row">
  <div class="col-md-8 col-md-offset-2">
    <div class="box box-default">
      <div class="box-body">
        <form class="form-horizontal" name="bstForm" id="bstForm" role="form" method="post" enctype="multipart/form-data" action="" >
        <!-- CSRF Token -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />

			@if (Session::get('message'))
			<p class="alert-danger">
				You have an error in your CSV file:<br />
				{{ Session::get('message') }}
			</p>
			@endif

        <p><strong>Upload BankStatement File.</strong>. </p>

            <div class="form-group {{ $errors->has('bank_master_id') ? ' has-error' : '' }}">
                <div class="col-md-3 control-label">{{ Form::label('bank_master_id', trans('admin/bankstmt/general.bank_master_id')) }}</div>
                <div class="col-md-6 col-sm-12 required">
                    {{ Form::select('bank_master_id', $bankList,'', array('class'=>'form-control', 'style'=>'width:100%', 'data-placeholder'=>trans('admin/users/general.bank_master_id'), 'id'=>'bank_master_id','onchange'=>'changeBankName($(this))')) }}
                    
                    {!! $errors->first('bank_master_id', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
                </div>
            </div>
            <input type="hidden" name="bankName" value="{{$bankName}}" id="bankName" />

            <div class="form-group {!! $errors->first('user_import_csv', 'has-error') }}">
                <label for="first_name" class="col-sm-3 control-label">{{ trans('admin/bankstmt/general.importfile') }}</label>
        				<div class="col-sm-5">
        					<input type="file" name="user_import_csv" id="user_import_csv">
        				</div>
            </div>


        </div>

    <!-- Form Actions -->
    <div class="box-footer text-right">
      <!-- <button type="submit" class="btn btn-default">{{ trans('button.submit') }}</button> -->
      <button type="button" class="btn btn-default" onclick="duplicationCheck();">{{ trans('button.submit') }}</button>
    </div>

</form>
</div></div></div></div>

<div class="modal fade" id="bankStatementModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">BankStatement Record Duplication Alert</h4>
            </div>
            <div class="modal-body">
                <div class="box box-default">
                    <div class="box-body" id="bstDupInfo">
                        
                        
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                    <button type="button" class="btn bg-green" data-dismiss="modal" onclick="submitData();">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('button.cancel') }}</button>
                    
            </div>
        </div><!-- /.modal-content -->
    </div>
</div>

@section('moar_scripts')
<script>
$(document).ready(function(){

});

function changeBankName(obj)
{
    $('#bankName').val(obj.val());
}

function duplicationCheck()
{
     $.ajaxSetup({
        headers: {
             'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });
  var form = $("#bstForm");
   var formData = new FormData(form[0]);
   startLoading();
    $.ajax({
          type: 'POST',
          url: "{{config('app.url') }}/bankstatement/duplicationCheck", 
          data: formData,
          processData: false,
            contentType: false,
          success:function( data ) 
          { 
            stopLoading();
           var jsonData = $.parseJSON(data);
           if(jsonData.code == "success")
           {
                var dateInfoHtml = '';
                $.each(jsonData.dupCheckBstDate,function(k,v){
                    dateInfoHtml += "<div class='col-md-12'><b> "+v+" </b></div>";
                });

                var btnHtml = "";
                if(jsonData.dataStoreFlg == "append")
                {
                    btnHtml += '<input type="radio" name="nextAction" id="appendData" value="append" /> Append All Entry';
                }
                else if(jsonData.dataStoreFlg == "overwrite")       
                {
                    btnHtml += '<input type="radio" name="nextAction" id="appendData" value="append" /> Append All Entry';
                    btnHtml += '<input type="radio" name="nextAction" id="overwrite" value="overwrite" /> OverWrite All Entry';
                }
                else
                {
                    btnHtml += '<input type="radio" name="nextAction" id="new" value="new" /> New All Entry';
                }  
                btnHtml += '<input type="radio" name="nextAction" id="cancelData" value="cancel" /> Cancel';

                dateInfoHtml += '<div class="col-md-12">'+btnHtml+'</div>';

                $('#bstDupInfo').html(dateInfoHtml);
                $('#bankStatementModal').modal('show');
           }
           else
           {
                alert(jsonData.msg);
           }

            
          },
          error : function(xhr,status,error)
          {
               
          }
    });
}

function submitData()
{
  if($("input[name='nextAction']:checked").val() !== undefined  && $('#bank_master_id').val() != '' && $("input[name='nextAction']:checked").val() != "cancel")
  {
      $.ajaxSetup({
          headers: {
               'X-CSRF-TOKEN': '{{ csrf_token() }}'
          }
      });
      startLoading();
      $.ajax({
              type: 'POST',
              url: "{{config('app.url') }}/bankstatement/submitbstdata", 
              data: { bstprocess : $("input[name='nextAction']:checked").val() , bank_master_id :$('#bank_master_id').val() },

              success:function( data ) 
              { 
                stopLoading();
                var jsonData = $.parseJSON(data);
                if(jsonData.code == "success")
               {
                  window.location.href = jsonData.path;
               }
               else
               {
                  alert(jsonData.msg);
               }
                
              },
              error : function(xhr,status,error)
              {
                   
              }
        });
  }
}
</script>
@stop
@stop
