<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    @if (isset($page['props']['siteSettings']))
        <meta name="description" content="{{ $page['props']['siteSettings']['meta_description_for_home'] ?? '' }}">
        <meta name="keywords" content="{{ $page['props']['siteSettings']['meta_keywords_for_home'] ?? '' }}">
    @else
        <title>Home</title>
    @endif

    @vite('resources/js/app.js')
    <!-- favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('frontend/favicon_io/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('frontend/favicon_io/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('frontend/favicon_io/favicon-16x16.png')}}">
    <!-- All CSS links Here -->
    <link rel="stylesheet" href="{{asset('frontend/css/all.min.css')}}" />
    <link rel="stylesheet" href="{{asset('frontend/css/bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{asset('frontend/css/slick.css')}}" />
    <link rel="stylesheet" href="{{asset('frontend/css/slick-theme.css')}}" />
    <link rel="stylesheet" href="{{asset('frontend/css/venobox.min.css')}}" />
    <link rel="stylesheet" href="{{asset('frontend/css/magnific-popup.css')}}" />
    <link rel="stylesheet" href="{{asset('frontend/css/style.css')}}" />
    @inertiaHead
</head>
<body>
@inertia
<script src="{{asset('frontend/js/jquery-3.7.1.min.js')}}"></script>
<script src="{{asset('frontend/js/all.min.js')}}"></script>
<script src="{{asset('frontend/js/bootstrap.min.js')}}"></script>
<script src="{{asset('frontend/js/slick.min.js')}}"></script>
<script src="{{asset('frontend/js/venobox.min.js')}}"></script>
<script src="{{asset('frontend/js/jquery.magnific-popup.js')}}"></script>
<script src="{{asset('frontend/js/custom.js')}}"></script>
</body>
</html>
