@extends('layouts/layoutMaster')

@section('title', 'Dashboard')

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/animate-css/animate.scss', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss', 'resources/assets/vendor/libs/flatpickr/flatpickr.scss'])
@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/bloodhound/bloodhound.js', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.js', 'resources/assets/vendor/libs/flatpickr/flatpickr.js'])
@endsection

@section('page-script')
    @vite(['resources/assets/js/forms-selects.js', 'resources/assets/vendor/libs/spinkit/spinkit.scss', 'resources/assets/js/forms-pickers.js'])
@endsection

@section('content')
    @if (Illuminate\Support\Facades\Auth::user()->role == 'employe')
        @livewire('Dashboard.Employe')
    @endif
    @if (Illuminate\Support\Facades\Auth::user()->role == 'grh')
        @livewire('Dashboard.Grh')
    @endif
    @if (Illuminate\Support\Facades\Auth::user()->role == 'responsable')
        @livewire('Dashboard.Responsable')
    @endif
@endsection
