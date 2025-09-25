"""
Web Shopping Bot - BOT สำหรับกดสินค้าในเว็บไซต์
"""

import time
import random
import logging
import os
from datetime import datetime
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.chrome.options import Options
from selenium.common.exceptions import TimeoutException, NoSuchElementException, WebDriverException
from webdriver_manager.chrome import ChromeDriverManager
from colorama import init, Fore, Style
from config import BotConfig

# Initialize colorama for colored output
init(autoreset=True)

class WebShoppingBot:
    def __init__(self):
        self.driver = None
        self.wait = None
        self.config = BotConfig()
        self.setup_logging()
        self.setup_directories()
        
    def setup_logging(self):
        """ตั้งค่าระบบ logging"""
        logging.basicConfig(
            level=getattr(logging, self.config.LOG_LEVEL),
            format='%(asctime)s - %(levelname)s - %(message)s',
            handlers=[
                logging.FileHandler(self.config.LOG_FILE, encoding='utf-8'),
                logging.StreamHandler()
            ]
        )
        self.logger = logging.getLogger(__name__)
        
    def setup_directories(self):
        """สร้างโฟลเดอร์ที่จำเป็น"""
        if self.config.SAVE_SCREENSHOTS:
            os.makedirs(self.config.SCREENSHOT_DIR, exist_ok=True)
            
    def setup_driver(self):
        """ตั้งค่า WebDriver"""
        try:
            chrome_options = Options()
            
            if self.config.HEADLESS:
                chrome_options.add_argument("--headless")
            
            # เพิ่มการตั้งค่าเพื่อหลีกเลี่ยงการตรวจจับ bot
            chrome_options.add_argument("--disable-blink-features=AutomationControlled")
            chrome_options.add_experimental_option("excludeSwitches", ["enable-automation"])
            chrome_options.add_experimental_option('useAutomationExtension', False)
            chrome_options.add_argument("--user-agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36")
            
            # Try to use chromium-browser first, then fallback to chrome
            import shutil
            if shutil.which("chromium-browser"):
                chrome_options.binary_location = "/usr/bin/chromium-browser"
                self.logger.info("🌐 ใช้ Chromium Browser")
            elif shutil.which("google-chrome"):
                self.logger.info("🌐 ใช้ Google Chrome")
            else:
                self.logger.info("🌐 ใช้ Chrome เริ่มต้น")
                
            service = Service(ChromeDriverManager().install())
            self.driver = webdriver.Chrome(service=service, options=chrome_options)
            
            # ซ่อน webdriver property
            self.driver.execute_script("Object.defineProperty(navigator, 'webdriver', {get: () => undefined})")
            
            self.driver.implicitly_wait(self.config.IMPLICIT_WAIT)
            self.driver.set_page_load_timeout(self.config.PAGE_LOAD_TIMEOUT)
            self.wait = WebDriverWait(self.driver, self.config.IMPLICIT_WAIT)
            
            self.logger.info("✅ WebDriver ตั้งค่าเรียบร้อยแล้ว")
            return True
            
        except Exception as e:
            self.logger.error(f"❌ ไม่สามารถตั้งค่า WebDriver: {e}")
            return False
    
    def random_delay(self, min_delay=None, max_delay=None):
        """หน่วงเวลาแบบสุ่มเพื่อหลีกเลี่ยงการตรวจจับ"""
        min_delay = min_delay or self.config.MIN_DELAY
        max_delay = max_delay or self.config.MAX_DELAY
        delay = random.uniform(min_delay, max_delay)
        time.sleep(delay)
        
    def take_screenshot(self, filename_suffix=""):
        """บันทึกภาพหน้าจอ"""
        if not self.config.SAVE_SCREENSHOTS or not self.driver:
            return
            
        timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
        filename = f"screenshot_{timestamp}{filename_suffix}.png"
        filepath = os.path.join(self.config.SCREENSHOT_DIR, filename)
        
        try:
            self.driver.save_screenshot(filepath)
            self.logger.info(f"📸 บันทึกภาพหน้าจอ: {filepath}")
        except Exception as e:
            self.logger.error(f"❌ ไม่สามารถบันทึกภาพหน้าจอ: {e}")
    
    def navigate_to_website(self, url=None):
        """เข้าสู่เว็บไซต์เป้าหมาย"""
        url = url or self.config.TARGET_URL
        
        try:
            self.logger.info(f"🌐 กำลังเข้าสู่เว็บไซต์: {url}")
            self.driver.get(url)
            self.random_delay(2, 4)
            
            self.logger.info(f"✅ เข้าสู่เว็บไซต์สำเร็จ: {self.driver.title}")
            return True
            
        except Exception as e:
            self.logger.error(f"❌ ไม่สามารถเข้าสู่เว็บไซต์: {e}")
            self.take_screenshot("_navigation_error")
            return False
    
    def login(self, username=None, password=None):
        """เข้าสู่ระบบ"""
        username = username or self.config.USERNAME
        password = password or self.config.PASSWORD
        
        if not username or not password:
            self.logger.warning("⚠️ ไม่มี username หรือ password")
            return False
            
        try:
            self.logger.info("🔑 กำลังเข้าสู่ระบบ...")
            
            # หาช่องใส่ username (ลองหลายๆ selector)
            username_selectors = [
                "input[name='username']",
                "input[name='email']", 
                "input[type='email']",
                "input[id='username']",
                "input[id='email']",
                "#username",
                "#email"
            ]
            
            username_field = None
            for selector in username_selectors:
                try:
                    username_field = self.wait.until(EC.presence_of_element_located((By.CSS_SELECTOR, selector)))
                    break
                except TimeoutException:
                    continue
                    
            if not username_field:
                self.logger.error("❌ ไม่พบช่องใส่ username")
                return False
                
            # ใส่ username
            username_field.clear()
            for char in username:
                username_field.send_keys(char)
                time.sleep(random.uniform(0.05, 0.15))
            
            self.random_delay(1, 2)
            
            # หาช่องใส่ password
            password_selectors = [
                "input[name='password']",
                "input[type='password']",
                "input[id='password']",
                "#password"
            ]
            
            password_field = None
            for selector in password_selectors:
                try:
                    password_field = self.driver.find_element(By.CSS_SELECTOR, selector)
                    break
                except NoSuchElementException:
                    continue
                    
            if not password_field:
                self.logger.error("❌ ไม่พบช่องใส่ password")
                return False
                
            # ใส่ password
            password_field.clear()
            for char in password:
                password_field.send_keys(char)
                time.sleep(random.uniform(0.05, 0.15))
                
            self.random_delay(1, 2)
            
            # หาปุ่ม login
            login_selectors = [
                "button[type='submit']",
                "input[type='submit']",
                "button:contains('เข้าสู่ระบบ')",
                "button:contains('Login')",
                ".login-btn",
                "#login-btn"
            ]
            
            login_button = None
            for selector in login_selectors:
                try:
                    if ":contains(" in selector:
                        # ใช้ XPath สำหรับ text-based search
                        text = selector.split(":contains('")[1].split("')")[0]
                        login_button = self.driver.find_element(By.XPATH, f"//button[contains(text(), '{text}')]")
                    else:
                        login_button = self.driver.find_element(By.CSS_SELECTOR, selector)
                    break
                except NoSuchElementException:
                    continue
                    
            if login_button:
                login_button.click()
            else:
                # ลองกด Enter ในช่อง password
                password_field.send_keys(Keys.RETURN)
                
            self.random_delay(3, 5)
            
            # ตรวจสอบการเข้าสู่ระบบ
            current_url = self.driver.current_url
            if "login" not in current_url.lower():
                self.logger.info("✅ เข้าสู่ระบบสำเร็จ")
                return True
            else:
                self.logger.error("❌ เข้าสู่ระบบไม่สำเร็จ")
                self.take_screenshot("_login_failed")
                return False
                
        except Exception as e:
            self.logger.error(f"❌ เกิดข้อผิดพลาดในการเข้าสู่ระบบ: {e}")
            self.take_screenshot("_login_error")
            return False
    
    def search_product(self, keyword):
        """ค้นหาสินค้า"""
        try:
            self.logger.info(f"🔍 กำลังค้นหาสินค้า: {keyword}")
            
            # หาช่องค้นหา
            search_selectors = [
                "input[name='search']",
                "input[type='search']",
                "input[placeholder*='ค้นหา']",
                "input[placeholder*='Search']",
                ".search-input",
                "#search",
                "[data-testid='search-input']"
            ]
            
            search_box = None
            for selector in search_selectors:
                try:
                    search_box = self.wait.until(EC.presence_of_element_located((By.CSS_SELECTOR, selector)))
                    break
                except TimeoutException:
                    continue
                    
            if not search_box:
                self.logger.error("❌ ไม่พบช่องค้นหา")
                return False
                
            # ใส่คำค้นหา
            search_box.clear()
            for char in keyword:
                search_box.send_keys(char)
                time.sleep(random.uniform(0.05, 0.15))
                
            self.random_delay(1, 2)
            
            # กดค้นหา
            search_box.send_keys(Keys.RETURN)
            
            self.random_delay(3, 5)
            
            self.logger.info("✅ ค้นหาสินค้าเสร็จสิ้น")
            return True
            
        except Exception as e:
            self.logger.error(f"❌ เกิดข้อผิดพลาดในการค้นหา: {e}")
            self.take_screenshot("_search_error")
            return False
    
    def select_product(self, max_price=None):
        """เลือกสินค้า"""
        max_price = max_price or self.config.MAX_PRICE
        
        try:
            self.logger.info(f"🛍️ กำลังเลือกสินค้า (ราคาสูงสุด: {max_price} บาท)")
            
            # รอให้ผลการค้นหาโหลด
            self.wait.until(EC.presence_of_element_located((By.CSS_SELECTOR, "[class*='product'], [class*='item'], .product-card")))
            
            # หาสินค้าทั้งหมด
            product_selectors = [
                "[class*='product']",
                "[class*='item']", 
                ".product-card",
                "[data-testid*='product']"
            ]
            
            products = []
            for selector in product_selectors:
                try:
                    products = self.driver.find_elements(By.CSS_SELECTOR, selector)
                    if products:
                        break
                except:
                    continue
                    
            if not products:
                self.logger.error("❌ ไม่พบสินค้า")
                return False
                
            self.logger.info(f"พบสินค้าทั้งหมด {len(products)} รายการ")
            
            # เลือกสินค้าที่เหมาะสม
            for i, product in enumerate(products[:10]):  # ตรวจสอบแค่ 10 รายการแรก
                try:
                    # หาราคา
                    price_text = ""
                    price_selectors = [
                        "[class*='price']",
                        "[class*='cost']", 
                        ".price",
                        "[data-testid*='price']"
                    ]
                    
                    for price_selector in price_selectors:
                        try:
                            price_element = product.find_element(By.CSS_SELECTOR, price_selector)
                            price_text = price_element.text
                            break
                        except:
                            continue
                    
                    # แยกตัวเลขจากราคา
                    import re
                    price_numbers = re.findall(r'[\d,]+', price_text.replace(',', ''))
                    
                    if price_numbers:
                        price = int(price_numbers[0])
                        
                        if price <= max_price:
                            self.logger.info(f"✅ เลือกสินค้า ราคา {price} บาท")
                            
                            # คลิกเลือกสินค้า
                            self.driver.execute_script("arguments[0].scrollIntoView(true);", product)
                            self.random_delay(1, 2)
                            product.click()
                            
                            self.random_delay(2, 4)
                            return True
                            
                except Exception as e:
                    self.logger.debug(f"ข้ามสินค้ารายการที่ {i+1}: {e}")
                    continue
                    
            self.logger.warning("⚠️ ไม่พบสินค้าที่เหมาะสมในช่วงราคาที่กำหนด")
            return False
            
        except Exception as e:
            self.logger.error(f"❌ เกิดข้อผิดพลาดในการเลือกสินค้า: {e}")
            self.take_screenshot("_select_product_error")
            return False
    
    def add_to_cart(self, quantity=None):
        """เพิ่มสินค้าลงตะกร้า"""
        quantity = quantity or self.config.QUANTITY
        
        try:
            self.logger.info(f"🛒 กำลังเพิ่มสินค้าลงตะกร้า (จำนวน: {quantity})")
            
            # หาช่องใส่จำนวน
            quantity_selectors = [
                "input[name='quantity']",
                "input[type='number']",
                "[class*='quantity'] input",
                ".qty input",
                "#quantity"
            ]
            
            for selector in quantity_selectors:
                try:
                    qty_input = self.driver.find_element(By.CSS_SELECTOR, selector)
                    qty_input.clear()
                    qty_input.send_keys(str(quantity))
                    self.random_delay(1, 2)
                    break
                except:
                    continue
            
            # หาปุ่มเพิ่มลงตะกร้า
            add_cart_selectors = [
                "button:contains('เพิ่มลงตะกร้า')",
                "button:contains('Add to Cart')",
                "[class*='add-cart']",
                "[class*='add-to-cart']",
                ".add-cart-btn",
                "#add-to-cart"
            ]
            
            add_cart_button = None
            for selector in add_cart_selectors:
                try:
                    if ":contains(" in selector:
                        text = selector.split(":contains('")[1].split("')")[0]
                        add_cart_button = self.driver.find_element(By.XPATH, f"//button[contains(text(), '{text}')]")
                    else:
                        add_cart_button = self.driver.find_element(By.CSS_SELECTOR, selector)
                    break
                except:
                    continue
                    
            if not add_cart_button:
                self.logger.error("❌ ไม่พบปุ่มเพิ่มลงตะกร้า")
                return False
                
            # คลิกเพิ่มลงตะกร้า
            self.driver.execute_script("arguments[0].scrollIntoView(true);", add_cart_button)
            self.random_delay(1, 2)
            add_cart_button.click()
            
            self.random_delay(3, 5)
            
            self.logger.info("✅ เพิ่มสินค้าลงตะกร้าเรียบร้อย")
            return True
            
        except Exception as e:
            self.logger.error(f"❌ เกิดข้อผิดพลาดในการเพิ่มลงตะกร้า: {e}")
            self.take_screenshot("_add_cart_error")
            return False
    
    def proceed_to_checkout(self):
        """ดำเนินการสั่งซื้อ"""
        try:
            self.logger.info("💳 กำลังดำเนินการสั่งซื้อ...")
            
            # หาปุ่มไปหน้าตะกร้า/สั่งซื้อ
            checkout_selectors = [
                "button:contains('สั่งซื้อ')",
                "button:contains('Checkout')",
                "button:contains('ไปหน้าตะกร้า')",
                "[class*='checkout']",
                ".checkout-btn",
                "#checkout"
            ]
            
            checkout_button = None
            for selector in checkout_selectors:
                try:
                    if ":contains(" in selector:
                        text = selector.split(":contains('")[1].split("')")[0]
                        checkout_button = self.driver.find_element(By.XPATH, f"//button[contains(text(), '{text}')]")
                    else:
                        checkout_button = self.driver.find_element(By.CSS_SELECTOR, selector)
                    break
                except:
                    continue
                    
            if not checkout_button:
                self.logger.error("❌ ไม่พบปุ่มสั่งซื้อ")
                return False
                
            # คลิกสั่งซื้อ
            self.driver.execute_script("arguments[0].scrollIntoView(true);", checkout_button)
            self.random_delay(1, 2)
            checkout_button.click()
            
            self.random_delay(3, 5)
            
            self.logger.info("✅ ดำเนินการสั่งซื้อเรียบร้อย")
            return True
            
        except Exception as e:
            self.logger.error(f"❌ เกิดข้อผิดพลาดในการสั่งซื้อ: {e}")
            self.take_screenshot("_checkout_error")
            return False
    
    def run_bot(self, keywords=None):
        """เรียกใช้ BOT หลัก"""
        keywords = keywords or self.config.PRODUCT_KEYWORDS
        
        try:
            self.logger.info("🤖 เริ่มต้นการทำงานของ BOT")
            
            # ตั้งค่า WebDriver
            if not self.setup_driver():
                return False
                
            # เข้าสู่เว็บไซต์
            if not self.navigate_to_website():
                return False
                
            # เข้าสู่ระบบ (ถ้ามี credentials)
            if self.config.USERNAME and self.config.PASSWORD:
                if not self.navigate_to_website(self.config.LOGIN_URL):
                    return False
                if not self.login():
                    return False
                    
            # ค้นหาและซื้อสินค้า
            for keyword in keywords:
                self.logger.info(f"🎯 กำลังประมวลผลสินค้า: {keyword}")
                
                if not self.search_product(keyword):
                    continue
                    
                if not self.select_product():
                    continue
                    
                if not self.add_to_cart():
                    continue
                    
                if not self.proceed_to_checkout():
                    continue
                    
                self.logger.info(f"✅ สำเร็จ! ดำเนินการสั่งซื้อ '{keyword}' เรียบร้อย")
                break  # หยุดหลังจากซื้อสินค้าแรกสำเร็จ
                
            return True
            
        except Exception as e:
            self.logger.error(f"❌ เกิดข้อผิดพลาดในการทำงานของ BOT: {e}")
            return False
            
        finally:
            self.cleanup()
    
    def cleanup(self):
        """ทำความสะอาดและปิด WebDriver"""
        if self.driver:
            try:
                self.logger.info("🧹 กำลังปิด WebDriver...")
                self.driver.quit()
                self.logger.info("✅ ปิด WebDriver เรียบร้อย")
            except Exception as e:
                self.logger.error(f"❌ เกิดข้อผิดพลาดในการปิด WebDriver: {e}")

if __name__ == "__main__":
    # แสดงข้อความต้นรับ
    print(f"{Fore.CYAN}{'='*50}")
    print(f"{Fore.CYAN}🤖 Web Shopping Bot - BOT กดสินค้า")
    print(f"{Fore.CYAN}{'='*50}")
    print(f"{Fore.YELLOW}⚠️  กรุณาตั้งค่าไฟล์ .env ก่อนใช้งาน")
    print(f"{Fore.GREEN}✅ พร้อมเริ่มทำงาน...")
    print()
    
    # สร้างและเรียกใช้ BOT
    bot = WebShoppingBot()
    success = bot.run_bot()
    
    if success:
        print(f"{Fore.GREEN}🎉 BOT ทำงานเสร็จสิ้น!")
    else:
        print(f"{Fore.RED}❌ BOT ทำงานไม่สำเร็จ กรุณาตรวจสอบ log")