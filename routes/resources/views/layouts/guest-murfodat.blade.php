<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'MufrodatKu') - MTs YPPU Karimunting</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Baloo+2:wght@600;700;800&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0 }

    :root {
      --green: #5C9C7D;
      --green-dark: #4A8A6B;
      --green-light: #F1F8F4;
      --accent: #80ED87;
      --accent-dark: #5FD968;
      --red: #DD948C;
      --red-light: #FBEEEC;
      --amber: #DDB667;
      --amber-light: #FCF4E7;
      --gray-50: #f9fafb;
      --gray-200: #e5e7eb;
      --gray-300: #d1d5db;
      --gray-400: #9ca3af;
      --gray-500: #6b7280;
      --gray-700: #374151;
      --gray-900: #111827;
      --font: 'Poppins', 'Segoe UI', system-ui, sans-serif;
      --font-display: 'Baloo 2', 'Poppins', sans-serif;
      --r: 10px;
      --rl: 16px;
    }

    body {
      font-family: var(--font);
      background: var(--gray-50);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px;
    }

    .auth-card {
      width: 100%;
      max-width: 400px;
      background: white;
      border: 1px solid var(--gray-200);
      border-radius: var(--rl);
      padding: 32px 28px;
      box-shadow: 0 4px 24px rgba(0,0,0,0.05);
    }

    .auth-logo {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      margin-bottom: 4px;
    }

    .auth-logo .icon {
      width: 34px;
      height: 34px;
      background: var(--green);
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 17px;
      color: white;
    }

    .auth-title {
      font-family: var(--font-display);
      font-size: 19px;
      font-weight: 700;
      color: var(--gray-900);
    }

    .auth-sub {
      text-align: center;
      font-size: 12px;
      color: var(--gray-500);
      margin-bottom: 22px;
    }

    .auth-heading {
      text-align: center;
      font-size: 15px;
      font-weight: 700;
      color: var(--gray-900);
      margin-bottom: 2px;
    }

    .auth-desc {
      text-align: center;
      font-size: 11.5px;
      color: var(--gray-500);
      margin-bottom: 20px;
    }

    .tab-row {
      display: flex;
      background: var(--gray-50);
      border: 1px solid var(--gray-200);
      border-radius: var(--r);
      padding: 3px;
      margin-bottom: 22px;
    }

    .tab-btn {
      flex: 1;
      text-align: center;
      padding: 8px 0;
      font-size: 12.5px;
      font-weight: 600;
      color: var(--gray-500);
      border-radius: 7px;
      cursor: pointer;
      border: none;
      background: none;
      font-family: var(--font);
    }

    .tab-btn.active {
      box-shadow: 0 1px 4px rgba(0,0,0,0.08);
      font-weight: 700;
    }

    .input-wrap {
      position: relative;
    }

    .input-wrap .input-icon {
      position: absolute;
      left: 11px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 14px;
      opacity: .55;
      pointer-events: none;
    }

    .input-wrap .form-input {
      padding-left: 34px;
    }

    .input-wrap .toggle-eye {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      cursor: pointer;
      font-size: 14px;
      opacity: .55;
      padding: 4px;
    }

    .form-group { margin-bottom: 14px }

    .form-label {
      font-size: 12px;
      font-weight: 600;
      color: var(--gray-700);
      margin-bottom: 5px;
      display: block
    }

    .form-input, .form-select {
      width: 100%;
      padding: 9px 12px;
      border: 1.5px solid var(--gray-200);
      border-radius: var(--r);
      font-size: 13px;
      font-family: var(--font);
      color: var(--gray-900);
      background: white;
      transition: .15s
    }

    .form-input:focus, .form-select:focus {
      outline: none;
      border-color: var(--green)
    }

    .form-error {
      font-size: 11px;
      color: var(--red);
      margin-top: 4px;
    }

    .btn-primary {
      width: 100%;
      background: var(--green);
      color: white;
      border: none;
      padding: 11px 0;
      border-radius: var(--r);
      font-size: 13px;
      font-weight: 600;
      font-family: var(--font);
      cursor: pointer;
      transition: .15s;
      margin-top: 6px;
    }

    .btn-primary:hover { background: var(--green-dark) }

    .auth-footer {
      text-align: center;
      font-size: 12px;
      color: var(--gray-500);
      margin-top: 18px;
    }

    .auth-footer a {
      color: var(--green-dark);
      font-weight: 600;
      text-decoration: none;
    }

    .auth-icon-only {
      width: 64px;
      height: 64px;
      background: var(--green);
      border-radius: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 30px;
      margin: 0 auto 14px;
      color: white;
    }

    .auth-divider {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-top: 18px;
      color: var(--gray-400);
      font-size: 12px;
    }

    .auth-divider::before,
    .auth-divider::after {
      content: '';
      flex: 1;
      height: 1px;
      background: var(--gray-200);
    }

    .status-msg {
      background: var(--green-light);
      color: var(--green-dark);
      font-size: 12px;
      padding: 8px 12px;
      border-radius: var(--r);
      margin-bottom: 16px;
    }
  </style>
</head>

<body>
  <div class="auth-card">
    @hasSection('header')
      @yield('header')
    @else
    <div class="auth-logo">
      <div class="icon" id="logo-icon"><x-icon name="book" size="24"/></div>
      <div class="auth-title">MufrodatKu</div>
    </div>
    <div class="auth-sub">MTs YPPU Karimunting</div>
    @endif

    @if (session('status'))
    <div class="status-msg">{{ session('status') }}</div>
    @endif

    {{ $slot ?? '' }}
    @yield('content')
  </div>
</body>

</html>
