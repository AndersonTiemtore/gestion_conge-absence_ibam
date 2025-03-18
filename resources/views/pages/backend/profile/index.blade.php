@extends('layouts/layoutMaster')

@section('title', 'Profile')

@section('vendor-style')
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
@endsection

<!-- Page Scripts -->
@section('page-script')
    @vite(['resources/assets/vendor/libs/spinkit/spinkit.scss'])
@endsection

@section('content')
    @livewire('Profile.Index')
@endsection
