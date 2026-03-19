<footer class="site-footer">
  <span>© <?php echo date('Y'); ?> <?php bloginfo('name'); ?></span>
  <?php
  $orgnr = get_field('contact_orgnr');
  if ( $orgnr ) : ?>
    <span>Org.nr. <?php echo esc_html($orgnr); ?></span>
  <?php endif; ?>
</footer>

<?php wp_footer(); ?>
</body>
</html>
