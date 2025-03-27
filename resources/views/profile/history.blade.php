<x-layouts.app title="Profile">
    <main>
        @vite(['resources/css/history.css'])

        <h1>Quiz History</h1>

        <div id="history-wrapper">
            
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
        @vite(['resources/js/history.js'])
    </main>
</x-layouts.app>
