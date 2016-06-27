<!DOCTYPE html>
<html>
	<head>
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<title>URL Shortner</title>
		@yield('header')
	</head>
	<body>
		@yield('container')
	</body>
</html>