<?php

global $wpdb;
$results = $wpdb->get_results( "SELECT * FROM wp_posts WHERE ID = '".$postId."'" );
foreach($results as $res){
	$content=$res->post_content;
	$shotcontent=explode("[spoonacular-price-visualizer-", $content);
	$shotcontentn=explode("]", $shotcontent[1]);

// nothing found? use ingredients
	if (strlen($shotcontentn[0])==0) {
		$shotcontent=explode("[spoonacular-ingredient-visualizer-", $content);
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
//var_dump($newcontent);
		$maincontent1=explode('<pre id="spoonacular-ingredients" style="display:none">', $newcontent);
		$maincontent2=explode("</pre>", $maincontent1[1]);
		$nutriserv=explode('var spoonacularServings =', $newcontent);
		$nutriserv=explode(";", $nutriserv[1]);

//var_dump($ingrdaserv2);
	}
}
?>

<style>
	#spe-form label {
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

<form id="spe-form" action="#" accept-charset="utf-8" class="panel" method="post">
	<div class="input textarea">
		<label>Ingredients</label>
		<textarea id="ingredientArea-2" name="ingredients" style="height:260px;width:100%;" placeholder="one ingredient per line, such as &quot;200 grams of cucumber&quot;"><?php echo $maincontent2[0]  ;?></textarea>
	</div>
	<div class="input text">
		<label>Servings</label>
		<input id="servings-2" name="servings" type="text" style="width:60px;" value="<?php echo trim($nutriserv[0]);?>" placeholder="e.g. 2">
	</div>
	<div class="input text">
		<label>View:</label>
		<div id="spoonacularPriceView" class="spoonacular-switch spoonacular-metro" style="width:130px">
			<input id="spoonacular-full" name="pview" type="radio" checked="" value="2">
			<label for="spoonacular-full" onclick="" style="color:#fff;font-weight:normal;">full</label>
			<input id="spoonacular-compact" name="pview" type="radio" value="1">
			<label for="spoonacular-compact" onclick="" style="color:#fff;font-weight:normal;">compact</label>
			<span class="slide-button"></span>
		</div>
	</div>
	<div class="button blue right" onclick="generatePriceEstimator();generatePrice();return false;" style="color: #fff;">Generate the Price Estimator</div>
	<div class="clearOnly"></div>
	<iframe id="previewWidget-2"></iframe>
	<div id="codeResult-2">
		<div class="input textarea" style="display:none;">
			<label for="spoonacularCode">Code:</label>
			<textarea id="spoonacularCode" style="height:360px;width:690px;"></textarea>
		</div>
		<div id="shortcode_return-2"></div>
	</div>
</form>