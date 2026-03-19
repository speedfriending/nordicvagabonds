<?php get_header(); ?>
<main style="padding:8rem 2rem 4rem; max-width:800px; margin:0 auto;">
  <?php if (have_posts()): while (have_posts()): the_post(); ?>
    <h1 style="font-family:'Fraunces',serif; color:var(--forest); margin-bottom:1rem;"><?php the_title(); ?></h1>
    <div><?php the_content(); ?></div>
  <?php endwhile; endif; ?>
</main>
<?php get_footer(); ?>
