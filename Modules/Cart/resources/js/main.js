document.addEventListener('DOMContentLoaded', () => {
    function handleCartAction(url, method, data, button = null) {
        if (button) button.disabled = true;

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': data.csrfToken,
            },
            body: JSON.stringify(data)
        })
            .then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                    return;
                }

                return response.ok ? response.json() : handleResponseError(response);
            })
            .then(response => handleResponseSuccess(response, data, button))
            .catch(error => console.error('Error:', error.message || 'An unexpected error occurred'))
            .finally(() => {
                if (button) button.disabled = false;
            });
    }

    function handleResponseError(response) {
        if (response.status === 422) {
            return response.json().then(error => {
                toastr.error(error.message + ': ' + Object.values(error.errors).join(' '));
                throw new Error('Validation error');
            });
        } else if (response.status === 404) {
            return response.json().then(error => {
                toastr.error(error.message + ': ' + error.data);
                throw new Error('Product not found');
            });
        }

        throw new Error('Network error');
    }

    function handleResponseSuccess(response, data, button) {
        if (data.action === 'toggle') {
            updateCartButton(button, response.isInCart);
        } else if (data.action === 'remove') {
            removeProductElement(data.productId, response.cartCount);
        }

        if (data.action !== 'update') {
            updateCartCount(response.cartCount);
        }

        if (response.type === 'success') {
            toastr.success(response.message);
        } else {
            toastr.info(response.message);
        }
    }

    function updateCartButton(button, isInCart) {
        const icon = button.querySelector('i');
        const buttonText = isInCart ? 'Remove from cart' : 'Add to cart';

        if (icon) {
            icon.className = isInCart ? 'fa-solid fa-remove' : 'fa-solid fa-bag-shopping';
            button.innerHTML = '';
            button.appendChild(icon);
            button.appendChild(document.createTextNode(buttonText));
        } else {
            button.textContent = buttonText;
        }
    }

    function removeProductElement(productId, cartCount) {
        const productElement = document.querySelector(`#product-${productId}`);
        if (productElement) productElement.remove();
        if (cartCount <= 0) renderEmptyCartMessage();
    }

    function renderEmptyCartMessage() {
        const cartContainer = document.querySelector('#cart-product-container');
        if (cartContainer) {
            cartContainer.innerHTML = `
                <div class="flex flex-col items-center justify-center text-center py-10">
                    <i class="fa-solid fa-heart-broken text-gray-400 text-4xl mb-4"></i>
                    <h2 class="text-2xl font-bold text-gray-700">Your cart is empty</h2>
                    <p class="text-gray-500 mt-2">You have not added any products to your cart yet.</p>
                    <a href="/shop" class="mt-6 px-6 py-2 text-center text-sm text-white bg-primary border border-primary rounded hover:bg-transparent hover:text-primary transition duration-300 uppercase font-roboto font-medium">
                        Start Shopping
                    </a>
                </div>
            `;
        }
    }

    function updateCartCount(count) {
        const cartCountElement = document.querySelector('#cart-count');
        if (cartCountElement) cartCountElement.textContent = count;
    }

    function setupFormSubmission(selector, actionType, method) {
        document.querySelectorAll(selector).forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const productId = this.dataset.productId;
                const csrfToken = this.querySelector('input[name="_token"]').value;
                const quantity  = this.querySelector('input[name="quantity"]')?.value;
                const button= this.querySelector('button');

                handleCartAction(this.action, method, { productId, csrfToken, quantity, action: actionType }, button);
            });
        });
    }

    setupFormSubmission('#toggle-cart-form', 'toggle', 'POST');
    setupFormSubmission('#remove-cart-form', 'remove', 'DELETE');
    setupFormSubmission('#update-quantity-cart-form', 'update', 'PATCH');
});
