#!/bin/bash

# Web Shopping Bot - สคริปต์เริ่มต้นใช้งาน
# Quick start script for Web Shopping Bot

echo "🤖 Web Shopping Bot - เริ่มต้นใช้งาน"
echo "=================================="

# ตรวจสอบว่ามี virtual environment หรือไม่
if [ ! -d "venv" ]; then
    echo "❌ ไม่พบ virtual environment"
    echo "กรุณารันคำสั่ง: python3 -m venv venv && source venv/bin/activate && pip install -r requirements.txt"
    exit 1
fi

# เปิดใช้งาน virtual environment
echo "⚡ เปิดใช้งาน virtual environment..."
source venv/bin/activate

# ตรวจสอบการติดตั้ง dependencies
echo "📦 ตรวจสอบ dependencies..."
python3 -c "import selenium, requests, colorama" 2>/dev/null
if [ $? -ne 0 ]; then
    echo "❌ ไม่พบ dependencies ที่จำเป็น"
    echo "กำลังติดตั้ง dependencies..."
    pip install -r requirements.txt
fi

# เรียกใช้สคริปต์หลัก
echo "🚀 เริ่มต้น Web Shopping Bot..."
echo ""
python3 start.py