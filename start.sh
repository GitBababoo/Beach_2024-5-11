#!/bin/bash

echo "🤖 เริ่มต้น BOT กดสินค้าอัตโนมัติ"
echo "================================="

# ตรวจสอบ Python
if ! command -v python3 &> /dev/null; then
    echo "❌ ไม่พบ Python 3 กรุณาติดตั้ง Python 3 ก่อน"
    exit 1
fi

# สร้าง virtual environment ถ้ายังไม่มี
if [ ! -d "bot_env" ]; then
    echo "📦 สร้าง virtual environment..."
    python3 -m venv bot_env
fi

# เปิดใช้งาน virtual environment
echo "🔧 เปิดใช้งาน virtual environment..."
source bot_env/bin/activate

# ติดตั้ง dependencies
echo "⬇️ ติดตั้ง dependencies..."
pip install -r requirements.txt

# เริ่มเซิร์ฟเวอร์
echo "🚀 เริ่มต้นเซิร์ฟเวอร์..."
echo "🌐 เปิดเว็บที่: http://localhost:5000"
python3 app.py