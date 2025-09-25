# 🤖 Web Shopping Bot - BOT กดสินค้าอัตโนมัติ

BOT สำหรับกดสินค้าในเว็บไซต์อีคอมเมิร์ซอัตโนมัติ พัฒนาด้วย Python และ Selenium

## ✨ คุณสมบัติ

- 🔍 **ค้นหาสินค้าอัตโนมัติ** - ค้นหาสินค้าตามคำค้นหาที่กำหนด
- 💰 **กรองราคา** - เลือกสินค้าที่ราคาไม่เกินที่กำหนด
- 🛒 **เพิ่มลงตะกร้า** - เพิ่มสินค้าลงตะกร้าอัตโนมัติ
- 💳 **สั่งซื้อ** - ดำเนินการสั่งซื้อสินค้า
- 🔐 **เข้าสู่ระบบ** - รองรับการเข้าสู่ระบบอัตโนมัติ
- 📸 **บันทึกภาพหน้าจอ** - บันทึกภาพเมื่อเกิดข้อผิดพลาด
- 🤖 **หลีกเลี่ยงการตรวจจับ** - ใช้เทคนิคต่างๆ เพื่อหลีกเลี่ยงการตรวจจับว่าเป็น bot
- 📝 **ระบบ Log** - บันทึกการทำงานทุกขั้นตอน

## 📋 ความต้องการของระบบ

- Python 3.7+
- Chrome Browser
- ระบบปฏิบัติการ: Windows, macOS, Linux

## 🚀 การติดตั้ง

### 1. Clone หรือดาวน์โหลดโปรเจ็กต์

```bash
git clone <repository-url>
cd web_bot
```

### 2. ติดตั้ง Dependencies

```bash
pip install -r requirements.txt
```

### 3. ตั้งค่าไฟล์ Environment

สร้างไฟล์ `.env` จากตัวอย่าง:

```bash
cp .env.example .env
```

แก้ไขไฟล์ `.env`:

```env
# Website Configuration
TARGET_URL=https://your-target-website.com
LOGIN_URL=https://your-target-website.com/login

# Login Credentials (ถ้าต้องการเข้าสู่ระบบ)
USERNAME=your_username
PASSWORD=your_password

# Optional Settings
MAX_PRICE=1000
QUANTITY=1
```

## 🎯 การใช้งาน

### วิธีที่ 1: รันโดยตรง

```bash
python web_bot.py
```

### วิธีที่ 2: Import เป็น Module

```python
from web_bot import WebShoppingBot

# สร้าง BOT instance
bot = WebShoppingBot()

# รัน BOT
success = bot.run_bot(keywords=["สินค้าที่ต้องการ"])

if success:
    print("✅ BOT ทำงานสำเร็จ!")
else:
    print("❌ BOT ทำงานไม่สำเร็จ")
```

### วิธีที่ 3: ปรับแต่งการทำงาน

```python
from web_bot import WebShoppingBot

bot = WebShoppingBot()

# ตั้งค่า WebDriver
bot.setup_driver()

# เข้าสู่เว็บไซต์
bot.navigate_to_website("https://example-shop.com")

# เข้าสู่ระบบ (ถ้าต้องการ)
bot.login("username", "password")

# ค้นหาสินค้า
bot.search_product("iPhone")

# เลือกสินค้า
bot.select_product(max_price=30000)

# เพิ่มลงตะกร้า
bot.add_to_cart(quantity=1)

# สั่งซื้อ
bot.proceed_to_checkout()

# ปิด BOT
bot.cleanup()
```

## ⚙️ การตั้งค่า

### ไฟล์ `config.py`

```python
class BotConfig:
    # Browser settings
    BROWSER_TYPE = "chrome"
    HEADLESS = False  # True = ไม่แสดงหน้าต่างเบราว์เซอร์
    IMPLICIT_WAIT = 10
    PAGE_LOAD_TIMEOUT = 30
    
    # Website settings
    TARGET_URL = "https://example-shop.com"
    LOGIN_URL = "https://example-shop.com/login"
    
    # Product settings
    PRODUCT_KEYWORDS = ["สินค้าที่ต้องการ"]
    MAX_PRICE = 1000
    QUANTITY = 1
    
    # Delays (เพื่อหลีกเลี่ยงการตรวจจับ)
    MIN_DELAY = 1
    MAX_DELAY = 3
```

### การปรับแต่งสำหรับเว็บไซต์ต่างๆ

BOT นี้ใช้ CSS Selectors และ XPath ที่ยืดหยุ่น ซึ่งควรทำงานได้กับเว็บไซต์ส่วนใหญ่ แต่หากต้องการปรับแต่งสำหรับเว็บไซต์เฉพาะ สามารถแก้ไข selectors ในฟังก์ชันต่างๆ ได้

## 🔧 การแก้ไขปัญหา

### 1. BOT ไม่พบ element

- ตรวจสอบว่าเว็บไซต์โหลดเสร็จแล้ว
- เพิ่มเวลารอใน `IMPLICIT_WAIT`
- ปรับ CSS Selectors ให้เหมาะสมกับเว็บไซต์

### 2. เว็บไซต์ตรวจจับว่าเป็น BOT

- เปิดใช้งาน `HEADLESS = False` เพื่อดูการทำงาน
- เพิ่มเวลาหน่วงใน `MIN_DELAY` และ `MAX_DELAY`
- ใช้ VPN หรือ Proxy

### 3. การเข้าสู่ระบบไม่สำเร็จ

- ตรวจสอบ username และ password
- ตรวจสอบว่าเว็บไซต์ไม่ใช้ CAPTCHA
- ตรวจสอบ LOGIN_URL

### 4. ไม่พบสินค้า

- ตรวจสอบคำค้นหาใน `PRODUCT_KEYWORDS`
- ปรับ `MAX_PRICE` ให้เหมาะสม
- ตรวจสอบว่าสินค้ามีในสต็อก

## 📁 โครงสร้างไฟล์

```
web_bot/
├── web_bot.py          # ไฟล์หลักของ BOT
├── config.py           # การตั้งค่า
├── requirements.txt    # Dependencies
├── .env.example        # ตัวอย่างไฟล์ environment
├── .env               # ไฟล์ environment (สร้างเอง)
├── README.md          # คู่มือนี้
├── screenshots/       # โฟลเดอร์บันทึกภาพหน้าจอ
└── bot.log           # ไฟล์ log
```

## ⚠️ ข้อควรระวัง

1. **กฎหมาย**: ใช้ BOT นี้อย่างถูกต้องตามกฎหมายและข้อกำหนดของเว็บไซต์
2. **ความเร็ว**: อย่าตั้งความเร็วสูงเกินไป เพื่อหลีกเลี่ยงการโดนบล็อก
3. **ความปลอดภัย**: อย่าใส่ข้อมูลสำคัญ (password, บัตรเครดิต) ในโค้ด
4. **การทดสอบ**: ทดสอบกับข้อมูลจริงด้วยความระมัดระวัง

## 🤝 การสนับสนุน

หากมีปัญหาหรือต้องการความช่วยเหลือ:

1. ตรวจสอบไฟล์ `bot.log` เพื่อดูรายละเอียดข้อผิดพลาด
2. ตรวจสอบภาพหน้าจอในโฟลเดอร์ `screenshots/`
3. เปิด issue ใน repository

## 📄 License

โปรเจ็กต์นี้เป็น open source สำหรับการศึกษาและใช้งานส่วนตัว

---

**หมายเหตุ**: BOT นี้พัฒนาขึ้นเพื่อการศึกษาและการใช้งานส่วนตัว กรุณาใช้อย่างรับผิดชอบและปฏิบัติตามข้อกำหนดของเว็บไซต์เป้าหมาย