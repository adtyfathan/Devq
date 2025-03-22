<x-layouts.app title="Quiz">
    @vite(['resources/css/quiz.css'])

    <h1 id="timer"></h1>

    <div id="quiz-container">

    </div>

    <button id="nextQuestionBtn" style="display: none;">Next</button>
    
    @vite(['resources/js/quiz.js'])
</x-layouts.app>
