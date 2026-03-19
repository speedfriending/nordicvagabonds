<?php
$heading  = get_field('contact_heading')  ?: "La oss ta\n<em>en kaffe.</em>";
$email    = get_field('contact_email')    ?: 'hei@nordicvagabonds.no';
$address  = get_field('contact_address')  ?: "Leirvågvegen 193\n6390 Vestnes";
$projects = get_field('contact_projects') ?: "speedfriending.no\nspeedfriending.com";
$form_id  = get_field('contact_form_id');
?>

<section class="nv-section contact" id="kontakt">
  <div class="nv-wrap contact-inner">

    <span class="section-label">Kontakt</span>

    <h2 class="nv-reveal">
      <?php echo wp_kses( nl2br($heading), ['em'=>[], 'br'=>[]] ); ?>
    </h2>

    <a href="mailto:<?php echo esc_attr($email); ?>" class="contact-email nv-reveal">
      <?php echo esc_html($email); ?>
    </a>

    <?php if ($form_id): ?>
      <div class="nv-reveal" style="max-width:560px; margin-bottom:4rem;">
        <?php echo do_shortcode('[contact-form-7 id="' . intval($form_id) . '" title="Kontaktskjema"]'); ?>
      </div>
    <?php endif; ?>

    <div class="contact-footer nv-reveal">
      <div class="cf-group">
        <span class="cf-label">Adresse</span>
        <div class="cf-val"><?php echo nl2br(esc_html($address)); ?></div>
      </div>
      <div class="cf-group">
        <span class="cf-label">E-post</span>
        <div class="cf-val">
          <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
        </div>
      </div>
      <?php if ($projects): ?>
        <div class="cf-group">
          <span class="cf-label">Tilknyttede prosjekter</span>
          <div class="cf-val"><?php echo nl2br(esc_html($projects)); ?></div>
        </div>
      <?php endif; ?>
    </div>

  </div>
</section>
