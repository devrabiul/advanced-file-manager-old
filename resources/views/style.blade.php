@if (Str::lower(config('advanced-file-manager.theme')) == 'modern')
    @include('advanced-file-manager::modern.style')
@elseif (Str::lower(config('advanced-file-manager.theme')) == 'material')
    @include('advanced-file-manager::material.style')
@else
    @include('advanced-file-manager::classic.style')
@endif

<!-- Styles -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ url('vendor/advanced-file-manager/assets/libs/bootstrap-icons-1.11.3/bootstrap-icons.min.css') }}" async>
<link rel="stylesheet" href="{{ url('vendor/advanced-file-manager/assets/css/style.css') }}">
<link rel="stylesheet" href="{{ url('vendor/advanced-file-manager/assets/css/header.css') }}">
<link rel="stylesheet" href="{{ url('vendor/advanced-file-manager/assets/css/main.css') }}">
<link rel="stylesheet" href="{{ url('vendor/advanced-file-manager/assets/css/sidebar.css') }}">
<link rel="stylesheet" href="{{ url('vendor/advanced-file-manager/assets/css/files.css') }}">
<link rel="stylesheet" href="{{ url('vendor/advanced-file-manager/assets/css/modal.css') }}">
<link rel="stylesheet" href="{{ url('vendor/advanced-file-manager/assets/css/pagination.css') }}">
<link rel="stylesheet" href="{{ url('vendor/advanced-file-manager/assets/libs/filepond/filepond.css') }}">
