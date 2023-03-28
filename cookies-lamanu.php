<?php 
/**
 * Plugin Name: Tarte Au Citron Cookies 
 * Description: Plugin de cookies
 * Author: Clement Jonckheere
 * Version: 0.0.1
 * 
 */
echo '<pre>';
var_dump(get_option('tarteaucitron_settings_group'));
echo '</pre>';
 function tarteaucitron_config() {
    if ( ! is_user_logged_in() ) : ?>
    <script type="text/javascript">
    tarteaucitron.init({
    "privacyUrl": "", /* Privacy policy url */
    "hashtag": "#tarteaucitron", /* Open the panel with this hashtag */
    "cookieName": "tarteaucitron", /* Cookie name */
    "orientation": "middle", /* Banner position (top - bottom) */
    "groupServices": false, /* Group services by category */
    "showAlertSmall": false, /* Show the small banner on bottom right */
    "cookieslist": false, /* Show the cookie list */
    "AcceptAllCta" : true, /* Show the accept all button when highPrivacy on */
    });
    </script>
    <?php endif;
}

add_action('wp_head', 'tarteaucitron_config');
function citron() {
    wp_enqueue_script( 'tarteaucitron', plugin_dir_url( __FILE__ ) . 'tarteaucitron.js', array(), '1.0.0', true );
    wp_enqueue_style( 'tarteaucitron-css', plugin_dir_url( __FILE__ ) . 'css/tarteaucitron.css', array(), '1.0.0', 'all' );
}
add_action( 'wp_enqueue_scripts', 'citron' );

function add_tarteaucitron_script() {
    ?>
    <script src="wp-content/plugins/tarteaucitron/tarteaucitron.js"></script>
    <script type="text/javascript">
        tarteaucitron.user.analyticsUa = 'UA-XXXXXXXX-X';
        tarteaucitron.user.analyticsAnonymizeIp = true;
        (tarteaucitron.job = tarteaucitron.job || []).push('analytics');
    </script>
    <?php
};
add_action('wp_head', 'add_tarteaucitron_script');

function tarteaucitron_cookie(){
    ?>
       <script type="text/javascript">
        tarteaucitron.init({
            "hashtag": "<?php echo esc_attr(get_option('tarteaucitron_hashtag')); ?>",
            "privacyUrl": "<?php echo esc_url(get_option('tarteaucitron_privacy')); ?>",
            "cookieName": "<?php echo esc_attr(get_option('tarteaucitron_cookies')); ?>",
            "orientation": "<?php echo esc_attr(get_option('tarteaucitron_orientation')); ?>",
            "showAlertSmall": <?php echo get_option('tarteaucitron_alert') ? 'true' : 'false'; ?>,
            "cookieslist": <?php echo get_option('tarteaucitron_cookies') ? 'true' : 'false'; ?>
        });
    </script>
    <?php
}
add_action('wp_footer', 'tarteaucitron_cookie');




function  tarte_options_page() {
    add_menu_page(
        'Tarte au Citron Settings',
        'Tarte au Citron',
        'manage_options',
        'tarteaucitron-settings',
        'tarte_citron_html',
        'dashicons-shield-alt',
        100
    );
}
add_action( 'admin_menu', 'tarte_options_page' );

function register_settings() {
    register_setting('tarteaucitron_settings_group', 'tarteaucitron_settings_group');

    add_settings_section('tarteaucitron_general','Tarte au citron','tarte_options_page','tarteaucitron-settings');

    add_settings_field('tarteaucitron_settings_group_hashtag','Hastag','addHashtag','tarteaucitron-settings', 'tarteaucitron_general');
    add_settings_field('tarteaucitron_settings_group_privacy','highPrivacy','addPrivacy','tarteaucitron-settings', 'tarteaucitron_general');
    add_settings_field('tarteaucitron_settings_group_cta','AcceptAllCta','addCta','tarteaucitron-settings', 'tarteaucitron_general');
    add_settings_field('tarteaucitron_settings_group_orientation','orientation','addOrientation','tarteaucitron-settings', 'tarteaucitron_general');
    add_settings_field('tarteaucitron_settings_group_adblocker','adblocker','addAdblocker','tarteaucitron-settings', 'tarteaucitron_general');
    add_settings_field('tarteaucitron_settings_group_alert','showAlertSmall','addAlert','tarteaucitron-settings', 'tarteaucitron_general');
    add_settings_field('tarteaucitron_settings_group_cookies','cookieslist','addCookieslist','tarteaucitron-settings', 'tarteaucitron_general');
}

add_action('admin_init', 'register_settings');

function tarte_au_citron_setting() {
    
}
add_action('admin_init', 'tarte_au_citron_setting');

function tarte_citron_html() {
    ?>
    <div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <form action="options.php" method="post">
    <?php
    // output security fields for the registered setting "wporg_options"
    settings_fields( 'tarteaucitron_settings_group' );
    // output setting sections and their fields
    // (sections are registered for "wporg", each field is registered to a specific section)
    do_settings_sections( 'tarteaucitron-settings' );
    // output save settings button
    submit_button( __( 'Save Settings', 'tarteaucitron' ) );
    ?>
    </form>
    </div>
    <?php
    }

    function addHashtag() {
        $options = get_option('tarteaucitron');
        $value = $options['hash'] ?? '';
        update_option('tarteaucitron_hashtag',$value);
    ?>
        <div>
            <label>Hashtag</label>
            <input type="text" name="tarteaucitron['hash']" value="<?php echo $value ?>"></input>
        </div>
    <?php
    }

    function addPrivacy() {
        $options = get_option('tarteaucitron_settings_group');
        $value = $options['privacy'] ?? '';
        update_option('tarteaucitron_privacy',$value);
    ?>
        <div>
            <label>HighPrivacy</label>
            <select name="tarteaucitron_settings_group['privacy']">
                <option value="1" <?php selected($value, '1');?>>True</option>
                <option value="0" <?php selected($value, '0');?>>False</option>
            </select>
        </div>
    <?php
    }

    function addCta() {
        $options = get_option('tarteaucitron_settings_group');
        $value = $options['cta'] ?? '';
        update_option('tarteaucitron_cta',$value);
    ?>
        <div>
            <label>AcceptAllCta</label>
            <select name="tarteaucitron_settings_group['cta']">
                <option value="1" <?php selected($value, '1');?>>True</option>
                <option value="0" <?php selected($value, '0');?>>False</option>
            </select>
        </div>
    <?php
    }

    function addOrientation() {
        $options = get_option('tarteaucitron_settings_group');
        $value = $options['orientation'] ?? '' ;
        update_option('tarteaucitron_orientation', $value);
    ?>
        <div>
            <label>orientation</label>
            <select name="tarteaucitron_settings_group['orientation']">
            <option value="top" <?php selected( $value, 'top' ); ?>>Top</option>
            <option value="middle" <?php selected( $value, 'middle' ); ?>>Middle</option>
            <option value="bottom" <?php selected( $value, 'bottom' ); ?>>Bottom</option>
        </select>
        </div>
    <?php
    }

    function addAdblocker() {
        $options = get_option('tarteaucitron_settings_group');
        $value = $options['adblocker'] ?? '' ;
        update_option('tarteaucitron_adblocker', $value);
    ?>
        <div>
            <label>adblocker</label>
            <select name="tarteaucitron_settings_group['adblocker']">
                <option value="1" <?php selected($value, '1');?>>True</option>
                <option value="0" <?php selected($value, '0');?>>False</option>
            </select>
        </div>
    <?php
    }

    function addAlert() {
        $options = get_option('tarteaucitron_settings_group');
        $value = $options['addAlert'] ?? '' ;
        update_option('tarteaucitron_alert', $value);
    ?>
        <div>
            <label>showAlertSmall</label>
            <select name="tarteaucitron_settings_group['addAlert']">
                <option value="1" <?php selected($value, '1');?>>True</option>
                <option value="0" <?php selected($value, '0');?>>False</option>
            </select>
        </div>
    <?php
    }

    function addCookieslist() {
        $options = get_option('tarteaucitron_settings_group');
        $value = $options['cookies'] ?? '' ;
        update_option('tarteaucitron_cookies', $value);
    ?>
        <div>
            <label>cookieslist</label>
            <select name="tarteaucitron_settings_group['cookies']">
                <option value="1" <?php selected($value, '1');?>>True</option>
                <option value="0" <?php selected($value, '0');?>>False</option>
            </select>
        </div>
    <?php
    }