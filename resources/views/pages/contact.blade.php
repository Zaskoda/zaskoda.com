@extends('layout')

@section('content')
{{-- Page header — Surface A --}}
<div class="bg-ink">
    <div class="max-w-2xl mx-auto px-6 pt-28 pb-6 text-center">
        <h1 class="page-heading mb-6">Make Contact</h1>
        <p class="body-copy text-lg">
            Email works. I read it.
        </p>
    </div>
</div>

@include('partials.surface-fade')

{{-- Page content — Surface B --}}
<div class="bg-slate">
    <div class="max-w-2xl mx-auto px-6 pt-6 pb-20 text-center">
        <div>
            <a href="mailto:{{ $site_settings['contact_email'] }}" class="btn-cta">
                {{ $site_settings['contact_email'] }}
            </a>
        </div>
        <div class="mt-12 flex justify-center">
            @include('partials.social-icons', ['class' => 'gap-5', 'iconClass' => 'w-6 h-6', 'iconSize' => 24])
        </div>
    </div>
</div>
@endsection
