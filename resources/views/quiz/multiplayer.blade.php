<x-layouts.app title="Multiplayer Quiz">
    @vite(['resources/css/multiplayer.css'])

    <h1>Ini Multiplayer Quiz</h1>

    <h1 id="timer"></h1>

    <div id="opening-container" class="opening-container">
        
    </div>
    
    <div id="quiz-container" class="quiz-container">
        
    </div>

    <div id="meme-container" class="meme-container">

    </div>

    <div id="standings-container" class="standings-container">
        <table id="standings-table">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Name</th>
                    <th>Points</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>

    </div>

    @vite(['resources/js/multiplayer.js'])
</x-layouts.app>