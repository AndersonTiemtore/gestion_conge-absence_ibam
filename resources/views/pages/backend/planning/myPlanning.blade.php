@extends('layouts/layoutMaster')

@section('title', 'Mon Planning - Pages')

@section('vendor-style')
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
@endsection

<!-- Page Scripts -->
@section('page-script')
@endsection

@section('content')

    @livewire('Planning.MyPlanning')
@endsection
