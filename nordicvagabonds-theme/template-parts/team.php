<?php
$heading = get_field('team_heading') ?: 'Folk som brenner for det.';

$members = get_posts([
    'post_type'      => 'nv_team',
    'posts_per_page' => -1,
    'orderby'        => 'meta_value_num',
    'meta_key'       => 'team_order',
    'order'          => 'ASC',
]);

// Unsplash-placeholders (gratis, åpne bilder)
$placeholders = [
    'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=480&h=640&fit=crop&q=80',
    'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=480&h=640&fit=crop&q=80',
    'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=480&h=640&fit=crop&q=80',
    'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=480&h=640&fit=crop&q=80',
];

// Fallback-team
$fallback = [
    ['name' => 'Viktor Sanden',      'role' => 'Gründer & Daglig leder'],
    ['name' => 'Julie Emblem Askim', 'role' => 'Team'],
    ['name' => 'Nora Marie Elieson', 'role' => 'Team'],
];
?>

<section class="nv-section" id="team">
  <div class="nv-wrap">

    <div class="team-head nv-reveal">
      <span class="section-label">Teamet</span>
      <h2><?php echo esc_html($heading); ?></h2>
    </div>

    <div class="profiles">
      <?php if ($members): ?>
        <?php foreach ($members as $i => $m):
          $photo = get_field('team_photo', $m->ID);
          $role  = get_field('team_role',  $m->ID);
          $img_url = $photo ? esc_url($photo['url']) : ($placeholders[$i % count($placeholders)] ?? '');
          $img_alt = $photo ? esc_attr($photo['alt']) : esc_attr($m->post_title);
        ?>
          <div class="profile nv-reveal">
            <div class="profile-pic">
              <?php if ($img_url): ?>
                <img src="<?php echo $img_url; ?>" alt="<?php echo $img_alt; ?>" loading="lazy">
              <?php else: ?>
                <span class="profile-pic-placeholder">◯</span>
              <?php endif; ?>
            </div>
            <div class="profile-name"><?php echo esc_html($m->post_title); ?></div>
            <?php if ($role): ?>
              <div class="profile-role"><?php echo esc_html($role); ?></div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <?php foreach ($fallback as $i => $m): ?>
          <div class="profile nv-reveal">
            <div class="profile-pic">
              <?php if (!empty($m['name'])): ?>
                <img src="<?php echo esc_url($placeholders[$i % count($placeholders)]); ?>"
                     alt="<?php echo esc_attr($m['name']); ?>"
                     loading="lazy">
              <?php else: ?>
                <span class="profile-pic-placeholder">◯</span>
              <?php endif; ?>
            </div>
            <?php if (!empty($m['name'])): ?>
              <div class="profile-name"><?php echo esc_html($m['name']); ?></div>
              <div class="profile-role"><?php echo esc_html($m['role']); ?></div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

  </div>
</section>
