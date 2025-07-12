import string
import random
import sqlite3
from flask import Flask, request, redirect, jsonify
import validators
from flask_cors import CORS
import logging



app = Flask(__name__)
CORS(app)  # Enable CORS for all routes

# Set up logging
logging.basicConfig(level=logging.DEBUG)


# Generate a random short code
def generate_short_code(length=6):
    characters = string.ascii_letters + string.digits
    return ''.join(random.choices(characters, k=length))


# Initialize database
def init_db():
    try:
        conn = sqlite3.connect('url_shortener.db')
        c = conn.cursor()
        c.execute('''CREATE TABLE IF NOT EXISTS urls (
                        id INTEGER PRIMARY KEY,
                        short_code TEXT UNIQUE,
                        original_url TEXT
                    )''')
        conn.commit()
        conn.close()
        print("Database initialized successfully")
    except Exception as e:
        print(f"Database initialization error: {e}")


@app.route('/shorten', methods=['POST'])
def shorten_url():
    try:
        # Debug: Print incoming request data
        print(f"Request content type: {request.content_type}")
        print(f"Request data: {request.data}")

        # Handle different content types
        if request.content_type == 'application/json':
            data = request.get_json()
        else:
            # Handle form data or other formats
            data = request.get_json(force=True, silent=True)
            if not data:
                # Try form data
                data = {'url': request.form.get('url') or request.args.get('url')}

        print(f"Parsed data: {data}")

        if not data or 'url' not in data or not data['url']:
            return jsonify({'error': 'No URL provided'}), 400

        original_url = data.get('url').strip()

        # Add protocol if missing
        if not original_url.startswith(('http://', 'https://')):
            original_url = 'https://' + original_url

        # Validate URL
        if not validators.url(original_url):
            return jsonify({'error': 'Invalid URL provided'}), 400

        # Database operations
        conn = sqlite3.connect('url_shortener.db')
        c = conn.cursor()

        # Check if URL already exists
        c.execute('SELECT short_code FROM urls WHERE original_url = ?', (original_url,))
        result = c.fetchone()
        if result:
            short_url = request.host_url + result[0]
            conn.close()
            return jsonify({'short_url': short_url})

        # Generate unique short code
        max_attempts = 10
        attempts = 0
        while attempts < max_attempts:
            short_code = generate_short_code()
            c.execute('SELECT id FROM urls WHERE short_code = ?', (short_code,))
            if not c.fetchone():
                break
            attempts += 1

        if attempts >= max_attempts:
            conn.close()
            return jsonify({'error': 'Could not generate unique short code'}), 500

        # Insert new URL
        c.execute('INSERT INTO urls (short_code, original_url) VALUES (?, ?)', (short_code, original_url))
        conn.commit()
        conn.close()

        short_url = request.host_url + short_code
        return jsonify({'short_url': short_url})

    except sqlite3.Error as e:
        print(f"Database error: {e}")
        return jsonify({'error': 'Database error occurred'}), 500
    except Exception as e:
        print(f"ERROR: {e}")
        import traceback
        traceback.print_exc()
        return jsonify({'error': f'Internal server error: {str(e)}'}), 500


@app.route('/<short_code>', methods=['GET'])
def redirect_to_url(short_code):
    try:
        conn = sqlite3.connect('url_shortener.db')
        c = conn.cursor()
        c.execute('SELECT original_url FROM urls WHERE short_code = ?', (short_code,))
        result = c.fetchone()
        conn.close()

        if result:
            return redirect(result[0])
        else:
            return jsonify({'error': 'URL not found'}), 404
    except Exception as e:
        print(f"Redirect error: {e}")
        return jsonify({'error': 'Internal server error'}), 500


# Health check endpoint
@app.route('/health', methods=['GET'])
def health_check():
    return jsonify({'status': 'OK', 'message': 'URL Shortener is running'})


# CORS preflight handler
@app.before_request
def handle_preflight():
    if request.method == "OPTIONS":
        response = jsonify()
        response.headers.add("Access-Control-Allow-Origin", "*")
        response.headers.add('Access-Control-Allow-Headers', "*")
        response.headers.add('Access-Control-Allow-Methods', "*")
        return response

#Urls history logic
@app.route('/user-urls', methods=['GET'])
def get_user_urls():
    try:
        conn = sqlite3.connect('url_shortener.db')
        c = conn.cursor()

        # Get latest 3 URLs (you can add user-based filtering later)
        c.execute('SELECT short_code, original_url FROM urls ORDER BY id DESC LIMIT 3')
        results = c.fetchall()
        conn.close()

        urls = []
        for result in results:
            short_code, original_url = result
            short_url = request.host_url + short_code
            urls.append({
                'short_code': short_code,
                'short_url': short_url,
                'original_url': original_url
            })

        return jsonify({'urls': urls})

    except Exception as e:
        print(f"Error fetching user URLs: {e}")
        return jsonify({'error': 'Could not fetch URLs'}), 500


if __name__ == '__main__':
    init_db()
    app.run(debug=True, host='0.0.0.0', port=5000)