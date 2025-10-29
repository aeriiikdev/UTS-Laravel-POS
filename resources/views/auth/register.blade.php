<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - POS System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .register-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .register-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 100%;
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-icon {
            font-size: 60px;
            color: #667eea;
            margin-bottom: 15px;
        }

        .register-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 5px;
        }

        .register-subtitle {
            color: #718096;
            font-size: 0.95rem;
        }

        .form-label {
            color: #4a5568;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-register {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            color: white;
            font-size: 1rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50px;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .back-button:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            transform: translateX(-5px);
        }

        .alert {
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #718096;
        }

        .login-link a {
            color: #667eea;
            font-weight: 600;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .password-strength {
            font-size: 0.85rem;
            margin-top: 5px;
        }

        .strength-weak { color: #e53e3e; }
        .strength-medium { color: #dd6b20; }
        .strength-strong { color: #38a169; }

        .input-group-text {
            background: transparent;
            border: 2px solid #e2e8f0;
            border-left: none;
            cursor: pointer;
        }

        .password-toggle {
            border-radius: 0 10px 10px 0 !important;
        }
    </style>
</head>
<body>
    <a href="{{ route('welcome') }}" class="back-button">
        <i class="fas fa-arrow-left"></i>
        Kembali
    </a>

    <div class="register-container">
        <div class="register-card">
            <div class="logo-section">
                <div class="logo-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h1 class="register-title">Buat Akun Baru</h1>
                <p class="register-subtitle">Daftar untuk mulai menggunakan POS System</p>
            </div>

            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                @if($errors->has('error'))
                    {{ $errors->first('error') }}
                @else
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <form method="POST" action="{{ route('register.submit') }}" id="registerForm">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" 
                           name="name" 
                           class="form-control @error('name') is-invalid @enderror" 
                           placeholder="Masukkan nama lengkap" 
                           value="{{ old('name') }}" 
                           required 
                           autofocus>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" 
                           name="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           placeholder="nama@email.com" 
                           value="{{ old('email') }}" 
                           required>
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" 
                               name="password" 
                               id="password"
                               class="form-control @error('password') is-invalid @enderror" 
                               placeholder="Minimal 8 karakter" 
                               required
                               onkeyup="checkPasswordStrength()">
                        <span class="input-group-text password-toggle" onclick="togglePassword('password')">
                            <i class="fas fa-eye" id="togglePasswordIcon"></i>
                        </span>
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div id="passwordStrength" class="password-strength"></div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Konfirmasi Password</label>
                    <div class="input-group">
                        <input type="password" 
                               name="password_confirmation" 
                               id="password_confirmation"
                               class="form-control" 
                               placeholder="Ketik ulang password" 
                               required>
                        <span class="input-group-text password-toggle" onclick="togglePassword('password_confirmation')">
                            <i class="fas fa-eye" id="togglePasswordConfirmIcon"></i>
                        </span>
                    </div>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" 
                           class="form-check-input" 
                           id="terms" 
                           required>
                    <label class="form-check-label" for="terms" style="color: #4a5568; font-weight: normal;">
                        Saya setuju dengan <a href="#" style="color: #667eea;">Syarat & Ketentuan</a>
                    </label>
                </div>

                <button type="submit" class="btn btn-register">
                    <i class="fas fa-user-plus me-2"></i>
                    Daftar Sekarang
                </button>
            </form>

            <div class="login-link">
                Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = fieldId === 'password' ? 
                document.getElementById('togglePasswordIcon') : 
                document.getElementById('togglePasswordConfirmIcon');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthDiv = document.getElementById('passwordStrength');
            
            if (password.length === 0) {
                strengthDiv.innerHTML = '';
                return;
            }
            
            let strength = 0;
            let message = '';
            let className = '';
            
            // Check length
            if (password.length >= 8) strength++;
            if (password.length >= 12) strength++;
            
            // Check for lowercase
            if (/[a-z]/.test(password)) strength++;
            
            // Check for uppercase
            if (/[A-Z]/.test(password)) strength++;
            
            // Check for numbers
            if (/[0-9]/.test(password)) strength++;
            
            // Check for special characters
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            if (strength <= 2) {
                message = '<i class="fas fa-times-circle"></i> Password lemah';
                className = 'strength-weak';
            } else if (strength <= 4) {
                message = '<i class="fas fa-exclamation-circle"></i> Password sedang';
                className = 'strength-medium';
            } else {
                message = '<i class="fas fa-check-circle"></i> Password kuat';
                className = 'strength-strong';
            }
            
            strengthDiv.innerHTML = message;
            strengthDiv.className = 'password-strength ' + className;
        }

        // Form validation
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password_confirmation').value;
            
            if (password !== passwordConfirm) {
                e.preventDefault();
                alert('Password dan konfirmasi password tidak sama!');
                return false;
            }
            
            if (password.length < 8) {
                e.preventDefault();
                alert('Password harus minimal 8 karakter!');
                return false;
            }
        });
    </script>
</body>
</html>