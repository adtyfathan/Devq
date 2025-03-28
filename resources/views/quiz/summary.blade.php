<x-layouts.app title="Quiz">
    @vite(['resources/css/summary.css'])

    @if(isset($error))
        <div>{{ $error }}</div>
    @endif


    <h1>ini summary</h1>

    <div id="summary-wrapper">

    </div>
    
    @vite(['resources/js/summary.js'])
</x-layouts.app>