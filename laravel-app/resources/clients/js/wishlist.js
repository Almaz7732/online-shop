// Wishlist management system using cookies
class WishlistManager {
    constructor() {
        this.cookieName = 'wishlist';
        this.cookieExpireDays = 30;
        this.init();
    }

    init() {
        this.updateWishlistCounter();
        this.bindEvents();
    }

    // Get wishlist from cookies
    getWishlist() {
        const cookieValue = this.getCookie(this.cookieName);
        if (cookieValue) {
            try {
                return JSON.parse(cookieValue);
            } catch (e) {
                console.error('Error parsing wishlist cookie:', e);
                return [];
            }
        }
        return [];
    }

    // Save wishlist to cookies
    saveWishlist(wishlist) {
        const cookieValue = JSON.stringify(wishlist);
        this.setCookie(this.cookieName, cookieValue, this.cookieExpireDays);
        this.updateWishlistCounter();
    }

    // Add product to wishlist
    addToWishlist(productId) {
        const wishlist = this.getWishlist();
        if (!wishlist.includes(productId)) {
            wishlist.push(productId);
            this.saveWishlist(wishlist);
            return true;
        }
        return false;
    }

    // Remove product from wishlist
    removeFromWishlist(productId) {
        const wishlist = this.getWishlist();
        const index = wishlist.indexOf(productId);
        if (index > -1) {
            wishlist.splice(index, 1);
            this.saveWishlist(wishlist);
            return true;
        }
        return false;
    }

    // Check if product is in wishlist
    isInWishlist(productId) {
        const wishlist = this.getWishlist();
        return wishlist.includes(productId);
    }

    // Toggle product in wishlist
    toggleWishlist(productId) {
        if (this.isInWishlist(productId)) {
            this.removeFromWishlist(productId);
            return false;
        } else {
            this.addToWishlist(productId);
            return true;
        }
    }

    // Update wishlist counter in header
    updateWishlistCounter() {
        const wishlist = this.getWishlist();
        const counter = document.querySelector('.wishlist .total-items');
        if (counter) {
            counter.textContent = wishlist.length;
        }

        // Update all wishlist buttons
        this.updateWishlistButtons();
    }

    // Update all wishlist buttons visual state
    updateWishlistButtons() {
        const wishlistButtons = document.querySelectorAll('[data-wishlist-id]');
        wishlistButtons.forEach(button => {
            const productId = parseInt(button.getAttribute('data-wishlist-id'));
            const isInWishlist = this.isInWishlist(productId);
            const wishlistText = button.querySelector('.wishlist-text');

            if (isInWishlist) {
                button.classList.add('active');
                button.querySelector('i').className = 'lni lni-heart-filled';
                if (wishlistText) {
                    wishlistText.textContent = 'Remove from Wishlist';
                }
            } else {
                button.classList.remove('active');
                button.querySelector('i').className = 'lni lni-heart';
                if (wishlistText) {
                    wishlistText.textContent = 'Add to Wishlist';
                }
            }
        });
    }

    // Bind events to wishlist buttons
    bindEvents() {
        document.addEventListener('click', (e) => {
            const wishlistButton = e.target.closest('[data-wishlist-id]');
            if (wishlistButton) {
                e.preventDefault();
                e.stopPropagation();

                const productId = parseInt(wishlistButton.getAttribute('data-wishlist-id'));
                const added = this.toggleWishlist(productId);
                const wishlistText = wishlistButton.querySelector('.wishlist-text');

                if (added) {
                    wishlistButton.classList.add('active');
                    wishlistButton.querySelector('i').className = 'lni lni-heart-filled';
                    if (wishlistText) {
                        wishlistText.textContent = 'Remove from Wishlist';
                    }
                    this.showNotification('Товар добавлен в избранное!', 'success');
                } else {
                    wishlistButton.classList.remove('active');
                    wishlistButton.querySelector('i').className = 'lni lni-heart';
                    if (wishlistText) {
                        wishlistText.textContent = 'Add to Wishlist';
                    }
                    this.showNotification('Товар удален из избранного!', 'info');
                }
            }
        });

        // Handle wishlist link clicks
        document.addEventListener('click', (e) => {
            const wishlistLink = e.target.closest('.wishlist a');
            if (wishlistLink) {
                e.preventDefault();
                this.handleWishlistLinkClick();
            }
        });
    }

    // Handle wishlist link click
    handleWishlistLinkClick() {
        const wishlist = this.getWishlist();
        if (wishlist.length === 0) {
            this.showNotification('Ваш список избранного пуст!', 'info');
            return;
        }

        // Redirect to wishlist page with product IDs
        const wishlistUrl = new URL('/wishlist', window.location.origin);
        wishlistUrl.searchParams.set('ids', wishlist.join(','));
        window.location.href = wishlistUrl.toString();
    }

    // Show notification to user using SweetAlert2
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

// Initialize wishlist manager when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.wishlistManager = new WishlistManager();
});