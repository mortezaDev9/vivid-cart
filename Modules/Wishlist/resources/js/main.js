document.addEventListener('DOMContentLoaded', function() {
    function handleWishlistAction(url, method, data, button = null) {
        if (button) button.disabled = true;

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': data.csrfToken,
            },
            body: JSON.stringify({ productId: data.productId })
        })
            .then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                    return;
                }

                return response.ok ? response.json() : handleResponseError(response);
            })
            .then(responseData => handleResponseSuccess(responseData, data, button))
            .catch(error => console.error('Error:', error.message || 'An unexpected error occurred'))
            .finally(() => {
                if (button) button.disabled = false;
            });
    }

    function handleResponseError(response) {
        if (response.status === 404) {
            return response.json().then(error => {
                toastr.error('Failed to add product to wishlist: ' + Object.values(error.errors).join(' '));
                throw new Error('Product not found');
            });
        }
        throw new Error('Network error');
    }

    function handleResponseSuccess(responseData, data, button) {
        if (data.action === 'toggle') {
            updateWishlistButton(button, responseData.isWishlisted);
        } else if (data.action === 'remove') {
            removeProductElement(data.productId, responseData.wishlistCount);
        }

        updateWishlistCount(responseData.wishlistCount);

        if (responseData.type === 'success') {
            toastr.success(responseData.message);
        } else {
            toastr.info(responseData.message);
        }
    }

    function updateWishlistButton(button, isWishlisted) {
        const icon = button.querySelector('i');
        const buttonText = isWishlisted ? ' Remove' : ' Wishlist';

        icon.className = isWishlisted ? 'fa-solid fa-remove' : 'fa-solid fa-heart';
        button.setAttribute('aria-label', isWishlisted ? 'Remove from wishlist' : 'Add to wishlist');

        if (button.id.includes('card')) {
            button.title = isWishlisted ? 'Remove from wishlist' : 'Add to wishlist';
        } else if (button.id.includes('detail')) {
            button.innerHTML = '';
            button.appendChild(icon);
            button.appendChild(document.createTextNode(buttonText));
        }
    }

    function removeProductElement(productId, wishlistCount) {
        const productElement = document.querySelector(`#product-${productId}`);
        if (productElement) productElement.remove();
        if (wishlistCount <= 0) renderEmptyWishlistMessage();
    }

    function renderEmptyWishlistMessage() {
        const wishlistContainer = document.querySelector('#wishlist-product-container');
        if (wishlistContainer) {
            wishlistContainer.innerHTML = `
                <div class="flex flex-col items-center justify-center text-center py-10">
                    <i class="fa-solid fa-heart-broken text-gray-400 text-4xl mb-4"></i>
                    <h2 class="text-2xl font-bold text-gray-700">Your wishlist is empty</h2>
                    <p class="text-gray-500 mt-2">You have not added any products to your wishlist yet.</p>
                    <a href="/shop" class="mt-6 px-6 py-2 text-center text-sm text-white bg-primary border border-primary rounded hover:bg-transparent hover:text-primary transition duration-300 uppercase font-roboto font-medium">
                        Start Shopping
                    </a>
                </div>
            `;
        }
    }

    function updateWishlistCount(count) {
        const wishlistCountElement = document.querySelector('#wishlist-count');
        if (wishlistCountElement) wishlistCountElement.textContent = count;
    }

    function setupFormSubmission(selector, actionType, method) {
        document.querySelectorAll(selector).forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const productId = this.dataset.productId;
                const csrfToken = this.querySelector('input[name="_token"]').value;
                const button = this.querySelector('button');

                handleWishlistAction(this.action, method, { productId, csrfToken, action: actionType }, button);
            });
        });
    }

    setupFormSubmission('#toggle-wishlist-form', 'toggle', 'POST');
    setupFormSubmission('#remove-wishlist-form', 'remove', 'DELETE');
});
