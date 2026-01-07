<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Multi Tenant App</title>
</head>
<body>
    <h1>Welcome, {{ $user['name'] }}</h1>
    <p>Your account has been created successfully.</p>
    <p>Here are your login credentials:</p>
    <p><strong>Email:</strong> {{ $user['email'] }}</p>
    <p><strong>Password:</strong> {{ $password }}</p>
    <p><strong>Role:</strong> {{ $user['role_name'] }}</p>
    <br>
    <p>Please login and change your password immediately.</p>
    <p><a href="{{ url('/') }}">Login Here</a></p>
</body>
</html>
