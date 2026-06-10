@extends('layout')

@section('content')
    @include('partials.home.hero')
    @include('partials.home.identity')
    @include('partials.home.pillars')
    @include('partials.home.featured-work')
    @include('partials.home.featured-projects')
    @include('partials.home.blog-preview')
@endsection
