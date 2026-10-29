<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Chauhan Agro Industries - Website Under Construction' }}</title>
    <meta name="description" content="Chauhan Agro Industries - India's trusted manufacturer of heavy-duty agricultural implements, tractor trolleys, and rotavators.">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Google Fonts & Bootstrap Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/bootstrap.min.css') }}">

    <style>
        :root {
            --agro-primary: #15803d;
            --agro-secondary: #ea580c;
            --agro-dark: #0f172a;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #064e3b 0%, #0f172a 60%, #022c22 100%);
            min-height: 100vh;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2.5rem 1rem;
            position: relative;
            overflow-x: hidden;
        }
        body::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 20% 20%, rgba(234, 88, 12, 0.15) 0%, transparent 40%),
                        radial-gradient(circle at 80% 80%, rgba(22, 163, 74, 0.15) 0%, transparent 40%);
            pointer-events: none;
        }
        .uc-container {
            position: relative;
            z-index: 2;
            max-width: 920px;
            width: 100%;
        }
        .uc-card {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 24px;
            padding: 3rem 2.5rem;
            box-shadow: 0 30px 70px rgba(0, 0, 0, 0.55);
        }
        .uc-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(234, 88, 12, 0.2);
            border: 1px solid rgba(234, 88, 12, 0.5);
            color: #fb923c;
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            padding: 6px 16px;
            border-radius: 30px;
            margin-bottom: 1.5rem;
        }
        .uc-title {
            font-size: 2.75rem;
            font-weight: 900;
            line-height: 1.2;
            margin-bottom: 1.25rem;
            color: #ffffff;
        }
        .uc-lead {
            font-size: 1.15rem;
            line-height: 1.7;
            color: #cbd5e1;
            max-width: 720px;
            margin: 0 auto 2.5rem auto;
        }
        .contact-box {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 1.25rem;
            text-align: center;
            transition: transform 0.2s ease, background 0.2s ease;
            height: 100%;
            text-decoration: none;
            color: #ffffff;
            display: block;
        }
        .contact-box:hover {
            transform: translateY(-4px);
            background: rgba(255, 255, 255, 0.1);
            color: #fb923c;
        }
        .contact-icon {
            font-size: 1.75rem;
            margin-bottom: 8px;
            color: #fb923c;
        }
        .client-access-box {
            background: rgba(0, 0, 0, 0.4);
            border: 1px dashed rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            padding: 1.5rem;
            margin-top: 2.5rem;
        }
        @media (max-width: 768px) {
            .uc-card {
                padding: 2rem 1.5rem;
            }
            .uc-title {
                font-size: 1.85rem;
            }
            .uc-lead {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="uc-container text-center">
        <!-- Logo -->
        <div class="mb-4">
            <img src="{{ asset('assets/images/branding/logo.svg') }}" alt="{{ $siteName }}" height="56" class="img-fluid" onerror="this.outerHTML='<h2 class=\'text-warning fw-bold\'>CHAUHAN AGRO</h2>'">
        </div>

        <div class="uc-card">
            <div class="uc-badge">
                <i class="bi bi-tools"></i>
                <span>Platform Upgrade & Launch in Progress</span>
            </div>

            <h1 class="uc-title">{{ $title ?? 'We Are Upgrading Our Digital Experience' }}</h1>
            <p class="uc-lead">{{ $message }}</p>

            @if(session('error'))
                <div class="alert alert-danger bg-danger bg-opacity-25 border-danger text-white py-2 px-3 rounded-3 mb-4 mx-auto" style="max-width: 500px;">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('error') }}
                </div>
            @endif

            <!-- Direct Contact Grid for Farmers & Dealers -->
            <div class="row g-3 mb-4 text-start">
                <div class="col-sm-6 col-lg-3">
                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}" class="contact-box">
                        <div class="contact-icon"><i class="bi bi-telephone-outbound-fill"></i></div>
                        <div class="small text-muted">Toll-Free Support</div>
                        <div class="fw-bold text-truncate">{{ $phone }}</div>
                    </a>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsapp) }}" target="_blank" rel="noopener" class="contact-box">
                        <div class="contact-icon text-success"><i class="bi bi-whatsapp"></i></div>
                        <div class="small text-muted">WhatsApp Chat</div>
                        <div class="fw-bold">Chat with Sales</div>
                    </a>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <a href="mailto:{{ $email }}" class="contact-box">
                        <div class="contact-icon"><i class="bi bi-envelope-check-fill"></i></div>
                        <div class="small text-muted">Inquiry Desk</div>
                        <div class="fw-bold text-truncate">{{ $email }}</div>
                    </a>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="contact-box">
                        <div class="contact-icon text-info"><i class="bi bi-geo-alt-fill"></i></div>
                        <div class="small text-muted">Factory Location</div>
                        <div class="fw-bold text-truncate">Karnal, Haryana</div>
                    </div>
                </div>
            </div>

            <!-- Client & Staff Preview Unlock Box -->
            <div class="client-access-box text-center">
                <div class="d-flex align-items-center justify-content-center gap-2 mb-2 text-warning fw-bold small text-uppercase">
                    <i class="bi bi-shield-lock-fill"></i> Client & Team Preview Access
                </div>
                <p class="text-light-muted small mb-3" style="color: #94a3b8;">
                    Are you the client or team member reviewing the site? Enter your access passkey to view the full website.
                </p>
                <form action="{{ route('client.access') }}" method="POST" class="d-flex flex-wrap justify-content-center gap-2" style="max-width: 440px; margin: auto;">
                    @csrf
                    <input type="password" name="passkey" class="form-control bg-dark text-white border-secondary" placeholder="Enter passkey (e.g. chauhan2026)" required style="flex: 1 1 200px;">
                    <button type="submit" class="btn btn-warning fw-bold px-4">
                        <i class="bi bi-unlock-fill me-1"></i> Unlock Site
                    </button>
                </form>
            </div>

            <!-- Admin Link -->
            <div class="mt-4 pt-3 border-top border-secondary border-opacity-25 d-flex justify-content-between align-items-center text-muted small">
                <span>&copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.</span>
                <a href="{{ route('admin.login') }}" class="text-muted text-decoration-none hover-light">
                    <i class="bi bi-person-lock me-1"></i> Admin Login
                </a>
            </div>
        </div>
    </div>
</body>
</html>
