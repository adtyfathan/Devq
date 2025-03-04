<x-layouts.app title="Quiz">
    <h2>Quiz Questions</h2>
    
    @foreach ($questions as $question)
        <div class="question-box">
            <h3>{{ $question['question'] }}</h3>
            <ul>
                @foreach ($question['answers'] as $key => $answer)
                @if ($answer)
                <li>
                    <input type="radio" name="question_{{ $question['id'] }}" value="{{ $key }}">
                    {{ $answer }}
                </li>
                @endif
                @endforeach
            </ul>
        </div>
    @endforeach
</x-layouts.app>
    
@push('css')
    @vite(['resources/css/home.css'])
@endpush

@push('js')
    @vite(['resources/css/home.js'])
@endpush