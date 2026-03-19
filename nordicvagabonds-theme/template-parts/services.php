<?php
$intro = get_field('services_intro') ?: 'Vi tilpasser oss oppdraget — og ikke omvendt.';

$services = get_posts([
    'post_type'      => 'nv_service',
    'posts_per_page' => -1,
    'orderby'        => 'meta_value_num',
    'meta_key'       => 'service_order',
    'order'          => 'ASC',
]);

// Fallback-tjenester hvis ingen er opprettet i WP ennå
$fallback = [
    ['name' => 'Workshops',             'desc' => 'Vi designer og fasiliterer workshops som skaper reell innsikt, engasjement og fremdrift for teamet ditt.'],
    ['name' => 'Forretningsutvikling',  'desc' => 'Fra idé til skalerbar virksomhet — vi hjelper deg å bygge strukturer, identifisere markeder og vokse bærekraftig.'],
    ['name' => 'Rådgivning',            'desc' => 'Strategisk sparring og praktisk veiledning basert på bred erfaring fra både offentlig og privat sektor.'],
    ['name' => 'Juridisk rådgivning',   'desc' => 'Navigering i regelverk, kontrakter og compliance — klart og forståelig, uten unødvendig kompleksitet.'],
    ['name' => 'Rekruttering',          'desc' => 'Vi hjelper deg å finne de riktige menneskene. Med blikk for kultur, kompetanse og langsiktig match.'],
    ['name' => 'Konseptutvikling',      'desc' => 'Fra innsikt til gjennomarbeidede konsepter — vi hjelper deg å forme ideer som engasjerer og holder.'],
];
?>

<section class="nv-section services" id="tjenester">
  <div class="nv-wrap">

    <div class="services-head nv-reveal">
      <div>
        <span class="section-label">Hva vi gjør</span>
        <h2>Tjenester som<br><em>faktisk virker.</em></h2>
      </div>
      <p class="services-intro"><?php echo esc_html($intro); ?></p>
    </div>

    <div class="services-list">
      <?php if ($services): ?>
        <?php foreach ($services as $s): ?>
          <div class="service-card nv-reveal">
            <div class="service-name"><?php echo esc_html($s->post_title); ?></div>
            <p class="service-desc"><?php echo esc_html( get_field('service_desc', $s->ID) ); ?></p>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <?php foreach ($fallback as $s): ?>
          <div class="service-card nv-reveal">
            <div class="service-name"><?php echo esc_html($s['name']); ?></div>
            <p class="service-desc"><?php echo esc_html($s['desc']); ?></p>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

  </div>
</section>
