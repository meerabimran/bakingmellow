from flask import Flask, render_template, request, redirect, url_for, session, jsonify
import sqlite3
import hashlib
import os

app = Flask(__name__)
app.secret_key = 'bakingmellow_secret_key_2026'

# --- SQLITE DATABASE SETUP (NO MYSQL NEEDED) ---
def init_db():
    conn = sqlite3.connect('bakery.db')
    c = conn.cursor()
    # Users table
    c.execute('''CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        firstname TEXT,
        lastname TEXT,
        email TEXT UNIQUE,
        password TEXT
    )''')
    # Orders table
    c.execute('''CREATE TABLE IF NOT EXISTS orders (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER,
        total REAL,
        order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )''')
    # Contact messages table
    c.execute('''CREATE TABLE IF NOT EXISTS contact_messages (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT,
        email TEXT,
        message TEXT
    )''')
    conn.commit()
    conn.close()

# Initialize database on startup
init_db()

# --- ROUTES ---
@app.route('/')
@app.route('/home')
def home():
    return render_template('home.html')

@app.route('/menu')
def menu():
    return render_template('menu.html')

@app.route('/about')
def about():
    return render_template('about.html')

@app.route('/social')
def social():
    return render_template('social.html')

@app.route('/contact')
def contact():
    return render_template('contact.html')

@app.route('/cart')
def cart():
    return render_template('cart.html')

@app.route('/order')
def order():
    return render_template('order.html')

@app.route('/login')
def login():
    return render_template('login.html')

@app.route('/signup')
def signup():
    return render_template('signup.html')

@app.route('/my_orders')
def my_orders():
    if 'user_id' not in session:
        return redirect(url_for('login'))
    conn = sqlite3.connect('bakery.db')
    c = conn.cursor()
    c.execute("SELECT id, total, order_date FROM orders WHERE user_id = ? ORDER BY order_date DESC", (session['user_id'],))
    orders = c.fetchall()
    conn.close()
    return render_template('my_orders.html', orders=orders)

@app.route('/order_success')
def order_success():
    if 'user_id' not in session:
        return redirect(url_for('login'))
    conn = sqlite3.connect('bakery.db')
    c = conn.cursor()
    c.execute("SELECT id, total, order_date FROM orders WHERE user_id = ? ORDER BY order_date DESC LIMIT 1", (session['user_id'],))
    order = c.fetchone()
    conn.close()
    return render_template('order_success.html', order=order)

# --- API ROUTES ---
@app.route('/api/signup', methods=['POST'])
def api_signup():
    data = request.json
    fname = data.get('firstname')
    lname = data.get('lastname')
    email = data.get('email')
    password = data.get('password')
    
    hashed = hashlib.sha256(password.encode()).hexdigest()
    
    conn = sqlite3.connect('bakery.db')
    c = conn.cursor()
    try:
        c.execute("INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)", (fname, lname, email, hashed))
        conn.commit()
        return jsonify({'success': True, 'message': 'Account created!'})
    except sqlite3.IntegrityError:
        return jsonify({'success': False, 'error': 'Email already exists!'})
    finally:
        conn.close()

@app.route('/api/login', methods=['POST'])
def api_login():
    data = request.json
    email = data.get('email')
    password = data.get('password')
    
    hashed = hashlib.sha256(password.encode()).hexdigest()
    
    conn = sqlite3.connect('bakery.db')
    c = conn.cursor()
    c.execute("SELECT id, firstname FROM users WHERE email = ? AND password = ?", (email, hashed))
    user = c.fetchone()
    conn.close()
    
    if user:
        session['user_id'] = user[0]
        session['user_name'] = user[1]
        return jsonify({'success': True, 'user_id': user[0], 'name': user[1]})
    else:
        return jsonify({'success': False, 'error': 'Invalid email or password'})

@app.route('/api/place-order', methods=['POST'])
def api_place_order():
    if 'user_id' not in session:
        return jsonify({'success': False, 'error': 'Please login first!'})
    
    data = request.json
    total = data.get('total')
    
    conn = sqlite3.connect('bakery.db')
    c = conn.cursor()
    c.execute("INSERT INTO orders (user_id, total) VALUES (?, ?)", (session['user_id'], total))
    conn.commit()
    order_id = c.lastrowid
    conn.close()
    
    return jsonify({'success': True, 'order_id': order_id})

@app.route('/api/contact', methods=['POST'])
def api_contact():
    data = request.json
    name = data.get('name')
    email = data.get('email')
    message = data.get('message')
    
    conn = sqlite3.connect('bakery.db')
    c = conn.cursor()
    c.execute("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)", (name, email, message))
    conn.commit()
    conn.close()
    
    return jsonify({'success': True, 'message': 'Message sent!'})

# --- SERVE STATIC FILES ---
from flask import send_from_directory
@app.route('/<path:filename>')
def static_files(filename):
    if filename.endswith('.html'):
        return render_template(filename)
    return send_from_directory('.', filename)

if __name__ == '__main__':
    app.run(debug=True, port=8000)