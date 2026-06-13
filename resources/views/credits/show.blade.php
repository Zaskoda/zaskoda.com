@extends('layout')

@section('content')
{{-- Page header — Surface A --}}
<div class="bg-ink">
    <div class="max-w-3xl mx-auto px-6 pt-28 pb-12">
        <h1 class="page-heading">{{ $page->title }}</h1>
    </div>
</div>

@include('partials.surface-fade')

{{-- Page content — Surface B --}}
<div class="bg-slate">
    <div class="max-w-3xl mx-auto px-6 pt-12 pb-20">
        <p class="body-copy text-cowboy-300 mb-8">Detail page coming soon.</p>
        <a href="/recognition#credits" class="font-ui text-sm">&larr; Recognition</a>
    </div>
</div>
@endsection
