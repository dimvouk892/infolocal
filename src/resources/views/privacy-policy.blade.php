@extends('layouts.app')

@section('title', __('messages.footer.privacy') . ' – ' . (optional($settings)->site_title ?? __('messages.brand.name')))
@section('meta_description', __('messages.footer.privacy'))

@section('content')
    <section class="max-w-4xl">
        <h1 class="text-2xl sm:text-3xl font-semibold text-primary mb-6">
            {{ __('messages.footer.privacy') }}
        </h1>
        @if(!empty($content))
            <div class="prose prose-slate max-w-none prose-p:text-slate-600 prose-a:text-accent prose-a:no-underline hover:prose-a:underline prose-ul:my-3 prose-li:my-1">
                {!! $content !!}
            </div>
        @else
            <p class="text-slate-600">{{ __('messages.legal.empty') }}</p>
        @endif
    </section>
@endsection
