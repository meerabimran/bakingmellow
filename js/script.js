// Cart functionality
function addToCart(productId, name, variantId, variantName, price) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    // Check if item already exists
    const existingItem = cart.find(item => 
        item.productId === productId && item.variantId === variantId
    );
    
    if (existingItem) {
        existingItem.qty += 1;
    } else {
        cart.push({
            productId: productId,
            name: name,
            variantId: variantId,
            variantName: variantName,
            price: price,
            qty: 1
        });
    }
    
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartBadge();
    showNotification('Item added to cart! 🛒');
}

function removeFromCart(index) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    cart.splice(index, 1);
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartBadge();
    displayCart();
}

function updateQuantity(index, change) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    if (cart[index]) {
        cart[index].qty += change;
        if (cart[index].qty < 1) cart[index].qty = 1;
        localStorage.setItem('cart', JSON.stringify(cart));
        displayCart();
    }
}

function displayCart() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const container = document.getElementById('cartItems');
    const totalEl = document.getElementById('totalPrice');
    const orderBtn = document.getElementById('orderBtn');
    
    if (!container) return;
    
    let total = 0;
    
    if (cart.length === 0) {
        container.innerHTML = `
            <tr>
                <td colspan="5" style="padding: 60px; text-align: center; color: var(--text-light);">
                    <div style="font-size: 60px; margin-bottom: 20px;">🛒</div>
                    <h3>Your cart is empty</h3>
                    <p>Browse our menu and add some delicious treats!</p>
                    <a href="/menu" class="btn-primary" style="margin-top: 15px; display: inline-block;">Browse Menu</a>
                </td>
            </tr>
        `;
        if (orderBtn) orderBtn.disabled = true;
        if (totalEl) totalEl.textContent = '0';
        return;
    }
    
    container.innerHTML = cart.map((item, index) => {
        const itemTotal = item.price * item.qty;
        total += itemTotal;
        return `
            <tr>
                <td style="text-align: left; padding: 15px;">
                    <strong>${item.name}</strong>
                </td>
                <td>${item.variantName}</td>
                <td>
                    <div class="qty-controls" style="display: flex; align-items: center; gap: 10px; justify-content: center;">
                        <button onclick="updateQuantity(${index}, -1)" style="background: none; border: 1px solid #ddd; border-radius: 4px; width: 30px; height: 30px; cursor: pointer;">−</button>
                        <span style="font-weight: 600; min-width: 20px;">${item.qty}</span>
                        <button onclick="updateQuantity(${index}, 1)" style="background: none; border: 1px solid #ddd; border-radius: 4px; width: 30px; height: 30px; cursor: pointer;">+</button>
                    </div>
                </td>
                <td style="font-weight: 600;">Rs ${itemTotal}</td>
                <td>
                    <button onclick="removeFromCart(${index})" style="background: none; border: none; color: #c0392b; cursor: pointer; font-size: 18px;">
                        <i class="fa-regular fa-trash-can"></i>
                    </button>
                </td>
            </tr>
        `;
    }).join('');
    
    if (orderBtn) orderBtn.disabled = false;
    if (totalEl) totalEl.textContent = total;
}

// Notification system
function showNotification(message) {
    const existing = document.querySelector('.notification');
    if (existing) existing.remove();
    
    const notification = document.createElement('div');
    notification.className = 'notification';
    notification.innerHTML = message;
    notification.style.cssText = `
        position: fixed;
        top: 80px;
        right: 20px;
        background: var(--primary-color);
        color: white;
        padding: 15px 25px;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(139, 107, 74, 0.3);
        z-index: 9999;
        animation: slideIn 0.3s ease;
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Add animation styles
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100px); opacity: 0; }
    }
`;
document.head.appendChild(style);

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    if (window.location.pathname === '/cart') {
        displayCart();
    }
    updateCartBadge();
});