@extends('layout')

@section('content')
<div class="max-w-2xl mx-auto px-6 pt-28 pb-20 text-center">
    <h1 class="page-heading mb-6">Make Contact</h1>
    <p class="body-copy text-lg mb-10">
        Email works. I read it.
    </p>
    <div>
        <a href="mailto:{{ $site_settings['contact_email'] }}" class="btn-cta">
            {{ $site_settings['contact_email'] }}
        </a>
    </div>
    <div class="mt-12 flex justify-center">
        @include('partials.social-icons', ['class' => 'gap-5', 'iconClass' => 'w-6 h-6', 'iconSize' => 24])
    </div>
</div>
@endsection
