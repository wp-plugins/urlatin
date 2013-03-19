<?php if ( !function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
	exit;
} 

if ( isset($_POST['submit']) ) {
        
        $user_id = get_current_user_id();
        $old_user = get_user_meta($user_id, 'url_in_wp_username',true);
        $new_user = trim($_POST['username']);
        update_user_meta($user_id, 'url_in_wp_username', $new_user );
		
        if ( (isset($_POST['password']) && $_POST['password'] != '')) {

            update_user_meta( $user_id, 'url_in_wp_password', sha1($_POST['password']) );
        }
        else if ($new_user != $old_user) {
            
            update_user_meta( $user_id, 'url_in_wp_password', '');
        }

        $old_session = session_name("urlat_in_session");
        session_start();

                unset($_SESSION['url_in_wp_auth']);

        session_name($old_session);
        session_start();
        urlat_in_wp_login();
    }
?><div class="wrap">
<h2>Configuración del plugin <a href="http://urlat.in/" class="add-new-h2">Visite urlat.in</a></h2>
<form enctype="multipart/form-data" method="post" action="" >
<h3>Credenciales en URLatin</h3>
<table class="form-table">
<tr valign="top">
<th scope="row"><label for="username">Usuario</label></th>
<td>
    <input name="username" autocomplete='off' size="30" type="text" value="<?php echo get_user_meta( get_current_user_id(), 'url_in_wp_username', true) ?>" />
</td>
</tr>
<tr valign="top">
<th scope="row"><label for="password">Contraseña</label></th>
<td>
    <input name="password" size="30" type="password" value="" />
	<p class="description">Deja la contraseña en blanco si no quieres cambiarla.</p>
</td>
</tr>
</table>
<br/>
<?php submit_button(__('Save')); ?>
</form>
</div>