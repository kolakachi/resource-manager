<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    {{-- <meta name="_uuid" content="{{ auth()->user()->uuid }}" /> --}}
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Resource Manager</title>

    <!-- Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Muli|Raleway&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito&amp;display=swap" rel="stylesheet">
    @yield('styles')
     <link href="{{ asset('css/app.css') }}?v=2" rel="stylesheet">
    <!-- Styles -->
    <style>
        .el-notification__content {
        text-justify: left !important;
        }

        body {
            font-family: Nunito, sans-serif;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
          <a class="navbar-brand" href="#">Resource Manager</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link {{ ($page == "home")? 'active': ''}}" aria-current="page" href="{{ route('visitor.index') }}">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ ($page == "admin")? 'active': ''}}" href="{{ route('admin.index') }}">Admin</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>

    <div id="app">
        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    @yield('scripts')  

    <script src="{{ asset('js/app.js') }}?v=2"></script>
</body>
</html>
