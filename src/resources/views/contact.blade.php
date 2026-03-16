@extends('layouts.app')

@section('title', __('messages.contact.meta_title'))
@section('meta_description', __('messages.contact.meta_description'))

@section('content')
    <section class="max-w-2xl mx-auto min-w-0">
        <div class="space-y-6 text-center">
            <div>
                <h1 class="text-2xl sm:text-3xl font-semibold text-primary">
                    {{ __('messages.cms.contact_title') }}
                </h1>
                <p class="mt-2 text-sm text-slate-600 max-w-xl mx-auto">
                    {{ __('messages.cms.contact_subtitle') }}
                </p>
                <p class="mt-3 text-sm text-slate-600 max-w-xl mx-auto">
                    {{ __('messages.cms.contact_intro') }}
                </p>
            </div>

            @if(optional($settings)->contact_email || optional($settings)->contact_phone)
                <x-ui.card class="text-left">
                    <h2 class="text-sm font-semibold text-primary mb-3">
                        {{ __('messages.contact.sidebar_contact_title') }}
                    </h2>
                    <div class="space-y-2 text-sm text-slate-600">
                        @if(optional($settings)->contact_email)
                            <p><a href="mailto:{{ $settings->contact_email }}" class="text-accent hover:text-accent-hover font-medium">{{ $settings->contact_email }}</a></p>
                        @endif
                        @if(optional($settings)->contact_phone)
                            <p><a href="tel:{{ preg_replace('/\s+/', '', $settings->contact_phone) }}" class="text-accent hover:text-accent-hover font-medium">{{ $settings->contact_phone }}</a></p>
                        @endif
                    </div>
                </x-ui.card>
            @endif
        </div>

        <x-ui.card class="mt-8">
            <form class="space-y-4">
                <div class="grid gap-4 sm:grid-cols-2">
                    <x-ui.input label="{{ __('messages.contact.fields.name') }}" name="name" placeholder="Jane Doe" />
                    <x-ui.input label="{{ __('messages.contact.fields.email') }}" name="email" type="email" placeholder="you@example.com" />
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">{{ __('messages.contact.fields.topic') }}</label>
                        <select class="block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-accent focus:ring-accent">
                            <option>{{ __('messages.contact.topics.general') }}</option>
                            <option>{{ __('messages.contact.topics.tours') }}</option>
                            <option>{{ __('messages.contact.topics.business') }}</option>
                            <option>{{ __('messages.contact.topics.media') }}</option>
                        </select>
                    </div>
                    <x-ui.input label="{{ __('messages.contact.fields.phone') }}" name="phone" placeholder="+30 ..." />
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">{{ __('messages.contact.fields.message') }}</label>
                    <textarea rows="5" class="block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-accent focus:ring-accent" placeholder="{{ __('messages.contact.fields.message_placeholder') }}"></textarea>
                </div>
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between text-xs text-slate-500">
                    <p class="max-w-lg">{{ __('messages.contact.consent_text') }}</p>
                    <x-ui.button type="button">{{ __('messages.buttons.send_message') }}</x-ui.button>
                </div>
            </form>
        </x-ui.card>
    </section>
@endsection
