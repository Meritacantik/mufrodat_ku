<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'MufrodatKu') - MTs YPPU Karimunting</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Baloo+2:wght@600;700;800&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0
    }

    :root {
      --green: #5C9C7D;
      --green-dark: #4A8A6B;
      --green-light: #F1F8F4;
      --red: #DD948C;
      --red-light: #FBEEEC;
      --amber: #DDB667;
      --amber-light: #FCF4E7;
      --cream: #FEFAF3;
      --cream-dark: #FAF1E3;
      --gray-50: #f9fafb;
      --gray-100: #f3f4f6;
      --gray-200: #e5e7eb;
      --gray-300: #d1d5db;
      --gray-400: #9ca3af;
      --gray-500: #6b7280;
      --gray-600: #4b5563;
      --gray-700: #374151;
      --gray-800: #1f2937;
      --gray-900: #111827;
      --font: 'Poppins', 'Segoe UI', system-ui, sans-serif;
      --font-display: 'Baloo 2', 'Poppins', sans-serif;
      --r: 10px;
      --rl: 16px;
      --rs: 6px;
    }

    body {
      font-family: var(--font);
      background: var(--cream);
      color: var(--gray-900);
      min-height: 100vh;
      display: flex
    }

    /* SIDEBAR */
    .sidebar {
      width: 176px;
      flex-shrink: 0;
      background: var(--green);
      display: flex;
      flex-direction: column;
      padding: 18px 12px;
      min-height: 100vh
    }

    .sidebar.admin {
      background: var(--red)
    }

    .sb-brand {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 0 6px 16px;
      margin-bottom: 10px;
      border-bottom: 1px solid rgba(255, 255, 255, .18)
    }

    .sb-logo {
      width: 32px;
      height: 32px;
      flex-shrink: 0;
      background: rgba(255, 255, 255, .15);
      border-radius: 9px;
      display: flex;
      align-items: center;
      justify-content: center
    }

    .sb-title {
      font-family: var(--font-display);
      font-size: 13px;
      font-weight: 700;
      color: white;
      line-height: 1.2
    }

    .sb-sub {
      font-size: 9px;
      color: rgba(255, 255, 255, .75);
      line-height: 1.3
    }

    .sb-label {
      font-size: 9px;
      font-weight: 700;
      color: rgba(255, 255, 255, .55);
      letter-spacing: .08em;
      text-transform: uppercase;
      padding: 0 8px;
      margin: 6px 0
    }

    .sb-item {
      display: flex;
      align-items: center;
      gap: 9px;
      padding: 8px 10px;
      border-radius: var(--r);
      font-size: 12px;
      font-weight: 500;
      color: rgba(255, 255, 255, .85);
      margin-bottom: 2px;
      text-decoration: none;
      transition: .15s
    }

    .sb-item:hover {
      background: rgba(255, 255, 255, .15)
    }

    .sb-item.active {
      background: rgba(255, 255, 255, .95);
      color: var(--gray-900);
      font-weight: 700;
      box-shadow: 0 2px 6px rgba(0, 0, 0, .15)
    }

    .sb-item svg {
      flex-shrink: 0;
      opacity: .9
    }

    .sb-item.active svg {
      opacity: 1
    }

    .sb-spacer {
      flex: 1
    }

    .sb-logout {
      color: #fecaca !important
    }

    /* MAIN */
    .main {
      flex: 1;
      display: flex;
      flex-direction: column;
      min-width: 0;
      background: var(--cream)
    }

    .topbar {
      height: 58px;
      flex-shrink: 0;
      background: white;
      border-bottom: 1px solid var(--gray-200);
      display: flex;
      align-items: center;
      gap: 14px;
      padding: 0 20px
    }

    .topbar-title {
      font-size: 15px;
      font-weight: 700;
      color: var(--gray-900);
      flex: 1
    }

    .topbar-search {
      display: flex;
      align-items: center;
      gap: 6px;
      background: var(--gray-50);
      border: 1px solid var(--gray-200);
      border-radius: 99px;
      padding: 6px 14px;
      font-size: 12px;
      color: var(--gray-400);
      width: 180px;
      cursor: pointer
    }

    .topbar-right {
      display: flex;
      align-items: center;
      gap: 10px
    }

    .role-badge {
      font-size: 11px;
      font-weight: 600;
      padding: 3px 10px;
      border-radius: 99px;
      background: var(--green-light);
      color: var(--green)
    }

    .role-badge.admin {
      background: var(--red-light);
      color: var(--red)
    }

    .avatar {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: var(--green-light);
      color: var(--green);
      font-size: 11px;
      font-weight: 700;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      position: relative
    }

    .avatar-dropdown {
      position: absolute;
      right: 0;
      top: 38px;
      background: white;
      border: 1px solid var(--gray-200);
      border-radius: var(--r);
      box-shadow: 0 4px 16px rgba(0, 0, 0, .1);
      min-width: 160px;
      z-index: 99;
      display: none
    }

    .avatar-dropdown.show {
      display: block
    }

    .avatar-dropdown a {
      display: block;
      padding: 10px 14px;
      font-size: 13px;
      color: var(--gray-700);
      text-decoration: none;
      border-bottom: 1px solid var(--gray-100)
    }

    .avatar-dropdown a:hover {
      background: var(--gray-50)
    }

    .avatar-dropdown button {
      width: 100%;
      text-align: left;
      padding: 10px 14px;
      font-size: 13px;
      color: #dc2626;
      background: none;
      border: none;
      cursor: pointer;
      font-family: var(--font)
    }

    .avatar-dropdown button:hover {
      background: #fef2f2
    }

    /* CONTENT */
    .content {
      padding: 24px;
      overflow: auto;
      flex: 1
    }

    .page-title {
      font-size: 18px;
      font-weight: 700;
      color: var(--gray-900);
      margin-bottom: 3px
    }

    .page-sub {
      font-size: 12px;
      color: var(--gray-500);
      margin-bottom: 20px
    }

    /* STAT CARDS */
    .stat-row {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 12px;
      margin-bottom: 16px
    }

    .stat-c {
      border-radius: var(--rl);
      padding: 14px 14px 12px;
      display: flex;
      flex-direction: column;
      gap: 10px;
      color: white;
      position: relative;
      overflow: hidden
    }

    .stat-c::after {
      content: '';
      position: absolute;
      right: -18px;
      bottom: -18px;
      width: 70px;
      height: 70px;
      border-radius: 50%;
      background: rgba(255, 255, 255, .12)
    }

    .stat-green {
      background: var(--green)
    }

    .stat-red {
      background: var(--red)
    }

    .stat-amber {
      background: var(--amber)
    }

    .stat-white {
      background: white;
      color: var(--gray-900);
      border: 1.5px solid var(--gray-200)
    }

    .stat-top {
      display: flex;
      align-items: center;
      justify-content: space-between
    }

    .stat-icon {
      width: 30px;
      height: 30px;
      border-radius: 9px;
      background: rgba(255, 255, 255, .22);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0
    }

    .stat-white .stat-icon {
      background: var(--green-light)
    }

    .stat-val {
      font-size: 20px;
      font-weight: 800;
      font-family: var(--font-display)
    }

    .stat-label {
      font-size: 10.5px;
      opacity: .9;
      font-weight: 600
    }

    .stat-sub {
      font-size: 10px;
      opacity: .85
    }

    .stat-white .stat-label {
      color: var(--gray-500);
      opacity: 1
    }

    .stat-white .stat-sub {
      color: var(--gray-400)
    }

    /* CARDS */
    .admin-form-wrap {
      max-width: 640px;
      margin: 0 auto;
    }

    .card {
      background: white;
      border: 1px solid var(--gray-200);
      border-radius: var(--rl);
      padding: 16px
    }

    .card-title {
      font-size: 12px;
      font-weight: 600;
      color: var(--gray-700);
      margin-bottom: 12px;
      display: flex;
      align-items: center;
      gap: 6px
    }

    .grid2 {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
      margin-bottom: 16px
    }

    .grid3 {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 10px
    }

    /* PROGRESS */
    .progress-wrap {
      margin-bottom: 10px
    }

    .progress-meta {
      display: flex;
      justify-content: space-between;
      font-size: 11px;
      color: var(--gray-500);
      margin-bottom: 4px
    }

    .progress-bar {
      height: 6px;
      background: var(--gray-100);
      border-radius: 99px;
      overflow: hidden
    }

    .progress-fill {
      height: 100%;
      border-radius: 99px
    }

    .fill-green {
      background: #A8CB82
    }

    .fill-red {
      background: #E7A9A2
    }

    .fill-amber {
      background: #EBC888
    }

    .fill-blue {
      background: #9BD1B3
    }

    /* LIST */
    .list-item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 8px 0;
      border-bottom: 1px solid var(--gray-100)
    }

    .list-item:last-child {
      border-bottom: none
    }

    /* PILLS */
    .pill {
      display: inline-flex;
      align-items: center;
      padding: 2px 8px;
      border-radius: 99px;
      font-size: 11px;
      font-weight: 600
    }

    .pill-green {
      background: #F5F8EC;
      color: #A8CB82;
      border: 1px solid #E7EFD3
    }

    .pill-amber {
      background: #FCF4E7;
      color: #DDB667;
      border: 1px solid #F7E6C4
    }

    .pill-red {
      background: #FBEEEC;
      color: #DD948C;
      border: 1px solid #F5D9D5
    }

    .pill-blue {
      background: #F1F8F4;
      color: #6FAE8B;
      border: 1px solid #DCEEE3
    }

    .pill-gray {
      background: var(--gray-100);
      color: var(--gray-600);
      border: 1px solid var(--gray-200)
    }

    /* BUTTONS */
    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      padding: 0 16px;
      height: 36px;
      border-radius: var(--r);
      font-size: 12px;
      font-weight: 600;
      font-family: var(--font);
      cursor: pointer;
      border: none;
      text-decoration: none;
      transition: .15s
    }

    .btn-primary {
      background: var(--green);
      color: white
    }

    .btn-primary:hover {
      background: var(--green-dark)
    }

    /* Konteks admin */
    .main.admin .btn-primary {
      background: var(--red);
    }
    .main.admin .btn-primary:hover {
      background: #c17a6f;
    }

    .btn-secondary {
      background: white;
      color: var(--gray-700);
      border: 1.5px solid var(--gray-300)
    }

    .btn-secondary:hover {
      background: var(--gray-50)
    }

    .btn-ghost {
      background: none;
      color: var(--gray-500);
      border: 1.5px solid var(--gray-200)
    }

    .btn-danger {
      background: var(--red-light);
      color: var(--red);
      border: 1px solid #F5D9D5
    }

    .btn-row {
      display: flex;
      gap: 8px;
      flex-wrap: wrap
    }

    .btn-full {
      width: 100%;
      height: 40px;
      font-size: 13px
    }

    /* CHIPS/FILTERS */
    .filter-row {
      display: flex;
      gap: 6px;
      margin-bottom: 14px;
      flex-wrap: wrap
    }

    .chip {
      height: 28px;
      padding: 0 12px;
      border-radius: 99px;
      font-size: 12px;
      font-weight: 500;
      border: 1.5px solid var(--gray-300);
      background: white;
      color: var(--gray-600);
      cursor: pointer;
      display: flex;
      align-items: center;
      text-decoration: none;
      transition: .15s
    }

    .chip.active {
      background: var(--green-light);
      border-color: #9BD1B3;
      color: var(--green);
      font-weight: 600
    }

    .chip:hover:not(.active) {
      border-color: #9BD1B3
    }

    /* FORM */
    .form-group {
      margin-bottom: 14px
    }

    .form-label {
      font-size: 12px;
      font-weight: 600;
      color: var(--gray-700);
      margin-bottom: 5px;
      display: block
    }

    .form-input {
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

    .form-input:focus {
      outline: none;
      border-color: var(--green)
    }

    .form-select {
      width: 100%;
      padding: 9px 12px;
      border: 1.5px solid var(--gray-200);
      border-radius: var(--r);
      font-size: 13px;
      font-family: var(--font);
      color: var(--gray-900);
      background: white
    }

    .form-error {
      font-size: 11px;
      color: var(--red);
      margin-top: 3px
    }

    /* WORD ITEMS */
    .word-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 14px;
      border: 1.5px solid var(--gray-200);
      border-radius: var(--rl);
      background: white;
      margin-bottom: 8px;
      transition: .15s
    }

    .word-item:hover {
      border-color: #9BD1B3;
      background: var(--green-light)
    }

    .word-arabic {
      font-size: 20px;
      font-weight: 600;
      color: var(--gray-900);
      direction: rtl;
      margin-bottom: 2px
    }

    .word-latin {
      font-size: 11px;
      color: var(--gray-500)
    }

    .word-arti {
      font-size: 12px;
      font-weight: 600;
      color: var(--green);
      margin-bottom: 3px
    }

    /* ALERT */
    .alert {
      padding: 10px 14px;
      border-radius: var(--r);
      font-size: 13px;
      margin-bottom: 14px;
      display: flex;
      align-items: center;
      gap: 8px
    }

    .alert-success {
      background: #F5F8EC;
      border: 1px solid #E7EFD3;
      color: #A8CB82
    }

    .alert-warning {
      background: var(--amber-light);
      border: 1px solid #F7E6C4;
      color: var(--amber)
    }

    .alert-error {
      background: var(--red-light);
      border: 1px solid #F5D9D5;
      color: var(--red)
    }

    /* TABLES */
    .tbl {
      width: 100%;
      border-collapse: collapse;
      font-size: 12px
    }

    .tbl th {
      text-align: left;
      font-size: 11px;
      font-weight: 600;
      color: var(--gray-500);
      padding: 8px 10px;
      border-bottom: 1.5px solid var(--gray-200);
      background: var(--gray-50)
    }

    .tbl td {
      padding: 9px 10px;
      border-bottom: 1px solid var(--gray-100);
      color: var(--gray-700)
    }

    .tbl tr:last-child td {
      border-bottom: none
    }

    /* PAGINATION */
    .pagination {
      display: flex;
      gap: 4px;
      margin-top: 14px;
      list-style: none;
      padding-left: 0;
      align-items: center;
    }

    .pagination li {
      list-style: none;
    }

    .pagination a,
    .pagination span {
      padding: 5px 10px;
      border: 1px solid var(--gray-200);
      border-radius: var(--rs);
      font-size: 12px;
      text-decoration: none;
      color: var(--gray-700)
    }

    .pagination .active {
      background: var(--green);
      color: white;
      border-color: var(--green)
    }

    .pagination svg {
      width: 14px;
      height: 14px;
      display: inline-block;
      vertical-align: -2px;
    }

    nav[role="navigation"] {
      font-size: 12px;
    }

    @media (max-width: 760px) {
      .responsive-table table,
      .responsive-table thead,
      .responsive-table tbody,
      .responsive-table th,
      .responsive-table td,
      .responsive-table tr {
        display: block;
      }
      .responsive-table thead {
        display: none;
      }
      .responsive-table tr {
        border-bottom: 1px solid var(--gray-200);
        padding: 10px 12px;
      }
      .responsive-table td {
        border-bottom: none !important;
        padding: 6px 0 !important;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        text-align: right;
      }
      .responsive-table td[data-label]::before {
        content: attr(data-label);
        font-weight: 600;
        font-size: 11px;
        color: var(--gray-400);
        text-align: left;
        flex-shrink: 0;
      }
    }

    /* KUIS */
    .quiz-card {
      background: white;
      border: 1.5px solid var(--gray-200);
      border-radius: var(--rl);
      padding: 28px;
      text-align: center;
      max-width: 480px;
      margin: 0 auto
    }

    .quiz-arabic {
      font-size: 40px;
      font-weight: 700;
      color: var(--gray-900);
      direction: rtl;
      margin-bottom: 6px
    }

    .quiz-input {
      width: 100%;
      height: 44px;
      border: 2px solid #9BD1B3;
      border-radius: var(--r);
      text-align: center;
      font-size: 16px;
      font-family: var(--font);
      color: var(--gray-900);
      background: white;
      margin-bottom: 10px
    }

    .quiz-input:focus {
      outline: none;
      border-color: var(--green)
    }

    .feedback {
      padding: 10px 14px;
      border-radius: var(--r);
      font-size: 13px;
      font-weight: 600;
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      gap: 6px
    }

    .feedback-benar {
      background: #F5F8EC;
      color: #A8CB82;
      border: 1px solid #E7EFD3
    }

    .feedback-typo {
      background: var(--amber-light);
      color: var(--amber);
      border: 1px solid #F7E6C4
    }

    .feedback-salah {
      background: var(--red-light);
      color: var(--red);
      border: 1px solid #F5D9D5
    }

    /* LEADERBOARD */
    .podium-row {
      display: flex;
      align-items: flex-end;
      justify-content: center;
      gap: 18px;
      margin: 8px 0 26px;
    }

    .podium-col {
      display: flex;
      flex-direction: column;
      align-items: center;
      width: 110px;
    }

    .podium-block {
      width: 100%;
      border-radius: 12px 12px 0 0;
      display: flex;
      align-items: flex-start;
      justify-content: center;
      padding-top: 10px;
      font-size: 22px;
      font-weight: 800;
      color: white;
    }

    .podium-gold   { background: var(--amber); }
    .podium-silver { background: var(--gray-300); color: var(--gray-700); }
    .podium-bronze { background: var(--red); }

    .lb-item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 14px;
      border-radius: var(--rl);
      border: 1.5px solid var(--gray-200);
      margin-bottom: 8px;
      background: white;
      transition: .15s
    }

    .lb-item.me {
      background: var(--green-light);
      border-color: #9BD1B3
    }

    .lb-item.gold {
      background: var(--amber-light);
      border-color: #F7E6C4
    }

    .rank {
      width: 30px;
      height: 30px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 12px;
      font-weight: 700;
      flex-shrink: 0
    }

    .rank-1 {
      background: #F7E6C4;
      color: var(--amber)
    }

    .rank-2 {
      background: var(--gray-200);
      color: var(--gray-600)
    }

    .rank-3 {
      background: #DCEEE3;
      color: var(--green)
    }

    .rank-n {
      background: var(--gray-100);
      color: var(--gray-500)
    }
  </style>
  @yield('styles')
</head>

<body>
  <div class="sidebar {{ request()->is('admin*') ? 'admin' : '' }}">
    <div class="sb-brand">
      <div class="sb-logo">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="color:white">
          <path d="M12 6.5c-1.5-1-3.6-1.5-6-1.5v13c2.4 0 4.5.5 6 1.5" />
          <path d="M12 6.5c1.5-1 3.6-1.5 6-1.5v13c-2.4 0-4.5.5-6 1.5" />
          <path d="M12 6.5v13" />
        </svg>
      </div>
      <div>
        <div class="sb-title">MufrodatKu</div>
        <div class="sb-sub">MTs YPPU Karimunting</div>
      </div>
    </div>

    @if(request()->is('admin*'))
    <div class="sb-label">Menu Admin</div>
    <a href="/admin" class="sb-item {{ request()->is('admin') || request()->is('admin/') ? 'active' : '' }}">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
        <rect x="3" y="3" width="7" height="9" rx="1"/>
        <rect x="14" y="3" width="7" height="5" rx="1"/>
        <rect x="14" y="12" width="7" height="9" rx="1"/>
        <rect x="3" y="16" width="7" height="5" rx="1"/>
      </svg>
      Dashboard
    </a>
    <a href="/admin/mufrodat" class="sb-item {{ request()->is('admin/mufrodat*') ? 'active' : '' }}">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M12 6.5c-1.5-1-3.6-1.5-6-1.5v13c2.4 0 4.5.5 6 1.5" />
        <path d="M12 6.5c1.5-1 3.6-1.5 6-1.5v13c-2.4 0-4.5.5-6 1.5" />
        <path d="M12 6.5v13" />
      </svg>
      Mufrodat
    </a>
    <a href="/admin/users" class="sb-item {{ request()->is('admin/users') ? 'active' : '' }}">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="8" r="4" />
        <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" />
      </svg>
      Siswa
    </a>
    <a href="/admin/sesi-kuis" class="sb-item {{ request()->is('admin/sesi-kuis*') ? 'active' : '' }}">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="9" />
        <path d="M12 7v5l3 3" />
      </svg>
      Sesi Kuis
    </a>
    <a href="/admin/laporan" class="sb-item {{ request()->is('admin/laporan') ? 'active' : '' }}">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M4 20V10" />
        <path d="M10 20V4" />
        <path d="M16 20v-7" />
        <path d="M4 20h16" />
      </svg>
      Laporan
    </a>
    <div class="sb-spacer"></div>
    <a href="/dashboard" class="sb-item">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M4 11.5 12 4l8 7.5" />
        <path d="M6 10v9a1 1 0 0 0 1 1h4v-5h2v5h4a1 1 0 0 0 1-1v-9" />
      </svg>
      Kembali ke App
    </a>
    @else
    <div class="sb-label">Menu Siswa</div>
    @php $kuisTerkunci = request()->routeIs('kuis.soal'); @endphp
    <a href="{{ $kuisTerkunci ? '#' : '/dashboard' }}" @if($kuisTerkunci) onclick="return false;" style="opacity:.4;cursor:not-allowed;" @endif class="sb-item {{ request()->is('dashboard') ? 'active' : '' }}">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M4 11.5 12 4l8 7.5" />
        <path d="M6 10v9a1 1 0 0 0 1 1h4v-5h2v5h4a1 1 0 0 0 1-1v-9" />
      </svg>
      Dashboard
    </a>
    <a href="{{ $kuisTerkunci ? '#' : '/mufrodat' }}" @if($kuisTerkunci) onclick="return false;" style="opacity:.4;cursor:not-allowed;" @endif class="sb-item {{ request()->is('mufrodat*') ? 'active' : '' }}">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="11" cy="11" r="6.5" />
        <path d="m20 20-4.3-4.3" />
      </svg>
      Cari Kata
    </a>
    <a href="/kuis" class="sb-item {{ request()->is('kuis*') ? 'active' : '' }}">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M4 20l1-4L15.5 5.5l3 3L8 20l-4 1Z" />
        <path d="M13.5 7 17 10.5" />
      </svg>
      Kuis
    </a>
    <a href="{{ $kuisTerkunci ? '#' : '/leaderboard' }}" @if($kuisTerkunci) onclick="return false;" style="opacity:.4;cursor:not-allowed;" @endif class="sb-item {{ request()->is('leaderboard') ? 'active' : '' }}">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M8 4h8v4a4 4 0 0 1-8 0V4Z" />
        <path d="M8 5H5a2 2 0 0 0 2 4" />
        <path d="M16 5h3a2 2 0 0 1-2 4" />
        <path d="M12 12v5" />
        <path d="M9 20h6" />
      </svg>
      Leaderboard
    </a>
    @if(Auth::user()->role === 'admin')
    <a href="/admin" class="sb-item">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="3" />
        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z" />
      </svg>
      Panel Admin
    </a>
    @endif
    <div class="sb-spacer"></div>
    <form method="POST" action="/logout">
      @csrf
      <button type="submit" class="sb-item sb-logout" style="width:100%;background:none;border:none;cursor:pointer;font-family:var(--font)">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M9 20H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h3" />
          <path d="m14 8 4 4-4 4" />
          <path d="M18 12H9" />
        </svg>
        Keluar
      </button>
    </form>
    @endif
  </div>

  <div class="main {{ request()->is('admin*') ? 'admin' : '' }}">
    <div class="topbar">
      <div class="topbar-title">@yield('title', 'Dashboard')</div>
      <div class="topbar-right">
        @if(request()->is('admin*'))
        <span class="role-badge admin">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:-2px;margin-right:3px">
                <circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
            </svg>
            Admin
        </span>
        @else
        <span class="role-badge">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:-2px;margin-right:3px">
                <path d="M12 3 2 8l10 5 10-5-10-5Z"/><path d="M6 10.5V16c0 1.5 2.7 3 6 3s6-1.5 6-3v-5.5"/>
            </svg>
            Siswa
        </span>
        @endif
        <div style="position:relative">
          <div class="avatar" onclick="toggleAvatar()">{{ strtoupper(substr(Auth::user()->name,0,2)) }}</div>
          <div class="avatar-dropdown" id="avatar-dd">
            <a href="/profile" style="display:flex;align-items:center;gap:8px">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                </svg>
                Profil
            </a>
            <form method="POST" action="/logout">
              @csrf
              <button type="submit" style="display:flex;align-items:center;gap:8px">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <path d="M16 17l5-5-5-5"/><path d="M21 12H9"/>
                </svg>
                Keluar
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="content">
      @yield('content')
    </div>
  </div>

  <script>
    function toggleAvatar() {
      document.getElementById('avatar-dd').classList.toggle('show');
    }
    document.addEventListener('click', function(e) {
      if (!e.target.closest('.avatar') && !e.target.closest('#avatar-dd')) {
        document.getElementById('avatar-dd').classList.remove('show');
      }
    });
  </script>
  @yield('scripts')
</body>

</html>