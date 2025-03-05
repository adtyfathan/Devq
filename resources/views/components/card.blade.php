@props(['category' => null, 'difficulty' => null, 'page' => 1])

<div>
    <h1>{{ $difficulty }} {{ $category }} Quiz {{ $page }}</h1>
    <a href="{{ route('show-quiz', ['category' => $category, 'difficulty' => $difficulty, 'page' => $page, 'perPage' => 10]) }}"
        class="btn btn-primary">
        Start Quiz
    </a>
</div>