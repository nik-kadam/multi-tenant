@extends('layouts.app')

@section('title', 'Signup')
<link href="{{ asset('css/login.css') }}" rel="stylesheet"> 

@section('content')
    <div class="login-box">
        <h2>Signup</h2>

        <form id="signupForm">
            @csrf

            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password">
            </div>
            
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="password_confirmation">
            </div>

            <button id="signupBtn" type="submit">Register</button>
            <p style="margin-top: 15px; text-align: center;">
                Already have an account? <a href="{{ route('login') }}">Login</a>
            </p>
        </form>
    </div>
@endsection
