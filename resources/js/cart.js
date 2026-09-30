const selectAll = document.getElementById('select-all');

const checkboxes = document.querySelectorAll('.item-checkbox');

const selectedCountDisplays = document.querySelectorAll(
    '#selected-count-mobile, #selected-count-desktop'
);

const cartTotalDisplays = document.querySelectorAll(
    '#cart-total-mobile, #cart-total-desktop'
);



// Select or deselect all items.
if (selectAll) {

    selectAll.addEventListener('change', function () {

        checkboxes.forEach(checkbox => {

            if (checkbox.classList.contains('out-of-stock-checkbox')){
                return;
            }
            checkbox.checked = selectAll.checked;

        });

        updateTotal();

    });

}


// Handle individual item selection.
checkboxes.forEach(checkbox => {

    checkbox.addEventListener('change', function () {

        const checkedItems = document.querySelectorAll(
            '.item-checkbox:checked'
        );
        const availableItems = document.querySelectorAll(
            '.item-checkbox:not(.out-of-stock-checkbox)'
        );

        selectAll.checked = availableItems.length > 0 &&
        checkedItems.length === availableItems.length

        updateTotal();

    });

});


// Recalculate total when quantity changes.
document.querySelectorAll('.item-quantity-input').forEach(quantity => {

    quantity.addEventListener('input', updateTotal);

});


// Set the correct delete URL when an item is selected.
document
    .querySelectorAll('.delete-cart-item')
    .forEach(button => {

        button.addEventListener('click', function () {

            const deleteUrl = this.dataset.deleteUrl;

            const deleteForm =
                document.getElementById('delete-cart-form');

            deleteForm.action = deleteUrl;

        });

    });

document.querySelectorAll('.item-quantity-input')
    .forEach(quantity => {
        quantity.addEventListener('change', function () {
            updateQuantity(this);
        });

    });


// Calculate initial total.
updateTotal();

// Calculate and display the selected cart total.
function updateTotal() {

    let total = 0;
    let checkedCount = 0;

    checkboxes.forEach((checkbox) => {

        if (!checkbox.checked) {
            return;
        }

        checkedCount++;

        const cartItem = checkbox.closest(
            '.list-group-item-container'
        );

        const price = parseFloat(
            cartItem.querySelector('.item-price-display').dataset.rawPrice
        );

        const quantity = parseInt(
            cartItem.querySelector('.item-quantity-input').value
        ) || 1;

        total += price * quantity;

    });


    cartTotalDisplays.forEach(display => {

        display.innerText = total.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

    });


    selectedCountDisplays.forEach(display => {

        display.innerText = checkedCount;

    });

}

async function updateQuantity(quantityInput) {

    const productId = quantityInput.dataset.productId;
    const quantity = quantityInput.value;
    const updateUrl = quantityInput.dataset.updateUrl;

    const response = await fetch(updateUrl, {

        method: 'PATCH',

        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute('content')
        },

        body: JSON.stringify({
            product_id: productId,
            quantity: quantity
        })

    });

    const data = await response.json();

    if (!response.ok) {
        showCartMessage(data.message);
        return;
    }

    console.log(data);

    updateTotal();
}
function showCartMessage(message) {
    const container = document.querySelector('#cart-message');

    container.innerHTML = `
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            ${message}
            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close"
            ></button>
        </div>
    `;
}
