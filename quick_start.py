#!/usr/bin/env python3
"""
Quick Start Script สำหรับ BOT กดสินค้าอัตโนมัติ
ใช้งานง่าย เพียงแก้ไขค่าตัวแปรด้านล่าง
"""

from bot_structure import ProductBot

# กำหนดค่าเว็บไซต์และข้อมูลผู้ใช้
WEBSITE_URL = "https://example-shop.com"
USERNAME = "your_username"
PASSWORD = "your_password"

# สินค้าที่ต้องการซื้อ
PRODUCT_NAME = "iPhone 15"
QUANTITY = 1

# ข้อมูลการชำระเงิน
PAYMENT_INFO = {
    'payment_method': 'credit_card',
    'shipping_address': 'ที่อยู่จัดส่งของคุณ'
}

def main():
    print("🤖 เริ่มต้น BOT กดสินค้าอัตโนมัติ")
    print(f"🌐 เว็บไซต์: {WEBSITE_URL}")
    print(f"📱 สินค้า: {PRODUCT_NAME}")
    print(f"🔢 จำนวน: {QUANTITY}")
    print("-" * 50)

    # สร้าง BOT
    bot = ProductBot(
        base_url=WEBSITE_URL,
        username=USERNAME,
        password=PASSWORD
    )

    try:
        # เริ่มซื้อสินค้า
        success = bot.auto_buy(PRODUCT_NAME, QUANTITY, PAYMENT_INFO)

        if success:
            print("✅ การซื้อสำเร็จ!")
        else:
            print("❌ การซื้อไม่สำเร็จ")

    except Exception as e:
        print(f"❌ เกิดข้อผิดพลาด: {e}")

    finally:
        bot.close()

if __name__ == "__main__":
    main()