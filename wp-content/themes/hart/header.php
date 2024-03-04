<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
  
<header class="header">
  <?php for ($i = 0; $i < 112; $i++): ?>
    <div class="header__hex"></div>
  <?php endfor; ?>

  <?php
    get_template_part("template-parts/svg", "pentagram", array('class' => 'header__pentagram header__pentagram--left'));
    get_template_part("template-parts/svg", "tha", array('class' => 'header__tha header__tha--left'));
  ?>

  <a href="<?php echo home_url(); ?>"><?php get_template_part("template-parts/svg", "logo", array('class' => 'header__logo')); ?></a>

  <?php
    get_template_part("template-parts/svg", "tha", array('class' => 'header__tha header__tha--right'));
    get_template_part("template-parts/svg", "pentagram", array('class' => 'header__pentagram header__pentagram--right'));
  ?>

  <?php
    get_template_part("template-parts/svg", "pentagram", array('class' => 'header__scrolled-pentagram header__scrolled-pentagram--left'));
    get_template_part("template-parts/svg", "tha", array('class' => 'header__scrolled-tha header__scrolled-tha--left'));
    get_template_part("template-parts/svg", "tha", array('class' => 'header__scrolled-tha header__scrolled-tha--right'));
    get_template_part("template-parts/svg", "pentagram", array('class' => 'header__scrolled-pentagram header__scrolled-pentagram--right'));
  ?>

  <?php $header_menu = wp_get_nav_menu_items('Header Menu');
  if ($header_menu): ?>
    <ul class="header__menu">
      <?php foreach ($header_menu as $key => $menu_item): ?>
        <?php
        $classes = '';
        foreach ($menu_item->classes as $class):
          $classes .= " header__menu-link--".$class;
        endforeach; ?>
        <li class="header__menu-item"><a class="header__menu-link<?php echo $classes; ?>" href="<?php echo $menu_item->url; ?>" target="<?php echo $menu_item->target; ?>"><?php echo get_field('icon', $menu_item->ID) ? get_field('icon', $menu_item->ID) : $menu_item->title; ?></a></li>
      <?php endforeach; ?>
    </ul>

    <a href="#" class="header__open-mobile-menu"><i class="fa-solid fa-bars"></i></a>
  <?php endif; ?>
</header>

<?php get_template_part("template-parts/mobile-menu", '', null); ?>

<div class="container">