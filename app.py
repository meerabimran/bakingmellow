from flask import Flask, render_template, request, redirect, url_for, session, jsonify
import mysql.connector
import hashlib
import os

app = Flask(__name__)
app.secret_key = 'bakingmellow_secret_2026'

# --- DATABASE CONNECTION ---
def get_db_connection():
    return mysql.connector.connect(
        host="localhost",
        user="root",
        password="Meerab@1234",
        database="demo_db"
    )

# --- ROUTES FOR HTML PAGES ---
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

@app.route('/order_success')
def order_success():
    if 'user_id' not in session:
        return redirect(url_for('login'))
    
    conn = get_db_connection()
    cursor = conn.cursor(dictionary=True)
    cursor.execute("SELECT id, total, order_date FROM orders WHERE user_id = ? ORDER BY order_date DESC LIMIT 1", (session['user_id'],))
    order = cursor.fetchone()
    conn.close()
    return render_template('order_success.html', order=order)

@app.route('/my_orders')
def my_orders():
    if 'user_id' not in session:
        return redirect(url_for('login'))
    
    conn = get_db_connection()
    cursor = conn.cursor(dictionary=True)
    cursor.execute("SELECT id, total, order_date FROM orders WHERE user_id = ? ORDER BY order_date DESC", (session['user_id'],))
    orders = cursor.fetchall()
    conn.close()
    return render_template('my_orders.html', orders=orders)

# --- API ROUTES (Backend Logic for AJAX) ---
@app.route('/api/signup', methods=['POST'])
def api_signup():
    data = request.json
    fname = data.get('firstname')
    lname = data.get('lastname')
    email = data.get('email')
    password = data.get('password')
    
    # Hash the password
    hashed = hashlib.sha256(password.encode()).hexdigest()
    
    conn = get_db_connection()
    cursor = conn.cursor()
    try:
        cursor.execute("INSERT INTO users (firstname, lastname, email, password) VALUES (%s, %s, %s, %s)", (fname, lname, email, hashed))
        conn.commit()
        return jsonify({'success': True, 'message': 'Account created!'})
    except mysql.connector.Error as err:
        if err.errno == 1062:  # Duplicate entry error
            return jsonify({'success': False, 'error': 'Email already exists!'})
        return jsonify({'success': False, 'error': str(err)})
    finally:
        conn.close()

@app.route('/api/login', methods=['POST'])
def api_login():
    data = request.json
    email = data.get('email')
    password = data.get('password')
    
    hashed = hashlib.sha256(password.encode()).hexdigest()
    
    conn = get_db_connection()
    cursor = conn.cursor(dictionary=True)
    cursor.execute("SELECT id, firstname FROM users WHERE email = %s AND password = %s", (email, hashed))
    user = cursor.fetchone()
    conn.close()
    
    if user:
        session['user_id'] = user['id']
        session['user_name'] = user['firstname']
        return jsonify({'success': True, 'user_id': user['id'], 'name': user['firstname']})
    else:
        return jsonify({'success': False, 'error': 'Invalid email or password'})

@app.route('/api/place-order', methods=['POST'])
def api_place_order():
    if 'user_id' not in session:
        return jsonify({'success': False, 'error': 'Please login first!'})
    
    data = request.json
    total = data.get('total')
    
    conn = get_db_connection()
    cursor = conn.cursor()
    cursor.execute("INSERT INTO orders (user_id, total) VALUES (%s, %s)", (session['user_id'], total))
    conn.commit()
    order_id = cursor.lastrowid
    conn.close()
    
    return jsonify({'success': True, 'order_id': order_id})

@app.route('/api/contact', methods=['POST'])
def api_contact():
    data = request.json
    name = data.get('name')
    email = data.get('email')
    message = data.get('message')
    
    conn = get_db_connection()
    cursor = conn.cursor()
    cursor.execute("INSERT INTO contact_messages (name, email, message) VALUES (%s, %s, %s)", (name, email, message))
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