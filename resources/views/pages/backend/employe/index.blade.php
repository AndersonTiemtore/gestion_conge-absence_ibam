@extends('layouts/layoutMaster')

@section('title', 'Liste Employes ')

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/animate-css/animate.scss', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss', 'resources/assets/vendor/libs/flatpickr/flatpickr.scss'])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
    @vite(['resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/bloodhound/bloodhound.js', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.js', 'resources/assets/vendor/libs/flatpickr/flatpickr.js'])
@endsection

<!-- Page Scripts -->
@section('page-script')
    @vite(['resources/assets/js/forms-selects.js', 'resources/assets/vendor/libs/spinkit/spinkit.scss', 'resources/assets/js/forms-pickers.js'])
@endsection

@section('content')

    @livewire('Employe.Index')
    @livewire('Employe.Create')
    @livewire('Employe.Update')
    @livewire('Responsable.Create')
@endsection
