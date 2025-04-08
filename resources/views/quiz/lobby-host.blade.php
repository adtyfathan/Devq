<x-layouts.app title="Host Lobby">
    @vite(['resources/css/host.css'])

    <h1>Host View</h1>

    <h3 id="code-text"></h3>

    <div id="players-wrapper">
        <h1>Player lists</h1>
    </div>
    
    @vite(['resources/js/host.js'])
</x-layouts.app>