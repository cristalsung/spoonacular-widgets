<?php

global $wpdb;
$results = $wpdb->get_results( "SELECT * FROM wp_posts WHERE ID = '".$postId."'" );
foreach($results as $res){
	$content=$res->post_content;

//// get the ingredients
	$shotcontent=explode("[spoonacular-nutrition-visualizer-", $content);
	$shotcontentn=explode("]", $shotcontent[1]);

// nothing found? use ingredients
	if (strlen($shotcontentn[0])==0) {
		$shotcontent=explode("[spoonacular-ingredient-visualizer-", $content);
		$shotcontentn=explode("]", $shotcontent[1]);
	}

// nothing found? use price
	if (strlen($shotcontentn[0])==0) {
		$shotcontent=explode("[spoonacular-price-visualizer-", $content);
		$shotcontentn=explode("]", $shotcontent[1]);
	}

	$newresults = $wpdb->get_results( "SELECT * FROM wp_posts WHERE ID = '".$shotcontentn[0]."'" );
	foreach($newresults as $newres){
		$newcontent=$newres->post_content;
		$maincontent1=explode('<pre id="spoonacular-ingredients" style="display:none">', $newcontent);
		$maincontent2=explode("</pre>", $maincontent1[1]);
		$nutriserv=explode('var spoonacularServings =', $newcontent);
		$nutriserv2=explode(";", $nutriserv[1]);
	}
}
?>

<style>
	#snv-form label {
		margin-bottom: 4px;
		display:block;
		font-weight:bold;
	}
	.button {
		border: none !important;
		border-style: none !important;
		border-radius: 0px !important;
		-webkit-border-radius: 0px !important;
	}
	#page {
		margin: inherit !important;
		width: inherit !important;
	}
	#tabs-container {
		max-width: 580px !important;
		margin-left: 5px !important;
		width: 94% !important;	
	}
</style>

<form id="snv-form" action="#" accept-charset="utf-8" class="panel" method="post">
	<div class="input textarea">
		<label>Ingredients</label>
		<textarea id="ingredientArea-3" name="ingredients" style="height:260px;width:100%;" placeholder="one ingredient per line, such as &quot;200 grams of cucumber&quot;"><?php echo $maincontent2[0];?></textarea>
	</div>
	<div class="input text">
		<label>Servings</label>
		<input id="servings-3" name="servings" type="text" style="width:60px" value="<?php echo trim($nutriserv2[0]);?>" placeholder="e.g. 2">
	</div>
	<div class="button blue right" onclick="generateNutritionVisualizer();generateNutrition();return false;" style="color: #fff;">Generate the Nutrition Visualizer</div>
	<div class="clearOnly"></div>
	<iframe id="previewWidget-3"></iframe>
	<div id="codeResult-3">
		<div class="input textarea" style="display:none;">
			<label for="spoonacularCode">Code:</label>
			<textarea id="spoonacularCode" style="height:360px;width:690px;"></textarea>
		</div>
		<div id="shortcode_return-3"></div>
	</div>

</form>