<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}" />

    <!-- IonIcons (External CDN - Tidak perlu asset) -->
    <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" />

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}" />

    <!-- Google Font: Source Sans Pro (External CDN - Tidak perlu asset) -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet" />
    <title>{{ $title ?? 'Dashboard' }}</title>

    {{-- Tempat CSS tambahan disisipkan oleh view anak --}}
    @stack('css')
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <x-navbar :title="$title ?? 'Dashboard'" :activeMenu="$activeMenu ?? ''" />
        <x-side-bar :activeMenu="$activeMenu ?? ''" />

        <main class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">{{ $title ?? 'Dashboard' }}</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item active">{{ $title ?? 'Dashboard' }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Slot Konten Utama --}}
            {{ $slot }}
        </main>

        <x-footer />
    </div>

    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('dist/js/demo.js') }}"></script>
    <script src="{{ asset('dist/js/pages/dashboard3.js') }}"></script>
    @stack('scripts')
</body>

</html>