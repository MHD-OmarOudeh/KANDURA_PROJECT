<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <title>Login - Kandura Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 50px;
            width: 100%;
            max-width: 450px;
        }
        .logo { text-align: center; margin-bottom: 40px; }
        .logo h1 {
            font-size: 2em;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .logo p { color: #718096; font-size: 0.95em; }
        .form-group { margin-bottom: 25px; }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #2d3748;
            font-weight: 600;
            font-size: 0.9em;
        }
        .form-control {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1em;
            transition: all 0.3s;
        }
        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 25px;
        }
        .btn-submit {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.05em;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 0.9em;
        }
        .alert-success {
            background: #c6f6d5;
            border: 1px solid #38a169;
            color: #22543d;
        }
        .alert-error {
            background: #fed7d7;
            border: 1px solid #e53e3e;
            color: #742a2a;
        }

        .language-switcher {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }

        .language-dropdown {
            position: relative;
        }

        .language-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            background: rgba(255, 255, 255, 0.95);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            color: #667eea;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .language-btn:hover {
            background: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .language-menu {
            position: absolute;
            top: 110%;
            right: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            min-width: 180px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .language-menu.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .language-menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            color: #2d3748;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .language-menu a:hover {
            background: #f7fafc;
        }

        .language-menu a.active {
            background: #eef2ff;
            color: #667eea;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <!-- Language Switcher -->
    <div class="language-switcher">
        <div class="language-dropdown">
            <button class="language-btn" onclick="toggleLanguage()">
                <span>🌐</span>
                <span>{{ strtoupper(app()->getLocale()) }}</span>
                <span>▼</span>
            </button>
            <div class="language-menu" id="languageMenu">
                <a href="{{ route('language.switch', 'en') }}" class="{{ app()->getLocale() == 'en' ? 'active' : '' }}">
                    <span>🇬🇧</span>
                    <span>English</span>
                </a>
                <a href="{{ route('language.switch', 'ar') }}" class="{{ app()->getLocale() == 'ar' ? 'active' : '' }}">
                    <span>🇸🇦</span>
                    <span>العربية</span>
                </a>
            </div>
        </div>
    </div>

    <div class="login-container">
        <div class="logo">
            <h1>{{ __('dashboard.kandura_store_management') }}</h1>
            <p>{{ __('dashboard.khaleeji_kandura_design') }}</p>
        </div>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Error Messages --}}
        @if ($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('dashboard.login.post') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">{{ __('dashboard.email') }}</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control"
                    placeholder="Enter your email"
                    value="{{ old('email') }}"
                    required
                >
            </div>

            <div class="form-group">
                <label for="password">{{ __('dashboard.password') }}</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control"
                    placeholder="Enter your password"
                    required
                >
            </div>

            <div class="checkbox-group">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember" style="margin: 0; font-weight: 400;">Remember me</label>
            </div>

            <button type="submit" class="btn-submit">{{ __('dashboard.login') }}</button>
        </form>
    </div>

    <script>
        function toggleLanguage() {
            const menu = document.getElementById('languageMenu');
            menu.classList.toggle('active');
        }

        document.addEventListener('click', function(event) {
            const dropdown = document.querySelector('.language-dropdown');
            const menu = document.getElementById('languageMenu');
            if (dropdown && !dropdown.contains(event.target)) {
                menu.classList.remove('active');
            }
        });
    </script>
</body>
</html>
