<?php include_once 'db.php'; ?>

<!-- Include GSAP, Alpine.js, and Font Awesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.10.3/cdn.min.js" defer></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<!-- Top Navbar -->
<div class="flex justify-between items-center bg-gradient-to-r from-indigo-800 via-purple-700 to-pink-600 p-4 shadow-xl rounded-b-3xl backdrop-blur-md bg-opacity-75 border-b border-white/20 transition-transform duration-500">
    <h1 class="text-white text-4xl font-extrabold tracking-wide transform transition duration-700 hover:scale-105">Admin Panel</h1>
    <div class="relative">
        <button class="text-white bg-pink-600 px-4 py-2 rounded-full shadow-xl hover:bg-indigo-500 hover:scale-110 transform transition-all duration-300">
            <i class="fas fa-user-circle text-3xl"></i>
        </button>
    </div>
</div>

<!-- Sidebar -->
<div class="flex">
    <div class="w-1/4 bg-gradient-to-b from-blue-900 via-blue-800 to-indigo-700 h-screen p-6 shadow-2xl rounded-tr-3xl rounded-br-3xl backdrop-blur-lg bg-opacity-80 border border-white/10">
        <h2 class="text-white text-4xl font-black mb-6 text-center tracking-wide bg-gradient-to-r from-cyan-400 to-blue-600 bg-clip-text text-transparent">Menu</h2>
        <ul class="space-y-4">
            <?php
            $menuItems = [
                ['link' => '/Beach/Admin/admin_Dashboard.php', 'icon' => 'fas fa-tachometer-alt', 'label' => 'แดชบอร์ด'],
                ['link' => '/Beach/Admin/users/users.php', 'icon' => 'fas fa-user-friends', 'label' => 'ผู้ใช้'],
                ['link' => '/Beach/Admin/cleanup_activities/cleanup_activities.php', 'icon' => 'fas fa-recycle', 'label' => 'เก็บขยะ'],
                ['link' => '/Beach/Admin/cleanup_photos/cleanup_photos.php', 'icon' => 'fas fa-image', 'label' => 'รูปภาพกิจกรรม'],
                ['link' => '/Beach/Admin/waste_types/waste_types.php', 'icon' => 'fas fa-trash-alt', 'label' => 'ประเภทขยะ'],
                ['link' => '/Beach/Admin/businesses/businesses.php', 'icon' => 'fas fa-store', 'label' => 'ธุรกิจในชุมชน'],
                ['link' => '/Beach/Admin/business_types/business_types.php', 'icon' => 'fas fa-tags', 'label' => 'ประเภทธุรกิจ'],
                ['link' => '/Beach/Admin/businesses_photos/businesses_photos.php', 'icon' => 'fas fa-images', 'label' => 'ภาพโปรโมทธุรกิจ'],
                ['icon' => 'fas fa-cogs', 'label' => 'API', 'submenu' => [
                    ['link' => '/Beach/Admin/API/upload_Hero.php', 'label' => 'เปลี่ยน Hero'],
                    ['link' => '/Beach/Admin/1/show_cards.php', 'label' => 'เปลี่ยน cartหน้าindex'],
                    ['link' => '/Beach/Admin/API/beach_content.php', 'label' => 'เปลี่ยน cartข้อมูลหาดทรายน้อย'],
                    ['link' => '/Beach/Admin/API/project_manager.php', 'label' => 'เปลี่ยน ที่มาของโครงการ'],
                    ['link' => '/Beach/Admin/API/อัพโหลดภาพรวม.php', 'label' => 'เปลี่ยน ภาพรวม'],
                ]],
                ['link' => '/beach/Admin/logout.php', 'icon' => 'fas fa-sign-out-alt', 'label' => 'ออกจากระบบ', 'bgColor' => 'from-red-600 to-red-500']
            ];

            foreach ($menuItems as $item) {
                $bgColor = isset($item['bgColor']) ? "bg-gradient-to-r {$item['bgColor']}" : "bg-gradient-to-r from-purple-800 to-purple-600";

                // Check if the item has a submenu
                if (isset($item['submenu'])) {
                    echo "<li x-data=\"{ open: false }\">
                <a @click=\"open = !open;\" class=\"flex items-center text-white {$bgColor} p-4 rounded-xl shadow-lg transition-all duration-300 transform hover:scale-110 hover:shadow-xl cursor-pointer\">
                    <i class=\"{$item['icon']} mr-3 text-xl text-gray-300\"></i> <span class=\"font-semibold text-lg\">{$item['label']}</span>
                </a>
                <ul x-show=\"open\" x-transition:enter=\"transition ease-out duration-300\" x-transition:enter-start=\"opacity-0 transform -translate-y-2\" x-transition:enter-end=\"opacity-100 transform translate-y-0\" x-transition:leave=\"transition ease-in duration-200\" x-transition:leave-start=\"opacity-100 transform translate-y-0\" x-transition:leave-end=\"opacity-0 transform -translate-y-2\" class=\"submenu ml-4 mt-2 space-y-2\" style=\"display: none;\">
                    " . implode('', array_map(function($submenu) {
                            return "<li>
                            <a href=\"{$submenu['link']}\" class=\"flex items-center text-white p-2 rounded-lg hover:bg-opacity-70 transition duration-300\">
                                <span class=\"font-semibold text-md\">{$submenu['label']}</span>
                            </a>
                        </li>";
                        }, $item['submenu'])) . "
                </ul>
            </li>";
                } else {
                    echo "<li>
                <a href=\"{$item['link']}\" class=\"flex items-center text-white {$bgColor} p-4 rounded-xl shadow-lg transition-all duration-300 transform hover:scale-110 hover:shadow-xl\">
                    <i class=\"{$item['icon']} mr-3 text-xl text-gray-300\"></i> <span class=\"font-semibold text-lg\">{$item['label']}</span>
                </a>
            </li>";
                }
            }
            ?>
        </ul>

    </div>
