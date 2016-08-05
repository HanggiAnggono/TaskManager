<!DOCTYPE html>
<html>
<head>
	<title>My Task Manager</title>
	<meta id="csrfToken" name="csrf-token" content="{{ csrf_token() }}" />
</head>
<body>
	@yield('body')
	<link rel="stylesheet" type="text/css" href="{{ asset('css/foundation/app.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/foundation/foundation.css') }}">

	<script type="text/javascript" src="{{asset('js/foundation/vendor/jquery.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/foundation/vendor/what-input.js')}}"></script>	
	<script type="text/javascript" src="{{asset('js/foundation/vendor/foundation.js')}}"></script>	
	<script type="text/javascript" src="{{asset('js/foundation/app.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/task.js')}}"></script>
</body>
</html>