<?php
$raw   = get_field('press_logos') ?: "NRK\nVG\nAftenposten\nGod Morgen Norge\nDagsavisen\nStavanger Aftenblad";
$logos = array_filter( array_map('trim', explode("\n", $raw)) );
if (!$logos) return;
?>

<div class="media-strip">
  <div class="nv-wrap">
    <p class="section-label media-label">Omtalt i</p>
    <div class="press-logos">
      <?php foreach ($logos as $logo): ?>
        <span class="press-logo"><?php echo esc_html($logo); ?></span>
      <?php endforeach; ?>
    </div>
  </div>
</div>
