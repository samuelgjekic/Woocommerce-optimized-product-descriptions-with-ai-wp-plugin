<?php
/*
Plugin Name: Sami Woocommerce AI Product Optimizer
Plugin URI:  https://yourwebsite.com/sami-optim
Description: An AI-powered WooCommerce product description optimizer.
Version:     1.0.0
Author:      Samuel Gjekic
Author URI:  https://
*/

require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/sami-woo-product-desc-opt/sami-woo-ai-settings-admin.php';

require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/sami-woo-product-desc-opt/sami-woo-ai-settings-admin.php';



/**
 * add_custom_button_under_editor
 *
 * @return void
 */
function add_custom_button_under_editor() {
    global $post;
    
    // Check if we are editing a product
    if ( 'product' !== $post->post_type ) {
        return;
    }
    
    ?>
    <div id="custom-description-button">
        <input type="button" id="replace-description-button" class="button" value="Create/Optimize description with AI" />
        <p>You can enter a small amount of data in the description box describing your product to the ai to create a new description or you can optimize your current description.</p>
        <div class="loading-spinner"></div>
    </div>
    <script>
        jQuery(document).ready(function($) {
            $('#replace-description-button').click(function() {
                // Show loading spinner
                $('.loading-spinner').addClass('show');
                
                // Get the current description text
                var currentDescription = $('#content_ifr').contents().find('body').html();
                
                // Send the current description text via AJAX to a PHP file
                $.post( '/wp-content/plugins/sami-woo-product-desc-opt/ajax.sami-woo-product-desc-opt.php', { sami_old_desc: currentDescription }, function(response) {
                    // Replace the description with the response from the PHP file
                    $('#content_ifr').contents().find('body').html(response);
                    
                    // Hide loading spinner
                    $('.loading-spinner').removeClass('show');
                });
            });
        });
    </script>
    <style>
        #custom-description-button { margin-top: 10px; position: relative; }
        .loading-spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: none;
        }
        .loading-spinner.show {
            display: block;
        }
        .loading-spinner::after {
            content: '';
            width: 20px;
            height: 20px;
            border: 2px solid #ccc;
            border-top: 2px solid #333;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            margin: auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
    <?php
}
add_action( 'edit_form_after_editor', 'add_custom_button_under_editor' );
