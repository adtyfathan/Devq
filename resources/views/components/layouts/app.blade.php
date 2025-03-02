@props(['title' => ''])

<x-layouts.base :$title>
    <x-navbar />
    {{ $slot }}
    <x-footer />
</x-layouts.base>