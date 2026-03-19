<?php
$tag   = get_field('hero_tag')   ?: 'Konsulent & forretningsutvikling';
$title = get_field('hero_title') ?: "Vi bygger\n<em>det som\nbetyr noe.</em>";
$body  = get_field('hero_body')  ?: 'Et skandinavisk byrå med bred kompetanse.';
$cta   = get_field('hero_cta')   ?: 'Ta kontakt';
$img   = get_field('hero_img');

// Unsplash placeholder hvis ingen bilde er lastet opp
$img_url = $img ? esc_url($img['url']) : 'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?w=900&q=80';
$img_alt = $img ? esc_attr($img['alt']) : 'Nordic Vagabonds team';
?>

<section class="hero">
  <div class="hero-text">
    <span class="hero-tag"><?php echo esc_html($tag); ?></span>
    <h1><?php echo wp_kses( nl2br($title), ['em'=>[], 'br'=>[]] ); ?></h1>
    <p class="hero-body"><?php echo esc_html($body); ?></p>
    <a href="#kontakt" class="hero-cta"><?php echo esc_html($cta); ?></a>
  </div>
  <div class="hero-img">
    <img src="<?php echo $img_url; ?>"
         alt="<?php echo $img_alt; ?>"
         loading="eager">
    <div class="hero-img-overlay"></div>
  </div>
</section>
