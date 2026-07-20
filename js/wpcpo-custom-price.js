document.addEventListener('DOMContentLoaded', () => {

    const select = document.querySelector(
        '.wpcpo-option-field[data-title="+ FRAIS TECHNIQUES"]'
    );

    if (!select) {
        return;
    }


    const optionContainer = select.closest('.wpcpo-option');

    if (!optionContainer) {
        return;
    }


    const display = document.createElement('div');
    display.className = 'wpcpo-frais-display';


    function updateDisplay() {

        const priceInput = document.querySelector(
            'input[name$="[price]"][name^="wpcpo-"]'
        );

        if (!priceInput) {
            display.textContent = '+ Frais techniques';
            return;
        }

        const price = priceInput.value;

        display.textContent = (!price)
            ? '+ Frais techniques'
            : `+ Frais techniques (+${price}€)`;
    }


    updateDisplay();

    optionContainer.after(display);


    // Surveillance des changements WPCPO
    const observer = new MutationObserver(() => {
        updateDisplay();
    });


    const priceInput = document.querySelector(
        'input[name$="[price]"][name^="wpcpo-"]'
    );


    if (priceInput) {

        observer.observe(priceInput, {
            attributes: true,
            attributeFilter: ['value']
        });

    }

});