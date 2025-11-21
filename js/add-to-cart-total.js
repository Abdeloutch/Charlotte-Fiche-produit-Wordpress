// jQuery(document).ready(function($){

//     function majBouton() {
//         var wpcpo_price = $('.wpcpo-subtotal .woocommerce-Price-amount').first().parent().html();
//         if(wpcpo_price){
//             $('.single_add_to_cart_button').html('Ajouter au panier - ' + wpcpo_price);
//         }
//     }

//     // Mise à jour initiale
//     majBouton();

//     // Observer le DOM pour détecter les changements de total WPCPO
//     var target = document.querySelector('.wpcpo-subtotal');
//     if(target){
//         var observer = new MutationObserver(function(mutations) {
//             majBouton();
//         });
//         observer.observe(target, { childList: true, subtree: true, characterData: true });
//     }

//     // Pour les changements manuels (au cas où)
//     $(document).on('change', '.wpcpo-option-field', majBouton);
// });
