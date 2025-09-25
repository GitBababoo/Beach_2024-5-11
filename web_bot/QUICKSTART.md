# 🚀 Quick Start - เริ่มใช้งานด่วน

## การเริ่มต้นใช้งาน 3 ขั้นตอน

### 1. เปิดใช้งาน Virtual Environment
```bash
cd /workspace/web_bot
source venv/bin/activate
```

### 2. รันคำสั่งเริ่มต้น
```bash
# วิธีที่ 1: ใช้สคริปต์อัตโนมัติ
./run.sh

# วิธีที่ 2: รันโดยตรง  
python3 start.py

# วิธีที่ 3: รัน BOT เลย
python3 web_bot.py
```

### 3. ตั้งค่าเว็บไซต์เป้าหมาย
แก้ไขไฟล์ `.env`:
```env
TARGET_URL=https://your-website.com
USERNAME=your_email@example.com  
PASSWORD=your_password
MAX_PRICE=5000
```

## 🎯 ตัวอย่างการใช้งาน

### ทดสอบพื้นฐาน (ไม่ซื้อจริง)
```bash
python3 -c "
from web_bot import WebShoppingBot
bot = WebShoppingBot()
bot.config.HEADLESS = False
bot.setup_driver()
bot.navigate_to_website('https://www.lazada.co.th')
bot.search_product('หูฟัง')
bot.cleanup()
"
```

### รันแบบกำหนดเอง
```bash
python3 example_usage.py
```

## 📋 การตั้งค่าที่สำคัญ

| ตัวแปร | ค่าเริ่มต้น | คำอธิบาย |
|--------|-------------|----------|
| `HEADLESS` | `False` | `True` = ซ่อนเบราว์เซอร์ |
| `MAX_PRICE` | `5000` | ราคาสูงสุดที่ยอมรับ (บาท) |
| `QUANTITY` | `1` | จำนวนที่ต้องการซื้อ |
| `MIN_DELAY` | `1` | เวลารอขั้นต่ำ (วินาที) |
| `MAX_DELAY` | `3` | เวลารอสูงสุด (วินาที) |

## ⚠️ ข้อควรระวัง

1. **ทดสอบก่อนใช้จริง**: ใช้ `HEADLESS=False` เพื่อดูการทำงาน
2. **ตั้งราคาสูงสุด**: เพื่อไม่ให้ซื้อสินค้าที่แพงเกินไป  
3. **ใช้อย่างรับผิดชอบ**: ปฏิบัติตามข้อกำหนดของเว็บไซต์
4. **สำรองข้อมูล**: เก็บข้อมูล login ไว้ในไฟล์ `.env` เท่านั้น

## 🔧 แก้ไขปัญหาเบื้องต้น

### BOT ไม่เริ่มทำงาน
```bash
# ตรวจสอบ dependencies
pip install -r requirements.txt

# ตรวจสอบ Chrome/Chromium
which chromium-browser
```

### ไม่พบ element
- เพิ่มเวลารอ: `IMPLICIT_WAIT = 15`
- ปรับ CSS Selectors ในโค้ด

### เว็บไซต์ตรวจจับ BOT
- ลดความเร็ว: เพิ่ม `MIN_DELAY` และ `MAX_DELAY`
- ปิด headless: `HEADLESS = False`

## 📞 ขอความช่วยเหลือ

- ดู log ไฟล์: `bot.log`
- ดูภาพหน้าจอ: โฟลเดอร์ `screenshots/`
- อ่านคู่มือเต็ม: `README.md`