<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no'>

    <title>Commentary Translations</title>

    <!-- Bootstrap Core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="/css/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="/css/sb-admin-2.css" rel="stylesheet">

    <!-- Datatables CSS -->
    <link href="/css/dataTables.bootstrap.css.css" rel="stylesheet">
    <link href="/css/dataTables.responsive.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- Custom CSS - For SCS -->
    <link href="/css/custom.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body class="position-relative">

<!-- Loading -->
@include('elements.loading')

<div id="wrapper">

    @include('elements.header')
    <div id="page-wrapper" style="margin: 0">
        @yield('content')
    </div>

</div>


<!-- jQuery -->
<script src="/js/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="/js/bootstrap.min.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="/js/metisMenu.min.js"></script>

<!-- DataTables JavaScript -->
<script src="/js/jquery.dataTables.min.js"></script>
<script src="/js/dataTables.bootstrap.min.js"></script>

<!-- Custom Theme JavaScript -->
<script src="/js/sb-admin-2.js"></script>
<script src="/js/custom.js"></script>

@yield('script')

</body>

</html>
