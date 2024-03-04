<?php get_header(); ?>

<div class="main">
  <?php if (have_posts()): while (have_posts()):
    the_post();

    the_content();
    
    endwhile; else: ?>
    <p class="no-posts">No posts found.</p>
  <?php endif; ?>
</div>

<?php get_template_part("template-parts/sidebar", '', null); ?>

<?php get_footer(); ?>