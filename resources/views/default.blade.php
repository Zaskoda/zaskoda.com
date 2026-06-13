@extends('layout')

@section('content')
    {{-- Page header — Surface A --}}
    <div class="bg-ink">
        <div class="max-w-3xl mx-auto px-6 pt-28 pb-12">
            <h1 class="font-display font-bold text-5xl text-cowboy-100">{{ $title }}</h1>
        </div>
    </div>

    @include('partials.surface-fade')

    {{-- Page content — Surface B --}}
    <div class="bg-slate">
        <div class="max-w-3xl mx-auto px-6 pt-12 pb-20">
            <div class="prose prose-invert font-body">
                {!! $content !!}
            </div>
        </div>
    </div>
@endsection
