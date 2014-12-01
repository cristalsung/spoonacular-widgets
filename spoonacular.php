<?php
/*
*Plugin Name: spoonacular widgets
*Description: This plugin allows you to make your recipes stand out with pretty ingredient pictures, information about the recipe's cost, and in-depth nutritional information. Have a look at <a href="https://spoonacular.com/blog/spoonacular-widgets-for-wordpress">this blog post</a> if you need help to get started. <strong>NOTE: This plugin requires the <a href="https://wordpress.org/plugins/shortcodes-ui/">ShortCode UI plugin</a> activated</strong>.
*Version: 1.2.0
*Author: David Urbansky
*/
if(!class_exists('spoonacular')){

	
	function generateShortCode() {
		global $wpdb;

		$long_code1 = $_POST['code'];
		$code_type = $_POST['codeType'];
	    $long_code1 = preg_replace("/\<\/script\>.*?<script.*/is", "</script><script type=\"text/javascript\" src=\"".plugins_url('js/spoonacular-1.5.min.js', __FILE__ )."\"></script>", $long_code1);
	    $long_code1 = preg_replace('/\\\"/', '"', $long_code1);
	    $long_code1 = preg_replace("/\\\'/", '"', $long_code1);
	    $long_code = $long_code1;

	    $sql = "INSERT INTO $wpdb->dbname.$wpdb->posts (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES (NULL, '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '".mysql_real_escape_string($long_code)."', 'ShortCode', '', 'publish', 'open', 'open', '', 'spoonacular-widget', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '0', '', '0', 'ba_sh', '', '0');";

	    $r = $wpdb->query($sql);

	    if ($r == 1) {
	        $inserted_id = $wpdb->insert_id;
	        $sql_meta = "INSERT INTO  $wpdb->dbname.$wpdb->postmeta (`meta_id`, `post_id`, `meta_key`, `meta_value`) VALUES (NULL, '".$inserted_id."', '_bascsh_tag', '$code_type-$inserted_id');";
	        if ($wpdb->query($sql_meta) == 1) {
	            echo "[$code_type-".$inserted_id.']';
	        }
	    } else {
	        echo 'Error: WP_1, please contact wp@spoonacular.com for help.';
	    }

		die();
	}		
	add_action('wp_ajax_spoonacular_shortcode', 'generateShortCode');

	class spoonacular{
		function __construct(){
			add_action( 'plugins_loaded', array(&$this,'plugin_paths'));
			add_action( 'admin_enqueue_scripts', array(&$this,'theme_name_scriptsn'));
			add_action( 'add_meta_boxes', array(&$this, 'adding_custom_meta_boxes'));
		}
		function plugin_paths(){
			define('plugin_dir_path',dirname(__FILE__));
			define('plugin_url_path',plugins_url().'/'.basename(dirname(__FILE__)));
		}
		function theme_name_scriptsn() {
			wp_enqueue_script('jquery');
			
			wp_register_style('styles', plugin_url_path.'/css/styles.css' );	
			wp_enqueue_style('styles');

			wp_register_style('ingredientWidget', plugin_url_path.'/css/ingredientWidget.css');
			wp_enqueue_style('ingredientWidget');
			
			wp_register_style('nutritionWidget', plugin_url_path.'/css/nutritionWidget.css');
			wp_enqueue_style('nutritionWidget');
			
			wp_register_script('application', plugin_url_path.'/js/application.js');
			wp_enqueue_script('application');
			
		}
		function adding_custom_meta_boxes() {
			add_meta_box( 
				'my-meta-box',
				__( 'Spoonacular Widgets' ),
				array(&$this,'render_my_meta_box'),
				'post',
				'normal',
				'high'
				);
		}

		function render_my_meta_box(){
			?>

			<div id="page">
				<ul class="mytabs" id="tabs">
					<?php $postId=$_GET['post'];?>
					<li id="tabHead1" class="current"><a href="#" onclick="spoonacularShowTab(1);return false;">Ingredient Visualizer</a></li>
					<li id="tabHead2"><a href="#" onclick="spoonacularShowTab(2);return false;">Price Breakdown</a></li>
					<li id="tabHead3"><a href="#" onclick="spoonacularShowTab(3);return false;">Nutrition Chart</a></li>

				</ul>
				<div class="mytabs-container" id="tabs-container1">
					<?php include('template/ingredient.php'); ?>
				</div>
				<div class="mytabs-container" id="tabs-container2" style="display:none;">
					<?php include('template/price.php'); ?>
				</div>
				<div class="mytabs-container" id="tabs-container3" style="display:none;">
					<?php include('template/nutrition.php'); ?>
				</div>
			</div>

			<script>
				function generateIngredient(){
					var spoonacularCode = document.getElementById("spoonacularCode").value;

					var data = {
						action: 'spoonacular_shortcode',
						code: spoonacularCode,
						codeType: 'spoonacular-ingredient-visualizer'
					};

					jQuery.post(ajaxurl,data,function(result){
						jQuery("#shortcode_return-1").html("Just copy the following code in your post where you want the widget to appear: <b>"+result+"</b>");
					});
				}
			</script>	
			<script>
				function generatePrice(){
					var spoonacularCode = document.getElementById("spoonacularCode").value;

					var data = {
						action: 'spoonacular_shortcode',
						code: spoonacularCode,
						codeType: 'spoonacular-price-visualizer'
					};

					jQuery.post(ajaxurl,data,function(result){
						jQuery("#shortcode_return-2").html("Just copy the following code in your post where you want the widget to appear: <b>"+result+"</b>");
					});
				}
			</script>	
			<script>
				function generateNutrition(){
					var spoonacularCode = document.getElementById("spoonacularCode").value;

					var data = {
						action: 'spoonacular_shortcode',
						code: spoonacularCode,
						codeType: 'spoonacular-nutrition-visualizer',
					};

					jQuery.post(ajaxurl,data,function(result){
						jQuery("#shortcode_return-3").html("Just copy the following code in your post where you want the widget to appear: <b>"+result+"</b>");
					});
				}
			</script>

			<?php
		}

	}
	$spoonacular = new spoonacular();
}
?>