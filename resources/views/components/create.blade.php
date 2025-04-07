@props(['category' => null, 'img' => null])

<div class="quiz-card">
    <img src="{{ $img }}" />
    <form id="card-form" action="">
        <h1>{{ $category }} Create Quiz</h1>

        <p>Select difficulty</p>
        <input type="radio" id="easy" name="difficulty" value="easy">
        <label for="easy">Easy</label>
        <input type="radio" id="medium" name="difficulty" value="medium">
        <label for="medium">Medium</label>
        <input type="radio" id="hard" name="difficulty" value="hard">
        <label for="hard">Hard</label>

        <p>Total quiz</p>
        <input type="radio" id="5" name="limit" value="5">
        <label for="5">5</label>
        <input type="radio" id="10" name="limit" value="10">
        <label for="10">10</label>
        <input type="radio" id="15" name="limit" value="15">
        <label for="15">15</label>
        <input type="radio" id="20" name="limit" value="20">
        <label for="20">20</label>

        <input type="submit" value="Create">
    </form>
</div>