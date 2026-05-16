<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    {{-- @include('components.head') --}}
    <title>{{ $title ?? 'Dashboard' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="hold-transition sidebar-mini">
    <x-side-bar :activeMenu="$activeMenu ?? ''" />
    <x-navbar :title="$title ?? 'Dashboard'" :activeMenu="$activeMenu ?? ''" />
    <main class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">{{ $title ?? 'Dashboard' }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">{{ $title ?? 'Dashboard' }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        {{ $slot }}
    </main>
    <x-footer />
    {{-- @include('components.script') --}}
</body>

</html>
