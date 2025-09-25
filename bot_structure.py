"""
BOT สำหรับกดสินค้าอัตโนมัติ
โครงสร้างพื้นฐานสำหรับการพัฒนา BOT
"""

import requests
import time
import json
from bs4 import BeautifulSoup
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.chrome.options import Options

class ProductBot:
    def __init__(self, base_url, username=None, password=None):
        self.base_url = base_url
        self.username = username
        self.password = password
        self.session = requests.Session()
        self.driver = None

    def setup_driver(self, headless=True):
        """ตั้งค่า WebDriver สำหรับ Selenium"""
        chrome_options = Options()
        if headless:
            chrome_options.add_argument("--headless")
        chrome_options.add_argument("--no-sandbox")
        chrome_options.add_argument("--disable-dev-shm-usage")

        self.driver = webdriver.Chrome(options=chrome_options)

    def login(self):
        """ฟังก์ชันสำหรับล็อกอิน"""
        if not self.username or not self.password:
            print("กรุณาระบุ username และ password")
            return False

        try:
            # ตัวอย่างการล็อกอิน (ปรับตามเว็บจริง)
            login_data = {
                'username': self.username,
                'password': self.password
            }

            response = self.session.post(f"{self.base_url}/login", data=login_data)

            if response.status_code == 200:
                print("ล็อกอินสำเร็จ")
                return True
            else:
                print("ล็อกอินไม่สำเร็จ")
                return False

        except Exception as e:
            print(f"เกิดข้อผิดพลาดในการล็อกอิน: {e}")
            return False

    def search_product(self, product_name):
        """ค้นหาสินค้า"""
        try:
            search_url = f"{self.base_url}/search"
            search_data = {'q': product_name}

            response = self.session.get(search_url, params=search_data)

            if response.status_code == 200:
                soup = BeautifulSoup(response.text, 'html.parser')
                products = soup.find_all('div', class_='product-item')

                product_list = []
                for product in products:
                    name = product.find('h3', class_='product-name').text.strip()
                    price = product.find('span', class_='price').text.strip()
                    product_id = product.find('a')['href'].split('/')[-1]

                    product_list.append({
                        'name': name,
                        'price': price,
                        'id': product_id
                    })

                return product_list
            else:
                print("ไม่สามารถค้นหาสินค้าได้")
                return []

        except Exception as e:
            print(f"เกิดข้อผิดพลาดในการค้นหา: {e}")
            return []

    def add_to_cart(self, product_id, quantity=1):
        """เพิ่มสินค้าลงตะกร้า"""
        try:
            cart_url = f"{self.base_url}/add-to-cart"
            cart_data = {
                'product_id': product_id,
                'quantity': quantity
            }

            response = self.session.post(cart_url, data=cart_data)

            if response.status_code == 200:
                print(f"เพิ่มสินค้า ID {product_id} ลงตะกร้าสำเร็จ")
                return True
            else:
                print("ไม่สามารถเพิ่มสินค้าลงตะกร้าได้")
                return False

        except Exception as e:
            print(f"เกิดข้อผิดพลาดในการเพิ่มสินค้าลงตะกร้า: {e}")
            return False

    def checkout(self, payment_info=None):
        """ชำระเงิน"""
        try:
            checkout_url = f"{self.base_url}/checkout"

            # กรอกข้อมูลการชำระเงิน
            if payment_info:
                checkout_data = payment_info
            else:
                checkout_data = {
                    'payment_method': 'credit_card',
                    'shipping_address': 'ที่อยู่จัดส่ง'
                }

            response = self.session.post(checkout_url, data=checkout_data)

            if response.status_code == 200:
                print("ชำระเงินสำเร็จ")
                return True
            else:
                print("ชำระเงินไม่สำเร็จ")
                return False

        except Exception as e:
            print(f"เกิดข้อผิดพลาดในการชำระเงิน: {e}")
            return False

    def auto_buy(self, product_name, quantity=1, payment_info=None):
        """ฟังก์ชันหลักสำหรับซื้อสินค้าอัตโนมัติ"""
        print(f"เริ่มต้นการซื้อ {product_name} จำนวน {quantity}")

        # ล็อกอิน
        if not self.login():
            return False

        # ค้นหาสินค้า
        products = self.search_product(product_name)
        if not products:
            print("ไม่พบสินค้าที่ต้องการ")
            return False

        # เพิ่มสินค้าลงตะกร้า
        for product in products[:quantity]:  # ซื้อตามจำนวนที่ต้องการ
            if not self.add_to_cart(product['id'], 1):
                return False

        # ชำระเงิน
        if not self.checkout(payment_info):
            return False

        print("การซื้อสำเร็จ!")
        return True

    def monitor_product(self, product_name, target_price, check_interval=60):
        """ตรวจสอบราคาสินค้าอัตโนมัติ"""
        print(f"เริ่มตรวจสอบราคา {product_name} (เป้าหมาย: {target_price})")

        while True:
            products = self.search_product(product_name)

            if products:
                current_price = float(products[0]['price'].replace(',', '').replace('บาท', ''))
                print(f"ราคาปัจจุบัน: {current_price}")

                if current_price <= target_price:
                    print("ราคาถึงเป้าหมายแล้ว! กำลังซื้อ...")
                    self.auto_buy(product_name)
                    break

            time.sleep(check_interval)

    def close(self):
        """ปิดการเชื่อมต่อ"""
        if self.driver:
            self.driver.quit()
        self.session.close()

# ตัวอย่างการใช้งาน
if __name__ == "__main__":
    # ตัวอย่างการใช้งาน
    bot = ProductBot(
        base_url="https://example-shop.com",
        username="your_username",
        password="your_password"
    )

    try:
        # ตัวอย่างการซื้อสินค้า
        bot.auto_buy("iPhone 15", quantity=1)

        # ตัวอย่างการตรวจสอบราคา
        # bot.monitor_product("iPhone 15", 30000)

    finally:
        bot.close()