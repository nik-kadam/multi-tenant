@extends('layouts.app')

@section('title', 'Login')
<link href="{{ asset('css/login.css') }}" rel="stylesheet"> 

@section('content')
    <div class="login-box">
        <h2>Login</h2>

        @if(session('error'))
            <p class="error">{{ session('error') }}</p>
        @endif

        <form id="loginForm">
            @csrf

            <div class="form-group">
                <label>Email</label>
                <input type="text" name="email">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password">
            </div>

            <button id="loginBtn" type="submit">Login</button>
            <p style="margin-top: 15px; text-align: center;">
                Don't have an account? <a href="{{ route('signup') }}">Sign up</a>
            </p>
        </form>
    </div>
@endsection