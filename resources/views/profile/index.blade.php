<x-layouts.app title="Profile">
    <main>
        @vite(['resources/css/profile.css'])

        <h1 id="title">Halo</h1>

        <form action="POST">
            <label for="name">Name</label>
            <input id="name" name="name" placeholder="Name" value="" type="text">   
            
            <label for="email">Email</label>
            <input id="email" name="email" placeholder="Email" value="" type="email">
            
            {{-- disable di backend --}}
            <label for="point">Point</label>
            <input id="point" name="point" placeholder="Point" value="" type="number" disabled>
    
            <input type="submit">
        </form>

        <a href="{{ route('quiz.history') }}">Quiz History</a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
        @vite(['resources/js/profile.js'])
    </main>
</x-layouts.app>