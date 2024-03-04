<div class="sidebar">

  <?php
    if (!is_user_logged_in()){
      get_template_part('template-parts/sidebar', 'login', null);
    } else {
      get_template_part('template-parts/sidebar', 'user', null);
    }
    
    get_template_part('template-parts/sidebar', 'about', null);

  ?>

</div>