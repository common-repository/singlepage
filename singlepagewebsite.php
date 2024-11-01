<?php
/*
Plugin Name: Singlepage Website
Description:   Singlepage Website is a plugin where you can create and design your own personal website of One page with custom features and easy development.
Author: Techforce
Author URI: https://techforceglobal.com
Version: 1.0.3
Text Domain:  Singlepage Website
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
*/

/**
 * This plugin allows users to construct a single-page website in which they can simply add and delete buttons, create any type of customized section, and do so all in one go in just a few minutes.
 * Additionally, the user can edit the data as needed, and with CSS, the entire website can be designed without the need for a coder. The plugin includes features like as creating a custom button, removing a button, and restoring the layout's full responsiveness.
 * Pre-made page templates and buttons are also included in the plugin to help you get started with any type of website.
 *
 *  Singlepage Website is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 *  Singlepage Website is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

if (!defined('WPINC')) {
  die;
}

//activation hook of singlepage
register_activation_hook(__FILE__, 'createpage');
  function createpage()
  {
    singlepagedefault_options();
    $page_title = 'One Page'; 
    if(get_page_by_title($page_title) == NULL) 
    { 
        $product = array( 'post_title' => $page_title, 'post_status'=> 'publish', 'post_type' => 'page' );
        $insert_page = wp_insert_post($product);
    } 
  }

  register_activation_hook( __FILE__, 'singlepage_activation_actions');
  function singlepage_activation_actions(){
    do_action( 'singlepage_website_extension_activation' );
  }
  add_action( 'singlepage_website_extension_activation', 'write_ps_default_options' );

//add menu
function Singlepage_menu() {
  add_menu_page('Singlepage', 'Singlepage','manage_options', 'singlepagewebsite', 'singlepage_plugin_func',plugins_url('/assets/img/icon.png', __FILE__ ));
  add_submenu_page('singlepagewebsite','Singlepage Setting ', 'Singlepage Setting','manage_options', 'singlepage-setting', 'singlepagesetting_function');
}
add_action('admin_menu','Singlepage_menu');

//call page 
function singlepage_plugin_func()
{
    include 'singledata/singlepagedata.php';
}

//create a custom template page
  add_filter( 'theme_page_templates', 'add_singlepage_template_to_dropdown' );
  function add_singlepage_template_to_dropdown($templates)
  {
  $templates['templates/singlepagetemplate.php'] = __('One Page', 'text-domain');
  return $templates;
  }
  add_filter('template_include', 'change_singlepage_template', 99);
  function change_singlepage_template($template)
  {
        $post_id = get_the_ID();
        $post = get_post($post_id);
        $slug = $post->post_name;
        if (is_page() && $slug=='one-page')
        {
          $meta = get_post_meta(get_the_ID());
          if (!empty($meta['_wp_page_template'][0]) && $meta['_wp_page_template'][0] != $template)
          {
           $template = dirname( __FILE__ ) .'/'.$meta['_wp_page_template'][0];
          }
        }
      return $template;
  } 
function singlepage_db()
{
    global $wpdb;
    $table = $wpdb->prefix.'onepagedata';
    $sql = "CREATE TABLE IF NOT EXISTS $table(`id` INT NOT NULL AUTO_INCREMENT,`title` VARCHAR(255) NOT NULL ,`desc_editor` LONGTEXT NOT NULL,`checkmenu` VARCHAR(255) NOT NULL,`ordering` INT(255) NOT NULL,  PRIMARY KEY (`id`)) ENGINE = MyISAM;";
    require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
    $sql = "ALTER TABLE $table ADD PRIMARY KEY (`id`)";
    $wpdb->query($sql);
    $sql = "ALTER TABLE $table MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1";
    $wpdb->query($sql);

    $result = $wpdb->get_results("SELECT title FROM $table ORDER BY ordering ASC");
    if(count($result) == 0 ){     
    $home = '<div class=container><div class=row><div class="col-md-6 py-6"><h1 class="fs-8 fw-bold mb-4">The Design Thinking Superpowers</h1><p class="lead mb-6 text-secondary">Tools tutorials, design and innovation experts, all<br class="d-none d-xl-block">in one place! The most intuitive way to imagine<br class="d-none d-xl-block">your next user experience.<div class="banner-btn text-center text-md-start"><a class="btn btn-lg btn-warning me-3"href=# role=button>Connect Wallet</a><a class="btn btn-link fw-medium text-warning"href=# role=button data-bs-target=#popupVideo data-bs-toggle=modal><i aria-hidden=true class="fa-solid fa-circle-play"style=margin-right:10px></i>Watch the video</a></div></div><div class="col-md-6 text-end"><img alt=""class="img-fluid pt-7 pt-md-0"src="'.  plugin_dir_url( dirname( __FILE__ ) ).'singlepage/assets/img/hero/hero-img.png'.'"></div></div></div>';
    $result_check = $wpdb->insert($table, array(
    'title' => 'Home',
    'desc_editor' =>  $home,
    'checkmenu' => 0,
    'ordering' => 1
    ));
  $about = '<div class="bg-holder z-index--1 bottom-0 d-lg-block" style="background-image:url("'.plugin_dir_url( dirname( __FILE__ ) ).'singlepage/assets/img/user.png'.'");opacity:.5;">
  </div><div class=container><h1 class="text-center fs-9 fw-bold mb-4">About</h1><div class=row><div class="text-center col-lg-12 col-sm-12 mb-2"><p class="mb-0 text-secondary">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?"</div></div>
  <div class="row text-center">
  <div class="col-md-2 col-sm-12 buddy"> <img src= "'.  plugin_dir_url( dirname( __FILE__ ) ).'singlepage/assets/img/user.png'.'"; ></div>
  <div class="col-md-2 col-sm-12 buddy"> <img src= "'.  plugin_dir_url( dirname( __FILE__ ) ).'singlepage/assets/img/user.png'.'"; ></div>
  <div class="col-md-2 col-sm-12 buddy"> <img src= "'.  plugin_dir_url( dirname( __FILE__ ) ).'singlepage/assets/img/user.png'.'"; ></div>
  <div class="col-md-2 col-sm-12 buddy"> <img src= "'.  plugin_dir_url( dirname( __FILE__ ) ).'singlepage/assets/img/user.png'.'"; ></div>
  <div class="col-md-2 col-sm-12 buddy"> <img src= "'.  plugin_dir_url( dirname( __FILE__ ) ).'singlepage/assets/img/user.png'.'"; ></div>
  <div class="col-md-2 col-sm-12 buddy"> <img src= "'.  plugin_dir_url( dirname( __FILE__ ) ).'singlepage/assets/img/user.png'.'"; ></div>
</div>
</div>'; 
$result_check = $wpdb->insert($table, array(
 'title' => 'About',
 'desc_editor' =>  $about,
 'checkmenu' => 1,
 'ordering' => 2
 
));
$roadmap = '<div class=container><div class=row><div class=row><h1 class="text-center fs-9 fw-bold mb-4">Roadmap</h1><div class="text-center col-lg-12 col-sm-12 mb-2"><p class="mb-0 text-secondary">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate pariatur?"</div></div></div></div>';  
$result_check = $wpdb->insert($table, array(
  'title' => 'Roadmap',
  'desc_editor' =>  $roadmap,
  'checkmenu' => 1,
  'ordering' => 3
  
 )); 
$team = '<div class=container><h2 class="fw-bold fs-9 mb-4 text-center">TEAM</h2><div class=row><div class="mb-4 text-center col-md-4"><div class=card><img alt=""class=card-img-top src="'.  plugin_dir_url( dirname( __FILE__ ) ).'singlepage/assets/img/user.png'.'"><div class="card-body ps-0"><h3 class=fw-bold>VIKKI</h3><p class=text-secondary><span class=ms-1>Web Designer</span></div></div></div><div class="mb-4 text-center col-md-4"><div class=card><img alt=""class=card-img-top src="'.  plugin_dir_url( dirname( __FILE__ ) ).'singlepage/assets/img/user.png'.'"><div class="card-body ps-0"><h3 class=fw-bold>MRUDANG</h3><p class=text-secondary><span class=ms-1>Web Designer</span></div></div></div><div class="mb-4 text-center col-md-4"><div class=card><img alt=""class=card-img-top src="'.  plugin_dir_url( dirname( __FILE__ ) ).'singlepage/assets/img/user.png'.'"><div class="card-body ps-0"><h3 class=fw-bold>ROBOT</h3><p class=text-secondary><span class=ms-1>Java Developer</span></div></div></div></div></div>';  
$result_check = $wpdb->insert($table, array(
  'title' => 'Team',
  'desc_editor' =>  $team,
  'checkmenu' => 1,
  'ordering' => 4
 )); 

}
}  
//delete function
function singlepage_ajax_deletedata()
{
  global $wpdb;
  if (isset($_POST['id']))
  {
      $id =intval($_POST['id']);
      $table=$wpdb->prefix.'onepagedata';
      $row_id= $wpdb->get_results('SELECT * FROM '.$table.' where id="'.$id.'"');
      $removefromdb= $wpdb->delete( $table, array('id' => $id));  
      return $removefromdb;
  }
}
add_action( "wp_ajax_deletedata", "singlepage_ajax_deletedata" );

add_action( "wp_ajax_nopriv_deletedata", "singlepage_ajax_deletedata" );
//add fields
function singlepage_settings_init() {
  register_setting( 'ps-setting', 'ps_settings' );
  register_setting("ps-setting", "logo", "singlepagehandlelogo_upload");
  add_settings_section('ps-plugin-section', __( 'Singlepage Settings', 'ps-plugin' ), 'singlepage_settings_section_callback', 'ps-setting' );
  add_settings_field( 'singlepagesetting_logo',    __( ' Logo:', 'ps-plugin' ), 'singlepagesetting_logo', 'ps-setting', 'ps-plugin-section' );
  add_settings_field( 'singlepagefacebook_url',    __( ' Facebook icon url:', 'ps-plugin' ), 'singlepagefacebook_url', 'ps-setting', 'ps-plugin-section' );
  add_settings_field( 'singlepagelinkedin_url', __( 'LinkedIn icon url:', 'ps-plugin' ), 'singlepagelinkedin_url', 'ps-setting', 'ps-plugin-section' );
  add_settings_field( 'singlepagetwitter_url', __( ' Twitter icon url:', 'ps-plugin' ), 'singlepagetwitter_url', 'ps-setting', 'ps-plugin-section' );
  add_settings_field( 'singlepagegithub_url', __( ' GitHub icon url:', 'ps-plugin' ), 'singlepagegithub_url', 'ps-setting', 'ps-plugin-section' );
  add_settings_field( 'singlepagepinterest_url', __( ' Pinterest icon url:', 'ps-plugin' ), 'singlepagepinterest_url', 'ps-setting', 'ps-plugin-section' );
  add_settings_field( 'singlepageinstagram_url', __( ' Instagram icon url:', 'ps-plugin' ), 'singlepageinstagram_url', 'ps-setting', 'ps-plugin-section' );
  add_settings_field( 'singlepagerightbtn_url',    __( 'Right button text and url:', 'ps-plugin' ), 'singlepagerightbtn_url', 'ps-setting', 'ps-plugin-section' );
  add_settings_field( 'singlepagecopytext_url',    __( 'Copyright text and url:', 'ps-plugin' ), 'singlepagecopytext_url', 'ps-setting', 'ps-plugin-section' );
  add_settings_field( 'singlepageadditionalcss_url',    __( ' Additional css:', 'ps-plugin' ), 'singlepageadditionalcss_url', 'ps-setting', 'ps-plugin-section' );
}
add_action( 'admin_init', 'singlepage_settings_init' );
function singlepage_settings_section_callback( ) {?>
<div class="ps-setting-notice-block">
    <?php 
		 	_e('<h3>Dear user, You can change below settings:</h3>Image logo size must be in 200x40.</br>You can change or hide social media links.</br>You can customize your desgin through css.');
		 ?>
</div>
<?php
}
//image logo callback
function singlepagesetting_logo(){
  $options = get_option( 'ps_settings' ); ?>
  <img src="<?php echo  esc_html( get_option('logo')); ?> " id="preview" width='100px' height='auto'>
  <input type="hidden" name="old_logo" value="<?php echo esc_html(get_option('logo')); ?>">
  <input type='file' id="uploader" name='logo' placeholder="Please Enter Logo " >
  <script>
  var uploader = document.getElementById('uploader');
  uploader.addEventListener('change', (event) => {
    var binaryData = [];
    binaryData.push(document.getElementById('uploader').files);
    if (binaryData) preview.src = window.URL.createObjectURL(binaryData[0][0]);
   });
  </script> 
  <?php 
}

function singlepagehandlelogo_upload($option)
{
if(!empty($_FILES["logo"]["tmp_name"]))
{
$urls = wp_handle_upload($_FILES["logo"], array('test_form' => FALSE));
$temp = $urls["url"];
return $temp;
}
else{
  $temp = sanitize_url($_POST['old_logo']);

return $temp;
}
return $option;
}

function singlepagefacebook_url()
  {
    $checked = '';
    $options = get_option( 'ps_settings' ); 
    if(!empty($options["chk"])){
    $checked = 'checked';
    }
    ?>
    <input type="checkbox" name="ps_settings[chk]" value="1" <?php echo esc_html( $checked);?>>
    <input type='text' name='ps_settings[facebook_url]' value='<?php echo esc_url($options["facebook_url"]); ?>'>
    <?php
  }
function singlepagelinkedin_url()
  {
    $checked = '';
    $options = get_option( 'ps_settings' ); 
    if(!empty($options["chk1"])){
    $checked = 'checked';
    }
    ?>
    <input type="checkbox" name="ps_settings[chk1]" value="1" <?php echo esc_html($checked);?>>
    <input type='text' name='ps_settings[linkedin_url]' value='<?php echo esc_url($options["linkedin_url"]); ?>'>
    <?php
  }
function singlepagetwitter_url()
  {
    $checked = '';
    $options = get_option( 'ps_settings' ); 
    if(!empty($options["chk2"])){
    $checked = 'checked';
    }
    ?>
    <input type="checkbox" name="ps_settings[chk2]" value="1" <?php echo esc_html($checked)?>>
    <input type='text' name='ps_settings[twitter_url]' value='<?php echo esc_url($options["twitter_url"]); ?>'> <?php
  }
function singlepagegithub_url()
  {
    $checked = '';
    $options = get_option( 'ps_settings' ); 
    if(!empty($options["chk3"])){
    $checked = 'checked';
    }
    ?>
    <input type="checkbox" name="ps_settings[chk3]" value="1" <?php echo esc_html($checked);?>>
    <input type='text' name='ps_settings[github_url]' value='<?php echo esc_url($options["github_url"]); ?>'> <?php
  }
function singlepagepinterest_url()
  {
    $checked = '';
    $options = get_option( 'ps_settings' ); 
    if(!empty($options["chk4"])){
    $checked = 'checked';
    }
    ?>
    <input type="checkbox" name="ps_settings[chk4]" value="1" <?php echo esc_html($checked);?>>
    <input type='text' name='ps_settings[pinterest_url]' value='<?php echo esc_url($options["pinterest_url"]); ?>'> <?php
  }
function singlepageinstagram_url()
  {
    $checked = '';
    $options = get_option( 'ps_settings' ); 
    if(!empty($options["chk5"])){
    $checked = 'checked';
    }
    ?>
    <input type="checkbox" name="ps_settings[chk5]" value="1" <?php echo esc_html($checked);?>>
    <input type='text' name='ps_settings[instagram_url]' value='<?php echo esc_url($options["instagram_url"]); ?>'> <?php
  }
function singlepagecopytext_url()
  {
  $checked = '';
  $options = get_option( 'ps_settings' );
  if(!empty($options["chk6"])){
    $checked = 'checked';
  }
  ?>
    <input type="checkbox" name="ps_settings[chk6]" value="1" <?php echo esc_html($checked);?>>
    <input type='text' name='ps_settings[ps_text_btn]' value='<?php echo esc_html(!empty($options["ps_text_btn"])) ?( esc_attr($options["ps_text_btn"]) ): '' ; ?>'>
    <input type='text' name='ps_settings[copytext_url]' value='<?php echo esc_url(!empty($options["copytext_url"]))?( esc_url(($options["copytext_url"])) ): '' ; ?>'> 
    <?php
  }
?>
<?php
 function singlepageadditionalcss_url()
  { 
    $options = get_option( 'ps_settings' ); ?>
    <textarea name='ps_settings[additionalcss_url]' rows="10"
    cols="70"><?php echo esc_url(trim($options["additionalcss_url"]));?></textarea>
    <?php
  }

function singlepagerightbtn_url()
  {
    $checked = '';
    $options = get_option( 'ps_settings'); 
    if(!empty($options["chk7"])){
    $checked = 'checked';
    }
    ?>
    <input type="checkbox" name="ps_settings[chk7]" value="1" <?php echo esc_html($checked);?>>
    <input type='text' name='ps_settings[ps_text_rightbtn]' value='<?php echo esc_html(!empty($options["ps_text_rightbtn"])) ?( esc_attr($options["ps_text_rightbtn"]) ): '' ;?>'> 
    <input type='text' name='ps_settings[rightbtn_url]' value='<?php echo esc_url(!empty($options["rightbtn_url"]))?( esc_url($options["rightbtn_url"]) ): '' ;?>'> 
    <?php
  } 

function singlepagesetting_function()
{ ?>
  <div class="ps-onepage">
  <?php settings_errors(); ?>
  <form action='options.php' method='post' enctype="multipart/form-data"> <?php
        settings_fields( 'ps-setting' );
        do_settings_sections( 'ps-setting' );
        submit_button(); ?>
  </form>

  </div> 
  <?php
}
//default page data
function singlepagedefault_options()
{
  singlepage_db();
  update_option( 'logo',  plugin_dir_url( dirname( __FILE__ ) ) . 'singlepage/assets/img/logo.png');
  
  $default = array(
  'chk' => '1',
  'facebook_url'=>'#',
  'chk1' => '1',
  'linkedin_url'=>'#',
  'chk2' => '1',
  'twitter_url'=>'#',
  'chk3' => '1',
  'github_url'=>'#',
  'chk4' => '1',
  'pinterest_url'=>'#',
  'chk5' => '1',
  'instagram_url'=>'#',
  'chk6' => '1',
  'ps_text_btn'=>'© 2022 All Rights Reserved.',
  'copytext_url'=>'#',
  'chk7' => '1',
  'ps_text_rightbtn'=>'Contact US',
  'rightbtn_url'=>'#',
  );
  update_option( 'ps_settings', $default );
} 

//call backend custom css
function singlepagejs_css()
{
  wp_enqueue_style('ps_admin_css', plugins_url( 'assets/css/ps-admin.css', __FILE__ ), array(),'1.0','all');
  wp_enqueue_style('bootstrap__min_css', plugins_url( 'assets/css/bootstrap.min.css', __FILE__ ), array(),'1.0','all');
  wp_enqueue_style('fontawsome_min_css', plugins_url( 'assets/fonts/sss-font-awesome/css/font-awesome.min.css', __FILE__ ), array(),'1.0','all');
  wp_enqueue_script('jquery_validation', plugins_url('assets/js/jquery.validate.min.js',__FILE__ ), array(),'1.0','all');
}
add_action( 'admin_enqueue_scripts', 'singlepagejs_css');

//custom frontend css
function frontendcss_js()
{
  wp_enqueue_style('all', plugins_url( 'assets/css/all.css', __FILE__ ), array(),'1.0','all'); 
  wp_enqueue_style('fontawsome', plugins_url( 'assets/css/fontawesome.css', __FILE__ ), array(),'1.0','all');
  wp_enqueue_style('style', plugins_url( 'assets/css/style.css', __FILE__ ), array(),'1.0','all'); 
  wp_enqueue_style('stylesheet', plugins_url( 'assets/css/stylesheet.css', __FILE__ ), array(),'1.0','all');
  wp_enqueue_script('bootstrap-min', plugins_url('assets/js/bootstrap.min.js',__FILE__ ), array(),'1.0','all');

}

  add_action( 'wp_enqueue_scripts', 'frontendcss_js');

?>


 