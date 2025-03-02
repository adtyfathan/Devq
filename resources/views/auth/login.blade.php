<x-layouts.app title="login">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li> <!-- Show error messages -->
                @endforeach
            </ul>
        </div>
    @endif
    
    <h1>LOGIN</h1>
    
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <label>Email:</label>
        <input type="email" name="email" required>
    
        <label>Password:</label>
        <input type="password" name="password" required>
    
        <button type="submit">Login</button>
    </form>
    
    <a href="{{ route(name: 'register') }}">Register</a>
</x-layouts.app>
