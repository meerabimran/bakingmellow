-- Create database
CREATE DATABASE IF NOT EXISTS baking_mellow_db;
USE baking_mellow_db;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    firstname VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image_url VARCHAR(255),
    is_bestseller BOOLEAN DEFAULT FALSE,
    is_new BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Orders table
CREATE TABLE IF NOT EXISTS orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    order_number VARCHAR(20) UNIQUE NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'confirmed', 'preparing', 'out_for_delivery', 'delivered', 'cancelled') DEFAULT 'pending',
    payment_method VARCHAR(50),
    delivery_address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Order items
CREATE TABLE IF NOT EXISTS order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Contact messages
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(200),
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Newsletter subscribers
CREATE TABLE IF NOT EXISTS newsletter (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(100) UNIQUE NOT NULL,
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sample products
INSERT INTO products (name, category, description, price, image_url, is_bestseller) VALUES
('Belgian Chocolate Cake', 'Cakes', 'Rich cocoa sponge layered with silky Belgian chocolate ganache and crowned with delicate dark chocolate curls.', 3250.00, 'product-chocolate-cake.jpg', TRUE),
('Butter Croissant', 'Pastries', 'Hand-laminated with cultured French butter, revealing crisp golden layers that shatter with every bite.', 750.00, 'product-croissant.jpg', TRUE),
('Strawberry Tart', 'Tarts', 'Buttery pâte sucrée filled with vanilla bean crème pâtissière and finished with vibrant hand-selected strawberries.', 1850.00, 'product-strawberry-tart.jpg', FALSE),
('Cinnamon Roll', 'Pastries', 'Soft spirals infused with Saigon cinnamon, brown sugar, and finished with a smooth vanilla glaze.', 650.00, 'product-cinnamon-roll.jpg', TRUE),
('Classic Cheesecake', 'Cakes', 'Creamy baked cheesecake resting on a buttery biscuit crust with a velvety finish.', 2800.00, 'product-cheesecake.jpg', FALSE),
('Almond Biscotti', 'Cookies', 'Twice-baked with roasted almonds for a crisp texture and warm toasted aroma.', 450.00, 'product-biscotti.jpg', FALSE),
('Red Velvet Cake', 'Cakes', 'Velvety cocoa sponge balanced with whipped cream cheese frosting and delicate crimson crumbs.', 3200.00, 'product-red-velvet.jpg', TRUE),
('Macarons', 'Cookies', 'Delicate almond shells embracing silky fillings in vibrant handcrafted flavors.', 2200.00, 'product-macarons.jpg', FALSE);
-- Add admin table
CREATE TABLE IF NOT EXISTS admins (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin account
INSERT INTO admins (username, password, email) 
VALUES ('admin', SHA2('admin123', 256), 'admin@bakingmellow.com');
-- =========================================
-- INSERT YOUR ACTUAL BAKERY PRODUCTS HERE
-- =========================================

-- CAKES
INSERT INTO products (name, category, description, price, image_url, is_bestseller) VALUES
('Chocolate Dream Cake', 'Cakes', 'Rich Belgian chocolate fudge layered with silky dark chocolate ganache.', 2000.00, 'chocake.png', TRUE),
('Classic Vanilla Cake', 'Cakes', 'Light Madagascar vanilla sponge layered with velvety buttercream.', 2000.00, 'cake.jpeg', FALSE),
('Red Velvet Cake', 'Cakes', 'Velvety red sponge with cream cheese frosting and a hint of cocoa.', 2200.00, 'redvelvet.jpg', FALSE),
('Black Forest Cake', 'Cakes', 'Chocolate sponge with cherries, whipped cream, and chocolate shavings.', 2500.00, 'blackforest.jpg', FALSE),
('Lotus Cake', 'Cakes', 'Caramelized lotus biscuit sponge with creamy lotus buttercream.', 2300.00, 'lotuscake.jpg', FALSE),
('Ferrero Cake', 'Cakes', 'Hazelnut sponge with Nutella filling and crushed hazelnuts.', 2800.00, 'ferrerocake.jpg', FALSE);

-- CUPCAKES
INSERT INTO products (name, category, description, price, image_url, is_bestseller) VALUES
('Chocolate Cupcake', 'Cupcakes', 'Moist chocolate sponge with rich chocolate buttercream.', 350.00, 'cupcake_choco.jpg', TRUE),
('Vanilla Cupcake', 'Cupcakes', 'Light vanilla sponge with creamy vanilla buttercream.', 350.00, 'cupcake_vanilla.jpg', FALSE),
('Red Velvet Cupcake', 'Cupcakes', 'Red velvet sponge with cream cheese frosting.', 400.00, 'cupcake_redvelvet.jpg', FALSE),
('Oreo Cupcake', 'Cupcakes', 'Chocolate sponge with Oreo buttercream and crushed Oreo topping.', 400.00, 'cupcake_oreo.jpg', FALSE),
('Lotus Cupcake', 'Cupcakes', 'Biscoff flavored sponge with lotus buttercream and biscuit crumb.', 400.00, 'cupcake_lotus.jpg', FALSE),
('Strawberry Cupcake', 'Cupcakes', 'Vanilla sponge with fresh strawberry buttercream and strawberry compote.', 380.00, 'cupcake_strawberry.jpg', FALSE);

-- DONUTS
INSERT INTO products (name, category, description, price, image_url, is_bestseller) VALUES
('Chocolate Donut', 'Donuts', 'Classic glazed donut dipped in rich chocolate ganache.', 250.00, 'donut_choco.jpg', TRUE),
('Oreo Donut', 'Donuts', 'Glazed donut with Oreo crumb topping and vanilla drizzle.', 280.00, 'donut_oreo.jpg', FALSE),
('Lotus Donut', 'Donuts', 'Glazed donut topped with Biscoff spread and biscuit crumbs.', 280.00, 'donut_lotus.jpg', FALSE),
('Nutella Donut', 'Donuts', 'Glazed donut filled with Nutella and topped with chocolate glaze.', 300.00, 'donut_nutella.jpg', FALSE),
('Sprinkles Donut', 'Donuts', 'Classic glazed donut with rainbow sprinkles and vanilla glaze.', 250.00, 'donut_sprinkles.jpg', FALSE),
('Caramel Donut', 'Donuts', 'Glazed donut with caramel drizzle and sea salt.', 270.00, 'donut_caramel.jpg', FALSE);

-- BROWNIES
INSERT INTO products (name, category, description, price, image_url, is_bestseller) VALUES
('Classic Brownie', 'Brownies', 'Rich, fudgy chocolate brownie with a classic crackly top.', 300.00, 'brownie_classic.jpg', TRUE),
('Walnut Brownie', 'Brownies', 'Chocolate brownie loaded with crunchy walnuts.', 350.00, 'brownie_walnut.jpg', FALSE),
('Nutella Brownie', 'Brownies', 'Fudgy brownie swirled with creamy Nutella.', 380.00, 'brownie_nutella.jpg', FALSE),
('Fudge Brownie', 'Brownies', 'Ultra-dense, gooey fudge brownie with melted chocolate chunks.', 350.00, 'brownie_fudge.jpg', FALSE),
('Oreo Brownie', 'Brownies', 'Fudgy brownie topped with Oreo pieces and white chocolate drizzle.', 380.00, 'brownie_oreo.jpg', FALSE);

-- BOUQUETS
INSERT INTO products (name, category, description, price, image_url, is_bestseller) VALUES
('Chocolate Bouquet', 'Bouquets', 'A beautiful bouquet made of assorted chocolate bars and truffles.', 2500.00, 'bouquet_choco.jpg', TRUE),
('Ferrero Bouquet', 'Bouquets', 'Elegant bouquet made of Ferrero Rocher chocolates.', 3000.00, 'bouquet_ferrero.jpg', FALSE),
('Mixed Donut Bouquet', 'Bouquets', 'A variety of donuts beautifully arranged in a bouquet.', 3500.00, 'bouquet_mixed.jpg', FALSE),
('Rose Bouquet', 'Bouquets', 'Classic red roses with chocolate accents and decorative wrapping.', 2800.00, 'bouquet_rose.jpg', FALSE),
('Customized Bouquet', 'Bouquets', 'Create your own bouquet with your favorite chocolates and flowers.', 3500.00, 'bouquet_custom.jpg', FALSE);

-- GIFT BASKETS
INSERT INTO products (name, category, description, price, image_url, is_bestseller) VALUES
('Chocolate Basket', 'Gift Baskets', 'A basket filled with assorted premium chocolates and truffles.', 4500.00, 'basket_chocolate.jpg', TRUE),
('Snack Basket', 'Gift Baskets', 'Curated basket of sweet and savory snacks for any occasion.', 3800.00, 'giftbasket.png', FALSE),
('Birthday Basket', 'Gift Baskets', 'Special basket with birthday treats, candles, and decorations.', 5000.00, 'basket_birthday.jpg', FALSE),
('Luxury Basket', 'Gift Baskets', 'Premium gourmet basket with fine chocolates, wines, and more.', 8000.00, 'basket.png', FALSE),
('Customized Basket', 'Gift Baskets', 'Build your own gift basket with your favorite items.', 4000.00, 'basket_custom.jpg', FALSE);