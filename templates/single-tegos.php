<?php get_header(); ?>
<main> 
  <?php
    if ( have_posts() ) :
      while ( have_posts() ) : the_post();

        echo "<dl>";
        $custom_fields = get_post_custom( $post_id );
        foreach ($tegos->colums as $colom) {
          $title = get_option( TEGOS::OPTION_NAME.$colom, $colom );
          $value = $custom_fields[TEGOS::POSTMETA_NAME.$colom][0];
          echo "<dt>$title</dt><dd>$value</dd>";
        }
        echo "</dl>";

      endwhile;
    endif;
	?>
<main>
<?php get_footer(); ?>