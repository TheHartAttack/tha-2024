<nav class="mobile-menu">
  <?php for ($i = 0; $i < 224; $i++): ?>
    <div class="mobile-menu__hex"></div>
  <?php endfor; ?>

  <?php $header_menu = wp_get_nav_menu_items('Header Menu');
    if ($header_menu): ?>
      <ul class="mobile-menu__list">
        <?php foreach ($header_menu as $key => $menu_item): ?>
          <li class="mobile-menu__item"><a class="mobile-menu__link" href="<?php echo $menu_item->url; ?>" target="<?php echo $menu_item->target; ?>"><?php echo get_field('icon', $menu_item->ID) ? get_field('icon', $menu_item->ID) : $menu_item->title; ?></a></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

  <a href="#" class="mobile-menu__close"><i class="fa-solid fa-xmark"></i></a>
</nav>