"""
สคริปต์ติดตั้งและตั้งค่าเริ่มต้นสำหรับ Web Shopping Bot
Installation and setup script for Web Shopping Bot
"""

import os
import sys
import subprocess
import shutil
from pathlib import Path

def check_python_version():
    """ตรวจสอบเวอร์ชัน Python"""
    print("🐍 ตรวจสอบเวอร์ชัน Python...")
    
    version = sys.version_info
    if version.major < 3 or (version.major == 3 and version.minor < 7):
        print("❌ ต้องการ Python 3.7 หรือสูงกว่า")
        print(f"   เวอร์ชันปัจจุบัน: {version.major}.{version.minor}.{version.micro}")
        return False
    
    print(f"✅ Python {version.major}.{version.minor}.{version.micro} - รองรับ")
    return True

def check_chrome_browser():
    """ตรวจสอบ Chrome Browser"""
    print("🌐 ตรวจสอบ Chrome Browser...")
    
    # ตรวจสอบใน PATH
    chrome_paths = [
        "google-chrome",
        "chrome", 
        "chromium",
        "chromium-browser",
        "/Applications/Google Chrome.app/Contents/MacOS/Google Chrome",  # macOS
        "C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe",    # Windows
        "C:\\Program Files (x86)\\Google\\Chrome\\Application\\chrome.exe"
    ]
    
    for chrome_path in chrome_paths:
        if shutil.which(chrome_path) or os.path.exists(chrome_path):
            print("✅ พบ Chrome Browser")
            return True
    
    print("⚠️  ไม่พบ Chrome Browser")
    print("   กรุณาติดตั้ง Google Chrome จาก: https://www.google.com/chrome/")
    return False

def install_requirements():
    """ติดตั้ง Python packages"""
    print("📦 กำลังติดตั้ง Python packages...")
    
    try:
        # อัปเดต pip
        subprocess.check_call([sys.executable, "-m", "pip", "install", "--upgrade", "pip"])
        
        # ติดตั้ง requirements
        requirements_file = Path(__file__).parent / "requirements.txt"
        if requirements_file.exists():
            subprocess.check_call([sys.executable, "-m", "pip", "install", "-r", str(requirements_file)])
            print("✅ ติดตั้ง packages สำเร็จ")
            return True
        else:
            print("❌ ไม่พบไฟล์ requirements.txt")
            return False
            
    except subprocess.CalledProcessError as e:
        print(f"❌ เกิดข้อผิดพลาดในการติดตั้ง: {e}")
        return False

def create_env_file():
    """สร้างไฟล์ .env"""
    print("⚙️ กำลังสร้างไฟล์ .env...")
    
    env_file = Path(__file__).parent / ".env"
    env_example = Path(__file__).parent / ".env.example"
    
    if env_file.exists():
        print("ℹ️  ไฟล์ .env มีอยู่แล้ว")
        return True
    
    if env_example.exists():
        # คัดลอกจาก .env.example
        shutil.copy(env_example, env_file)
        print("✅ สร้างไฟล์ .env จาก .env.example")
        print("📝 กรุณาแก้ไขไฟล์ .env ให้เหมาะสมกับการใช้งาน")
        return True
    else:
        # สร้างไฟล์ .env พื้นฐาน
        env_content = """# Website Configuration
TARGET_URL=https://www.lazada.co.th
LOGIN_URL=https://member.lazada.co.th/user/login

# Login Credentials (ถ้าต้องการเข้าสู่ระบบ)
USERNAME=
PASSWORD=

# Product Settings
MAX_PRICE=1000
QUANTITY=1
"""
        
        with open(env_file, 'w', encoding='utf-8') as f:
            f.write(env_content)
        
        print("✅ สร้างไฟล์ .env พื้นฐาน")
        print("📝 กรุณาแก้ไขไฟล์ .env ให้เหมาะสมกับการใช้งาน")
        return True

def create_directories():
    """สร้างโฟลเดอร์ที่จำเป็น"""
    print("📁 กำลังสร้างโฟลเดอร์...")
    
    directories = ["screenshots", "logs"]
    
    for directory in directories:
        dir_path = Path(__file__).parent / directory
        dir_path.mkdir(exist_ok=True)
        print(f"✅ สร้างโฟลเดอร์: {directory}")
    
    return True

def test_installation():
    """ทดสอบการติดตั้ง"""
    print("🧪 ทดสอบการติดตั้ง...")
    
    try:
        # ทดสอบ import modules
        import selenium
        import requests
        import colorama
        from webdriver_manager.chrome import ChromeDriverManager
        
        print("✅ Import modules สำเร็จ")
        
        # ทดสอบ ChromeDriverManager
        ChromeDriverManager().install()
        print("✅ ChromeDriver ติดตั้งสำเร็จ")
        
        return True
        
    except ImportError as e:
        print(f"❌ ไม่สามารถ import module: {e}")
        return False
    except Exception as e:
        print(f"❌ เกิดข้อผิดพลาดในการทดสอบ: {e}")
        return False

def show_next_steps():
    """แสดงขั้นตอนถัดไป"""
    print("\n" + "="*50)
    print("🎉 การติดตั้งเสร็จสิ้น!")
    print("="*50)
    
    print("\n📋 ขั้นตอนถัดไป:")
    print("1. แก้ไขไฟล์ .env ให้เหมาะสมกับเว็บไซต์ของคุณ")
    print("2. รันคำสั่ง: python web_bot.py")
    print("3. หรือดูตัวอย่างการใช้งาน: python example_usage.py")
    
    print("\n📚 ไฟล์สำคัญ:")
    print("- web_bot.py: ไฟล์หลักของ BOT")
    print("- config.py: ไฟล์การตั้งค่า")
    print("- .env: ไฟล์ environment variables")
    print("- example_usage.py: ตัวอย่างการใช้งาน")
    print("- README.md: คู่มือการใช้งาน")
    
    print("\n⚠️ ข้อควรระวัง:")
    print("- ใช้ BOT อย่างถูกต้องตามกฎหมาย")
    print("- อย่าตั้งความเร็วสูงเกินไป")
    print("- ทดสอบด้วยข้อมูลจริงด้วยความระมัดระวัง")

def main():
    """ฟังก์ชันหลัก"""
    print("🤖 Web Shopping Bot - สคริปต์ติดตั้ง")
    print("="*50)
    
    steps = [
        ("ตรวจสอบเวอร์ชัน Python", check_python_version),
        ("ตรวจสอบ Chrome Browser", check_chrome_browser),
        ("ติดตั้ง Python packages", install_requirements),
        ("สร้างไฟล์ .env", create_env_file),
        ("สร้างโฟลเดอร์", create_directories),
        ("ทดสอบการติดตั้ง", test_installation)
    ]
    
    failed_steps = []
    
    for step_name, step_func in steps:
        print(f"\n🔄 {step_name}...")
        try:
            if not step_func():
                failed_steps.append(step_name)
        except Exception as e:
            print(f"❌ เกิดข้อผิดพลาดใน {step_name}: {e}")
            failed_steps.append(step_name)
    
    print("\n" + "="*50)
    if failed_steps:
        print("⚠️ การติดตั้งเสร็จสิ้น แต่มีปัญหาในขั้นตอน:")
        for step in failed_steps:
            print(f"   - {step}")
        print("\nกรุณาแก้ไขปัญหาเหล่านี้ก่อนใช้งาน")
    else:
        show_next_steps()

if __name__ == "__main__":
    main()