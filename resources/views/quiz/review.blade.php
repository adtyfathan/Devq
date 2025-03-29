<x-layouts.app title="Review">
    @vite(['resources/css/review.css'])

    @if(isset($error))
        <div>{{ $error }}</div>
    @endif

    <h1>Review</h1>

    <div id="review-wrapper">

    </div>

    @vite(['resources/js/review.js'])
</x-layouts.app>