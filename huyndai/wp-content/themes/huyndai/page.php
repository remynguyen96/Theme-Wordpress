<?php get_header() ?>


<?php
if(have_posts())
{
    while(have_posts())
      {
        the_post();
?>
		  <h3><?php the_title(); ?></h3>
		  <?php the_post_thumbnail();?>
		  <p class=""><?php the_content();?></p>
<?php
      }
}
?>
<?php get_footer() ?>