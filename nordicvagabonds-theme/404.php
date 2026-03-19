<?php get_header(); ?>
<main class="nv-section" style="min-height:60vh; display:flex; align-items:center; justify-content:center;">
  <div class="nv-wrap" style="text-align:center;">
    <h1 style="font-family:'Fraunces',serif; font-size:clamp(3rem,8vw,8rem); font-weight:700; color:var(--forest); line-height:1;">404</h1>
    <p style="font-size:1.1rem; color:var(--muted); margin:1.5rem 0 2.5rem;">Siden du leter etter finnes ikke.</p>
    <a href="<?php echo esc_url( home_url('/') ); ?>" class="hero-cta" style="color:var(--forest); border-color:var(--gold);">Tilbake til forsiden</a>
  </div>
</main>
<?php get_footer(); ?>
