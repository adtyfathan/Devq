<x-layouts.app title="Player Lobby">
    @vite(['resources/css/player.css'])

    <h1>Player View</h1>

    <button id="leave-button">Leave lobby</button>

    <div id="players-wrapper">
        <p id="error-text"></p>
        <h1>Player lists</h1>
    </div>

    @vite(['resources/js/player.js'])
</x-layouts.app>