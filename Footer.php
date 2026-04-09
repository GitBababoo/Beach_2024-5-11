<!-- Footer -->
<footer class="footer mt-5 py-4" style="background: linear-gradient(90deg, #1a1a1a, #3b3b3b); color: #fff;">
    <div class="container text-center">
        <div class="row">
            <!-- คอลัมน์ 1: ข้อมูลการติดต่อ -->
            <div class="col-md-4 mb-3">
                <h5 class="font-weight-bold mb-3" style="font-size: 1.5rem;">ติดต่อเรา</h5>
                <p>
                    123 ถนนหลัก,<br>
                    กรุงเทพมหานคร, ประเทศไทย<br>
                    โทร: +66 095 846 2520<br>
                    อีเมล: <a href="https://mail.google.com/mail/pushilkun@gmail.com" class="text-white text-decoration-none">pushilkun@gmail.com</a>
                </p>
            </div>

            <!-- คอลัมน์ 2: ลิงก์โซเชียลมีเดีย -->
            <div class="col-md-4 mb-3">
                <h5 class="font-weight-bold mb-3" style="font-size: 1.5rem;">ติดตามเรา</h5>
                <div class="social-icons mb-3">
                    <a href="https://www.facebook.com" target="_blank" class="social-icon">
                        <i class="bi bi-facebook"></i>
                        <span class="visually-hidden">Facebook</span>
                    </a>
                    <a href="https://www.twitter.com" target="_blank" class="social-icon">
                        <i class="bi bi-twitter"></i>
                        <span class="visually-hidden">Twitter</span>
                    </a>
                    <a href="https://www.instagram.com" target="_blank" class="social-icon">
                        <i class="bi bi-instagram"></i>
                        <span class="visually-hidden">Instagram</span>
                    </a>
                </div>
            </div>

            <!-- คอลัมน์ 3: ลิขสิทธิ์และลิงก์ -->
            <div class="col-md-4 mb-3">
                <h5 class="font-weight-bold mb-3" style="font-size: 1.5rem;">ข้อมูลทางกฎหมาย</h5>
                <p class="mb-1" style="font-size: 0.9rem;">&copy; 2024 สงวนลิขสิทธิ์</p>
                <div>
                    <a href="#" class="text-white text-decoration-none mx-2">นโยบายความเป็นส่วนตัว</a>
                    <span class="text-white">|</span>
                    <a href="#" class="text-white text-decoration-none mx-2">ข้อกำหนดในการให้บริการ</a>
                    <span class="text-white">|</span>
                    <a href="#" class="text-white text-decoration-none mx-2">ติดต่อเรา</a>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom py-3">
        <p class="mb-0" style="font-size: 0.9rem;">สงวนสิทธิ์ &copy; 2024 - All rights reserved</p>
    </div>
</footer>

<!-- Styles -->
<style>
    .footer {
        font-family: 'Orbitron', sans-serif;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
    }

    .social-icons {
        display: flex;
        justify-content: center;
        gap: 15px;
    }

    .social-icon {
        color: #fff;
        font-size: 28px;
        transition: transform 0.3s, color 0.3s;
    }

    .social-icon:hover {
        transform: scale(1.2);
        color: #00ffcc; /* สีที่ล้ำสมัยเมื่อชี้ไปที่ไอคอน */
    }

    .footer a {
        position: relative;
        color: white;
        text-decoration: none;
        transition: color 0.3s;
    }

    .footer a:hover {
        color: #00ffcc; /* เปลี่ยนสีตัวอักษรเมื่อชี้ */
    }

    .footer-bottom {
        background-color: rgba(0, 0, 0, 0.5);
        text-align: center;
    }

    @media (max-width: 768px) {
        .footer {
            padding: 40px 0;
        }
    }

    @media (max-width: 576px) {
        .footer h5 {
            font-size: 1.3rem;
        }

        .footer p {
            font-size: 0.8rem;
        }
    }
</style>
