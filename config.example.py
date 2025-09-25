"""
ไฟล์กำหนดค่าตัวอย่างสำหรับ BOT
คัดลอกไฟล์นี้เป็น config.py และปรับแต่งค่าตามต้องการ
"""

# ข้อมูลเว็บไซต์
WEBSITE_CONFIG = {
    'base_url': 'https://example-shop.com',
    'login_url': '/login',
    'search_url': '/search',
    'cart_url': '/cart/add',
    'checkout_url': '/checkout'
}

# ข้อมูลผู้ใช้ (ควรเก็บเป็นความลับ)
USER_CREDENTIALS = {
    'username': 'your_username',
    'password': 'your_password'
}

# การตั้งค่า BOT
BOT_CONFIG = {
    'headless': True,  # เปิด headless mode (ไม่มี GUI)
    'check_interval': 60,  # ตรวจสอบราคาทุก 60 วินาที
    'max_retries': 3,  # ลองใหม้สูงสุด 3 ครั้ง
    'timeout': 30  # หมดเวลา 30 วินาที
}

# ข้อมูลการชำระเงิน
PAYMENT_INFO = {
    'payment_method': 'credit_card',
    'card_number': '4111111111111111',
    'expiry_date': '12/25',
    'cvv': '123',
    'shipping_address': 'ที่อยู่จัดส่ง'
}

# สินค้าที่ต้องการซื้อ
TARGET_PRODUCTS = [
    {
        'name': 'iPhone 15',
        'quantity': 1,
        'target_price': 30000
    },
    {
        'name': 'Samsung Galaxy S24',
        'quantity': 1,
        'target_price': 25000
    }
]