<x-layouts.app title="Quiz">
    <h1 id="timer"></h1>

    <div id="quiz-container">

    </div>

    <button id="nextQuestionBtn" style="display: none;">Next</button>

</x-layouts.app>

@push('css')
    @vite(['resources/css/quiz.css'])
@endpush

@push('js')
    @vite(['resources/js/quiz.js'])
@endpush