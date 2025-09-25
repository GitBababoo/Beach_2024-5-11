"""
ตัวอย่างการใช้งาน Web Shopping Bot
Example usage of Web Shopping Bot
"""

from web_bot import WebShoppingBot
from config import BotConfig
import time

def example_basic_usage():
    """ตัวอย่างการใช้งานพื้นฐาน"""
    print("🤖 ตัวอย่างการใช้งานพื้นฐาน")
    print("-" * 40)
    
    # สร้าง BOT
    bot = WebShoppingBot()
    
    # รัน BOT ด้วยการตั้งค่าเริ่มต้น
    success = bot.run_bot()
    
    if success:
        print("✅ BOT ทำงานสำเร็จ!")
    else:
        print("❌ BOT ทำงานไม่สำเร็จ")

def example_custom_keywords():
    """ตัวอย่างการใช้งานด้วยคำค้นหาที่กำหนดเอง"""
    print("🔍 ตัวอย่างการค้นหาสินค้าหลายรายการ")
    print("-" * 40)
    
    bot = WebShoppingBot()
    
    # กำหนดสินค้าที่ต้องการหา
    custom_keywords = [
        "iPhone 15",
        "Samsung Galaxy S24", 
        "MacBook Air M2",
        "iPad Pro"
    ]
    
    success = bot.run_bot(keywords=custom_keywords)
    
    if success:
        print("✅ ค้นหาสินค้าสำเร็จ!")
    else:
        print("❌ ไม่พบสินค้าที่ต้องการ")

def example_step_by_step():
    """ตัวอย่างการควบคุมแต่ละขั้นตอน"""
    print("⚙️ ตัวอย่างการควบคุมแต่ละขั้นตอน")
    print("-" * 40)
    
    bot = WebShoppingBot()
    
    try:
        # ขั้นตอนที่ 1: ตั้งค่า WebDriver
        print("1. กำลังตั้งค่า WebDriver...")
        if not bot.setup_driver():
            print("❌ ไม่สามารถตั้งค่า WebDriver")
            return
        
        # ขั้นตอนที่ 2: เข้าสู่เว็บไซต์
        print("2. กำลังเข้าสู่เว็บไซต์...")
        if not bot.navigate_to_website("https://www.lazada.co.th"):
            print("❌ ไม่สามารถเข้าสู่เว็บไซต์")
            return
        
        # ขั้นตอนที่ 3: ค้นหาสินค้า
        print("3. กำลังค้นหาสินค้า...")
        if not bot.search_product("iPhone"):
            print("❌ ไม่สามารถค้นหาสินค้า")
            return
        
        # ขั้นตอนที่ 4: เลือกสินค้า
        print("4. กำลังเลือกสินค้า...")
        if not bot.select_product(max_price=50000):
            print("❌ ไม่พบสินค้าในช่วงราคาที่กำหนด")
            return
        
        # ขั้นตอนที่ 5: เพิ่มลงตะกร้า
        print("5. กำลังเพิ่มลงตะกร้า...")
        if not bot.add_to_cart(quantity=1):
            print("❌ ไม่สามารถเพิ่มลงตะกร้า")
            return
        
        print("✅ ดำเนินการทุกขั้นตอนสำเร็จ!")
        
        # รอให้ผู้ใช้ดูผลลัพธ์
        input("กด Enter เพื่อปิด BOT...")
        
    except Exception as e:
        print(f"❌ เกิดข้อผิดพลาด: {e}")
    
    finally:
        # ปิด WebDriver
        bot.cleanup()

def example_with_login():
    """ตัวอย่างการใช้งานพร้อมเข้าสู่ระบบ"""
    print("🔐 ตัวอย่างการเข้าสู่ระบบ")
    print("-" * 40)
    
    bot = WebShoppingBot()
    
    try:
        # ตั้งค่า WebDriver
        if not bot.setup_driver():
            return
        
        # เข้าสู่หน้า login
        if not bot.navigate_to_website("https://member.lazada.co.th/user/login"):
            return
        
        # เข้าสู่ระบบ (ต้องมี username และ password ใน .env)
        if bot.config.USERNAME and bot.config.PASSWORD:
            if bot.login():
                print("✅ เข้าสู่ระบบสำเร็จ!")
                
                # ค้นหาและซื้อสินค้า
                bot.navigate_to_website("https://www.lazada.co.th")
                bot.search_product("smartphone")
                bot.select_product()
                bot.add_to_cart()
                
            else:
                print("❌ เข้าสู่ระบบไม่สำเร็จ")
        else:
            print("⚠️ ไม่พบข้อมูลการเข้าสู่ระบบ กรุณาตั้งค่าใน .env")
    
    finally:
        bot.cleanup()

def example_multiple_products():
    """ตัวอย่างการซื้อสินค้าหลายรายการ"""
    print("🛍️ ตัวอย่างการซื้อสินค้าหลายรายการ")
    print("-" * 40)
    
    bot = WebShoppingBot()
    
    # รายการสินค้าที่ต้องการ
    shopping_list = [
        {"keyword": "หูฟัง bluetooth", "max_price": 2000, "quantity": 1},
        {"keyword": "เมาส์ไร้สาย", "max_price": 1000, "quantity": 2},
        {"keyword": "คีย์บอร์ด gaming", "max_price": 3000, "quantity": 1}
    ]
    
    try:
        if not bot.setup_driver():
            return
        
        if not bot.navigate_to_website():
            return
        
        for item in shopping_list:
            print(f"\n🎯 กำลังหา: {item['keyword']}")
            
            # ค้นหาสินค้า
            if not bot.search_product(item['keyword']):
                print(f"❌ ไม่พบ {item['keyword']}")
                continue
            
            # เลือกสินค้า
            if not bot.select_product(max_price=item['max_price']):
                print(f"❌ ไม่พบ {item['keyword']} ในช่วงราคา {item['max_price']} บาท")
                continue
            
            # เพิ่มลงตะกร้า
            if bot.add_to_cart(quantity=item['quantity']):
                print(f"✅ เพิ่ม {item['keyword']} ลงตะกร้าแล้ว")
            
            # รอสักครู่ก่อนหาสินค้าถัดไป
            time.sleep(3)
        
        print("\n🛒 เสร็จสิ้นการเพิ่มสินค้าทั้งหมด!")
        
    finally:
        bot.cleanup()

def example_price_monitoring():
    """ตัวอย่างการตรวจสอบราคาสินค้า"""
    print("💰 ตัวอย่างการตรวจสอบราคาสินค้า")
    print("-" * 40)
    
    bot = WebShoppingBot()
    
    try:
        if not bot.setup_driver():
            return
        
        if not bot.navigate_to_website():
            return
        
        # ค้นหาสินค้า
        if bot.search_product("iPhone 15"):
            print("✅ พบสินค้า iPhone 15")
            
            # ตรวจสอบราคาสินค้า 5 รายการแรก
            products = bot.driver.find_elements(By.CSS_SELECTOR, "[class*='product']")[:5]
            
            for i, product in enumerate(products, 1):
                try:
                    # หาชื่อสินค้า
                    title_element = product.find_element(By.CSS_SELECTOR, "[class*='title'], [class*='name']")
                    title = title_element.text[:50] + "..." if len(title_element.text) > 50 else title_element.text
                    
                    # หาราคา
                    price_element = product.find_element(By.CSS_SELECTOR, "[class*='price']")
                    price = price_element.text
                    
                    print(f"{i}. {title}")
                    print(f"   ราคา: {price}")
                    print()
                    
                except Exception as e:
                    print(f"{i}. ไม่สามารถอ่านข้อมูลสินค้า: {e}")
    
    finally:
        bot.cleanup()

if __name__ == "__main__":
    print("🤖 ตัวอย่างการใช้งาน Web Shopping Bot")
    print("=" * 50)
    
    # เลือกตัวอย่างที่ต้องการรัน
    examples = {
        "1": ("การใช้งานพื้นฐาน", example_basic_usage),
        "2": ("ค้นหาสินค้าหลายรายการ", example_custom_keywords),
        "3": ("ควบคุมแต่ละขั้นตอน", example_step_by_step),
        "4": ("เข้าสู่ระบบ", example_with_login),
        "5": ("ซื้อสินค้าหลายรายการ", example_multiple_products),
        "6": ("ตรวจสอบราคาสินค้า", example_price_monitoring)
    }
    
    print("\nเลือกตัวอย่างที่ต้องการรัน:")
    for key, (desc, _) in examples.items():
        print(f"{key}. {desc}")
    
    choice = input("\nใส่หมายเลข (1-6): ").strip()
    
    if choice in examples:
        desc, func = examples[choice]
        print(f"\n🚀 กำลังรัน: {desc}")
        print("=" * 50)
        func()
    else:
        print("❌ กรุณาเลือกหมายเลข 1-6")