
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SPAMAST SSG Event Management</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #0B1120;
            padding: 20px;
            color: #F8FAFC;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            background: #111C32;
            padding: 40px;
            border-radius: 16px;
            border: 1px solid #1E293B;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
        }

        .brand {
            text-align: center;
            margin-bottom: 30px;
        }

        .brand h1 {
            color: #3B82F6;
            font-size: 26px;
            margin-bottom: 8px;
        }

        .brand p {
            color: #94A3B8;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #E2E8F0;
            font-size: 14px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 13px;
            border: 1px solid #334155;
            border-radius: 8px;
            background: #0B1120;
            color: white;
            outline: none;
        }

        input:focus {
            border-color: #3B82F6;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 22px;
            color: #CBD5E1;
            font-size: 14px;
        }

        .remember input {
            accent-color: #3B82F6;
        }

        .login-button {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 8px;
            background: #3B82F6;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        .login-button:hover {
            background: #2563EB;
        }

        .message {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 18px;
            font-size: 14px;
        }

        .error {
            background: #7F1D1D;
            color: #FEE2E2;
        }

        .success {
            background: #14532D;
            color: #DCFCE7;
        }

        .footer {
            text-align: center;
            margin-top: 25px;
            color: #64748B;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="login-container">

        <div class="brand">
            <h1>SPAMAST SSG</h1>
            <p>Event Management System</p>
        </div>

        @if ($errors->any())
            <div class="message error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @if (session('success'))
            <div class="message success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ url('/login') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="email">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Enter your email"
                    required
                    autofocus
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter your password"
                    required
                >
            </div>

            <label class="remember">
                <input
                    type="checkbox"
                    name="remember"
                    value="1"
                >
                Remember Me
            </label>

            <button type="submit" class="login-button">
                Login
            </button>
        </form>

        <div class="footer">
            SPAMAST Supreme Student Government
        </div>

    </div>
</body>
</html>
