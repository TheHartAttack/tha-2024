<?php get_header(); ?>

<div class="main">
  <div class="search">
    <form action="<?php echo esc_url(site_url('/')); ?>" method="get" class="search__form form">
      <input type="text" name="s" class="search__input form__input" placeholder="Enter a search term...">
      <button type="submit" class="search__submit form__submit" style="--icon: '\f002';">Search</button>
    </form>
  </div>
</div>

<?php get_template_part("template-parts/sidebar", '', null); ?>

<?php get_footer(); ?>