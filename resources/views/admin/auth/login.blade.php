<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Chauhan Agro Industries CMS</title>
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
    <style>
        body.login-screen {
            background: linear-gradient(135deg, #0f172a 0%, #064e3b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-card {
            width: 100%;
            max-width: 440px;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
            overflow: hidden;
        }
        .login-header {
            background: #0f172a;
            color: #ffffff;
            padding: 35px 30px;
            text-align: center;
        }
        .login-body {
            padding: 35px 30px;
        }
    </style>
</head>
<body class="login-screen">

    <div class="login-card">
        <div class="login-header">
            <div class="d-inline-flex align-items-center justify-content-center bg-success text-white rounded-circle p-3 mb-3" style="width: 56px; height: 56px;">
                <i class="bi bi-shield-lock-fill fs-3 text-white"></i>
            </div>
            <h3 class="fw-bold mb-1 text-white">Chauhan Agro Industries</h3>
            <p class="text-secondary small mb-0">chauhanagroindustries.com &bull; Control Center</p>
        </div>

        <div class="login-body">
            @if(session('info'))
                <div class="alert alert-info py-2 small mb-4">{{ session('info') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger py-2 small mb-4">
                    @foreach($errors->all() as $err)
                        <div>{{ $err }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('admin.login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-muted">Administrative Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                        <input type="email" name="email" value="{{ old('email', 'admin@agrovision.com') }}" class="form-control border-start-0" required autofocus placeholder="admin@agrovision.com">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small text-muted">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-key text-muted"></i></span>
                        <input type="password" name="password" value="password123" class="form-control border-start-0" required placeholder="••••••••">
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="rememberMe" checked>
                        <label class="form-check-label small text-muted" for="rememberMe">Remember me</label>
                    </div>
                    <span class="small text-muted">Protected System</span>
                </div>

                <button type="submit" class="btn btn-agro-primary w-100 py-2 fw-bold">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Sign In to Admin Console
                </button>
            </form>

            <div class="mt-4 pt-3 border-top text-center">
                <small class="text-muted d-block">Demo Credentials:</small>
                <code class="small text-dark">admin@agrovision.com / password123</code>
            </div>
        </div>
    </div>

</body>
</html>
