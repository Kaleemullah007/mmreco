
@extends('layouts/default')

{{-- Page title --}}
@section('title')
{{ trans('admin/users/general.view_user', array('name' => $user->fullName())) }}
@parent
@stop

{{-- Page content --}}
@section('content')

<div class="row">
    <div class="col-md-12">


      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#info_tab" data-toggle="tab"><span class="hidden-lg hidden-md"><i class="fa fa-info-circle"></i></span> <span class="hidden-xs hidden-sm">Info</span></a></li>
          
          
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="info_tab">
            <div class="row">
                @if ($user->deleted_at!='')
                    <div class="col-md-12">
                    <div class="callout callout-warning">
                        <i class="icon fa fa-warning"></i>
                        This user has been marked as deleted.
                        @can('users.edit')
                            <a href="{{ route('restore/user', $user->id) }}">Click here</a> to restore them.
                        @endcan
                      </div>
                  </div>
                @endif
              <div class="col-md-1">
              @if ($user->avatar)
                <img src="{{ URL::to('/') }}/uploads/avatars/{{ $user->avatar }}" class="avatar img-thumbnail hidden-print">
              @else
                <img src="{{ $user->gravatar() }}" class="avatar img-circle hidden-print">
              @endif
            </div>
            <div class="col-md-8">

                <div class="table table-responsive">
                  <table class="table table-striped">
                  

                    <tr>
                        <td>Name</td>
                        <td>{{ $user->fullName() }}</td>
                    </tr>
                    @if ($user->jobtitle)
                    <tr>
                        <td>Title</td>
                        <td>{{ $user->jobtitle }}</td>
                    </tr>
                    @endif

                    @if ($user->employee_num)
                    <tr>
                        <td>Employee No.</td>
                        <td>{{ $user->employee_num }}</td>
                    </tr>
                    @endif

                    @if ($user->manager)
                    <tr>
                        <td>Manager</td>
                        <td><a href="{{ route('view/user', $user->manager->id) }}">{{ $user->manager->fullName() }}</a></td>
                    </tr>
                    @endif

                    @if ($user->email)
                    <tr>
                        <td>Email</td>
                        <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                    </tr>
                    @endif

                    @if ($user->phone)
                    <tr>
                        <td>Phone</td>
                        <td>{{ $user->phone }}</td>
                    </tr>
                    @endif

                    @if ($user->userloc)
                    <tr>
                        <td>Location</td>
                        <td>{{ $user->userloc->name }}</td>
                    </tr>
                    @endif
                   
                  
                    @if (count($user_skill) != 0)
                    <tr>
                        <td>Trade</td>
                        <td>
                          @foreach($user_skill as $key=>$val)
                            {{$val['skill_name']}},
                          @endforeach
                        </td>
                    </tr>
                    @endif
                    @if ($user->created_at)
                        <tr>
                            <td>{{ trans('general.created_at') }}</td>
                            <td>
                                {{ date('d-M-Y H:i:s',strtotime($user->created_at)) }}
                            </td>
                        </tr>
                    @endif

                  </table>
                </div>
              </div>

              <!-- Start button column -->
              <div class="col-md-2">
                  @can('users.edit')
                  <div class="col-md-12">

                      <a href="{{ route('update/user', $user->id) }}" style="width: 100%;" class="btn btn-sm btn-default">{{ trans('admin/users/general.edit') }}</a>
                  </div>
                  


                  
                @endcan
              </div>
              <!-- End button column -->

            </div>



          </div><!-- /.tab-pane -->
          
      </div><!-- nav-tabs-custom -->

    </div>
  </div>

@section('moar_scripts')
<script>
$(function () {
    //binds to onchange event of your input field
    var uploadedFileSize = 0;
    $('#fileupload').bind('change', function() {
      uploadedFileSize = this.files[0].size;
      $('#progress-container').css('visibility', 'visible');
    });

    $('#fileupload').fileupload({
        //maxChunkSize: 100000,
        dataType: 'json',
        formData:{
        _token:'{{ csrf_token() }}',
        notes: $('#notes').val(),
        },

        progress: function (e, data) {
            //var overallProgress = $('#fileupload').fileupload('progress');
            //var activeUploads = $('#fileupload').fileupload('active');
            var progress = parseInt((data.loaded / uploadedFileSize) * 100, 10);
            $('.progress-bar').addClass('progress-bar-warning').css('width',progress + '%');
            $('#progress-bar-text').html(progress + '%');
            //console.dir(overallProgress);
        },

        done: function (e, data) {
            console.dir(data);

            // We use this instead of the fail option, since our API
            // returns a 200 OK status which always shows as "success"

            if (data && data.jqXHR.responseJSON.error && data.jqXHR.responseJSON && data.jqXHR.responseJSON.error) {
                $('#progress-bar-text').html(data.jqXHR.responseJSON.error);
                $('.progress-bar').removeClass('progress-bar-warning').addClass('progress-bar-danger').css('width','100%');
                $('.progress-checkmark').fadeIn('fast').html('<i class="fa fa-times fa-3x icon-white" style="color: #d9534f"></i>');
                console.log(data.jqXHR.responseJSON.error);
            } else {
                $('.progress-bar').removeClass('progress-bar-warning').addClass('progress-bar-success').css('width','100%');
                $('.progress-checkmark').fadeIn('fast');
                $('#progress-container').delay(950).css('visibility', 'visible');
                $('.progress-bar-text').html('Finished!');
                $('.progress-checkmark').fadeIn('fast').html('<i class="fa fa-check fa-3x icon-white" style="color: green"></i>');
                $.each(data.result.file, function (index, file) {
                    $('<tr><td>' + file.notes + '</td><<td>' + file.name + '</td><td>Just now</td><td>' + file.filesize + '</td><td><a class="btn btn-info btn-sm" href="import/process/' + file.name + '"><i class="fa fa-spinner process"></i> Process</a></td></tr>').prependTo("#upload-table > tbody");
                    //$('<tr><td>').text(file.name).appendTo(document.body);
                });
            }
            $('#progress').removeClass('active');


        }
    });
});
</script>

@stop

@stop
