<?php get_header(); ?>

<div class="main">
  <?php if (have_posts()): ?>
    <?php while (have_posts()):
      the_post();
      get_template_part('template-parts/post-panel', '', array('ID' => get_the_id()));
    endwhile; ?>
  <?php else: ?>
    <p class="no-posts">No posts found.</p>
  <?php endif; ?>
</div>

<?php if ($wp_query->max_num_pages > 1):
  $current_page = (get_query_var('paged')) ? get_query_var('paged') : 1;
  $total_pages = $wp_query->max_num_pages;
  $is_first_page = $current_page == 1 ? true : false;
  $is_last_page = $current_page == $total_pages ? true : false;
  ?>

  <div class="pagination">
    <?php if (!$is_first_page): ?>
      <a class="pagination__link pagination__link--newest" href="<?php echo site_url(); ?>">Newest</a>
    <?php endif; ?>

    <ul class="pagination__list">
      <?php if ($current_page > 2): ?><li class="pagination__item pagination__item--1"><a class="pagination__link" href="<?php echo site_url('/page/'.$current_page - 2); ?>"><?php echo $current_page - 2; ?></a></li><?php endif; ?>
      <?php if ($current_page > 1): ?><li class="pagination__item pagination__item--2"><a class="pagination__link" href="<?php echo site_url('/page/'.$current_page - 1); ?>"><?php echo $current_page - 1; ?></a></li><?php endif; ?>
      <li class="pagination__item pagination__item--3"><span class="pagination__span"><?php echo $current_page; ?></span></li>
      <?php if ($current_page < $total_pages): ?><li class="pagination__item pagination__item--4"><a class="pagination__link" href="<?php echo site_url('/page/'.$current_page + 1); ?>"><?php echo $current_page + 1; ?></a></li><?php endif; ?>
      <?php if ($current_page < $total_pages - 1): ?><li class="pagination__item pagination__item--5"><a class="pagination__link" href="<?php echo site_url('/page/'.$current_page + 2); ?>"><?php echo $current_page + 2; ?></a></li><?php endif; ?>
    </ul>

    <?php if (!$is_last_page): ?>
      <a class="pagination__link pagination__link--oldest" href="<?php echo site_url('/page/'.$total_pages); ?>">Oldest</a>
    <?php endif; ?>
  </div>

<?php endif; ?>

<?php get_template_part("template-parts/sidebar", '', null); ?>

<?php get_footer(); ?>