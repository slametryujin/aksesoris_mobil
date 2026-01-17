// Cart functionality - Working Add to Cart

(function() {
    'use strict';
    
    // Find project root and build API URL
    var CART_API_URL = (function() {
        var path = window.location.pathname;
        var segments = path.split('/');
        var projectRoot = '';
        
        // Build path until we find 'api' or end
        for (var i = 0; i < segments.length; i++) {
            if (segments[i] === 'api') {
                projectRoot = segments.slice(0, i).join('/') || '/';
                break;
            }
            if (segments[i] === 'admin' && i > 0) {
                projectRoot = segments.slice(0, i).join('/') || '/';
                break;
            }
        }
        
        if (!projectRoot) {
            projectRoot = '/aksesoris_mobil';
        }
        
        return projectRoot + '/api/cart_handler.php';
    })();
    
    // Add to Cart - Main function
    window.addToCart = function(productId, quantity) {
        if (!productId || productId <= 0) {
            alert('Invalid product');
            return;
        }
        
        quantity = quantity || 1;
        
        // Find button
        var button = document.querySelector('[data-product-id="' + productId + '"].btn-add-cart-debug') ||
                     document.querySelector('[data-product-id="' + productId + '"]');
        
        var originalText = 'Add to Cart';
        if (button) {
            originalText = button.textContent.trim() || 'Add to Cart';
            button.disabled = true;
            button.textContent = 'Adding...';
        }
        
        var formData = new FormData();
        formData.append('action', 'add');
        formData.append('product_id', String(productId));
        formData.append('quantity', String(quantity));
        
        // Create timeout controller
        var controller = new AbortController();
        var timeoutId = setTimeout(function() {
            controller.abort();
        }, 10000);
        
        fetch(CART_API_URL, {
            method: 'POST',
            body: formData,
            signal: controller.signal
        })
        .then(function(response) {
            clearTimeout(timeoutId);
            
            if (!response.ok) {
                throw new Error('HTTP ' + response.status);
            }
            return response.text();
        })
        .then(function(text) {
            // Always reset button state first
            if (button) {
                button.disabled = false;
                button.textContent = originalText;
                button.style.background = '';
            }
            
            // Parse JSON
            var data;
            try {
                data = JSON.parse(text);
            } catch (e) {
                console.error('Invalid JSON:', text);
                alert('Server error. Please try again.');
                return;
            }
            
            if (data.success) {
                // Update cart count ONLY after server success
                var counter = document.getElementById('cart-counter');
                if (counter) {
                    counter.textContent = data.cart_count;
                    counter.style.display = data.cart_count > 0 ? 'flex' : 'none';
                }
                
                // Show success on button
                if (button) {
                    button.textContent = '✓ Added!';
                    button.style.background = '#4caf50';
                }
                
                // Reset button after delay
                setTimeout(function() {
                    if (button) {
                        button.textContent = originalText;
                        button.style.background = '';
                    }
                }, 1500);
            } else {
                // Server returned error
                alert(data.message || 'Failed to add item');
            }
        })
        .catch(function(error) {
            clearTimeout(timeoutId);
            
            // Reset button state on any failure
            if (button) {
                button.disabled = false;
                button.textContent = originalText;
                button.style.background = '';
            }
            
            // Handle different error types
            if (error.name === 'AbortError') {
                alert('Request timed out. Please try again.');
            } else if (error.message && error.message.indexOf('HTTP') === 0) {
                alert('Server error: ' + error.message);
            } else {
                alert('Network error. Please check your connection.');
            }
        });
    };
    
    // Fetch cart count from server
    window.fetchCartCount = function() {
        var formData = new FormData();
        formData.append('action', 'get_count');
        
        fetch(CART_API_URL, {
            method: 'POST',
            body: formData
        })
        .then(function(response) {
            if (!response.ok) return null;
            return response.json();
        })
        .then(function(data) {
            if (data && data.success) {
                var counter = document.getElementById('cart-counter');
                if (counter) {
                    counter.textContent = data.cart_count;
                    counter.style.display = data.cart_count > 0 ? 'flex' : 'none';
                }
            }
        })
        .catch(function(e) {});
    };
    
    // Remove from cart
    window.removeFromCart = function(productId) {
        if (!confirm('Remove item from cart?')) return;
        
        var formData = new FormData();
        formData.append('action', 'remove');
        formData.append('product_id', String(productId));
        
        fetch(CART_API_URL, {
            method: 'POST',
            body: formData
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            if (data.success) {
                window.fetchCartCount();
                location.reload();
            }
        })
        .catch(function(e) { location.reload(); });
    };
    
    // Update quantity
    window.updateQuantity = function(productId, quantity) {
        var formData = new FormData();
        formData.append('action', 'update');
        formData.append('product_id', String(productId));
        formData.append('quantity', String(quantity));
        
        fetch(CART_API_URL, {
            method: 'POST',
            body: formData
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            if (data.success) {
                window.fetchCartCount();
                location.reload();
            }
        })
        .catch(function(e) { location.reload(); });
    };
    
    // Clear cart
    window.clearCart = function() {
        if (!confirm('Clear all items from cart?')) return;
        
        var formData = new FormData();
        formData.append('action', 'clear');
        
        fetch(CART_API_URL, {
            method: 'POST',
            body: formData
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            if (data.success) {
                window.fetchCartCount();
                location.reload();
            }
        })
        .catch(function(e) { location.reload(); });
    };
    
    // Initialize
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', window.fetchCartCount);
    } else {
        window.fetchCartCount();
    }
})();

