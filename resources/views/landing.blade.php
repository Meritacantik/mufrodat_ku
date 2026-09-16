<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MufrodatKu - MTs YPPU Karimunting</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Baloo+2:wght@600;700;800&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0 }

    :root {
      --green: #5C9C7D;
      --green-dark: #4A8A6B;
      --green-light: #F1F8F4;
      --red: #DD948C;
      --red-light: #FBEEEC;
      --amber: #DDB667;
      --amber-light: #FCF4E7;
      --cream: #FEFAF3;
      --gray-500: #6b7280;
      --gray-700: #374151;
      --gray-900: #111827;
      --font: 'Poppins', 'Segoe UI', system-ui, sans-serif;
      --font-display: 'Baloo 2', 'Poppins', sans-serif;
    }

    html, body {
      overflow-x: hidden;
      max-width: 100%
    }

    body {
      font-family: var(--font);
      background: var(--cream);
      color: var(--gray-900);
      min-height: 100vh;
    }

    /* NAVBAR */
    .nav {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 18px 24px;
      max-width: 1080px;
      margin: 0 auto;
    }

    .nav-brand {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .nav-logo {
      width: 38px;
      height: 38px;
      flex-shrink: 0;
      background: var(--green);
      border-radius: 11px;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 4px 10px rgba(92, 156, 125, .35);
    }

    .nav-title {
      font-family: var(--font-display);
      font-weight: 700;
      font-size: 16px;
      color: var(--gray-900);
      line-height: 1.15;
    }

    .nav-sub {
      font-size: 10.5px;
      color: var(--gray-500);
    }

    .nav-actions {
      display: flex;
      gap: 8px;
    }

    .btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 9px 18px;
      border-radius: 99px;
      font-size: 13px;
      font-weight: 600;
      text-decoration: none;
      cursor: pointer;
      border: none;
      transition: .15s;
      font-family: var(--font);
    }

    .btn-ghost {
      background: transparent;
      color: var(--gray-700);
      border: 1.5px solid #e5e7eb;
    }

    .btn-ghost:hover { background: white }

    .btn-primary {
      background: var(--green);
      color: white;
      box-shadow: 0 6px 14px rgba(92, 156, 125, .35);
    }

    .btn-primary:hover { background: var(--green-dark) }

    /* HERO */
    .hero {
      max-width: 1080px;
      margin: 0 auto;
      padding: 40px 24px 20px;
      display: grid;
      grid-template-columns: 1.1fr .9fr;
      gap: 40px;
      align-items: center;
    }

    .hero-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: var(--amber-light);
      color: #B8853A;
      font-size: 11.5px;
      font-weight: 700;
      padding: 6px 14px;
      border-radius: 99px;
      margin-bottom: 16px;
    }

    .hero h1 {
      font-family: var(--font-display);
      font-size: 40px;
      line-height: 1.15;
      color: var(--gray-900);
      margin-bottom: 14px;
    }

    .hero h1 span { color: var(--green) }

    .hero p {
      font-size: 15px;
      color: var(--gray-500);
      line-height: 1.6;
      margin-bottom: 26px;
      max-width: 480px;
    }

    .hero-actions { display: flex; gap: 10px; flex-wrap: wrap }

    .btn-lg { padding: 13px 26px; font-size: 14.5px; border-radius: 12px }

    .hero-art {
      background: var(--green);
      border-radius: 28px;
      padding: 34px;
      position: relative;
      overflow: hidden;
      min-height: 300px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .hero-art::before {
      content: '';
      position: absolute;
      width: 220px;
      height: 220px;
      border-radius: 50%;
      background: rgba(255, 255, 255, .1);
      top: -60px;
      right: -60px;
    }

    .hero-art::after {
      content: '';
      position: absolute;
      width: 160px;
      height: 160px;
      border-radius: 50%;
      background: rgba(255, 255, 255, .08);
      bottom: -50px;
      left: -40px;
    }

    .hero-arab {
      font-family: 'Traditional Arabic', 'Arial', sans-serif;
      font-size: 64px;
      color: white;
      font-weight: 700;
      text-align: center;
      position: relative;
      z-index: 1;
    }

    .hero-arab .sub {
      font-family: var(--font);
      font-size: 14px;
      font-weight: 500;
      color: rgba(255, 255, 255, .85);
      margin-top: 10px;
    }

    /* FEATURES */
    .features {
      max-width: 1080px;
      margin: 30px auto 0;
      padding: 20px 24px 60px;
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 16px;
    }

    .feat-card {
      background: white;
      border: 1px solid #eee6d8;
      border-radius: 18px;
      padding: 22px;
    }

    .feat-icon {
      width: 42px;
      height: 42px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 14px;
    }

    .feat-icon.green { background: var(--green-light); color: var(--green) }
    .feat-icon.red { background: var(--red-light); color: var(--red) }
    .feat-icon.amber { background: var(--amber-light); color: #B8853A }

    .feat-title {
      font-weight: 700;
      font-size: 14.5px;
      margin-bottom: 6px;
      color: var(--gray-900);
    }

    .feat-desc {
      font-size: 12.5px;
      color: var(--gray-500);
      line-height: 1.55;
    }

    .footer {
      text-align: center;
      padding: 22px 24px 34px;
      font-size: 11.5px;
      color: var(--gray-500);
    }

    /* RESPONSIVE */
    @media (max-width: 800px) {
      .hero { grid-template-columns: 1fr; padding-top: 20px }
      .hero h1 { font-size: 30px }
      .hero-art { order: -1; min-height: 200px }
      .hero-arab { font-size: 46px }
      .features { grid-template-columns: 1fr; }
      .nav-title { font-size: 14px }
      .nav-sub { display: none }
    }
  </style>
</head>

<body>
  <div class="nav">
    <div class="nav-brand">
      <div class="nav-logo">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="color:white">
          <path d="M12 6.5c-1.5-1-3.6-1.5-6-1.5v13c2.4 0 4.5.5 6 1.5" />
          <path d="M12 6.5c1.5-1 3.6-1.5 6-1.5v13c-2.4 0-4.5.5-6 1.5" />
          <path d="M12 6.5v13" />
        </svg>
      </div>
      <div>
        <div class="nav-title">MufrodatKu</div>
        <div class="nav-sub">MTs YPPU Karimunting</div>
      </div>
    </div>
    <div class="nav-actions">
      <a href="{{ route('login') }}" class="btn btn-ghost">Masuk</a>
      <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
    </div>
  </div>

  <div class="hero">
    <div>
      <div class="hero-badge">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2 2 7l10 5 10-5-10-5Z"/><path d="M6 9.5V16c0 1.5 2.7 3 6 3s6-1.5 6-3V9.5"/></svg>
        Media Belajar Mufrodat Arab
      </div>
      <h1>Belajar Mufrodat Arab<br>Jadi Lebih <span>Mudah &amp; Seru</span></h1>
      <p>MufrodatKu membantu siswa menghafal kosakata bahasa Arab lewat pencarian kata pintar dengan koreksi ejaan otomatis, serta kuis bergamifikasi lengkap dengan poin dan papan peringkat.</p>
      <div class="hero-actions">
        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Mulai Belajar</a>
        <a href="{{ route('login') }}" class="btn btn-ghost btn-lg">Saya sudah punya akun</a>
      </div>
    </div>
    <div class="hero-art">
      <div class="hero-arab">
        كِتَابٌ
        <div class="sub">kitābun — buku</div>
      </div>
    </div>
  </div>

  <div class="features">
    <div class="feat-card">
      <div class="feat-icon green">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="6.5"/><path d="m20 20-4.3-4.3"/></svg>
      </div>
      <div class="feat-title">Pencarian Pintar</div>
      <div class="feat-desc">Cari kosakata dengan koreksi ejaan otomatis (autocorrect) berbasis Levenshtein Distance, tetap ketemu meski ada typo.</div>
    </div>
    <div class="feat-card">
      <div class="feat-icon red">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20l1-4L15.5 5.5l3 3L8 20l-4 1Z"/><path d="M13.5 7 17 10.5"/></svg>
      </div>
      <div class="feat-title">Kuis Bergamifikasi</div>
      <div class="feat-desc">Uji hafalan lewat kuis per bab, kumpulkan poin, dan naikkan level belajarmu setiap hari.</div>
    </div>
    <div class="feat-card">
      <div class="feat-icon amber">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 4h8v4a4 4 0 0 1-8 0V4Z"/><path d="M8 5H5a2 2 0 0 0 2 4"/><path d="M16 5h3a2 2 0 0 1-2 4"/><path d="M12 12v5"/><path d="M9 20h6"/></svg>
      </div>
      <div class="feat-title">Papan Peringkat</div>
      <div class="feat-desc">Bandingkan progres belajar dengan teman sekelas lewat leaderboard yang selalu update.</div>
    </div>
  </div>

  <div class="footer">
    &copy; {{ date('Y') }} MufrodatKu &mdash; MTs YPPU Karimunting
  </div>
</body>

</html>
