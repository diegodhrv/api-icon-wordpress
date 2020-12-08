<?php
function registrar_icons() {
  register_post_type('icons', array(
    'labels' => array(
      'name' => 'Icons',
      'singular_name' => 'Icon'
    ),
    'public' => true,
    'has_archive' => true,
  ));
}
add_action('init', 'registrar_icons');

?>