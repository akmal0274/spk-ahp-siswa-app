@extends('layouts.authLayout')

@section('content')
    @if ($errors->any())
        <div class="error">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <label>Email</label><br>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>

        <div class="form-group">
            <label>Password</label><br>
            <input type="password" name="password" required>
        </div>

        <div class="form-group">
            <label><input type="checkbox" name="remember"> Remember me</label>
        </div>

        <button type="submit">Login</button>
    </form>
@endsection