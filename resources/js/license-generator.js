function calculateHotcoin() {
    const codeTypeSelect = document.getElementById('code_type');
    const codeNumberInput = document.getElementById('code_number');
    const needHotcoinInput = document.getElementById('need_hotcoin');

    if (!codeTypeSelect || !codeNumberInput || !needHotcoinInput) {
        return;
    }

    const selectedOption = codeTypeSelect.options[codeTypeSelect.selectedIndex];

    if (selectedOption && selectedOption.value && selectedOption.hasAttribute('data-cost')) {
        const cost = parseFloat(selectedOption.getAttribute('data-cost')) || 0;
        let number = parseInt(codeNumberInput.value) || 1;

        // Enforce a minimum of 1
        if (number < 1) {
            number = 1;
            codeNumberInput.value = 1;
        }

        const totalPrice = cost * number;
        needHotcoinInput.value = totalPrice.toFixed(2);
    } else {
        needHotcoinInput.value = '0.00';
    }
}

function initializeCalculator() {
    const codeTypeSelect = document.getElementById('code_type');
    const codeNumberInput = document.getElementById('code_number');

    if (!codeTypeSelect || !codeNumberInput) {
        return;
    }

    document.body.addEventListener('change', function(event) {
        if (event.target.id === 'code_type') {
            calculateHotcoin();
        }
    });

    codeNumberInput.addEventListener('input', calculateHotcoin);

    // Also add a 'blur' event to catch cases where the user leaves the input
    codeNumberInput.addEventListener('blur', calculateHotcoin);

    setTimeout(function() {
        calculateHotcoin();
    }, 500);
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeCalculator);
} else {
    initializeCalculator();
}
