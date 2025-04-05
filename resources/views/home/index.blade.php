<x-layouts.app title="Home">
    <main>
        @vite(['resources/css/home.css'])
        <h1>DevQ</h1>

        <form id="multiplayer-form">
            <p id="error-message"></p>
            
            {{-- max 6 digit --}}
            <label for="username">Username</label>
            <input type="text" name="username" id="lobby-username" />
            <label for="quiz-id">Lobby Id</label>
            <input type="number" name="quiz-id" id="lobby-input" />
            <input type="submit" value="Join" />
        </form>

        <a href="{{ route('quiz.create') }}">create quiz</a>

        <div class="quiz-cards">
            <x-card category="Code" img="{{ asset('images/code.jpeg') }}" route="quiz.show"/>
            
            <x-card category="VueJS" img="{{ asset('images/vue.png') }}" route="quiz.show" />
            
            <x-card category="NodeJS" img="{{ asset('images/node.png') }}" route="quiz.show" />
            
            <x-card category="Linux" img="{{ asset('images/linux.png') }}" route="quiz.show" />
            
            <x-card category="cPanel" img="{{ asset('images/cpanel.png') }}" route="quiz.show" />
            
            <x-card category="Django" img="{{ asset('images/django.png') }}" route="quiz.show" />
            
            {{-- easy 0 --}}
            <x-card category="Postgres" img="{{ asset('images/postgres.png') }}" route="quiz.show" />
            
            {{-- easy 15 --}}
            <x-card category="React" img="{{ asset('images/react.png') }}" route="quiz.show" />
            
            <x-card category="Next.js" img="{{ asset('images/next.png') }}" route="quiz.show" />
            
            <x-card category="DevOps" img="{{ asset('images/devops.png') }}" route="quiz.show" />
            
            <x-card category="SQL" img="{{ asset('images/sql.png') }}" route="quiz.show" />
            
            <x-card category="Apache Kafka" img="{{ asset('images/apache_kafka.png') }}" route="quiz.show" />
            
            <x-card category="Wordpress" img="{{ asset('images/wordpress.png') }}" route="quiz.show" />

            {{-- easy 5, hard 15 --}}
            <x-card category="Bash" img="{{ asset('images/bash.png') }}" route="quiz.show" />

            <x-card category="Docker" img="{{ asset('images/docker.png') }}" route="quiz.show" />

            {{-- easy 11 --}}
            <x-card category="Laravel" img="{{ asset('images/laravel.png') }}" route="quiz.show" />

        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>

        @vite(['resources/js/home.js'])
    </main>
</x-layouts.app>
