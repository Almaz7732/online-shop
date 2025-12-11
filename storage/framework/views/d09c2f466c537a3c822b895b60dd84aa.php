<?php $__env->startPush('styles'); ?>
    <link href="<?php echo e(URL::asset('build/libs/intl-tel-input/build/css/intlTelInput.min.css')); ?>" rel="stylesheet">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('title', 'Корзина - TechStore'); ?>

<?php $__env->startSection('content'); ?>
<?php
    use Illuminate\Support\Facades\Storage;
?>

<!-- Start Breadcrumbs -->
<div class="breadcrumbs">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-6 col-12">
                <ul class="breadcrumb-nav">
                    <li><a href="<?php echo e(route('shop.index')); ?>"><i class="lni lni-home"></i> Главная</a></li>
                    <li>Корзина</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- End Breadcrumbs -->

<!-- Start Cart Area -->
<section class="shopping-cart section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title">
                    <h2>Корзина</h2>
                </div>
            </div>
        </div>

        <div class="row" id="cart-content">
            <!-- Cart Items (Left Column) - Always show structure, will be populated by JavaScript -->
            <div class="col-lg-8 col-12" id="cart-items-column" style="display: none;">
                <div class="cart-list-head">
                    <div class="cart-list-title">
                        <div class="row">
                            <div class="col-lg-1 col-md-1 col-12"></div>
                            <div class="col-lg-4 col-md-3 col-12">
                                <p>Название товара</p>
                            </div>
                            <div class="col-lg-2 col-md-2 col-12">
                                <p>Количество</p>
                            </div>
                            <div class="col-lg-2 col-md-2 col-12">
                                <p>Цена</p>
                            </div>
                            <div class="col-lg-2 col-md-2 col-12">
                                <p>Итого</p>
                            </div>
                            <div class="col-lg-1 col-md-2 col-12">
                                <p>Удалить</p>
                            </div>
                        </div>
                    </div>

                    <!-- Cart Items - Will be populated by JavaScript -->
                    <div id="cart-items-list">
                        <!-- Products will be loaded here by JavaScript -->
                    </div>
                </div>
            </div>

            <!-- Cart Summary (Right Column) - Always show structure -->
            <div class="col-lg-4 col-12" id="cart-summary-column" style="display: none;">
                <div class="cart-summary">
                    <h4>Сумма заказа</h4>
                    <div class="summary-details">
                        <div class="summary-item">
                            <span>Общее количество:</span>
                            <span id="cart-total-items">0 товар(ов)</span>
                        </div>
                        <div class="summary-item">
                            <span>Стоимость:</span>
                            <span id="cart-subtotal">0.00 СОМ</span>
                        </div>
                        <div class="summary-item">
                            <span>Доставка:</span>
                            <span id="cart-shipping">0.00 СОМ</span>
                        </div>
                        <div class="summary-item discount">
                            <span>Скидка:</span>
                            <span id="cart-discount">-0.00 СОМ</span>
                        </div>
                        <hr>
                        <div class="summary-total">
                            <span>К оплате:</span>
                            <span id="cart-final-total">0.00 СОМ</span>
                        </div>
                    </div>

                    <div class="checkout-button">
                        <button type="button" class="btn checkout-btn" id="proceed-to-checkout">
                            Оформление заказа
                            <span class="item-count">(0)</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Loading State -->
            <div class="col-12 text-center" id="cart-loading">
                <div class="loading-content" style="padding: 80px 20px;">
                    <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem; margin-bottom: 20px;">
                        <span class="visually-hidden">Загрузка...</span>
                    </div>
                    <h4 style="color: #666;">Загрузка корзины...</h4>
                </div>
            </div>

            <!-- Empty Cart State -->
            <div class="col-12 text-center" id="empty-cart" style="display: none;">
                <div class="empty-cart-content" style="padding: 80px 20px;">
                    <div class="empty-icon" style="font-size: 64px; color: #ddd; margin-bottom: 20px;">
                        <i class="lni lni-cart"></i>
                    </div>
                    <h3 style="color: #666; margin-bottom: 15px;">Ваша корзина пуста</h3>
                    <p style="color: #999; margin-bottom: 30px;">
                        Добавьте товары в корзину, чтобы продолжить покупки
                    </p>
                    <div class="button">
                        <a href="<?php echo e(route('shop.index')); ?>" class="btn">
                            Продолжить покупки
                            <i class="lni lni-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Checkout Form Section -->
            <div class="col-12" id="checkout-form" style="display: none;">
                <div class="checkout-form-wrapper">
                    <div class="row">
                        <!-- Back to Cart Button -->
                        <div class="col-12 mb-4">
                            <button type="button" class="btn-back-to-cart" id="back-to-cart-btn">
                                <i class="lni lni-arrow-left"></i> Назад к корзине
                            </button>
                        </div>

                        <!-- Checkout Form -->
                        <div class="col-lg-8 col-12">
                            <div class="checkout-form-container">
                                <h3 class="checkout-title">Оформление заказа</h3>
                                <form id="checkout-form-element" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <div class="row">
                                        <!-- Name Field -->
                                        <div class="col-lg-6 col-12 mb-3">
                                            <label for="checkout-name" class="form-label">Имя <span class="required">*</span></label>
                                            <input type="text" class="form-control" id="checkout-name" name="name" required>
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <!-- Surname Field -->
                                        <div class="col-lg-6 col-12 mb-3">
                                            <label for="checkout-surname" class="form-label">Фамилия <span class="required">*</span></label>
                                            <input type="text" class="form-control" id="checkout-surname" name="surname" required>
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <!-- Phone Field -->
                                        <div class="col-12 mb-3">
                                            <label for="checkout-phone" class="form-label">Номер телефона <span class="required">*</span></label>
                                            <input type="tel" class="form-control" id="checkout-phone" name="phone" required>
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <!-- Address Field -->
                                        <div class="col-12 mb-3">
                                            <label for="checkout-address" class="form-label">Адрес <span class="required">*</span></label>
                                            <input type="text" class="form-control" id="checkout-address" name="address" required>
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <!-- Comment Field -->
                                        <div class="col-12 mb-3">
                                            <label for="checkout-comment" class="form-label">Комментарий</label>
                                            <textarea class="form-control" id="checkout-comment" name="comment" rows="4" maxlength="324" placeholder="Комментарий..."></textarea>
                                            <div class="character-count">
                                                <span id="comment-count">0</span>/324
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Order Summary -->
                        <div class="col-lg-4 col-12">
                            <div class="checkout-summary">
                                <h4>Итого заказа</h4>
                                <div class="summary-details">
                                    <div class="summary-item">
                                        <span>Общее количество:</span>
                                        <span id="checkout-total-items">0 товар(ов)</span>
                                    </div>
                                    <div class="summary-item">
                                        <span>Стоимость:</span>
                                        <span id="checkout-subtotal">$0.00</span>
                                    </div>
                                    <div class="summary-item">
                                        <span>Доставка:</span>
                                        <span id="checkout-shipping">$0.00</span>
                                    </div>
                                    <div class="summary-item discount">
                                        <span>Скидка:</span>
                                        <span id="checkout-discount">-$0.00</span>
                                    </div>
                                    <hr>
                                    <div class="summary-total">
                                        <span>К оплате:</span>
                                        <span id="checkout-final-total">0.00 СОМ</span>
                                    </div>
                                </div>

                                <div class="place-order-button">
                                    <button type="button" class="btn place-order-btn" id="place-order-btn">
                                        Оформить заказ
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Cart Area -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script src="<?php echo e(URL::asset('build/libs/intl-tel-input/build/js/intlTelInputWithUtils.min.js')); ?>"></script>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize cart page functionality with retry mechanism
    initializeCartPage();

    function initializeCartPage() {
        if (window.cartManager) {
            console.log('CartManager found, loading cart data...');
            loadCartPageData();

            // Listen for cart changes from any source (header dropdown, other pages, etc.)
            document.addEventListener('cartChanged', function(e) {
                console.log('Cart changed event received:', e.detail);
                refreshCartPage();
            });

        } else {
            console.log('CartManager not found, retrying in 100ms...');
            setTimeout(initializeCartPage, 100);
        }
    }

    // Refresh cart page data
    function refreshCartPage() {
        const cart = window.cartManager.getCart();
        console.log('Refreshing cart page, current cart:', cart);

        if (cart.length === 0) {
            showEmptyCartState();
            return;
        }

        // Check which products should be removed from the page
        const currentCartIds = cart.map(item => item.id);
        const pageProductRows = document.querySelectorAll('.cart-single-list');

        pageProductRows.forEach(row => {
            const productId = parseInt(row.getAttribute('data-product-id'));
            if (!currentCartIds.includes(productId)) {
                console.log('Removing product', productId, 'from cart page');
                row.remove();
            }
        });

        // Update quantities for remaining items
        updateCartPageQuantities(cart);

        // Recalculate summary
        calculateCartSummaryFromDOM();

        // Check if page is now empty
        const remainingItems = document.querySelectorAll('.cart-single-list');
        if (remainingItems.length === 0) {
            showEmptyCartState();
        } else {
            // Re-initialize wishlist states for remaining items
            initializeWishlistStates();
        }
    }

    // Update cart page quantities from cookies
    function updateCartPageQuantities(cart) {
        cart.forEach(cartItem => {
            const productRow = document.querySelector(`[data-product-id="${cartItem.id}"]`);
            if (productRow) {
                const qtyInput = productRow.querySelector('.qty-input');
                const totalElement = productRow.querySelector('.cart-total .total');
                const priceElement = productRow.querySelector('.cart-price .price');

                if (qtyInput && qtyInput.value != cartItem.quantity) {
                    qtyInput.value = cartItem.quantity;

                    if (totalElement && priceElement) {
                        const price = parseFloat(priceElement.textContent.replace('$', ''));
                        const total = (price * cartItem.quantity).toFixed(2);
                        totalElement.textContent = `${total} СОМ`;
                    }
                }
            }
        });
    }

    // Load cart data and render products
    function loadCartPageData() {
        const cart = window.cartManager.getCart();
        console.log('Cart data from cookies:', cart);

        if (cart.length === 0) {
            console.log('Cart is empty, showing empty state');
            showEmptyCartState();
            return;
        }

        console.log('Cart has', cart.length, 'items, loading product data...');

        // Load product data from server
        const productIds = cart.map(item => item.id);
        const url = new URL('/cart-data', window.location.origin);
        url.searchParams.set('ids', productIds.join(','));

        console.log('Fetching from URL:', url.toString());

        fetch(url)
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Received data:', data);
                if (data.products && data.products.length > 0) {
                    console.log('Rendering', data.products.length, 'products');
                    renderCartProducts(data.products, cart);
                    showCartContent();
                    bindCartPageEvents();
                } else {
                    console.log('No products in response, showing empty state');
                    showEmptyCartState();
                }
            })
            .catch(error => {
                console.error('Error loading cart data:', error);
                showEmptyCartState();
            });
    }

    // Render cart products
    function renderCartProducts(products, cart) {
        const cartItemsList = document.getElementById('cart-items-list');
        cartItemsList.innerHTML = '';

        products.forEach(product => {
            const cartItem = cart.find(item => item.id === product.id);
            if (!cartItem) return;

            const productRow = document.createElement('div');
            productRow.className = 'cart-single-list';
            productRow.setAttribute('data-product-id', product.id);

            productRow.innerHTML = `
                <div class="row align-items-center">
                    <div class="col-lg-1 col-md-1 col-12">
                        <div class="cart-image">
                            <img src="${product.primary_image}" alt="${product.name}">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-3 col-12">
                        <div class="cart-info">
                            <h5><a href="/product/${product.slug}">${product.name}</a></h5>
                            <p>${product.category}</p>
                            <div class="cart-wishlist-btn">
                                <a href="javascript:void(0)" class="wishlist-toggle" data-wishlist-id="${product.id}" title="Add to Wishlist">
                                    <i class="lni lni-heart"></i>
                                    Добавить в избранное
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-12">
                        <div class="cart-quantity">
                            <div class="quantity-controls">
                                <button type="button" class="qty-btn qty-minus" data-product-id="${product.id}">
                                    <i class="lni lni-minus"></i>
                                </button>
                                <input type="number" class="qty-input" value="${cartItem.quantity}" min="1" data-product-id="${product.id}">
                                <button type="button" class="qty-btn qty-plus" data-product-id="${product.id}">
                                    <i class="lni lni-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-12">
                        <div class="cart-price">
                            <p class="price">${parseFloat(product.price).toFixed(2)} СОМ</p>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-12">
                        <div class="cart-total">
                            <p class="total" data-product-id="${product.id}">${(product.price * cartItem.quantity).toFixed(2)} СОМ</p>
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-2 col-12">
                        <a class="remove-item" href="javascript:void(0)" data-product-id="${product.id}" title="Remove">
                            <i class="lni lni-close"></i>
                        </a>
                    </div>
                </div>
            `;

            cartItemsList.appendChild(productRow);
        });

        // Calculate and update summary
        calculateCartSummary(cart, products);

        // Initialize wishlist states for rendered products (with small delay to ensure wishlistManager is ready)
        setTimeout(initializeWishlistStates, 50);
    }

    // Show cart content (hide empty state, show cart items and summary)
    function showCartContent() {
        document.getElementById('cart-loading').style.display = 'none';
        document.getElementById('empty-cart').style.display = 'none';
        document.getElementById('cart-items-column').style.display = 'block';
        document.getElementById('cart-summary-column').style.display = 'block';
    }

    // Show empty cart state
    function showEmptyCartState() {
        document.getElementById('cart-loading').style.display = 'none';
        document.getElementById('empty-cart').style.display = 'block';
        document.getElementById('cart-items-column').style.display = 'none';
        document.getElementById('cart-summary-column').style.display = 'none';
    }

    // Calculate and update cart summary
    function calculateCartSummary(cart, products) {
        let totalItems = 0;
        let subtotal = 0;

        cart.forEach(cartItem => {
            const product = products.find(p => p.id === cartItem.id);
            if (product) {
                totalItems += cartItem.quantity;
                subtotal += product.price * cartItem.quantity;
            }
        });

        // Update summary elements
        document.getElementById('cart-total-items').textContent = `${totalItems} товар(ов)`;
        document.getElementById('cart-subtotal').textContent = `${subtotal.toFixed(2)} СОМ`;
        document.getElementById('cart-final-total').textContent = `${subtotal.toFixed(2)} СОМ`;

        // Update checkout button
        const checkoutBtn = document.querySelector('.checkout-btn');
        if (checkoutBtn) {
            checkoutBtn.querySelector('.item-count').textContent = `(${totalItems})`;
        }
    }

    // Bind cart page specific events
    function bindCartPageEvents() {
        // Quantity controls
        document.addEventListener('click', function(e) {
            if (e.target.closest('.qty-btn')) {
                e.preventDefault();
                const btn = e.target.closest('.qty-btn');
                const productId = parseInt(btn.getAttribute('data-product-id'));
                const input = btn.closest('.quantity-controls').querySelector('.qty-input');
                let quantity = parseInt(input.value);

                if (btn.classList.contains('qty-minus')) {
                    quantity = Math.max(1, quantity - 1);
                } else if (btn.classList.contains('qty-plus')) {
                    quantity = quantity + 1;
                }

                input.value = quantity;
                window.cartManager.updateQuantity(productId, quantity);

                // Update totals immediately
                updateProductTotal(productId, quantity);
                calculateCartSummaryFromDOM();
            }
        });

        // Quantity input changes
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('qty-input')) {
                const productId = parseInt(e.target.getAttribute('data-product-id'));
                const quantity = Math.max(1, parseInt(e.target.value) || 1);
                e.target.value = quantity;

                window.cartManager.updateQuantity(productId, quantity);
                updateProductTotal(productId, quantity);
                calculateCartSummaryFromDOM();
            }
        });

        // Remove item buttons
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-item')) {
                e.preventDefault();
                const removeBtn = e.target.closest('.remove-item');
                const productId = parseInt(removeBtn.getAttribute('data-product-id'));

                window.cartManager.removeFromCart(productId);

                // Remove row from page
                const productRow = document.querySelector(`[data-product-id="${productId}"]`);
                if (productRow) {
                    productRow.remove();
                }

                // Check if cart is empty
                const remainingItems = document.querySelectorAll('.cart-single-list');
                if (remainingItems.length === 0) {
                    showEmptyCartState();
                } else {
                    calculateCartSummaryFromDOM();
                }
            }
        });
    }

    // Update individual product total
    function updateProductTotal(productId, quantity) {
        const productRow = document.querySelector(`[data-product-id="${productId}"]`);
        if (productRow) {
            const priceElement = productRow.querySelector('.cart-price .price');
            const totalElement = productRow.querySelector('.cart-total .total');

            if (priceElement && totalElement) {
                const price = parseFloat(priceElement.textContent.replace('$', ''));
                const total = (price * quantity).toFixed(2);
                totalElement.textContent = `$${total}`;
            }
        }
    }

    // Calculate summary from DOM elements
    function calculateCartSummaryFromDOM() {
        let totalItems = 0;
        let subtotal = 0;

        document.querySelectorAll('.cart-single-list').forEach(row => {
            const qtyInput = row.querySelector('.qty-input');
            const priceElement = row.querySelector('.cart-price .price');

            if (qtyInput && priceElement) {
                const quantity = parseInt(qtyInput.value);
                const price = parseFloat(priceElement.textContent.replace('$', ''));

                totalItems += quantity;
                subtotal += price * quantity;
            }
        });

        // Update summary elements
        document.getElementById('cart-total-items').textContent = `${totalItems} товар(ов)`;
        document.getElementById('cart-subtotal').textContent = `${subtotal.toFixed(2)} СОМ`;
        document.getElementById('cart-final-total').textContent = `${subtotal.toFixed(2)} СОМ`;

        // Update checkout button
        const checkoutBtn = document.querySelector('.checkout-btn');
        if (checkoutBtn) {
            checkoutBtn.querySelector('.item-count').textContent = `(${totalItems})`;
        }
    }

    // Initialize wishlist states for all products
    function initializeWishlistStates() {
        if (!window.wishlistManager) {
            console.log('WishlistManager not found, skipping wishlist initialization');
            return;
        }

        const wishlist = window.wishlistManager.getWishlist();
        console.log('Initializing wishlist states, current wishlist:', wishlist);

        // Update all wishlist buttons based on current wishlist state
        document.querySelectorAll('[data-wishlist-id]').forEach(button => {
            const productId = parseInt(button.getAttribute('data-wishlist-id'));
            const isInWishlist = wishlist.includes(productId);

            if (isInWishlist) {
                button.classList.add('active');
                button.title = 'Remove from Wishlist';
                const icon = button.querySelector('i');
                if (icon) {
                    icon.className = 'lni lni-heart-filled';
                }
            } else {
                button.classList.remove('active');
                button.title = 'Add to Wishlist';
                const icon = button.querySelector('i');
                if (icon) {
                    icon.className = 'lni lni-heart';
                }
            }
        });
    }

    // Checkout form functionality
    function initializeCheckoutForm() {
        const proceedToCheckoutBtn = document.getElementById('proceed-to-checkout');
        const backToCartBtn = document.getElementById('back-to-cart-btn');
        const placeOrderBtn = document.getElementById('place-order-btn');
        const checkoutForm = document.getElementById('checkout-form-element');

        // Handle proceed to checkout
        if (proceedToCheckoutBtn) {
            proceedToCheckoutBtn.addEventListener('click', function() {
                showCheckoutForm();
            });
        }

        // Handle back to cart
        if (backToCartBtn) {
            backToCartBtn.addEventListener('click', function() {
                showCartView();
            });
        }

        // Handle place order
        if (placeOrderBtn) {
            placeOrderBtn.addEventListener('click', function() {
                submitOrder();
            });
        }

        // Character counter for comment field
        const commentField = document.getElementById('checkout-comment');
        const commentCounter = document.getElementById('comment-count');

        if (commentField && commentCounter) {
            commentField.addEventListener('input', function() {
                commentCounter.textContent = this.value.length;
            });
        }
    }

    // Show checkout form view
    function showCheckoutForm() {
        // Hide cart content and empty state
        document.getElementById('cart-items-column').style.display = 'none';
        document.getElementById('cart-summary-column').style.display = 'none';
        document.getElementById('empty-cart').style.display = 'none';

        // Show checkout form
        document.getElementById('checkout-form').style.display = 'block';

        // Copy cart summary data to checkout summary
        copyCartSummaryToCheckout();

        // Initialize phone input
        initializePhoneInput();
    }

    // Show cart view
    function showCartView() {
        // Hide checkout form
        document.getElementById('checkout-form').style.display = 'none';

        // Show cart content
        const cart = window.cartManager.getCart();
        if (cart.length === 0) {
            showEmptyCartState();
        } else {
            document.getElementById('cart-items-column').style.display = 'block';
            document.getElementById('cart-summary-column').style.display = 'block';
            document.getElementById('empty-cart').style.display = 'none';
        }
    }

    // Copy cart summary to checkout summary
    function copyCartSummaryToCheckout() {
        const cartTotalItems = document.getElementById('cart-total-items');
        const cartSubtotal = document.getElementById('cart-subtotal');
        const cartFinalTotal = document.getElementById('cart-final-total');

        const checkoutTotalItems = document.getElementById('checkout-total-items');
        const checkoutSubtotal = document.getElementById('checkout-subtotal');
        const checkoutFinalTotal = document.getElementById('checkout-final-total');

        if (cartTotalItems && checkoutTotalItems) {
            checkoutTotalItems.textContent = cartTotalItems.textContent;
        }
        if (cartSubtotal && checkoutSubtotal) {
            checkoutSubtotal.textContent = cartSubtotal.textContent;
        }
        if (cartFinalTotal && checkoutFinalTotal) {
            checkoutFinalTotal.textContent = cartFinalTotal.textContent;
        }
    }

    // Initialize phone input with Kyrgyzstan default
    function initializePhoneInput() {
        const phoneInput = document.getElementById('checkout-phone');
        if (phoneInput && window.intlTelInput) {
            // Remove existing instance if any
            if (phoneInput.iti) {
                phoneInput.iti.destroy();
            }

            // Initialize with Kyrgyzstan as default
            phoneInput.iti = window.intlTelInput(phoneInput, {
                initialCountry: 'kg',
                preferredCountries: ['kg', 'ru', 'kz'],
                utilsScript: '<?php echo e(asset("build/libs/intl-tel-input/build/js/intlTelInputWithUtils.min.js")); ?>'
            });
        }
    }

    // Submit order
    function submitOrder() {
        const form = document.getElementById('checkout-form-element');
        const formData = new FormData(form);

        // Clear previous validation errors
        clearValidationErrors();

        // Validate form
        if (!validateCheckoutForm()) {
            return;
        }

        // Prepare order data
        const cart = window.cartManager.getCart();
        const orderData = {
            name: formData.get('name'),
            surname: formData.get('surname'),
            phone: document.getElementById('checkout-phone').iti ?
                   document.getElementById('checkout-phone').iti.getNumber() :
                   formData.get('phone'),
            address: formData.get('address'),
            comment: formData.get('comment'),
            cart_data: cart,
            total_amount: parseFloat(document.getElementById('checkout-final-total').textContent.replace('$', ''))
        };

        // Disable submit button
        const submitBtn = document.getElementById('place-order-btn');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Оформляем заказ...';

        // Send order to server
        fetch('<?php echo e(route("order.store")); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(orderData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Заказ оформлен!',
                    text: data.message,
                    confirmButtonText: 'ОК'
                }).then(() => {
                    // Clear cart
                    window.cartManager.saveCart([]);

                    // Redirect to home page
                    window.location.href = '<?php echo e(route("shop.index")); ?>';
                });
            } else {
                throw new Error(data.message || 'Ошибка при оформлении заказа');
            }
        })
        .catch(error => {
            console.error('Order error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Ошибка',
                text: error.message || 'Произошла ошибка при оформлении заказа. Попробуйте еще раз.',
                confirmButtonText: 'ОК'
            });
        })
        .finally(() => {
            // Re-enable submit button
            submitBtn.disabled = false;
            submitBtn.textContent = 'Оформить заказ';
        });
    }

    // Validate checkout form
    function validateCheckoutForm() {
        let isValid = true;
        const requiredFields = ['checkout-name', 'checkout-surname', 'checkout-phone', 'checkout-address'];

        requiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (!field.value.trim()) {
                showFieldError(field, 'Это поле обязательно для заполнения');
                isValid = false;
            }
        });

        // Validate phone number
        const phoneField = document.getElementById('checkout-phone');
        if (phoneField.iti && !phoneField.iti.isValidNumber()) {
            showFieldError(phoneField, 'Введите корректный номер телефона');
            isValid = false;
        }

        return isValid;
    }

    // Show field validation error
    function showFieldError(field, message) {
        field.classList.add('is-invalid');
        const feedback = field.parentNode.querySelector('.invalid-feedback');
        if (feedback) {
            feedback.textContent = message;
        }
    }

    // Clear validation errors
    function clearValidationErrors() {
        document.querySelectorAll('.is-invalid').forEach(field => {
            field.classList.remove('is-invalid');
        });
        document.querySelectorAll('.invalid-feedback').forEach(feedback => {
            feedback.textContent = '';
        });
    }

    // Initialize checkout form when cart page is ready
    setTimeout(initializeCheckoutForm, 100);
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('clients.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/clients/shop/cart.blade.php ENDPATH**/ ?>