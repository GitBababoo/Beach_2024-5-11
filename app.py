"""
Flask Web Server สำหรับ BOT กดสินค้าอัตโนมัติ
"""

from flask import Flask, request, jsonify, render_template_string, send_from_directory
import json
import os
import threading
from bot_structure import ProductBot

app = Flask(__name__)

# เก็บข้อมูล BOT ที่กำลังทำงาน
active_bots = {}

@app.route('/')
def home():
    return send_from_directory('.', 'bot_interface.html')

@app.route('/test-connection', methods=['POST'])
def test_connection():
    try:
        data = request.json
        website_url = data.get('website_url')

        if not website_url:
            return jsonify({
                'success': False,
                'message': 'กรุณาระบุ URL เว็บไซต์'
            })

        # ทดสอบการเชื่อมต่อ
        bot = ProductBot(base_url=website_url)
        response = bot.session.get(website_url)

        if response.status_code == 200:
            return jsonify({
                'success': True,
                'message': 'เชื่อมต่อสำเร็จ'
            })
        else:
            return jsonify({
                'success': False,
                'message': f'ไม่สามารถเชื่อมต่อได้ (Status: {response.status_code})'
            })

    except Exception as e:
        return jsonify({
            'success': False,
            'message': f'เกิดข้อผิดพลาด: {str(e)}'
        })

@app.route('/run-bot', methods=['POST'])
def run_bot():
    try:
        data = request.json
        website_url = data.get('website_url')
        username = data.get('username')
        password = data.get('password')
        product_name = data.get('product_name')
        quantity = data.get('quantity', 1)

        if not all([website_url, username, password, product_name]):
            return jsonify({
                'success': False,
                'message': 'กรุณากรอกข้อมูลให้ครบถ้วน'
            })

        # สร้างและรัน BOT
        bot = ProductBot(
            base_url=website_url,
            username=username,
            password=password
        )

        # รันใน thread แยกเพื่อไม่ให้ block
        def run_bot_thread():
            try:
                success = bot.auto_buy(product_name, quantity)
                active_bots['status'] = 'completed' if success else 'failed'
            except Exception as e:
                active_bots['status'] = f'error: {str(e)}'
            finally:
                bot.close()

        bot_thread = threading.Thread(target=run_bot_thread)
        bot_thread.start()

        active_bots['status'] = 'running'
        active_bots['product'] = product_name

        return jsonify({
            'success': True,
            'message': 'BOT เริ่มทำงานแล้ว'
        })

    except Exception as e:
        return jsonify({
            'success': False,
            'message': f'เกิดข้อผิดพลาด: {str(e)}'
        })

@app.route('/monitor-price', methods=['POST'])
def monitor_price():
    try:
        data = request.json
        website_url = data.get('website_url')
        username = data.get('username')
        password = data.get('password')
        product_name = data.get('product_name')
        target_price = data.get('target_price')

        if not all([website_url, username, password, product_name, target_price]):
            return jsonify({
                'success': False,
                'message': 'กรุณากรอกข้อมูลให้ครบถ้วน'
            })

        # สร้าง BOT สำหรับตรวจสอบราคา
        bot = ProductBot(
            base_url=website_url,
            username=username,
            password=password
        )

        # รันใน thread แยก
        def monitor_thread():
            try:
                bot.monitor_product(product_name, target_price)
                active_bots['monitor_status'] = 'completed'
            except Exception as e:
                active_bots['monitor_status'] = f'error: {str(e)}'
            finally:
                bot.close()

        monitor_thread = threading.Thread(target=monitor_thread)
        monitor_thread.start()

        active_bots['monitor_status'] = 'running'
        active_bots['monitoring_product'] = product_name

        return jsonify({
            'success': True,
            'message': 'เริ่มตรวจสอบราคาแล้ว'
        })

    except Exception as e:
        return jsonify({
            'success': False,
            'message': f'เกิดข้อผิดพลาด: {str(e)}'
        })

@app.route('/status')
def get_status():
    return jsonify(active_bots)

@app.route('/stop', methods=['POST'])
def stop_bot():
    # หยุดการทำงานของ BOT
    active_bots.clear()
    return jsonify({
        'success': True,
        'message': 'หยุดการทำงานแล้ว'
    })

if __name__ == '__main__':
    # สร้างโฟลเดอร์สำหรับเก็บข้อมูล
    if not os.path.exists('logs'):
        os.makedirs('logs')

    print("🚀 เริ่มต้น BOT Server...")
    print("🌐 เปิดเว็บที่: http://localhost:5000")
    app.run(debug=True, host='0.0.0.0', port=5000)