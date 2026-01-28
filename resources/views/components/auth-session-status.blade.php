@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'p-4 bg-neutral-800 border border-neutral-700 rounded-lg']) }}>
        <p class="text-sm text-neutral-300">{{ $status }}</p>
    </div>
@endif
