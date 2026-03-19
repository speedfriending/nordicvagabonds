<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header" id="site-header">
  <a href="<?php echo esc_url( home_url('/') ); ?>" class="site-logo">
    <?php bloginfo('name'); ?>
  </a>
  <button class="nav-toggle" id="nav-toggle" aria-label="Meny" aria-expanded="false">
    <span></span><span></span><span></span>
  </button>
  <nav class="nav-menu" id="nav-menu">
    <ul class="nav-links">
      <li><a href="#om">Om oss</a></li>
      <li><a href="#tjenester">Tjenester</a></li>
      <li><a href="#team">Team</a></li>
      <li><a href="#kontakt">Kontakt</a></li>
    </ul>
  </nav>
</header>
