<?php get_header(); ?>
<main class="nv-section" style="padding-top:8rem; min-height:60vh;">
  <div class="nv-wrap">
    <?php if (have_posts()): while (have_posts()): the_post(); ?>
      <h1 style="font-family:'Fraunces',serif; font-size:clamp(2rem,4vw,3.5rem); font-weight:700; color:var(--forest); margin-bottom:2rem; line-height:1.1;"><?php the_title(); ?></h1>
      <div class="entry-content" style="font-size:1rem; line-height:1.8; color:var(--muted); max-width:720px;">
        <?php the_content(); ?>
      </div>
    <?php endwhile; endif; ?>
  </div>
</main>
<?php get_footer(); ?>
