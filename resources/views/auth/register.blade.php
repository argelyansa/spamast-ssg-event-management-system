
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | SPAMAST SSG Event Management</title>

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
            color: #F8FAFC;
            padding: 20px;
        }

        .register-container {
            width: 100%;
            max-width: 460px;
            background: #111C32;
            padding: 40px;
            border-radius: 16px;
            border: 1px solid #1E293B;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
        }

        .brand {
            text-align: center;
            margin-bottom: 28px;
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
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #E2E8F0;
            font-size: 14px;
        }

        input, select {
            width: 100%;
            padding: 13px;
            border: 1px solid #334155;
            border-radius: 8px;
            background: #0B1120;
            color: white;
            outline: none;
        }

        input:focus, select:focus {
            border-color: #3B82F6;
        }

        .register-button {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 8px;
            background: #3B82F6;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 8px;
        }

        .register-button:hover {
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

        .footer {
            text-align: center;
            margin-top: 24px;
            color: #94A3B8;
            font-size: 14px;
        }

        .footer a {
            color: #60A5FA;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="register-container">

        <div class="brand">
            <h1>SPAMAST SSG</h1>
            <p>Create an Event Management Account</p>
        </div>

        @if ($errors->any())
            <div class="message error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ url('/register') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Full Name</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Enter full name"
                    required
                >
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Enter email address"
                    required
                >
            </div>

            <div class="form-group">
                <label for="role_id">Account Role</label>
                <select id="role_id" name="role_id" required>
                    <option value="">Select account role</option>

                    @foreach ($roles as $role)
                        <option
                            value="{{ $role->id }}"
                            {{ old('role_id') == $role->id ? 'selected' : '' }}
                        >
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="At least 8 characters"
                    required
                >
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    placeholder="Re-enter your password"
                    required
                >
            </div>

            <button type="submit" class="register-button">
                Create Account
            </button>
        </form>

        <div class="footer">
            Already have an account?
            <a href="{{ url('/login') }}">Login here</a>
        </div>

    </div>
</body>
</html>
