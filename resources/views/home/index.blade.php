<x-layouts.app title="Home">
    <main>
        <h1>DevQ</h1>

        <div class="quiz-cards">
            <x-card category="Code" img="{{ asset('images/code.jpeg') }}" />
            
            <x-card category="VueJS" img="{{ asset('images/vue.png') }}" />
            
            <x-card category="NodeJS" img="{{ asset('images/node.png') }}" />
            
            <x-card category="Linux" img="{{ asset('images/linux.png') }}" />
            
            <x-card category="cPanel" img="{{ asset('images/cpanel.png') }}" />
            
            <x-card category="Django" img="{{ asset('images/django.png') }}" />
            
            {{-- easy 0 --}}
            <x-card category="Postgres" img="{{ asset('images/postgres.png') }}" />
            
            {{-- easy 15 --}}
            <x-card category="React" img="{{ asset('images/react.png') }}" />
            
            <x-card category="Next.js" img="{{ asset('images/next.png') }}" />
            
            <x-card category="DevOps" img="{{ asset('images/devops.png') }}" />
            
            <x-card category="SQL" img="{{ asset('images/sql.png') }}" />
            
            <x-card category="Apache Kafka" img="{{ asset('images/apache_kafka.png') }}" />
            
            <x-card category="Wordpress" img="{{ asset('images/wordpress.png') }}" />

            {{-- easy 5, hard 15 --}}
            <x-card category="Bash" img="{{ asset('images/bash.png') }}" />

            <x-card category="Docker" img="{{ asset('images/docker.png') }}" />

            {{-- easy 11 --}}
            <x-card category="Laravel" img="{{ asset('images/laravel.png') }}" />


        </div>

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
    @vite(['resources/css/home.js'])
@endpush