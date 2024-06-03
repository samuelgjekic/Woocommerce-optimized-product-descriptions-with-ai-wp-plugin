<?php
/**
 * display_api_key_settings
 *
 * @return void
 */
function display_api_key_settings() {
    if ( isset( $_POST['submit'] ) && check_admin_referer( 'api_key_settings_nonce_action', 'api_key_settings_nonce' ) ) {
        $api_key = sanitize_text_field( $_POST['api_key'] );
        update_option( 'sami_api_key', $api_key );

        // Save maximum word count
        $max_word_count = isset( $_POST['max_word_count'] ) ? absint( $_POST['max_word_count'] ) : 200;
        update_option( 'sami_max_words', $max_word_count );

        // Save AI model
        $ai_model = isset( $_POST['ai_model'] ) ? sanitize_text_field( $_POST['ai_model'] ) : 'gpt4-o';
        update_option( 'sami_ai_model', $ai_model );

        echo '<div class="updated"><p>Settings saved.</p></div>';
    }
    
    $api_key = get_option( 'sami_api_key', '' );
    $max_word_count = get_option( 'sami_max_words', 200 ); // Default value: 200
    $ai_model = get_option( 'sami_ai_model', 'gpt4-o' ); // Default value: 'gpt4-o'
    
    ?>
    <div class="wrap">
        <h1>Sami AI Settings</h1>
        <p>You can get your own OpenAI API Key <a href='https://openai.com' target="_blank"> here</a></p></div>
        <form method="post" action="">
            <?php wp_nonce_field( 'api_key_settings_nonce_action', 'api_key_settings_nonce' ); ?>
            
            <!-- OpenAI API Key -->
            <div class="sami-setting-row">
                <label for="api_key">OpenAI API Key:</label>
                <input type="password" name="api_key" id="api_key" value="<?php echo esc_attr( $api_key ); ?>" class="regular-text" />
            </div>
</br>
            
            <!-- Max Word Count -->
            <div class="sami-setting-row">
                <label for="max_word_count">Max Word Count:</label>
                <input type="number" name="max_word_count" id="max_word_count" value="<?php echo esc_attr( $max_word_count ); ?>" class="regular-text" min="1" />
            </div>
            </br>
            <!-- AI Model -->
            <div class="sami-setting-row">
                <label for="ai_model">AI Model:</label>
                <select name="ai_model" id="ai_model">
                    <!-- <option value="gpt3.5-turbo" <?php //selected( $ai_model, 'gpt3.5-turbo' ); ?>>GPT3.5 Turbo</option> -->
                    <option value="gpt4-o" <?php selected( $ai_model, 'gpt4-o' ); ?>>GPT4-O</option>
                </select>
            </div>

            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

add_action( 'admin_menu', 'add_woocommerce_submenu' );

/**
 * add_woocommerce_submenu
 *
 * @return void
 */
function add_woocommerce_submenu() {
    add_submenu_page(
        'woocommerce',
        'Sami AI Settings',
        'Sami AI Settings',
        'manage_woocommerce',
        'api-key-settings',
        'display_api_key_settings'
    );
}
?>
