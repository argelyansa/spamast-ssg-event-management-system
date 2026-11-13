
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | SPAMAST SSG</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #0B1120;
            color: #F8FAFC;
            min-height: 100vh;
            padding: 30px 20px;
        }

        .container {
            max-width: 650px;
            margin: 40px auto;
            background: #111C32;
            padding: 35px;
            border-radius: 14px;
            border: 1px solid #1E293B;
        }

        h1 {
            color: #FFFFFF;
            margin-bottom: 10px;
        }

        .subtitle {
            color: #94A3B8;
            margin-bottom: 30px;
        }

        .photo-section {
            text-align: center;
            margin-bottom: 25px;
        }

        .profile-photo {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #3B82F6;
            background: #1E293B;
        }

        .photo-placeholder {
            width: 130px;
            height: 130px;
            margin: 0 auto;
            border-radius: 50%;
            background: #1E293B;
            border: 3px solid #3B82F6;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #93C5FD;
            font-size: 42px;
            font-weight: bold;
        }

        .photo-help {
            color: #94A3B8;
            font-size: 13px;
            margin-top: 12px;
        }

        label {
            display: block;
            margin: 20px 0 8px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 13px;
            border-radius: 7px;
            border: 1px solid #334155;
            background: #0B1120;
            color: #FFFFFF;
            font-size: 15px;
        }

        input[type="file"] {
            cursor: pointer;
        }

        button, .back-link {
            display: inline-block;
            margin-top: 25px;
            padding: 12px 20px;
            border: none;
            border-radius: 7px;
            background: #3B82F6;
            color: #FFFFFF;
            font-weight: bold;
            text-decoration: none;
            cursor: pointer;
        }

        button:hover, .back-link:hover {
            background: #2563EB;
        }

        .success {
            background: #14532D;
            color: #BBF7D0;
            padding: 12px;
            border-radius: 7px;
            margin-bottom: 20px;
        }

        .error {
            color: #FCA5A5;
            font-size: 14px;
            margin-top: 6px;
        }

        .error-box {
            background: #7F1D1D;
            color: #FECACA;
            padding: 12px;
            border-radius: 7px;
            margin-bottom: 20px;
        }

        .back-link {
            background: #334155;
            margin-left: 8px;
        }

        @media (max-width: 500px) {
            .container {
                padding: 24px 18px;
            }

            .back-link {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>My Profile</h1>
        <p class="subtitle">Manage your SPAMAST SSG account information.</p>

        @if (session('success'))
            <div class="success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="error-box">
                Please correct the errors below.
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="photo-section">
                @if ($user->photo)
                    <img
                        src="{{ asset('storage/' . $user->photo) }}"
                        alt="Profile Photo"
                        class="profile-photo"
                    >
                @else
                    <div class="photo-placeholder">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif

                <p class="photo-help">Your current profile photo</p>
            </div>

            <label for="photo">Upload Profile Photo</label>
            <input
                type="file"
                id="photo"
                name="photo"
                accept="image/jpeg,image/png,image/webp"
            >

            @error('photo')
                <p class="error">{{ $message }}</p>
            @enderror

            <p class="photo-help">
                Accepted formats: JPG, PNG, or WEBP. Maximum size: 2 MB.
            </p>

            <label for="name">Full Name</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name', $user->name) }}"
                required
            >

            @error('name')
                <p class="error">{{ $message }}</p>
            @enderror

            <label for="email">Email Address</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email', $user->email) }}"
                required
            >

            @error('email')
                <p class="error">{{ $message }}</p>
            @enderror

            <button type="submit">Save Changes</button>

            <a href="{{ route($user->dashboardRoute()) }}" class="back-link">
                Back to Dashboard
            </a>
        </form>
    </div>
</body>
</html>
