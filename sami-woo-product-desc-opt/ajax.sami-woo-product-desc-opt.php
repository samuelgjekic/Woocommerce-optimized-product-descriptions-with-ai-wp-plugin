<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/sami-woo-product-desc-opt/src/class.sami-product-desc-ai.php';



if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $api_key = get_option('sami_api_key'); // Set api key to variable for validation
    if(!isset($_POST['sami_old_desc'])){ return; }
    if(!isset($api_key)){ return; } // Return if no API Key is found
    unset($api_key); // Delete this variable containing the api key.
     
    $modelid = 1;
    $model = get_option('sami_ai_model');
    if($model !== false){
        switch($model){
            case 'gpt4-o': $modelid = 1;
            case 'gpt3.5-turbo': $modelid = 0;
            default: $modelid = 1;
        }
    } else {
        $modelid = 1;
    }
    $client = new SamiProductDescAi($modelid);
   
    $old_desc = esc_html($_POST['sami_old_desc']);
    $old_desc = wp_strip_all_tags($old_desc); // Strip all old HTML Tags
    $_new_description = $client->optimizeProductDescription($old_desc);
    $test = get_option('sami_max_words');
    
    $new_description = wp_kses($_new_description,sami_allowed_html());
    echo $new_description;   
}

function sami_allowed_html() {

	$allowed_tags = array(
		'a' => array(
			'class' => array(),
			'href'  => array(),
			'rel'   => array(),
			'title' => array(),
		),
		'abbr' => array(
			'title' => array(),
		),
		'b' => array(),
		'blockquote' => array(
			'cite'  => array(),
		),
		'cite' => array(
			'title' => array(),
		),
		'code' => array(),
		'del' => array(
			'datetime' => array(),
			'title' => array(),
		),
		'dd' => array(),
		'div' => array(
			'class' => array(),
			'title' => array(),
			'style' => array(),
		),
		'dl' => array(),
		'dt' => array(),
		'em' => array(),
		'h1' => array(),
		'h2' => array(),
		'h3' => array(),
		'h4' => array(),
		'h5' => array(),
		'h6' => array(),
		'i' => array(),
		'img' => array(
			'alt'    => array(),
			'class'  => array(),
			'height' => array(),
			'src'    => array(),
			'width'  => array(),
		),
		'li' => array(
			'class' => array(),
		),
		'ol' => array(
			'class' => array(),
		),
		'p' => array(
			'class' => array(),
		),
		'q' => array(
			'cite' => array(),
			'title' => array(),
		),
		'span' => array(
			'class' => array(),
			'title' => array(),
			'style' => array(),
		),
		'strike' => array(),
		'strong' => array(),
		'ul' => array(
			'class' => array(),
		),
	);
	
	return $allowed_tags;
}