<?php
$heading = get_field('about_heading') ?: "Tverrfaglig lag.\n<em>Felles retning.</em>";
$text1   = get_field('about_text1')   ?: 'Nordic Vagabonds AS er et lite konsulent- og forretningsutviklingsbyrå med bred bakgrunn. Teamet vårt dekker jus, økonomi, helse, psykologi og markedsføring — vi møter oppdraget der det er.';
$text2   = get_field('about_text2')   ?: 'Vi tror den beste rådgivningen kommer fra folk som tør å utfordre, bygge og levere i samme åndedrag.';
$tags_raw = get_field('about_tags')   ?: "Jus\nØkonomi\nPsykologi\nHelse\nMarkedsføring\nForskning\nProsjektledelse";
$tags    = array_filter( array_map('trim', explode("\n", $tags_raw)) );
$img     = get_field('about_img');

// Unsplash placeholder
$img_url = $img ? esc_url($img['url']) : 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=900&q=80';
$img_alt = $img ? esc_attr($img['alt']) : 'Workshop — Nordic Vagabonds';
?>

<section class="nv-section" id="om">
  <div class="nv-wrap">
    <div class="about-grid">

      <div class="about-aside nv-reveal">
        <span class="section-label">Om oss</span>
        <div class="monogram">NV</div>
      </div>

      <div class="about-main nv-reveal">
        <h2><?php echo wp_kses( nl2br($heading), ['em'=>[], 'br'=>[]] ); ?></h2>
        <?php if ($text1): ?><p><?php echo esc_html($text1); ?></p><?php endif; ?>
        <?php if ($text2): ?><p><?php echo esc_html($text2); ?></p><?php endif; ?>

        <?php if ($img_url): ?>
          <div class="about-img">
            <img src="<?php echo $img_url; ?>" alt="<?php echo $img_alt; ?>" loading="lazy">
          </div>
        <?php endif; ?>

        <?php if ($tags): ?>
          <div class="tags">
            <?php foreach ($tags as $tag): ?>
              <span class="tag"><?php echo esc_html($tag); ?></span>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

    </div>
  </div>
</section>
