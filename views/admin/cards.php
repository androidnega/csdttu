<?php
require VIEWS . '/admin/layout.php';

$cards = getAllCards();

// Helper to find a card title by id
$hubsList = array_filter($cards, fn($c) => $c['parent_id'] === null);
$hubsMap = [];
foreach ($hubsList as $hb) {
    $hubsMap[$hb['id']] = $hb['title'];
}

adminLayout('Manage Cards', function() use ($cards, $hubsMap) {
?>
<style>
  .cards-ctrl {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    gap: 16px;
    flex-wrap: wrap;
  }

  /* Table Style */
  .table-container {
    overflow-x: auto;
    background: var(--panel);
    border: 1px solid var(--panel-border);
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.06);
  }

  .admin-table {
    width: 100%;
    border-collapse: collapse;
    text-align: left;
    font-size: 13.5px;
  }

  .admin-table th {
    padding: 16px 20px;
    font-size: 11px;
    text-transform: uppercase;
    font-weight: 700;
    color: var(--text-muted);
    border-bottom: 1px solid var(--panel-border);
    letter-spacing: 0.05em;
  }

  .admin-table td {
    padding: 16px 20px;
    border-bottom: 1px solid var(--panel-border);
    color: var(--text);
    vertical-align: middle;
  }

  .admin-table tr:last-child td {
    border-bottom: none;
  }

  .admin-table tr:hover {
    background: #F8FAFC;
  }

  .card-meta-cell {
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .card-icon-preview {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }

  .card-title-text {
    display: flex;
    flex-direction: column;
    gap: 2px;
  }

  .card-title-main {
    font-weight: 700;
    color: var(--text);
  }

  .card-title-sub {
    font-size: 11px;
    color: var(--text-muted);
  }

  /* Badge styling */
  .type-badge {
    display: inline-flex;
    padding: 3px 8px;
    border-radius: 6px;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
  }

  .type-badge.hub {
    background: #EFF6FF;
    color: var(--accent);
    border: 1px solid #BFDBFE;
  }

  .type-badge.app {
    background: #F5F3FF;
    color: #7C3AED;
    border: 1px solid #DDD6FE;
  }

  /* Switch styling */
  .switch {
    position: relative;
    display: inline-block;
    width: 40px;
    height: 20px;
  }

  .switch input {
    opacity: 0;
    width: 0;
    height: 0;
  }

  .slider {
    position: absolute;
    cursor: pointer;
    inset: 0;
    background-color: #E2E8F0;
    transition: .3s;
    border-radius: 20px;
    border: 1px solid var(--panel-border);
  }

  .slider:before {
    position: absolute;
    content: "";
    height: 14px;
    width: 14px;
    left: 2px;
    bottom: 2px;
    background-color: #FFFFFF;
    transition: .3s;
    border-radius: 50%;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.1);
  }

  input:checked + .slider {
    background-color: #BFDBFE;
    border-color: #93C5FD;
  }

  input:checked + .slider:before {
    transform: translateX(20px);
    background-color: var(--accent);
  }

  /* Action buttons */
  .action-btns {
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .btn-edit {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 6px;
    background: #F8FAFC;
    border: 1px solid var(--panel-border);
    color: var(--text);
    transition: all var(--r);
    text-decoration: none;
  }

  .btn-edit:hover {
    color: var(--accent);
    background: #EFF6FF;
    border-color: #BFDBFE;
  }

  .btn-delete {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 6px;
    background: #FEF2F2;
    border: 1px solid #FECACA;
    color: #DC2626;
    cursor: pointer;
    transition: all var(--r);
  }

  .btn-delete:hover {
    background: #FEE2E2;
    border-color: #FCA5A5;
  }
</style>

<header class="cards-ctrl">
  <div>
    <h1 class="page-title">Manage Cards</h1>
    <p class="page-subtitle" style="margin-bottom: 0;">Add, edit, toggle visibility, or delete landing portal elements.</p>
  </div>
  <a href="/admin/cards/new" class="btn-primary">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Add Portal Card
  </a>
</header>

<section class="table-container" aria-label="Portal elements list">
  <table class="admin-table">
    <thead>
      <tr>
        <th scope="col">Card Info</th>
        <th scope="col">Type</th>
        <th scope="col">Hierarchy</th>
        <th scope="col">Badge</th>
        <th scope="col">Status</th>
        <th scope="col">Sort Order</th>
        <th scope="col">Visible</th>
        <th scope="col" style="text-align: right;">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($cards as $card): 
        $clr = colorConfig($card['color'] ?? 'blue');
        $isHub = ($card['type'] ?? 'app') === 'hub';
        $parentTitle = '';
        if (!$isHub && !empty($card['parent_id'])) {
            $parentTitle = $hubsMap[$card['parent_id']] ?? 'Unknown Hub';
        }
      ?>
      <tr id="row-<?= esc($card['id']) ?>">
        <!-- Card Info -->
        <td>
          <div class="card-meta-cell">
            <div class="card-icon-preview" style="color: <?= esc($clr['accent']) ?>; background: rgba(<?= esc($clr['rgb']) ?>, 0.08); border: 1px solid rgba(<?= esc($clr['rgb']) ?>, 0.15)">
              <?= iconSvg($card['icon'] ?? 'document', 18) ?>
            </div>
            <div class="card-title-text">
              <span class="card-title-main"><?= esc($card['title']) ?></span>
              <span class="card-title-sub"><?= esc($card['full_name'] ?: 'No subtitle/full name') ?></span>
            </div>
          </div>
        </td>

        <!-- Type -->
        <td>
          <span class="type-badge <?= $isHub ? 'hub' : 'app' ?>">
            <?= $isHub ? 'Hub' : 'App' ?>
          </span>
        </td>

        <!-- Hierarchy -->
        <td>
          <span style="font-size: 12.5px; color: var(--text-muted)">
            <?= $isHub ? 'Top-level' : ($parentTitle ? 'Child of <strong>' . esc($parentTitle) . '</strong>' : 'Orphan App') ?>
          </span>
        </td>

        <!-- Badge -->
        <td>
          <?php if (!empty($card['badge'])): ?>
          <span class="type-badge" style="background: #F8FAFC; color: var(--text); border: 1px solid var(--panel-border)">
            <?= esc($card['badge']) ?>
          </span>
          <?php else: ?>
          <span style="color: #CBD5E1">&mdash;</span>
          <?php endif; ?>
        </td>

        <!-- Status -->
        <td>
          <span class="chip <?= esc(statusChipClass($card['status'] ?? 'live')) ?>" style="padding: 1px 7px; font-size: 9px">
            <?= esc(statusLabel($card['status'] ?? 'live')) ?>
          </span>
        </td>

        <!-- Order -->
        <td>
          <span style="font-variant-numeric: tabular-nums; color: var(--text-muted)">
            <?= (int)($card['order'] ?? 99) ?>
          </span>
        </td>

        <!-- Visibility Toggle -->
        <td>
          <label class="switch" aria-label="Toggle visible status">
            <input type="checkbox" 
                   class="vis-toggle" 
                   data-id="<?= esc($card['id']) ?>" 
                   <?= ($card['active'] ?? true) ? 'checked' : '' ?>>
            <span class="slider"></span>
          </label>
        </td>

        <!-- Actions -->
        <td style="text-align: right;">
          <div class="action-btns" style="justify-content: flex-end;">
            <a href="/admin/cards/edit?id=<?= urlencode($card['id']) ?>" class="btn-edit" title="Edit Card" aria-label="Edit Card">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
            </a>
            <form method="POST" action="/admin/cards/delete" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this card?<?php if ($isHub): ?> Deleting a Hub card will also delete all of its child applications!<?php endif; ?>');">
              <?= csrfField() ?>
              <input type="hidden" name="id" value="<?= esc($card['id']) ?>">
              <button type="submit" class="btn-delete" title="Delete Card" aria-label="Delete Card">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
              </button>
            </form>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>

      <?php if (empty($cards)): ?>
      <tr>
        <td colspan="8" style="text-align: center; padding: 40px 20px; color: var(--text-muted)">
          No cards configured. Click "Add Portal Card" to get started.
        </td>
      </tr>
      <?php endif; ?>
    </tbody>
  </table>
</section>

<script>
document.querySelectorAll('.vis-toggle').forEach(el => {
  el.addEventListener('change', function() {
    const id = this.getAttribute('data-id');
    const token = '<?= csrfToken() ?>';
    
    // Asynchronous toggle
    fetch('/admin/cards/toggle', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: new URLSearchParams({
        'id': id,
        'csrf_token': token
      })
    })
    .then(r => r.json())
    .then(data => {
      if (data && data.ok) {
        // Toggle successful, checkbox reflects state
      } else {
        // Revert UI if error
        this.checked = !this.checked;
        alert('Failed to change visibility state.');
      }
    })
    .catch(err => {
      this.checked = !this.checked;
      alert('Network error while toggling visibility.');
    });
  });
});
</script>
<?php
});
?>
