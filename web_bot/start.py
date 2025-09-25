#!/usr/bin/env python3
"""
สคริปต์เริ่มต้นใช้งาน Web Shopping Bot
Quick start script for Web Shopping Bot
"""

import os
import sys
from pathlib import Path
from colorama import init, Fore, Style

# Initialize colorama
init(autoreset=True)

def print_banner():
    """แสดงแบนเนอร์"""
    print(f"{Fore.CYAN}{'='*60}")
    print(f"{Fore.CYAN}🤖 Web Shopping Bot - BOT กดสินค้าอัตโนมัติ")
    print(f"{Fore.CYAN}   Automated Web Shopping Bot")
    print(f"{Fore.CYAN}{'='*60}")
    print()

def check_env_file():
    """ตรวจสอบไฟล์ .env"""
    env_file = Path(".env")
    if not env_file.exists():
        print(f"{Fore.RED}❌ ไม่พบไฟล์ .env")
        print(f"{Fore.YELLOW}กรุณารันคำสั่ง: python3 install.py")
        return False
    return True

def show_quick_setup():
    """แสดงการตั้งค่าด่วน"""
    print(f"{Fore.YELLOW}⚡ การตั้งค่าด่วน:")
    print(f"{Fore.WHITE}1. แก้ไขไฟล์ .env:")
    
    env_content = """TARGET_URL=https://www.lazada.co.th
LOGIN_URL=https://member.lazada.co.th/user/login
USERNAME=your_email@example.com
PASSWORD=your_password
MAX_PRICE=5000
QUANTITY=1"""
    
    print(f"{Fore.GREEN}{env_content}")
    print()
    
    print(f"{Fore.WHITE}2. หรือใช้การตั้งค่าเริ่มต้น (Lazada)")
    print()

def get_user_choice():
    """รับคำตอบจากผู้ใช้"""
    print(f"{Fore.CYAN}เลือกการทำงาน:")
    print(f"{Fore.WHITE}1. รัน BOT ด้วยการตั้งค่าเริ่มต้น (ทดสอบ)")
    print(f"{Fore.WHITE}2. ดูตัวอย่างการใช้งาน")
    print(f"{Fore.WHITE}3. ตั้งค่าและรัน BOT")
    print(f"{Fore.WHITE}4. ออกจากโปรแกรม")
    print()
    
    while True:
        try:
            choice = input(f"{Fore.YELLOW}ใส่หมายเลข (1-4): ").strip()
            if choice in ['1', '2', '3', '4']:
                return int(choice)
            else:
                print(f"{Fore.RED}❌ กรุณาใส่หมายเลข 1-4")
        except KeyboardInterrupt:
            print(f"\n{Fore.YELLOW}👋 ยกเลิกการทำงาน")
            sys.exit(0)
        except:
            print(f"{Fore.RED}❌ กรุณาใส่หมายเลข 1-4")

def run_test_bot():
    """รัน BOT ทดสอบ"""
    print(f"{Fore.GREEN}🚀 เริ่มรัน BOT ทดสอบ...")
    print(f"{Fore.YELLOW}⚠️  BOT จะค้นหาสินค้าใน Lazada แต่ไม่ซื้อจริง")
    print()
    
    try:
        from web_bot import WebShoppingBot
        
        bot = WebShoppingBot()
        
        # Override config for testing
        bot.config.TARGET_URL = "https://www.lazada.co.th"
        bot.config.PRODUCT_KEYWORDS = ["หูฟัง bluetooth"]
        bot.config.MAX_PRICE = 2000
        bot.config.HEADLESS = False  # แสดงเบราว์เซอร์เพื่อดู
        
        print(f"{Fore.INFO}📋 การตั้งค่าทดสอบ:")
        print(f"   เว็บไซต์: {bot.config.TARGET_URL}")
        print(f"   ค้นหา: {bot.config.PRODUCT_KEYWORDS}")
        print(f"   ราคาสูงสุด: {bot.config.MAX_PRICE} บาท")
        print()
        
        # Setup and navigate
        if bot.setup_driver():
            bot.navigate_to_website()
            bot.search_product(bot.config.PRODUCT_KEYWORDS[0])
            
            print(f"{Fore.GREEN}✅ ทดสอบเสร็จสิ้น!")
            print(f"{Fore.YELLOW}💡 ตรวจสอบหน้าต่างเบราว์เซอร์เพื่อดูผลลัพธ์")
            input(f"{Fore.CYAN}กด Enter เพื่อปิดเบราว์เซอร์...")
            
        bot.cleanup()
        
    except ImportError:
        print(f"{Fore.RED}❌ ไม่สามารถ import WebShoppingBot")
        print(f"{Fore.YELLOW}กรุณารันคำสั่ง: source venv/bin/activate && python3 install.py")
    except Exception as e:
        print(f"{Fore.RED}❌ เกิดข้อผิดพลาด: {e}")

def run_examples():
    """รันตัวอย่าง"""
    print(f"{Fore.GREEN}📚 เรียกใช้ตัวอย่างการใช้งาน...")
    try:
        os.system("python3 example_usage.py")
    except:
        print(f"{Fore.RED}❌ ไม่สามารถเรียกใช้ตัวอย่าง")

def run_interactive_setup():
    """ตั้งค่าแบบโต้ตอบ"""
    print(f"{Fore.GREEN}⚙️ การตั้งค่าแบบโต้ตอบ")
    print(f"{Fore.YELLOW}กรุณาใส่ข้อมูลต่อไปนี้:")
    print()
    
    # Get user input
    target_url = input(f"{Fore.CYAN}URL เว็บไซต์เป้าหมาย (เช่น https://www.lazada.co.th): ").strip()
    if not target_url:
        target_url = "https://www.lazada.co.th"
    
    keywords = input(f"{Fore.CYAN}สินค้าที่ต้องการค้นหา (เช่น iPhone, หูฟัง): ").strip()
    if not keywords:
        keywords = "หูฟัง bluetooth"
    
    try:
        max_price = int(input(f"{Fore.CYAN}ราคาสูงสุด (บาท): ").strip() or "5000")
    except:
        max_price = 5000
    
    try:
        quantity = int(input(f"{Fore.CYAN}จำนวนที่ต้องการ: ").strip() or "1")
    except:
        quantity = 1
    
    headless = input(f"{Fore.CYAN}ซ่อนเบราว์เซอร์? (y/n): ").strip().lower() == 'y'
    
    print(f"\n{Fore.GREEN}📋 สรุปการตั้งค่า:")
    print(f"   เว็บไซต์: {target_url}")
    print(f"   ค้นหา: {keywords}")
    print(f"   ราคาสูงสุด: {max_price:,} บาท")
    print(f"   จำนวน: {quantity}")
    print(f"   ซ่อนเบราว์เซอร์: {'ใช่' if headless else 'ไม่'}")
    print()
    
    confirm = input(f"{Fore.YELLOW}เริ่มทำงาน? (y/n): ").strip().lower()
    if confirm != 'y':
        print(f"{Fore.YELLOW}ยกเลิกการทำงาน")
        return
    
    # Run bot with custom settings
    try:
        from web_bot import WebShoppingBot
        
        bot = WebShoppingBot()
        
        # Apply custom settings
        bot.config.TARGET_URL = target_url
        bot.config.PRODUCT_KEYWORDS = [keywords]
        bot.config.MAX_PRICE = max_price
        bot.config.QUANTITY = quantity
        bot.config.HEADLESS = headless
        
        print(f"\n{Fore.GREEN}🚀 เริ่มทำงาน...")
        success = bot.run_bot()
        
        if success:
            print(f"{Fore.GREEN}🎉 BOT ทำงานเสร็จสิ้น!")
        else:
            print(f"{Fore.RED}❌ BOT ทำงานไม่สำเร็จ")
            
    except Exception as e:
        print(f"{Fore.RED}❌ เกิดข้อผิดพลาด: {e}")

def main():
    """ฟังก์ชันหลัก"""
    print_banner()
    
    # Check if we're in virtual environment
    if not hasattr(sys, 'real_prefix') and not (hasattr(sys, 'base_prefix') and sys.base_prefix != sys.prefix):
        print(f"{Fore.YELLOW}⚠️  แนะนำให้เปิดใช้งาน virtual environment:")
        print(f"{Fore.WHITE}   source venv/bin/activate")
        print()
    
    if not check_env_file():
        return
    
    while True:
        choice = get_user_choice()
        
        if choice == 1:
            run_test_bot()
        elif choice == 2:
            run_examples()
        elif choice == 3:
            run_interactive_setup()
        elif choice == 4:
            print(f"{Fore.GREEN}👋 ขอบคุณที่ใช้งาน Web Shopping Bot!")
            break
        
        print(f"\n{'-'*60}\n")

if __name__ == "__main__":
    try:
        main()
    except KeyboardInterrupt:
        print(f"\n{Fore.YELLOW}👋 ยกเลิกการทำงาน")
        sys.exit(0)