<x-layouts.app title="Home">
    <main>
        <h1>Home cik</h1>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </main>
</x-layouts.app>

@push('css')
    @vite(['resources/css/home.css'])
@endpush

@push('js')
    @vite(['resources/css/home.js'])
@endpush