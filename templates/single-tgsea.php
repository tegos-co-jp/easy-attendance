<?php get_header(); ?>
<main>
  <?php
    if ( have_posts() ) :
      while ( have_posts() ) : the_post();

        echo "<dl>";
        $custom_fields = get_post_custom( get_the_ID() );
        foreach ($tgsea->colums as $colom) {
          $title = esc_html(get_option( TGSEA::OPTION_NAME.$colom, $colom ));
          $value = esc_html($custom_fields[TGSEA::POSTMETA_NAME.$colom][0]);
          echo esc_attr("<dt>$title</dt><dd>$value</dd>");
        }
        echo "</dl>";

      endwhile;
    endif;
	?>
<main>
<?php get_footer(); ?>
