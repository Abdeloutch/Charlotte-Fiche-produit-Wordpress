<?php

/**
 * The template for displaying the footer.
 *
 * Contains the body & html closing tags.
 *
 * @package HelloElementor
 */
if (! defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

if (! function_exists('elementor_theme_do_location') || ! elementor_theme_do_location('footer')) {
  if (hello_elementor_display_header_footer()) {
    if (did_action('elementor/loaded') && hello_header_footer_experiment_active()) {
      get_template_part('template-parts/dynamic-footer');
    } else {
      get_template_part('template-parts/footer');
    }
  }
}
?>

<script>
  document.addEventListener("DOMContentLoaded", () => {

    //Mettre dans l'ordre blocks pris et frais tech
    function reorderBlocks() {
      const wpcqBlock = document.querySelector(".wpcpq-wrap");
      const wpcpoBlock = document.querySelector(".wpcpo-wrapper");

      if (!wpcqBlock || !wpcpoBlock) return false;

      if (
        wpcpoBlock.compareDocumentPosition(wpcqBlock) &
        Node.DOCUMENT_POSITION_FOLLOWING
      ) {
        wpcqBlock.insertAdjacentElement("afterend", wpcpoBlock);
      }
      return true;
    }

    // Observer pour détecter l'arrivée des blocs
    const observer = new MutationObserver(() => {
      if (reorderBlocks()) {
        observer.disconnect();
      }
    });

    observer.observe(document.body, {
      childList: true,
      subtree: true,
    });


    //AFFICHAGE DU SELECT AUTOMATIQUEMENT POUR LES FRAIS TECHNIQUES
    const wpcpo_options_arrow = document.querySelector(".wpcpo-options .wpcpo-option-form select")
    if (wpcpo_options_arrow) {

      wpcpo_options_arrow.style.pointerEvents = "none";
      wpcpo_options_arrow.style.backgroundImage = "none"
      wpcpo_options_arrow.style.cursor = "default"

      const selectMarquage = document.getElementById('pa_marquage');
      const selectPrix = document.querySelector('.wpcpo-options .form-row select');

      // Map des valeurs du premier select vers le texte du second select
      const mapping = {
        "quadrichromie-iml": "Quadrichromie IML",
        "sans-marquage": "Sans Marquage",
        "serigraphie-1-couleur": "Sérigraphie 1 couleur",
        "serigraphie-2-couleurs": "Sérigraphie 2 couleurs"
      };

      function syncSelects() {
        const selectedValue = selectMarquage.value;
        const targetText = mapping[selectedValue];

        if (!targetText) return;

        Array.from(selectPrix.options).forEach(option => {
          option.selected = option.text.toLowerCase().includes(targetText.toLowerCase());
        });

        const event = new Event('change', {
          bubbles: true
        });
        selectPrix.dispatchEvent(event);
      }

      // Écoute quand l'utilisateur change le premier select
      selectMarquage.addEventListener('change', syncSelects);

      // ⚡ Force la synchronisation au chargement si une valeur est déjà présente
      if (selectMarquage.value !== "") {
        syncSelects();
      }
    }




    //AJOUTER PRIX DANS BTN PANIER
const addToCartBtn = document.querySelector(".single_add_to_cart_button");
if (!addToCartBtn) return;

// Fonction universelle pour parser les prix
function parsePrice(raw) {
    if (!raw) return 0;

    let str = raw
        .replace(/€/g, "")
        .replace(/\s/g, "")
        .trim();

    // Format FR classique : 1 031,40 → 1031.40
    if (/,/.test(str) && !/\.\d{3}$/.test(str)) {
        str = str.replace(",", ".");
    }

    // Format mixte US : 1,031.40 → 1031.40
    if (/,\d{3}\./.test(str)) {
        str = str.replace(/,/g, "");
    }

    // Format mixte inversé : 1.031,400 → 1031.400
    if (/\.\d{3},/.test(str)) {
        str = str.replace(/\./g, "").replace(",", ".");
    }

    return parseFloat(str) || 0;
}

function updateButtonPrice() {
    const wcpq = document.querySelector(
        ".elementor-widget-woocommerce-product-price .woocommerce-Price-amount.amount"
    );

    if (!wcpq) return;

    setTimeout(() => {
        let supplementValue = 0;

        // CAS 1 : select
        const wpcpo_select = document.querySelector(".wpcpo-option-form select");
        if (wpcpo_select) {
            supplementValue = Number(wpcpo_select.value) || 0;
        }

        // CAS 2 : label prix
        else {
            const wpcpo_label_price = document.querySelector(
                ".wpcpo-option-form label .woocommerce-Price-amount.amount"
            );

            if (wpcpo_label_price) {
                const labelText = wpcpo_label_price.textContent;
                supplementValue = parsePrice(labelText);
            }
        }

        // Prix principal
        const wcpqValue = parsePrice(wcpq.textContent);

        // Total
        const total = wcpqValue + supplementValue;

        // Format FR
        const formatted = total.toFixed(2).replace(".", ",") + " €";

        // Mettre à jour bouton
        addToCartBtn.textContent = `Ajouter au panier - ${formatted}`;
    }, 300);
}

// Exécution initiale
updateButtonPrice();

// Observateurs
const simpleContainer = document.querySelector(".wpcpo-total");
if (simpleContainer) {
    const obsSimple = new MutationObserver(updateButtonPrice);
    obsSimple.observe(simpleContainer, { childList: true, subtree: true, characterData: true });
}

const variableContainer = document.querySelector(".wpcpq-summary");
if (variableContainer) {
    const obsVariable = new MutationObserver(updateButtonPrice);
    obsVariable.observe(variableContainer, { childList: true, subtree: true, characterData: true });
}








function cleanWpcpoOptionText() {
    document.querySelectorAll(".wpcpo-option-form select option").forEach(opt => {
        let text = opt.textContent;

        // Supprime TOUT ce qui ressemble à "(+XX€)" en fin de texte
        text = text.replace(/\(\s*\+\s*[\d.,]+\s*€\s*\)\s*$/u, "");

        opt.textContent = text.trim();
    });
}

cleanWpcpoOptionText();

// Re-nettoyer si WP-CPO régénère le select
const wpcpoForm = document.querySelector(".wpcpo-option-form");
if (wpcpoForm) {
    const obs = new MutationObserver(cleanWpcpoOptionText);
    obs.observe(wpcpoForm, { childList: true, subtree: true });
}

  });
</script>
<?php wp_footer(); ?>



</body>

</html>