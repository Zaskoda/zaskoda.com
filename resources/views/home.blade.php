@extends('layout')

@section('content')
    @include('partials.home.hero')

    {{-- Surfaces alternate ink / slate down the page; gradient bands avoid hard cuts --}}
    @include('partials.home.identity')           {{-- Surface A (ink) --}}
    <div class="h-16 bg-gradient-to-b from-ink to-slate" aria-hidden="true"></div>
    @include('partials.home.pillars')            {{-- Surface B (slate) --}}
    <div class="h-16 bg-gradient-to-b from-slate to-ink" aria-hidden="true"></div>
    @include('partials.home.featured-work')      {{-- Surface A --}}
    <div class="h-16 bg-gradient-to-b from-ink to-slate" aria-hidden="true"></div>
    @include('partials.home.featured-projects')  {{-- Surface B --}}
    <div class="h-16 bg-gradient-to-b from-slate to-ink" aria-hidden="true"></div>
    @include('partials.home.blog-preview')       {{-- Surface A --}}
@endsection
