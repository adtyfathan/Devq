<x-layouts.app title="register">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li> 
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <label>Name:</label>
        <input type="text" name="name" value="{{ old('name') }}" required>
        @error('name')
            <div class="error">{{ $message }}</div>
        @enderror

        <label>Email:</label>
        <input type="email" name="email" value="{{ old('email') }}" required>
        @error('email')
            <div class="error">{{ $message }}</div>
        @enderror

        <label>Password:</label>
        <input type="password" name="password" required>
        @error('password')
            <div class="error">{{ $message }}</div>
        @enderror

        <label>Confirm Password:</label>
        <input type="password" name="password_confirmation" required>

        <button type="submit">Register</button>
    </form>

    <a href="{{ route('login') }}">Login</a>
</x-layouts.app>