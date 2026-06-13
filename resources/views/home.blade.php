@extends('layout')

@section('content')
    @include('partials.home.hero')

    {{-- Surfaces alternate slate / ink down the page with clean hard edges --}}
    @include('partials.home.identity')           {{-- Surface A (slate) --}}
    @include('partials.home.pillars')            {{-- Surface B (ink) --}}
    @include('partials.home.featured-work')      {{-- Surface A --}}
    @include('partials.home.featured-projects')  {{-- Surface B --}}
    @include('partials.home.blog-preview')       {{-- Surface A --}}
@endsection
