<?php

function generateViewFile($mode = 0) {
  $posts = array();
  $uploadDir = wp_get_upload_dir()['basedir'];
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

  $conteudo = "[class^='icon-r-'], [class*=' icon-r-'] {\n display: inline-block;\n mask-repeat: no-repeat;\n mask-size: 100% 100%;\n mask-position: center;\n -webkit-mask-repeat: no-repeat;\n -webkit-mask-size: 100% 100%;\n -webkit-mask-position: center;\n width: 20px;\n height: 20px;\n background: #000;\n}\n\n[class^='icon-r-']::before, [class*=' icon-r-']::before {\n  content: '';\n}\n\n\n";

  foreach($posts as $value) {
    $conteudo .= ".icon-r-".$value['name']." {\n mask-image: url('".$value['base64']."');\n -webkit-mask-image: url('".$value['base64']."');\n}\n\n";
  }

  $arquivo = fopen($uploadDir.'/css/icons.css', 'w');
  fwrite($arquivo, $conteudo);
  fclose($arquivo);
  /* if($mode == 1) {
    $tipo = 'text/css';
    header("Content-Type: ".$tipo);
    header("Content-Length: ".filesize($uploadDir.'/css/icons.css'));
    header("Content-Disposition: attachment; filename=".basename($uploadDir.'/css/icons.css'));
  }
  readfile($uploadDir.'/css/icons.css');
  exit; */
  return rest_ensure_response($uploadDir);
}

function api_get_icons($request) {
    return generateViewFile(1);
}
function api_view_icons($request) {
    generateViewFile();
}

function register_api_get_icons() {
  register_rest_route('v1', '/icons', [
    'methods' => WP_REST_Server::READABLE,
    'callback' => 'api_get_icons',
  ]);
}
function register_api_view_icons() {
    register_rest_route('v1', '/icons-view', [
      'methods' => WP_REST_Server::READABLE,
      'callback' => 'api_view_icons',
    ]);
  }
add_action('rest_api_init', 'register_api_get_icons');
add_action('rest_api_init', 'register_api_view_icons');

?>