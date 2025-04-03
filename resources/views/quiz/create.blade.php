<x-layouts.app title="Create Quiz">
    @vite(['resources/css/quiz-create.css'])
        <h1>Create a quiz</h1>

        <h3>Multiplayer</h3>
        <p>Use existing quiz</p>
        
    <div class="quiz-cards">
        <x-card category="Code" img="{{ asset('images/code.jpeg') }}" route="quiz.host" />
    
        <x-card category="VueJS" img="{{ asset('images/vue.png') }}" route="quiz.host" />
    
        <x-card category="NodeJS" img="{{ asset('images/node.png') }}" route="quiz.host" />
    
        <x-card category="Linux" img="{{ asset('images/linux.png') }}" route="quiz.host" />
    
        <x-card category="cPanel" img="{{ asset('images/cpanel.png') }}" route="quiz.host" />
    
        <x-card category="Django" img="{{ asset('images/django.png') }}" route="quiz.host" />
    
        {{-- easy 0 --}}
        <x-card category="Postgres" img="{{ asset('images/postgres.png') }}" route="quiz.host" />
    
        {{-- easy 15 --}}
        <x-card category="React" img="{{ asset('images/react.png') }}" route="quiz.host" />
    
        <x-card category="Next.js" img="{{ asset('images/next.png') }}" route="quiz.host" />
    
        <x-card category="DevOps" img="{{ asset('images/devops.png') }}" route="quiz.host" />
    
        <x-card category="SQL" img="{{ asset('images/sql.png') }}" route="quiz.host" />
    
        <x-card category="Apache Kafka" img="{{ asset('images/apache_kafka.png') }}" route="quiz.host" />
    
        <x-card category="Wordpress" img="{{ asset('images/wordpress.png') }}" route="quiz.host" />
    
        {{-- easy 5, hard 15 --}}
        <x-card category="Bash" img="{{ asset('images/bash.png') }}" route="quiz.host" />
    
        <x-card category="Docker" img="{{ asset('images/docker.png') }}" route="quiz.host" />
    
        {{-- easy 11 --}}
        <x-card category="Laravel" img="{{ asset('images/laravel.png') }}" route="quiz.host" />
    
    </div>
    
        <h3>Singleplayer</h3>
    @vite(['resources/js/quiz-create.js'])
</x-layouts.app>