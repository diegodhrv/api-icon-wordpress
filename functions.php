<?php
// Remove as rotas definidas pelo WordPress
/* remove_action('rest_api_init', 'create_initial_rest_routes', 99); */

// Remove as rotas especificas definidas pelo WordPress
add_filter('rest_endpoints', function ($endpoints) {
  $endpoints['/wp/v2/users'];
  $endpoints['/wp/v2/users/(?P<id>[\d]+)'];
  return $endpoints;
});

$dirbase = get_template_directory();
require_once $dirbase . '/endpoints/icons_post.php';
require_once $dirbase . '/endpoints/icons_get.php';
require_once $dirbase . '/custom-post-type/icons.php';

// Modifica o prefixo da API de wp-json para json apenas
// Necessário salvar os permalinks para dar um refresh nos URL's
function change_api($slug) {
  return 'api-site';
}
add_filter('rest_url_prefix', 'change_api');
?>