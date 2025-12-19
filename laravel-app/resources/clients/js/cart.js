// Cart management system using cookies
class CartManager {
    constructor() {
        this.cookieName = 'cart';
        this.cookieExpireDays = 30;
        this.init();
    }

    init() {
        this.updateCartCounter();
        this.bindEvents();
        this.loadCartData();
    }

    // Get cart from cookies
    getCart() {
        const cookieValue = this.getCookie(this.cookieName);
        if (cookieValue) {
            try {
                return JSON.parse(cookieValue);
            } catch (e) {
                console.error('Error parsing cart cookie:', e);
                return [];
            }
        }
        return [];
    }

    // Save cart to cookies
    saveCart(cart) {
        const cookieValue = JSON.stringify(cart);
        this.setCookie(this.cookieName, cookieValue, this.cookieExpireDays);
        this.updateCartCounter();
        this.loadCartData();

        // Dispatch custom event for cart changes
        const cartChangeEvent = new CustomEvent('cartChanged', {
            detail: { cart: cart }
        });
        document.dispatchEvent(cartChangeEvent);
    }

    // Add product to cart
    addToCart(productId, quantity = 1) {
        const cart = this.getCart();
        const existingItem = cart.find(item => item.id === productId);

        if (existingItem) {
            existingItem.quantity += quantity;
        } else {
            cart.push({ id: productId, quantity: quantity });
        }

        this.saveCart(cart);
        return true;
    }

    // Remove product from cart
    removeFromCart(productId) {
        const cart = this.getCart();
        const filteredCart = cart.filter(item => item.id !== productId);
        this.saveCart(filteredCart);
        return true;
    }

    // Update product quantity in cart
    updateQuantity(productId, quantity) {
        const cart = this.getCart();
        const item = cart.find(item => item.id === productId);

        if (item) {
            if (quantity <= 0) {
                this.removeFromCart(productId);
            } else {
                item.quantity = quantity;
                this.saveCart(cart);
            }
            return true;
        }
        return false;
    }

    // Get total items count
    getTotalItems() {
        const cart = this.getCart();
        return cart.reduce((total, item) => total + item.quantity, 0);
    }

    // Get total cart value (will be calculated when we load product data)
    getTotalValue() {
        return this.cartTotal || 0;
    }

    // Check if product is in cart
    isInCart(productId) {
        const cart = this.getCart();
        return cart.some(item => item.id === productId);
    }

    // Get product quantity in cart
    getProductQuantity(productId) {
        const cart = this.getCart();
        const item = cart.find(item => item.id === productId);
        return item ? item.quantity : 0;
    }

    // Update cart counter in header
    updateCartCounter() {
        const totalItems = this.getTotalItems();
        const counter = document.querySelector('.cart-items .total-items');
        if (counter) {
            counter.textContent = totalItems;
        }
    }

    // Load cart data from server
    loadCartData() {
        const cart = this.getCart();
        if (cart.length === 0) {
            this.showEmptyCart();
            return;
        }

        const productIds = cart.map(item => item.id);
        const url = new URL('/cart-data', window.location.origin);
        url.searchParams.set('ids', productIds.join(','));

        fetch(url)
            .then(response => response.json())
            .then(data => {
                this.renderCartItems(data.products);
                this.calculateTotal(data.products);
            })
            .catch(error => {
                console.error('Error loading cart data:', error);
                this.showEmptyCart();
            });
    }

    // Render cart items in dropdown
    renderCartItems(products) {
        const cart = this.getCart();
        const cartList = document.querySelector('.shopping-list');
        const cartHeader = document.querySelector('.dropdown-cart-header span');

        if (!cartList) return;

        cartList.innerHTML = '';

        products.forEach(product => {
            const cartItem = cart.find(item => item.id === product.id);
            if (!cartItem) return;

            const li = document.createElement('li');
            li.innerHTML = `
                <a href="javascript:void(0)" class="remove" data-product-id="${product.id}" title="Remove this item">
                    <i class="lni lni-close"></i>
                </a>
                <div class="cart-img-head">
                    <a class="cart-img" href="/product/${product.slug}">
                        <img src="${product.primary_image}" alt="${product.name}">
                    </a>
                </div>
                <div class="content">
                    <h4><a href="/product/${product.slug}">${product.name}</a></h4>
                    <p class="quantity">${cartItem.quantity}x - <span class="amount">${(product.price * cartItem.quantity).toFixed(2)} СОМ</span></p>
                </div>
            `;

            cartList.appendChild(li);
        });

        // Update header
        const totalItems = this.getTotalItems();
        if (cartHeader) {
            cartHeader.textContent = `${totalItems} Items`;
        }
    }

    // Calculate and display total
    calculateTotal(products) {
        const cart = this.getCart();
        let total = 0;

        products.forEach(product => {
            const cartItem = cart.find(item => item.id === product.id);
            if (cartItem) {
                total += product.price * cartItem.quantity;
            }
        });

        this.cartTotal = total;

        const totalElement = document.querySelector('.total-amount');
        if (totalElement) {
            totalElement.textContent = `${total.toFixed(2)} СОМ`;
        }
    }

    // Show empty cart state
    showEmptyCart() {
        const cartList = document.querySelector('.shopping-list');
        const cartHeader = document.querySelector('.dropdown-cart-header span');
        const totalElement = document.querySelector('.total-amount');

        if (cartList) {
            cartList.innerHTML = '<li style="text-align: center; padding: 20px; color: #999;">Корзина пуста</li>';
        }

        if (cartHeader) {
            cartHeader.textContent = '0 Items';
        }

        if (totalElement) {
            totalElement.textContent = '$0.00';
        }
    }

    // Bind events to cart buttons
    bindEvents() {
        // Add to cart buttons
        document.addEventListener('click', (e) => {
            const addToCartButton = e.target.closest('.add-to-cart[data-product-id]');
            if (addToCartButton) {
                e.preventDefault();
                e.stopPropagation();

                const productId = parseInt(addToCartButton.getAttribute('data-product-id'));
                this.addToCart(productId, 1);
                this.showNotification('Товар добавлен в корзину!', 'success');
            }
        });

        // Remove from cart buttons
        document.addEventListener('click', (e) => {
            const removeButton = e.target.closest('.remove[data-product-id]');
            if (removeButton) {
                e.preventDefault();
                e.stopPropagation();

                const productId = parseInt(removeButton.getAttribute('data-product-id'));
                this.removeFromCart(productId);
                this.showNotification('Товар удален из корзины!', 'info');
            }
        });
    }

    // Show notification using SweetAlert2
    showNotification(message, type = 'info') {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: type === 'success' ? 'success' : 'info',
            title: message,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
    }

    // Check if current page is cart page
    isCartPage() {
        return window.location.pathname === '/cart';
    }

    // Navigate to cart page
    goToCartPage() {
        // Simple redirect to cart page - no URL parameters needed
        // Cart content will be loaded dynamically via JavaScript
        window.location.href = '/cart';
    }

    // Cookie management helpers
    setCookie(name, value, days) {
        let expires = "";
        if (days) {
            const date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + value + expires + "; path=/";
    }

    getCookie(name) {
        const nameEQ = name + "=";
        const ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }
}

// Initialize cart manager when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.cartManager = new CartManager();
});
