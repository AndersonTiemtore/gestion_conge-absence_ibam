@extends('layouts/layoutMaster')

@section('title', 'Fullcalendar - Apps')

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/fullcalendar/fullcalendar.scss', 'resources/assets/vendor/libs/flatpickr/flatpickr.scss', 'resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/quill/editor.scss', 'resources/assets/vendor/libs/@form-validation/form-validation.scss', 'resources/assets/vendor/libs/bootstrap-select/bootstrap-select.scss'])
@endsection

@section('page-style')
    @vite(['resources/assets/vendor/scss/pages/app-calendar.scss'])
@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/fullcalendar/fullcalendar.js', 'resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/auto-focus.js', 'resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/flatpickr/flatpickr.js', 'resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/bootstrap-select/bootstrap-select.js'])
@endsection

@if (Illuminate\Support\Facades\Auth::user()->role == 'coordinateur')
    @section('page-script')
        @vite(['resources/assets/js/app-calendar-events.js', 'resources/assets/js/app-calendar.js'])
    @endsection
@else
    @section('page-script')
        @vite(['resources/assets/js/app-calendar-other.js'])
    @endsection
@endif


@section('content')
    @livewire('Planning.Index')
@endsection
