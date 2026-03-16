@extends('layouts.admin')

@section('title', 'Page Texts')

@section('content')
    <div class="space-y-4">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Page Texts</h1>
            <p class="text-sm text-slate-500">
                Αλλάζεις τίτλους / υπότιτλους / εισαγωγές για EN &amp; ΕΛ.
                Άφησε κενό για να χρησιμοποιηθεί το default από τα translations.
            </p>
        </div>

        @if(session('success'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.page-texts.store') }}" class="space-y-6">
            @csrf

            @foreach($pages as $pageKey => $pageLabel)
                <x-ui.card>
                    <h2 class="text-sm font-semibold text-primary mb-3">{{ $pageLabel }} <span class="text-xs text-slate-400">({{ $pageKey }})</span></h2>
                    <div class="grid gap-4 md:grid-cols-2">
                        @foreach($keys as $key => $label)
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">
                                    {{ $label }} – EN
                                </label>
                                @php
                                    $enKey = $pageKey.'.'.$key.'.en';
                                    $enVal = optional(optional($texts->get($enKey))->first())->value ?? '';
                                @endphp
                                <input
                                    type="text"
                                    name="texts[{{ $pageKey }}][{{ $key }}][en]"
                                    value="{{ old('texts.'.$pageKey.'.'.$key.'.en', $enVal) }}"
                                    class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-accent focus:ring-accent"
                                >
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">
                                    {{ $label }} – ΕΛ
                                </label>
                                @php
                                    $elKey = $pageKey.'.'.$key.'.el';
                                    $elVal = optional(optional($texts->get($elKey))->first())->value ?? '';
                                @endphp
                                <input
                                    type="text"
                                    name="texts[{{ $pageKey }}][{{ $key }}][el]"
                                    value="{{ old('texts.'.$pageKey.'.'.$key.'.el', $elVal) }}"
                                    class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-accent focus:ring-accent"
                                >
                            </div>
                        @endforeach
                    </div>
                </x-ui.card>
            @endforeach

            <x-ui.button type="submit" variant="primary">
                Save texts
            </x-ui.button>
        </form>
    </div>
@endsection

