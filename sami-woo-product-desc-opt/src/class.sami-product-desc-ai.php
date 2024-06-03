<?php
use LLPhant\Chat\Enums\OpenAIChatModel;
use LLPhant\Chat\OpenAIChat;
use LLPhant\OpenAIConfig;
require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/sami-woo-product-desc-opt/vendor/autoload.php';

/**
 * SamiProductDescAi
 */
class SamiProductDescAi
{

    public $ai_client;

    private int $maxlength = 200;
    
    /**
     * __construct
     *
     * @param  mixed $modelid
     * @return void
     */
    public function __construct(int $modelid = 0)
    {
        // We get the key from the options storage of wordpress
        $api_key = get_option('sami_api_key');

        // Create a new config file to pass to the openaichat client
        $config = new OpenAIConfig();
        $config->apiKey = $api_key;
        if($modelid == 1)
        {
        $config->model = OpenAIChatModel::Gpt4Omni->getModelName();
        }
        else if($modelid == 0)
        {
        $config->model = OpenAIChatModel::Gpt35Turbo->getModelName();
        }

        $this->ai_client = new OpenAIChat($config);

        // We remove the unused variables with the key for security reasons
        unset($config);
        unset($api_key);

        // Set the max word limit for the ai
        $maxwords = get_option('sami_max_words');
        if($maxwords !== false){ $this->maxlength = $maxwords;}
    }
    
    
    /**
     * optimizeProductDescription
     *
     * @param  mixed $current_desc
     * @return string
     */
    public function optimizeProductDescription(string $current_desc): string
    {
        $systemMessage = 'Your job is to optimize product descriptions for woocommerce based on the data you recieve about the product or its current
        description. \\n You will only ever reply with the string that you are requested to make, so in this case you will only ever reply with the description of the product. \\n
        Never add any conversation into the returned message \\n You will also add HTML tags for a nicer all around look of the description such as <p> <b> <h1> <h2> etc. \\n
        the description should be rather long but not to long. Your max word count should be around:' . $this->maxlength . ' words! Its very important you follow this limit and dont be to far away from it and not exceed it to much
        \\n You should also take use of
        1-2 SEO Friendly keywords optimized for the specific product to increase SEO rankings';
        
        $this->ai_client->setSystemMessage($systemMessage); // Set the instructions for the ai
        
        $optimized_product_desc = $this->ai_client->generateText('here is the product desc i want you to optimize: ' . $current_desc);
   
        return $optimized_product_desc; // Return the new optimized product description
    }
}