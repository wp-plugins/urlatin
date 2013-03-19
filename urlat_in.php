<?php
/*
  Plugin Name: URLatin
  Plugin URI: http://urlat.in/
  Description: ..
  Author: urlat.in
  Version: 1.0
 */

function urlat_in_wp_css() {
    if (!current_user_can('edit_posts'))
        return;
    
    ?><link rel='stylesheet' href='<?php echo WP_PLUGIN_URL . '/urlat_in/app.css' ?>' type='text/css' media='all' /><?php
}

function urlat_in_wp_js() {
    if (!current_user_can('edit_posts'))
        return;
    
    $old_session = session_name("urlat_in_session");
    session_start();
    
    ?><script type='text/javascript'>var urlat_in_url_proxy='<?php echo WP_PLUGIN_URL . '/urlat_in/proxy.php' ?>', urlat_in_user='<?php echo isset($_SESSION['url_in_wp_username'])?$_SESSION['url_in_wp_username']:'' ?>',urlat_in_pass='<?php echo isset($_SESSION['url_in_wp_password'])?$_SESSION['url_in_wp_password']:'' ?>';
    </script><script type='text/javascript' src="<?php echo WP_PLUGIN_URL . '/urlat_in/js/app.js' ?>"></script><div id="urlat_in_wp_widget"><form method="POST"><textarea name="url" id="urlat_in_wp_url" style='float:left;width:240px !important;font-size:16px;height:46px;border: 1px solid #999;border-radius: 3px 0 0 3px;margin: 0;'></textarea><button type="submit" style="height: 46px;font-size: 16px;display: block;float:left;border-radius: 0 3px 3px 0">&nbsp;&nbsp;&nbsp;</button><div style="clear:both"></div></form><div id='urlat_in_wp_shortly'><span style='float:right;cursor:default;font-size:12px'>&times;</span><input readonly="true" /><div style="clear:both"></div></div></div>
    <?php
    
    session_name($old_session);
    session_start();
}

function urlat_in_wp_menu($wp_admin_bar) {
    if (!current_user_can('edit_posts'))
        return;

    wp_enqueue_script('jquery');

    $wp_admin_bar->add_menu(array(
        'parent' => 'top-secondary',
        'id' => 'urlat_in_menu',
        'title' => '<span id="urlat_in_wp_menu">UR<span class="urlat_in_code">L</span>atin</span>',
        'meta' => array('title' => 'Proteger y acortar con URLatin'),
    ));
}

function urlat_in_wp_login(){
    
    $old_session = session_name("urlat_in_session");
    session_start();
    
    if (!isset($_SESSION['url_in_wp_auth']) || !$_SESSION['url_in_wp_auth']) {
        
        $_SESSION['url_in_wp_auth'] = true;

        $_SESSION['url_in_wp_username'] = get_user_meta(get_current_user_id(), 'url_in_wp_username', true);
        $_SESSION['url_in_wp_password'] = get_user_meta(get_current_user_id(), 'url_in_wp_password', true);
    }

    session_name($old_session);
    session_start();
}

function urlat_in_wp_init() {
    if (!current_user_can('edit_posts'))
        return;
    
    urlat_in_wp_login();
    
    /*
    if(get_user_option('rich_editing') == 'true') {
        add_filter("mce_external_plugins", "urlat_in_tinymce_add_plugin");
        add_filter('mce_buttons', 'urlat_in_tinymce_register_button');
    }*/
}

function urlat_in_wp_admin_menu() {
    if (!current_user_can('edit_posts'))
        return;

    if (function_exists('add_options_page')) {
        add_options_page('URLatin', 'URLatin', 6, 'urlat_in/options.php');
    }
}

add_action('admin_bar_menu', 'urlat_in_wp_menu', 10);
add_action('admin_head', 'urlat_in_wp_css');
add_action('admin_footer', 'urlat_in_wp_js');
add_action('init', 'urlat_in_wp_init');
add_action('admin_menu', 'urlat_in_wp_admin_menu');

function urlat_in_tinymce_register_button($buttons) {
    array_push($buttons, 'separator', 'urlat_in');
    return $buttons;
}

function urlat_in_tinymce_add_plugin($plugin_array) {
    $plugin_array['urlat_in'] = WP_PLUGIN_URL . '/urlat_in/js/tinymce_editor_plugin.js';
    return $plugin_array;
}

/*
add_filter( 'wp_insert_post_data' , 'modify_post_data' , '99', 2 );

function modify_post_data( $data , $postarr )
{
  $pos = strpos($data['post_content'], '[urlatin]');
  if ($data['post_status'] == 'publish' && $data['post_type'] == 'post' && $pos !== false) {
    
    preg_match_all('#\[urlatin\](.*?)\[/urlatin\]#sie', $data['post_content'], $matches, null, $pos);
    
    $old_session = session_name("urlat_in_session");
    session_start(); 
        $user = $_SESSION['url_in_wp_username'];
        $pass = $_SESSION['url_in_wp_password'];
    session_name($old_session);
    session_start();
    
    require 'urlat_in_connector.class.php';
    
    $links = $matches[1][0];
    
    if ($user != null)
        $response = urlat_in_connector::send( array('url'=>$links), $user, $pass );
    else 
        $response = urlat_in_connector::send( array('url'=>$links) );

    if ($response != 'NO CONECTION') {
        
        $link = urlat_in_connector::getShortUrl($response);
        $link = '<a href="'.$link.'">'.$link.'</a>';

        $data['post_content'] = str_replace($matches[0], $link, $data['post_content']);
    }
  }

  return $data;
}*/