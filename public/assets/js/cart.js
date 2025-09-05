document.addEventListener('DOMContentLoaded', function () {
    const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
    if (!csrfTokenElement) {
        console.error('CSRF token meta tag is missing.');
        alert('CSRF token is missing. Please contact support.');
        return;
    }

    loadCart();

    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.getAttribute('data-id');
            console.log(`Add to Cart clicked for product ID: ${productId}`);
            addToCart(productId);
        });
    });

    function addToCart(productId) {
        const form = document.getElementById('add-to-cart-form');
        const quantityElement = document.getElementById(`quantity_${productId}`);
        const variationOption = document.querySelector(`input[name="variation_option[${productId}]"]:checked`);
        const restaurantIdElement = document.getElementById('restaurant_id');
        const customerIdElement = document.getElementById('customer_id');

        if (!quantityElement || !restaurantIdElement) {
            console.error(`Missing elements for product ID ${productId}`);
            alert('Error: Required fields not found');
            return;
        }

        const data = {
            restaurant_id: restaurantIdElement.value,
            customer_id: customerIdElement ? customerIdElement.value || null : null,
            product_id: productId,
            quantity: parseInt(quantityElement.value),
            variation_option: variationOption ? variationOption.value : null
        };

        console.log('Sending data to /cart/add:', data);

        fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfTokenElement.content
            },
            body: JSON.stringify(data)
        })
            .then(response => {
                console.log('Response status from /cart/add:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response from /cart/add:', data);
                if (data.error) {
                    console.error('Server error:', data.error);
                    alert(data.error);
                    return;
                }
                loadCart();
            })
            .catch(error => {
                console.error('Error adding to cart:', error);
                alert('Failed to add item to cart: ' + error.message);
            });
    }

    function loadCart() {
        fetch('/cart/show', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfTokenElement.content
            }
        })
            .then(response => {
                console.log('Response status from /cart/show:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response from /cart/show:', data);
                if (data.error) {
                    console.error('Server error:', data.error);
                    alert(data.error);
                    return;
                }
                updateCartPreview(data.cart);
            })
            .catch(error => {
                console.error('Error loading cart:', error);
                alert('Failed to load cart: ' + error.message);
            });
    }

    function updateCartPreview(cart) {
        const cartPreview = document.getElementById('cart-preview');
        if (!cartPreview) {
            console.error('Cart preview element not found');
            alert('Error: Cart preview element not found');
            return;
        }

        if (!cart || Object.keys(cart.items).length === 0) {
            cartPreview.innerHTML = '<p>Your cart is empty.</p>';
            console.log('Cart is empty');
            return;
        }

        const groupedCart = {};
        for (const [key, item] of Object.entries(cart.items)) {
            const groupKey = `${item.product_id}:${item.variation_option || 'none'}`;
            if (groupedCart[groupKey]) {
                groupedCart[groupKey].quantity += item.quantity;
                groupedCart[groupKey].item_total += item.item_total;
                groupedCart[groupKey].cartIds.push(key);
            } else {
                groupedCart[groupKey] = {
                    ...item,
                    cartIds: [key]
                };
            }
        }

        let html = '<ul class="list-group">';
        for (const [groupKey, item] of Object.entries(groupedCart)) {
            html += `
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>${item.name}</strong>
                                ${item.variation_option_name ? `<br><small>${item.variation_option_name}</small>` : ''}
                                <br><small>
                                    $${item.price.toFixed(2)} x 
                                    <button class="btn btn-sm btn-outline-secondary decrement-item" data-ids="${item.cartIds.join(',')}">-</button>
                                    ${item.quantity}
                                    <button class="btn btn-sm btn-outline-secondary increment-item" data-ids="${item.cartIds.join(',')}">+</button>
                                    = $${item.item_total.toFixed(2)}
                                </small>
                            </div>
                            <button class="btn btn-danger btn-sm remove-from-cart" data-ids="${item.cartIds.join(',')}">Remove</button>
                        </li>
                    `;
        }
        html += '</ul>';
        html += `
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <input class="form-control" type="text" id="coupon_code" placeholder="Coupon Code" value="${cart.coupon_code || ''}">
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-info btn-sm apply-coupon">Apply Coupon Code</button>
                        </div>
                    </div>
                `;
        html += `<div class="mt-3"><strong>Subtotal: $${cart.subtotal.toFixed(2)}</strong></div>`;
        html += `<div class="mt-3"><strong>Delivery Charge: $${cart.delivery_charge.toFixed(2)}</strong></div>`;
        html += `<div class="mt-3"><strong>Discount: $${cart.discount.toFixed(2)}</strong></div>`;
        html += `<div class="mt-3"><strong>Total: $${cart.total.toFixed(2)}</strong></div>`;
        html += `<button class="btn btn-primary btn-sm proceed-checkout mt-2">Proceed to Checkout</button>`;

        cartPreview.innerHTML = html;
        console.log('Cart preview updated:', groupedCart);

        document.querySelectorAll('.increment-item').forEach(button => {
            button.addEventListener('click', function () {
                const cartIds = this.getAttribute('data-ids').split(',');
                console.log(`Increment clicked for IDs: ${cartIds}`);
                incrementItem(cartIds[0]);
            });
        });

        document.querySelectorAll('.decrement-item').forEach(button => {
            button.addEventListener('click', function () {
                const cartIds = this.getAttribute('data-ids').split(',');
                console.log(`Decrement clicked for IDs: ${cartIds}`);
                decrementItem(cartIds[0]);
            });
        });

        document.querySelectorAll('.remove-from-cart').forEach(button => {
            button.addEventListener('click', function () {
                const cartIds = this.getAttribute('data-ids').split(',');
                console.log(`Remove from cart clicked for IDs: ${cartIds}`);
                removeFromCart(cartIds);
            });
        });

        document.querySelector('.proceed-checkout').addEventListener('click', function () {
            console.log('Proceed to Checkout clicked');
            proceedToCheckout();
        });

        document.querySelector('.apply-coupon').addEventListener('click', function () {
            console.log('Apply Coupon clicked');
            applyCoupon();
        });
    }

    // function applyCoupon() {
    //     const couponCode = document.getElementById('coupon_code').value;
    //     const restaurantIdElement = document.getElementById('restaurant_id');

    //     if (!couponCode) {
    //         alert('Please enter a coupon code');
    //         return;
    //     }

    //     if (!restaurantIdElement) {
    //         console.error('Restaurant ID element not found');
    //         alert('Error: Restaurant ID not found');
    //         return;
    //     }

    //     const data = {
    //         coupon_code: couponCode,
    //         restaurant_id: restaurantIdElement.value
    //     };

    //     console.log('Sending data to /cart/apply-coupon:', data);

    //     fetch('/cart/apply-coupon', {
    //         method: 'POST',
    //         headers: {
    //             'Content-Type': 'application/json',
    //             'X-CSRF-TOKEN': csrfTokenElement.content
    //         },
    //         body: JSON.stringify(data)
    //     })
    //         .then(response => {
    //             console.log('Response status from /cart/apply-coupon:', response.status);
    //             if (!response.ok) {
    //                 throw new Error(`HTTP error! Status: ${response.status}`);
    //             }
    //             return response.json();
    //         })
    //         .then(data => {
    //             console.log('Response from /cart/apply-coupon:', data);
    //             if (data.error) {
    //                 console.error('Server error:', data.error);
    //                 alert(data.error);
    //                 return;
    //             }
    //             alert(data.message || 'Coupon applied successfully');
    //             loadCart();
    //         })
    //         .catch(error => {
    //             console.error('Error applying coupon:', error);
    //             alert('Failed to apply coupon: ' + error.message);
    //         });
    // }

    function applyCoupon() {
        const couponCode = document.getElementById('coupon_code').value;
        const restaurantIdElement = document.getElementById('restaurant_id');
        const couponErrorDiv = document.getElementById('coupon-error');

        // Clear previous error message
        couponErrorDiv.style.display = 'none';
        couponErrorDiv.textContent = '';

        if (!couponCode) {
            couponErrorDiv.textContent = 'Please enter a coupon code';
            couponErrorDiv.style.display = 'block';
            return;
        }

        if (!restaurantIdElement) {
            console.error('Restaurant ID element not found');
            couponErrorDiv.textContent = 'Error: Restaurant ID not found';
            couponErrorDiv.style.display = 'block';
            return;
        }

        const data = {
            coupon_code: couponCode,
            restaurant_id: restaurantIdElement.value
        };

        console.log('Sending data to /cart/apply-coupon:', data);

        fetch('/cart/apply-coupon', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        })
            .then(response => {
                console.log('Response status from /cart/apply-coupon:', response.status);
                if (!response.ok) {
                    return response.json().then(err => { throw new Error(err.error || `HTTP error! Status: ${response.status}`); });
                }
                return response.json();
            })
            .then(data => {
                console.log('Response from /cart/apply-coupon:', data);
                if (data.error) {
                    console.error('Server error:', data.error);
                    couponErrorDiv.textContent = data.error;
                    couponErrorDiv.style.display = 'block';
                    return;
                }
                // Display success message (optional: could use another div for success)
                couponErrorDiv.textContent = data.message || 'Coupon applied successfully';
                couponErrorDiv.classList.remove('alert-danger');
                couponErrorDiv.classList.add('alert-success');
                couponErrorDiv.style.display = 'block';
                loadCart(); // Refresh cart to reflect discount
            })
            .catch(error => {
                console.error('Error applying coupon:', error);
                couponErrorDiv.textContent = 'Failed to apply coupon: ' + error.message;
                couponErrorDiv.style.display = 'block';
            });
    }

    function proceedToCheckout() {
        fetch('/cart/proceed', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfTokenElement.content
            }
        })
            .then(response => {
                console.log('Response status from /cart/proceed:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response from /cart/proceed:', data);
                if (data.error) {
                    console.error('Server error:', data.error);
                    alert(data.error);
                    return;
                }
                if (data.redirect) {
                    window.location.href = data.redirect;
                }
            })
            .catch(error => {
                console.error('Error proceeding to checkout:', error);
                alert('Failed to proceed to checkout: ' + error.message);
            });
    }

    function incrementItem(cartId) {
        fetch(`/cart/increment/${cartId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfTokenElement.content
            }
        })
            .then(response => {
                console.log(`Response status from /cart/increment/${cartId}:`, response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response from /cart/increment:', data);
                if (data.error) {
                    console.error('Server error:', data.error);
                    alert(data.error);
                    return;
                }
                loadCart();
            })
            .catch(error => {
                console.error('Error incrementing item:', error);
                alert('Failed to increment item: ' + error.message);
            });
    }

    function decrementItem(cartId) {
        fetch(`/cart/decrement/${cartId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfTokenElement.content
            }
        })
            .then(response => {
                console.log(`Response status from /cart/decrement/${cartId}:`, response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response from /cart/decrement:', data);
                if (data.error) {
                    console.error('Server error:', data.error);
                    alert(data.error);
                    return;
                }
                loadCart();
            })
            .catch(error => {
                console.error('Error decrementing item:', error);
                alert('Failed to decrement item: ' + error.message);
            });
    }

    function removeFromCart(cartIds) {
        Promise.all(cartIds.map(cartId =>
            fetch(`/cart/remove/${cartId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfTokenElement.content
                }
            })
                .then(response => {
                    console.log(`Response status from /cart/remove/${cartId}:`, response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
        ))
            .then(results => {
                const errors = results.filter(data => data.error);
                if (errors.length > 0) {
                    console.error('Server errors:', errors);
                    alert('Failed to remove some items: ' + errors.map(e => e.error).join(', '));
                    return;
                }
                loadCart();
            })
            .catch(error => {
                console.error('Error removing from cart:', error);
                alert('Failed to remove item from cart: ' + error.message);
            });
    }

    function updateTotalPrice(productId) {
        const quantityElement = document.getElementById(`quantity_${productId}`);
        const variationOption = document.querySelector(`input[name="variation_option[${productId}]"]:checked`);
        const priceElement = document.getElementById(`price_${productId}`);
        const productInput = document.getElementById(`product_id_${productId}`);

        if (!quantityElement || !priceElement || !productInput) {
            console.error(`Missing elements for product ID ${productId}:`, { quantityElement, priceElement, productInput });
            return;
        }

        let price = parseFloat(productInput.dataset.basePrice || 0);
        if (variationOption) {
            price = parseFloat(variationOption.dataset.price);
        }

        const quantity = parseInt(quantityElement.value);
        const totalPrice = (price * quantity).toFixed(2);
        priceElement.innerHTML = `$${totalPrice} (${price.toFixed(2)}/person)`;
    }
});