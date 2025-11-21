// document.addEventListener("DOMContentLoaded", () => {
//   const addToCartBtn = document.querySelector(".single_add_to_cart_button");
//   if (!addToCartBtn) return;

//   function updateButtonPrice() {
//     const wcpq = document.querySelector(
//       ".elementor-widget-woocommerce-product-price .woocommerce-Price-amount.amount"
//     );

//     if (wcpq) {
//       setTimeout(() => {
//         const wpcpo_supplement = document.querySelector(
//           ".wpcpo-option-form label .woocommerce-Price-amount.amount"
//         );
//         if (!wpcpo_supplement) return;

//         const subtotalText = wpcpo_supplement.textContent
//           .trim()
//           .replace("€", "")
//           .trim();
//         const wcpqText = wcpq.textContent.trim().replace("€", "").trim();

//         // Convertir correctement en nombre
//         const supplementValue = parseFloat(subtotalText.replace(",", "."));
//         const wcpqValue = parseFloat(wcpqText.replace(",", "."));

//         const result = supplementValue + wcpqValue;

//         // Reformater en version FR avec virgule
//         const total_price = result.toFixed(3).replace(".", ",") + " €";

//         addToCartBtn.textContent = `Ajouter au panier - ${total_price}`;
//       }, 300);

//       return;
//     }
//   }

//   // Mise à jour au chargement
//   updateButtonPrice();

//   // 🔹 OBSERVATEUR PRODUIT SIMPLE
//   const simpleContainer = document.querySelector(".wpcpo-total");
//   if (simpleContainer) {
//     const obsSimple = new MutationObserver(updateButtonPrice);
//     obsSimple.observe(simpleContainer, {
//       childList: true,
//       subtree: true,
//       characterData: true,
//     });
//   }

//   // 🔹 OBSERVATEUR PRODUIT VARIABLE
//   const variableContainer = document.querySelector(".wpcpq-summary");
//   if (variableContainer) {
//     const obsVariable = new MutationObserver(updateButtonPrice);
//     obsVariable.observe(variableContainer, {
//       childList: true,
//       subtree: true,
//       characterData: true,
//     });
//   }





// });

