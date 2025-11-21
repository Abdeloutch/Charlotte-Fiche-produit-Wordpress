<?php
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style(
        'hello-elementor-style',
        get_template_directory_uri() . '/style.css'
    );

    wp_enqueue_style(
        'hello-elementor-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        ['hello-elementor-style'],
        wp_get_theme()->get('Version')
    );
    
      wp_enqueue_script(
        'add-to-cart-li', 
        get_stylesheet_directory_uri() . '/js/add-to-cart-li.js', 
        [], 
         time(), 
        true
    );
});


add_shortcode('afficher_marquage', function() {
    global $product;
    if (! $product) return '';

    $attribut = $product->get_attribute('pa_marquage'); 
    if ($attribut) {
        return '<p class="attribut-produit"><strong>TYPE DE MARQUAGE </strong> <br/>' . esc_html($attribut) . '</p>';
    }
    return '';
});



add_filter('woocommerce_add_to_cart_validation', function($passed, $product_id, $quantity) {

    // Vérification du marquage
    if (empty($_POST['attribute_pa_marquage'])) {
        return $passed;
    }

    $marquage = sanitize_text_field($_POST['attribute_pa_marquage']);

    // Mapping : marquage → texte attendu 
    $mapping = [
        "quadrichromie-iml"      => "Quadrichromie IML",
        "quadrichromie-iml-mini" => "Quadrichromie IML mini",
        "sans-marquage"          => "sans marquage",
        "serigraphie-1-couleur"  => "sérigraphie 1 couleur",
        "serigraphie-2-couleurs" => "sérigraphie 2 couleurs"
    ];

    if (!isset($mapping[$marquage])) {
        wc_add_notice("Sélection de marquage invalide.", "error");
        return false;
    }

    $expected_text = strtolower($mapping[$marquage]);

    // 3️⃣ Trouver la sélection WPCPO dynamique
    $selected_label = null;

    foreach ($_POST as $key => $value) {
        // Recherche d'un champ WPCPO dynamique : wpcpo-xxxx[value]
        if (preg_match('/^wpcpo\-[a-z0-9]+$/i', $key) && isset($value['value'])) {

            // Récupérer le texte choisi : data-label envoyé par le plugin
            if (isset($value['label'])) {
                $selected_label = strtolower($value['label']);
            }

            break;
        }
    }

    // Si aucun label récupéré → on laisse passer (sécurité)
    if (!$selected_label) {
        return $passed;
    }

    // 4️⃣ Vérification par texte : on compare le début ou contenant
    if (strpos($selected_label, $expected_text) === false) {
        wc_add_notice("Le frais technique sélectionné ne correspond pas au marquage choisi.", "error");
        return false;
    }

    return $passed;

}, 10, 3);


