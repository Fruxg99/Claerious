<?php
    // Check if session has started
    if (strlen(session_id()) < 1) {
        session_start();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ url('ogani/js/jquery-3.3.1.min.js') }}"></script>

    <title>@yield('title')</title>

    @include('Template.Store.head_content')

</head>

<body id="page-top">

    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    @include('Template.Store.navbar')

    @yield('content')

    @include('Template.Store.footer')

    @include('Template.Store.script')

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
    </script>

</body>
</html>