<?php

global $wpdb;

$results = $wpdb->get_results( "SELECT * FROM wp_posts WHERE ID = '".$postId."'" );
foreach($results as $res){
	$content=$res->post_content;
	$shotcontent=explode("[spoonacular-ingredient-visualizer-", $content);
	$shotcontentn=explode("]", $shotcontent[1]);

// nothing found? use price
	if (strlen($shotcontentn[0])==0) {
		$shotcontent=explode("[spoonacular-price-visualizer-", $content);
		$shotcontentn=explode("]", $shotcontent[1]);
	}

// nothing found? use nutrition
	if (strlen($shotcontentn[0])==0) {
		$shotcontent=explode("[spoonacular-nutrition-visualizer-", $content);
		$shotcontentn=explode("]", $shotcontent[1]);
	}

	$newresults = $wpdb->get_results( "SELECT * FROM wp_posts WHERE ID = '".$shotcontentn[0]."'" );
	foreach($newresults as $newres){
		$newcontent=$newres->post_content;
		$maincontent1=explode('<pre id="spoonacular-ingredients" style="display:none">', $newcontent);
		$maincontent2=explode("</pre>", $maincontent1[1]);
		$nutriserv=explode('var spoonacularServings =', $newcontent);
		$nutriserv2=explode(";", $nutriserv[1]);

// var_dump($ingrdaserv2);

	}
}
?>
<style>
	#siv-form label {
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
<form data-abide="" id="siv-form" action="#" accept-charset="utf-8" class="panel" method="post" novalidate="novalidate">
	<div class="input textarea">
		<label>Ingredients</label>
		<textarea id="ingredientArea-1" name="ingredients" style="height:260px;width: 100%" placeholder="one ingredient per line, such as &quot;200 grams of cucumber&quot;" aria-invalid="false"><?php echo $maincontent2[0];?></textarea>
	</div>
	<div class="input text">
		<label>Servings</label>
		<input id="servings-1" name="servings" type="text" style="width:60px" value="<?php echo trim($nutriserv2[0]);?>" aria-invalid="false" placeholder="e.g. 2">
	</div>
	<div class="input text">
		<label>View</label>
		<div id="spoonacularIngredientView" class="spoonacular-switch spoonacular-metro" style="width:130px">
			<input id="spoonacular-grid" name="view" type="radio" checked="" value="grid" aria-invalid="false">
			<label for="spoonacular-grid" onClick="" style="color:#fff;font-weight:normal;">grid</label>
			<input id="spoonacular-list" name="view" type="radio" value="list" aria-invalid="false">
			<label for="spoonacular-list" onClick="" style="color:#fff;font-weight:normal;">list</label>
			<span class="slide-button"></span>
		</div>
	</div>
	<div class="input text">
		<label>Measure</label>
		<div id="spoonacularMeasure" class="spoonacular-switch spoonacular-metro" style="width:130px">
			<input id="spoonacular-us" name="measure" type="radio" checked="" value="us" aria-invalid="false">
			<label for="spoonacular-us" onClick="" style="color:#fff;font-weight:normal;">US</label>
			<input id="spoonacular-metric" name="measure" type="radio" value="metric" aria-invalid="false">
			<label for="spoonacular-metric" onClick="" style="color:#fff;font-weight:normal;">metric</label>
			<span class="slide-button"></span>
		</div>
	</div>
	<div class="button blue right" onClick="generateIngredientVisualizer();generateIngredient();return false;" style="color: #fff;">Generate the Ingredient Visualizer</div>
	<div class="clearOnly"></div>
	<iframe id="previewWidget-1"></iframe>
	<div id="codeResult-1">
		<div class="input textarea" style="display:none;">
			<label for="spoonacularCode">Code:</label>

			<textarea id="spoonacularCode" style="height:360px;width:690px;" aria-invalid="false"></textarea>
		</div>
		<div id="shortcode_return-1"></div>
	</div>
</form>