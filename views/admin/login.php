<?php
// $loginError is available from index.php
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login | CS Dept Portal</title>
  <link rel="icon" type="image/webp" href="<?= esc(siteFaviconUrl()) ?>"/>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
      --bg: #F4F6F9;
      --text: #1E293B;
      --muted: #64748B;
      --border: #E2E8F0;
      --accent: #1D4ED8;
      --navy: #1E3A5F;
      --r: 0.2s ease;
    }

    body {
      background: var(--bg);
      color: var(--text);
      font-family: 'Inter', system-ui, sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      -webkit-font-smoothing: antialiased;
    }

    .login-box {
      width: 100%;
      max-width: 400px;
      background: #FFFFFF;
      border: 1px solid var(--border);
      border-radius: 8px;
      padding: 36px 32px;
      box-shadow: 0 1px 3px rgba(15, 23, 42, 0.08);
      animation: enter 0.35s ease forwards;
    }

    @keyframes enter {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .login-header {
      text-align: center;
      margin-bottom: 28px;
    }

    .login-logo {
      margin: 0 auto 16px;
    }
    .login-logo .site-logo{
      height:72px;width:auto;margin:0 auto;
      -webkit-user-select:none;user-select:none;
      -webkit-user-drag:none;
    }

    .login-title {
      font-size: 20px;
      font-weight: 700;
      letter-spacing: -0.01em;
      margin-bottom: 6px;
      color: var(--navy);
    }

    .login-sub {
      font-size: 13px;
      color: var(--muted);
    }

    .form-group {
      margin-bottom: 18px;
      display: flex;
      flex-direction: column;
      gap: 6px;
    }

    .form-label {
      font-size: 12px;
      font-weight: 600;
      color: var(--text);
    }

    .input-field {
      width: 100%;
      background: var(--bg);
      border: 1px solid var(--border);
      border-radius: 6px;
      padding: 11px 14px;
      font-size: 14px;
      font-family: inherit;
      color: var(--text);
      transition: all var(--r);
    }

    .input-field:focus {
      outline: none;
      border-color: var(--accent);
      box-shadow: 0 0 0 3px rgba(29, 78, 216, 0.1);
      background: #FFFFFF;
    }

    .error-alert {
      background: #FEF2F2;
      border: 1px solid #FECACA;
      color: #DC2626;
      padding: 12px;
      border-radius: 6px;
      font-size: 13px;
      margin-bottom: 18px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .btn-submit {
      width: 100%;
      background: var(--accent);
      color: #FFFFFF;
      border: none;
      border-radius: 6px;
      padding: 12px;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      transition: all var(--r);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      margin-top: 8px;
    }

    .btn-submit:hover {
      background: #1E40AF;
    }

    .back-portal {
      display: block;
      text-align: center;
      margin-top: 22px;
      font-size: 13px;
      font-weight: 500;
      color: var(--muted);
      text-decoration: none;
      transition: color var(--r);
    }

    .back-portal:hover {
      color: var(--accent);
    }
  </style>
</head>
<body>
  <main class="login-box" role="main">
    <div class="login-header">
      <div class="login-logo"><?= siteLogo(72) ?></div>
      <h1 class="login-title">Admin Portal</h1>
      <p class="login-sub">Authorized personnel access only</p>
    </div>

    <?php if (!empty($loginError)): ?>
    <div class="error-alert" role="alert">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
      </svg>
      <?= esc($loginError) ?>
    </div>
    <?php endif; ?>

    <form method="POST" action="/admin/login">
      <?= csrfField() ?>

      <div class="form-group">
        <label for="username" class="form-label">Username</label>
        <input type="text" id="username" name="username" class="input-field" required autocomplete="username" autofocus>
      </div>

      <div class="form-group">
        <label for="password" class="form-label">Password</label>
        <input type="password" id="password" name="password" class="input-field" required autocomplete="current-password">
      </div>

      <button type="submit" class="btn-submit">
        <span>Sign In</span>
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="5" y1="12" x2="19" y2="12"></line>
          <polyline points="12 5 19 12 12 19"></polyline>
        </svg>
      </button>

      <a href="/" class="back-portal">
        &larr; Return to main portal
      </a>
    </form>
  </main>
  <script>
  document.addEventListener('contextmenu',function(e){if(e.target.closest('.site-logo'))e.preventDefault()});
  document.addEventListener('dragstart',function(e){if(e.target.closest('.site-logo'))e.preventDefault()});
  </script>
</body>
</html>
