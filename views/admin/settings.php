<?php
require VIEWS . '/admin/layout.php';

// Fetch current username
$settings = getSettings();
$currentUsername = $settings['admin_username'] ?? 'admin';

// $settingsError and $settingsSuccess are defined in index.php
adminLayout('Security Settings', function() use ($currentUsername, $settingsError, $settingsSuccess) {
?>
<style>
  .settings-container {
    max-width: 600px;
  }

  .form-group {
    margin-bottom: 20px;
    display: flex;
    flex-direction: column;
    gap: 8px;
  }

  .input-desc {
    font-size: 11px;
    color: var(--text-muted);
    line-height: 1.4;
  }

  /* Alerts */
  .alert {
    padding: 14px 16px;
    border-radius: 6px;
    font-size: 13.5px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .alert-danger {
    background: #FEF2F2;
    border: 1px solid #FECACA;
    color: #DC2626;
  }

  .alert-success {
    background: #ECFDF5;
    border: 1px solid #A7F3D0;
    color: #059669;
  }
</style>

<header>
  <h1 class="page-title">Security Settings</h1>
  <p class="page-subtitle">Update administrator credentials and access security configurations.</p>
</header>

<div class="settings-container">
  <div class="admin-card">
    <?php if (!empty($settingsError)): ?>
    <div class="alert alert-danger" role="alert">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
      </svg>
      <span><?= esc($settingsError) ?></span>
    </div>
    <?php endif; ?>

    <?php if (!empty($settingsSuccess)): ?>
    <div class="alert alert-success" role="alert">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
      </svg>
      <span><?= esc($settingsSuccess) ?></span>
    </div>
    <?php endif; ?>

    <form method="POST" action="/admin/settings">
      <?= csrfField() ?>

      <!-- Username -->
      <div class="form-group">
        <label for="new_username" class="form-label">Admin Username</label>
        <input type="text" id="new_username" name="new_username" class="input-field" value="<?= esc($currentUsername) ?>" required autocomplete="username">
        <span class="input-desc">You can rename the administrator user login name (default: admin).</span>
      </div>

      <div style="height: 1px; background: var(--panel-border); margin: 24px 0;" aria-hidden="true"></div>

      <!-- Current Password -->
      <div class="form-group">
        <label for="current_password" class="form-label">Current Password *</label>
        <input type="password" id="current_password" name="current_password" class="input-field" required autocomplete="current-password">
        <span class="input-desc">Confirm your current admin password to apply changes.</span>
      </div>

      <!-- New Password -->
      <div class="form-group">
        <label for="new_password" class="form-label">New Password *</label>
        <input type="password" id="new_password" name="new_password" class="input-field" required autocomplete="new-password" minlength="8">
        <span class="input-desc">Choose a strong password of at least 8 characters.</span>
      </div>

      <!-- Confirm Password -->
      <div class="form-group">
        <label for="confirm_password" class="form-label">Confirm New Password *</label>
        <input type="password" id="confirm_password" name="confirm_password" class="input-field" required autocomplete="new-password" minlength="8">
        <span class="input-desc">Retype the new password to confirm.</span>
      </div>

      <button type="submit" class="btn-primary" style="margin-top: 10px; width: 100%; justify-content: center;">
        Save Credentials
      </button>
    </form>
  </div>
</div>
<?php
});
?>
