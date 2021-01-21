<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MMReco</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">

    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/select2/select2.min.css') }}">

    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/colorpicker/bootstrap-colorpicker.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/js/plugins/datepicker/bootstrap-datepicker.css') }}">

    <!-- jvectormap -->
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/jvectormap/jquery-jvectormap-1.2.2.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/skins/skin-blue.css') }}">

    <!-- bootstrap tables CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-table.css') }}">

    <link rel="stylesheet" href="{{ elixir('assets/css/app.css') }}">
    <link rel="shortcut icon" type="image/ico" href="{{ asset('favicon.ico') }}">


  

    <style type="text/css">
    	.login-page
    	{
            background-image: url('{{config('app.url') }}/assets/img/marqbg.jpg');
            background-size: 100% 100%;
		    background-repeat: no-repeat;
		    background-attachment: fixed;
		    background-position: top;

    	}
    	.login-page .box 
    	{
    		border-top: 3px solid #D13C41;
    	}
    	.login-page .btn-primary
    	{
    		background-color: #D13C41;
    		border-color: #D13C41;
    	}
    	.login-page .btn-primary:hover {
		    background-color: #de3538;
		}
		.login-page a {
		    color: #D13C41;
		}
		.login-page a:hover {
		    color: #D34043;
		}
		.login-page .form-control:focus {
    		border-color: #D34043;
    	}
    	.login-page #forgot
    	{
    		padding-top: 10px;
    		font-size: 18px;
		    font-weight: 600;
		    text-decoration: underline;
    	}
    </style>
</head>

<body class="hold-transition login-page">


  <!-- Content -->
  @yield('content')



</body>
 <script src="{{ asset(elixir('assets/js/all.js')) }}"></script>
</html>
