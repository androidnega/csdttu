<?php
require VIEWS . '/admin/layout.php';

// Fetch all hubs to populate the parent selection list
$allHubs = getAllHubs();
if ($editCard) {
    // Prevent setting self as parent
    $allHubs = array_filter($allHubs, fn($h) => $h['id'] !== $editCard['id']);
}

$titleText = $editCard ? 'Edit Card' : 'Add New Card';
$subtext   = $editCard ? 'Modify portal card details: ' . esc($editCard['title']) : 'Create a new hub or sub-application card for the portal.';

adminLayout($titleText, function() use ($editCard, $allHubs, $titleText, $subtext) {
    $cType    = $editCard['type'] ?? 'app';
    $cParent  = $editCard['parent_id'] ?? '';
    $cIcon    = $editCard['icon'] ?? 'document';
    $cColor   = $editCard['color'] ?? 'blue';
    $cStatus  = $editCard['status'] ?? 'live';
    $cOrder   = $editCard['order'] ?? 99;
    $cFeatures= !empty($editCard['features']) ? implode("\n", $editCard['features']) : '';
?>
<style>
  .form-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 24px;
  }

  @media (max-width: 1024px) {
    .form-grid {
      grid-template-columns: 1fr;
    }
  }

  .form-card {
    background: var(--panel);
    border: 1px solid var(--panel-border);
    border-radius: 8px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.06);
  }

  .input-desc {
    font-size: 11.5px;
    color: var(--text-muted);
    margin-top: 4px;
    line-height: 1.4;
  }

  .btn-group {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-top: 24px;
  }

  /* Radio Card selector */
  .type-selector {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 24px;
  }

  .type-option {
    border: 1px solid var(--panel-border);
    background: #F8FAFC;
    border-radius: 6px;
    padding: 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    transition: all var(--r);
    position: relative;
  }

  .type-option:hover {
    background: #F1F5F9;
    border-color: #CBD5E1;
  }

  .type-option input {
    position: absolute;
    opacity: 0;
  }

  .type-option-dot {
    width: 18px;
    height: 18px;
    border-radius: 50%;
    border: 2px solid var(--text-muted);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: all var(--r);
  }

  .type-option input:checked + .type-option-dot {
    border-color: var(--accent);
  }

  .type-option input:checked + .type-option-dot::after {
    content: '';
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: var(--accent);
  }

  .type-option.selected {
    border-color: #BFDBFE;
    background: #EFF6FF;
  }

  .type-option-meta {
    display: flex;
    flex-direction: column;
    gap: 2px;
  }

  .type-option-title {
    font-size: 13.5px;
    font-weight: 700;
  }

  .type-option-desc {
    font-size: 11px;
    color: var(--text-muted);
  }

  /* Custom icon grid selector */
  .icon-selector-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(60px, 1fr));
    gap: 8px;
    max-height: 200px;
    overflow-y: auto;
    border: 1px solid var(--panel-border);
    border-radius: 6px;
    padding: 12px;
    background: #F8FAFC;
  }

  .icon-select-btn {
    aspect-ratio: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid transparent;
    background: #FFFFFF;
    border-radius: 6px;
    color: var(--text-muted);
    cursor: pointer;
    transition: all var(--r);
  }

  .icon-select-btn:hover {
    background: #F1F5F9;
    color: var(--text);
    border-color: var(--panel-border);
  }

  .icon-select-btn.active {
    background: #EFF6FF;
    color: var(--accent);
    border-color: #BFDBFE;
  }

  /* Color picker */
  .color-selector-list {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
  }

  .color-select-btn {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: 2px solid transparent;
    cursor: pointer;
    transition: all var(--r);
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .color-select-btn:hover {
    transform: scale(1.1);
  }

  .color-select-btn.active {
    border-color: var(--text);
    box-shadow: 0 0 0 2px #FFFFFF, 0 0 0 4px var(--color-val);
  }

  .color-dot {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background-color: var(--color-val);
  }
</style>

<header>
  <h1 class="page-title"><?= esc($titleText) ?></h1>
  <p class="page-subtitle"><?= esc($subtext) ?></p>
</header>

<form method="POST" action="/admin/cards/save" class="form-grid">
  <?= csrfField() ?>
  <?php if ($editCard): ?>
  <input type="hidden" name="id" value="<?= esc($editCard['id']) ?>">
  <?php endif; ?>

  <!-- Main Inputs Form -->
  <section class="form-card" aria-label="Card Details Form">
    <!-- Type Selector -->
    <div class="form-group" style="margin-bottom: 24px;">
      <label class="form-label" style="margin-bottom: 10px; display:block;">Card Type</label>
      <div class="type-selector">
        <label class="type-option <?= $cType === 'app' ? 'selected' : '' ?>" id="type-opt-app">
          <input type="radio" name="type" value="app" <?= $cType === 'app' ? 'checked' : '' ?>>
          <div class="type-option-dot"></div>
          <div class="type-option-meta">
            <span class="type-option-title">Application</span>
            <span class="type-option-desc">Individual platform/tool</span>
          </div>
        </label>
        
        <label class="type-option <?= $cType === 'hub' ? 'selected' : '' ?>" id="type-opt-hub">
          <input type="radio" name="type" value="hub" <?= $cType === 'hub' ? 'checked' : '' ?>>
          <div class="type-option-dot"></div>
          <div class="type-option-meta">
            <span class="type-option-title">Hub Group</span>
            <span class="type-option-desc">Container holding sub-apps</span>
          </div>
        </label>
      </div>
    </div>

    <!-- Parent Hub (Conditional) -->
    <div class="form-group" id="parent-field-group" style="margin-bottom: 20px; <?= $cType === 'app' ? '' : 'display:none;' ?>">
      <label for="parent_id" class="form-label">Parent Hub</label>
      <select id="parent_id" name="parent_id" class="input-field">
        <option value="">-- None (Standalone App Card) --</option>
        <?php foreach ($allHubs as $hub): ?>
        <option value="<?= esc($hub['id']) ?>" <?= $cParent === $hub['id'] ? 'selected' : '' ?>>
          <?= esc($hub['title']) ?> (<?= esc($hub['full_name'] ?: 'Hub') ?>)
        </option>
        <?php endforeach; ?>
      </select>
      <p class="input-desc">Select which hub card this application belongs to. If none, it appears directly on the homepage.</p>
    </div>

    <!-- Title -->
    <div class="form-group" style="margin-bottom: 20px;">
      <label for="title" class="form-label">Card Title *</label>
      <input type="text" id="title" name="title" class="input-field" value="<?= esc($editCard['title'] ?? '') ?>" required placeholder="e.g. CSD, QuizSnap, Documento">
      <p class="input-desc">Short, prominent display name.</p>
    </div>

    <!-- Subtitle / Full Name -->
    <div class="form-group" style="margin-bottom: 20px;">
      <label for="full_name" class="form-label">Full Name / Subtitle</label>
      <input type="text" id="full_name" name="full_name" class="input-field" value="<?= esc($editCard['full_name'] ?? '') ?>" placeholder="e.g. Final Year Project Portal">
      <p class="input-desc">Longer descriptive label displayed beneath the title.</p>
    </div>

    <!-- Description -->
    <div class="form-group" style="margin-bottom: 20px;">
      <label for="description" class="form-label">Description</label>
      <textarea id="description" name="description" class="input-field" rows="4" placeholder="Brief summary of the platform's purpose and utility..."><?= esc($editCard['description'] ?? '') ?></textarea>
      <p class="input-desc">Short overview shown in list details or main card fallback.</p>
    </div>

    <!-- Features / Highlights list -->
    <div class="form-group" style="margin-bottom: 20px;">
      <label for="features" class="form-label">Features / Highlights</label>
      <textarea id="features" name="features" class="input-field" rows="5" placeholder="Highlight 1&#10;Highlight 2&#10;Highlight 3"><?= esc($cFeatures) ?></textarea>
      <p class="input-desc">One highlight per line. Shown as bullet points in detailed list views or direct link cards.</p>
    </div>

    <!-- External Link (URL) -->
    <div class="form-group" style="margin-bottom: 20px;">
      <label for="url" class="form-label">Application URL / Target Link</label>
      <input type="text" id="url" name="url" class="input-field" value="<?= esc($editCard['url'] ?? '') ?>" placeholder="e.g. https://quizsnap.ttu.edu.gh">
      <p class="input-desc">Web address to open when launched. (For hub cards, this will override detail expansion if provided).</p>
    </div>
  </section>

  <!-- Side Panel (Aesthetic Controls) -->
  <aside class="form-card" style="height:fit-content;" aria-label="Visual configurations">
    <!-- Icon Selector -->
    <div class="form-group" style="margin-bottom: 24px;">
      <label class="form-label" style="margin-bottom:8px; display:block;">Select Icon</label>
      <input type="hidden" id="icon-input" name="icon" value="<?= esc($cIcon) ?>">
      
      <div class="icon-selector-grid" role="radiogroup" aria-label="Icon grid picker">
        <?php foreach (availableIcons() as $ic): ?>
        <button type="button" 
                class="icon-select-btn <?= $cIcon === $ic ? 'active' : '' ?>" 
                data-icon="<?= esc($ic) ?>"
                title="<?= esc($ic) ?>"
                aria-label="Use <?= esc($ic) ?> icon">
          <?= iconSvg($ic, 20) ?>
        </button>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Color Theme Selector -->
    <div class="form-group" style="margin-bottom: 24px;">
      <label class="form-label" style="margin-bottom:8px; display:block;">Color Theme</label>
      <input type="hidden" id="color-input" name="color" value="<?= esc($cColor) ?>">
      
      <div class="color-selector-list" role="radiogroup" aria-label="Accent color theme">
        <?php foreach (availableColors() as $col): 
          $colVal = colorConfig($col);
        ?>
        <button type="button" 
                class="color-select-btn <?= $cColor === $col ? 'active' : '' ?>" 
                data-color="<?= esc($col) ?>"
                style="--color-val: <?= esc($colVal['accent']) ?>;"
                title="<?= esc($col) ?> theme"
                aria-label="Use <?= esc($col) ?> color theme">
          <div class="color-dot"></div>
        </button>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Status -->
    <div class="form-group" style="margin-bottom: 20px;">
      <label for="status" class="form-label">Launch Status</label>
      <select id="status" name="status" class="input-field">
        <option value="live" <?= $cStatus === 'live' ? 'selected' : '' ?>>Live (Fully Operational)</option>
        <option value="beta" <?= $cStatus === 'beta' ? 'selected' : '' ?>>Beta (Testing Stage)</option>
        <option value="coming_soon" <?= $cStatus === 'coming_soon' ? 'selected' : '' ?>>Coming Soon (In Development)</option>
      </select>
    </div>

    <!-- Badge Text -->
    <div class="form-group" style="margin-bottom: 20px;">
      <label for="badge" class="form-label">Badge Text</label>
      <input type="text" id="badge" name="badge" class="input-field" value="<?= esc($editCard['badge'] ?? '') ?>" placeholder="e.g. Featured, Portal">
      <p class="input-desc">Small text flag displayed on the card header (optional).</p>
    </div>

    <!-- Sort Order -->
    <div class="form-group" style="margin-bottom: 20px;">
      <label for="order" class="form-label">Sort Order</label>
      <input type="number" id="order" name="order" class="input-field" value="<?= (int)$cOrder ?>" required min="0">
      <p class="input-desc">Lower numbers rank first.</p>
    </div>

    <div class="btn-group">
      <button type="submit" class="btn-primary" style="flex:1; justify-content:center;">Save Changes</button>
      <a href="/admin/cards" class="btn-secondary">Cancel</a>
    </div>
  </aside>
</form>

<script>
// Dynamic type and parent toggle
const typeRadios = document.querySelectorAll('input[name="type"]');
const parentGroup = document.getElementById('parent-field-group');
const typeAppLabel = document.getElementById('type-opt-app');
const typeHubLabel = document.getElementById('type-opt-hub');

typeRadios.forEach(radio => {
  radio.addEventListener('change', function() {
    if (this.value === 'app') {
      parentGroup.style.display = 'block';
      typeAppLabel.classList.add('selected');
      typeHubLabel.classList.remove('selected');
    } else {
      parentGroup.style.display = 'none';
      typeAppLabel.classList.remove('selected');
      typeHubLabel.classList.add('selected');
    }
  });
});

// Icon picker logic
const iconInput = document.getElementById('icon-input');
document.querySelectorAll('.icon-select-btn').forEach(btn => {
  btn.addEventListener('click', function() {
    document.querySelectorAll('.icon-select-btn').forEach(b => b.classList.remove('active'));
    this.classList.add('active');
    iconInput.value = this.getAttribute('data-icon');
  });
});

// Color picker logic
const colorInput = document.getElementById('color-input');
document.querySelectorAll('.color-select-btn').forEach(btn => {
  btn.addEventListener('click', function() {
    document.querySelectorAll('.color-select-btn').forEach(b => b.classList.remove('active'));
    this.classList.add('active');
    colorInput.value = this.getAttribute('data-color');
  });
});
</script>
<?php
});
?>
