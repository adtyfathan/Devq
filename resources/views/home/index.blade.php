<x-layouts.app title="Home">
    <main>
        <h1>Home cik</h1>

        <x-card category="Code" difficulty="Easy" page="1" />


        {{-- <h1>Quiz mudah acak</h1>
        <a href="{{ route('quiz.show', ['category' => 'Code', 'difficulty' => 'Easy']) }}" class="btn btn-primary">
            Start Easy Quiz
        </a>
        
        <h1>Quiz sedang acak</h1>
        <a href="{{ route('quiz.show', ['category' => 'Code', 'difficulty' => 'Medium']) }}" class="btn btn-primary">
            Start Medium Quiz
        </a>

        <h1>Quiz sulit acak</h1>
        <a href="{{ route('quiz.show', ['category' => 'Code', 'difficulty' => 'Hard']) }}" class="btn btn-primary">
            Start Hard Quiz
        </a>

        <h1>Quiz Docker</h1>
        <a href="{{ route('quiz.show', ['category' => 'Docker']) }}" class="btn btn-secondary">
            Start Docker Quiz
        </a>

        <h1>Quiz Vue JS</h1>
        <a href="{{ route('quiz.show', ['category' => 'VueJS']) }}" class="btn btn-secondary">
            Start Vue JS Quiz
        </a>

        <h1>Quiz Node JS</h1>
        <a href="{{ route('quiz.show', ['category' => 'NodeJS']) }}" class="btn btn-secondary">
            Start Node JS Quiz
        </a> --}}


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
    @vite(['resources/css/home.js https://code.jquery.com/jquery-3.6.0.min.js'])
@endpush