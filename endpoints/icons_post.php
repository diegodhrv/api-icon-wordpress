<?php

function api_post_icons($request) {
  $posts = array();
  $args = array('post_type' => 'icons', 'post_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC',);
  $loop = new WP_Query($args);

  while ($loop->have_posts()) : $loop->the_post();
    $id = get_the_ID();
    $titulo = get_the_title();
    $img = get_field('imagem', $id);
    $base64 = 'data:image/'.pathinfo($img, PATHINFO_EXTENSION).'+xml;base64,'.base64_encode(file_get_contents($img));
    $aux = false;

    $post = array(
      'id' => $id,
      'name' => $titulo,
      'image' => $img,
      'base64' => $base64,
    );

    if(count($posts) > 0) {
      for ($i = 0; $i < count($posts); $i++) {
        if($posts[$i]['name'] == $post['name'] || $posts[$i]['base64'] == $post['base64']) {
          $aux = true;
          break;
        }
      }
    }
    if(!$aux && $titulo != "") {
      array_push($posts, $post);
    }
  endwhile;
  return rest_ensure_response($posts);
}

function register_api_post_icons() {
  register_rest_route('v1', '/icons', [
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'api_post_icons',
  ]);
}
add_action('rest_api_init', 'register_api_post_icons');

?>