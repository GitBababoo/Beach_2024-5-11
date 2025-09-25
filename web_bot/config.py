"""
Configuration file for Web Shopping Bot
ไฟล์การตั้งค่าสำหรับ BOT กดสินค้า
"""

import os
from dotenv import load_dotenv

# Load environment variables
load_dotenv()

class BotConfig:
    # Browser settings
    BROWSER_TYPE = "chrome"  # chrome, firefox, edge
    HEADLESS = False  # True = ไม่แสดงหน้าต่างเบราว์เซอร์
    IMPLICIT_WAIT = 10  # รอเวลาในการหา element (วินาที)
    PAGE_LOAD_TIMEOUT = 30  # เวลารอโหลดหน้าเว็บ (วินาที)
    
    # Website settings
    TARGET_URL = os.getenv('TARGET_URL', 'https://example-shop.com')
    LOGIN_URL = os.getenv('LOGIN_URL', 'https://example-shop.com/login')
    
    # Login credentials (ควรใส่ใน .env file)
    USERNAME = os.getenv('USERNAME', '')
    PASSWORD = os.getenv('PASSWORD', '')
    
    # Product settings
    PRODUCT_KEYWORDS = ["สินค้าที่ต้องการ"]  # คำค้นหาสินค้า
    MAX_PRICE = 1000  # ราคาสูงสุดที่ยอมรับได้
    QUANTITY = 1  # จำนวนที่ต้องการซื้อ
    
    # Retry settings
    MAX_RETRIES = 3  # จำนวนครั้งที่ลองใหม่เมื่อเกิดข้อผิดพลาด
    RETRY_DELAY = 5  # เวลารอระหว่างการลองใหม่ (วินาที)
    
    # Delays (เพื่อไม่ให้ดูเหมือน bot)
    MIN_DELAY = 1  # เวลารอขั้นต่ำระหว่างการกระทำ
    MAX_DELAY = 3  # เวลารอสูงสุดระหว่างการกระทำ
    
    # Screenshots
    SAVE_SCREENSHOTS = True  # บันทึกภาพหน้าจอเมื่อเกิดข้อผิดพลาด
    SCREENSHOT_DIR = "screenshots"
    
    # Logging
    LOG_LEVEL = "INFO"  # DEBUG, INFO, WARNING, ERROR
    LOG_FILE = "bot.log"