<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function products()
    {
        return [
            'chrisbale-urban-runner' => [
                'slug' => 'chrisbale-urban-runner', 'name' => 'CHRISBALE Urban Runner', 'brand' => 'CHRISBALE',
                'price' => 149, 'old' => null, 'badge' => 'new', 'rating' => 5, 'review_count' => 124,
                'img' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&q=80&auto=format&fit=crop',
                'imgs' => [
                    'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&q=80',
                    'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=800&q=80',
                    'https://images.unsplash.com/photo-1608231387042-66d1773070a5?w=800&q=80',
                ],
                'desc' => 'Sneaker performa tinggi dengan desain urban modern. Dibuat dari bahan premium yang nyaman dipakai sepanjang hari, cocok untuk gaya aktif Anda.',
                'features' => ['Upper mesh breathable', 'Insole memory foam', 'Sol karet anti-slip', 'Desain ergonomis', 'Berat ringan hanya 280gr'],
                'sizes' => [39, 40, 41, 42, 43, 44], 'colors' => ['Hitam', 'Putih', 'Navy'],
                'sku' => 'CB-UR-001', 'category' => 'Sneakers',
            ],
            'gold-leather-loafer' => [
                'slug' => 'gold-leather-loafer', 'name' => 'Gold Leather Loafer', 'brand' => 'Agatha',
                'price' => 189, 'old' => 249, 'badge' => 'hot', 'rating' => 5, 'review_count' => 89,
                'img' => 'https://images.unsplash.com/photo-1614252369475-531eba835eb1?w=800&q=80&auto=format&fit=crop',
                'imgs' => [
                    'https://images.unsplash.com/photo-1614252369475-531eba835eb1?w=800&q=80',
                    'https://images.unsplash.com/photo-1614252369475-531eba835eb1?w=800&q=80',
                ],
                'desc' => 'Loafers kulit dengan detail emas yang elegan. Perpaduan sempurna antara gaya klasik dan sentuhan mewah untuk tampilan formal maupun kasual.',
                'features' => ['Kulit sapi full-grain', 'Aksen emas 24K', 'Insole empuk', 'Sol kulit fleksibel', 'Buatan tangan'],
                'sizes' => [38, 39, 40, 41, 42], 'colors' => ['Emas', 'Cokelat', 'Hitam'],
                'sku' => 'AG-GL-002', 'category' => 'Loafers',
            ],
            'combat-boot-black' => [
                'slug' => 'combat-boot-black', 'name' => 'Combat Boot — Hitam', 'brand' => 'CHRISBALE',
                'price' => 215, 'old' => 280, 'badge' => 'new', 'rating' => 4, 'review_count' => 56,
                'img' => 'https://images.unsplash.com/photo-1608256246200-53e635b5b65f?w=800&q=80&auto=format&fit=crop',
                'imgs' => [
                    'https://images.unsplash.com/photo-1608256246200-53e635b5b65f?w=800&q=80',
                    'https://images.unsplash.com/photo-1608256246200-53e635b5b65f?w=800&q=80',
                ],
                'desc' => 'Boot gagah dengan gaya combat ikonik. Dibuat dari kulit premium dengan sol kokoh, siap menemani petualangan Anda di berbagai medan.',
                'features' => ['Kulit full-grain tebal', 'Sol karet gigi', 'Ritsleting samping', 'Lapisan dalam hangat', 'Tahan air'],
                'sizes' => [40, 41, 42, 43, 44, 45], 'colors' => ['Hitam', 'Cokelat Tua'],
                'sku' => 'CB-CB-003', 'category' => 'Boots',
            ],
            'classic-slip-on-white' => [
                'slug' => 'classic-slip-on-white', 'name' => 'Classic Slip-On Putih', 'brand' => 'Agatha',
                'price' => 99, 'old' => 139, 'badge' => 'sale', 'rating' => 5, 'review_count' => 203,
                'img' => 'https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?w=800&q=80&auto=format&fit=crop',
                'imgs' => [
                    'https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?w=800&q=80',
                    'https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?w=800&q=80',
                ],
                'desc' => 'Slip-on klasik warna putih yang timeless. Nyaman dipakai tanpa tali, cocok untuk gaya sehari-hari yang santai namun tetap stylish.',
                'features' => ['Canvas premium', 'Insole empuk', 'Sol karet fleksibel', 'Tanpa tali', 'Ringan'],
                'sizes' => [38, 39, 40, 41, 42, 43], 'colors' => ['Putih', 'Krem'],
                'sku' => 'AG-CS-004', 'category' => 'Sneakers',
            ],
            'red-strap-sandal' => [
                'slug' => 'red-strap-sandal', 'name' => 'Red Strap Sandal', 'brand' => 'CHRISBALE',
                'price' => 119, 'old' => null, 'badge' => 'new', 'rating' => 4, 'review_count' => 42,
                'img' => 'https://images.unsplash.com/photo-1603487742131-4160ec999306?w=800&q=80&auto=format&fit=crop',
                'imgs' => [
                    'https://images.unsplash.com/photo-1603487742131-4160ec999306?w=800&q=80',
                    'https://images.unsplash.com/photo-1603487742131-4160ec999306?w=800&q=80',
                ],
                'desc' => 'Sandal strap merah yang berani dan stylish. Nyaman dipakai untuk liburan atau hangout santai dengan desain yang menonjol.',
                'features' => ['Kulit asli', 'Strap adjustable', 'Sol karet', 'Nyaman dipakai lama', 'Desain时尚'],
                'sizes' => [37, 38, 39, 40, 41], 'colors' => ['Merah', 'Hitam'],
                'sku' => 'CB-RS-005', 'category' => 'Sandal',
            ],
            'navy-slide-sandal' => [
                'slug' => 'navy-slide-sandal', 'name' => 'Navy Slide Sandal', 'brand' => 'Agatha',
                'price' => 109, 'old' => null, 'badge' => 'new', 'rating' => 5, 'review_count' => 78,
                'img' => 'https://images.unsplash.com/photo-1562183241-b937e9102f3b?w=800&q=80&auto=format&fit=crop',
                'imgs' => [
                    'https://images.unsplash.com/photo-1562183241-b937e9102f3b?w=800&q=80',
                    'https://images.unsplash.com/photo-1562183241-b937e9102f3b?w=800&q=80',
                ],
                'desc' => 'Slide sandal navy yang elegan dan praktis. Tinggal selip dan jalan — kenyamanan maksimal untuk hari-hari santai Anda.',
                'features' => ['Material premium', 'Desain slide', 'Sol empuk', 'Anti-slip', 'Tahan air'],
                'sizes' => [38, 39, 40, 41, 42], 'colors' => ['Navy', 'Hitam', 'Putih'],
                'sku' => 'AG-NS-006', 'category' => 'Sandal',
            ],
            'brown-leather-mule' => [
                'slug' => 'brown-leather-mule', 'name' => 'Brown Leather Mule', 'brand' => 'CHRISBALE',
                'price' => 159, 'old' => null, 'badge' => null, 'rating' => 5, 'review_count' => 34,
                'img' => 'https://images.unsplash.com/photo-1516478177764-9fe5bd7e9717?w=800&q=80&auto=format&fit=crop',
                'imgs' => [
                    'https://images.unsplash.com/photo-1516478177764-9fe5bd7e9717?w=800&q=80',
                    'https://images.unsplash.com/photo-1516478177764-9fe5bd7e9717?w=800&q=80',
                ],
                'desc' => 'Mule kulit cokelat yang sophisticated. Perpaduan antara kenyamanan sandal dan elegansi sepatu — sempurna untuk ke kantor maupun acara semi-formal.',
                'features' => ['Kulit sapi premium', 'Insole memory foam', 'Sol kulit', 'Desain open-back', 'Buatan tangan'],
                'sizes' => [39, 40, 41, 42, 43], 'colors' => ['Cokelat', 'Hitam'],
                'sku' => 'CB-BM-007', 'category' => 'Loafers',
            ],
            'canvas-high-top' => [
                'slug' => 'canvas-high-top', 'name' => 'Canvas High Top', 'brand' => 'Agatha',
                'price' => 89, 'old' => 118, 'badge' => 'sale', 'rating' => 5, 'review_count' => 167,
                'img' => 'https://images.unsplash.com/photo-1460353581641-37baddab0fa2?w=800&q=80&auto=format&fit=crop',
                'imgs' => [
                    'https://images.unsplash.com/photo-1460353581641-37baddab0fa2?w=800&q=80',
                    'https://images.unsplash.com/photo-1460353581641-37baddab0fa2?w=800&q=80',
                ],
                'desc' => 'High top canvas klasik yang wajib dimiliki. Gaya kasual ikonik dengan harga terjangkau — favorit sepanjang masa untuk semua kalangan.',
                'features' => ['Canvas premium', 'Sol karet putih', 'Tali panjang', 'Ventilasi baik', 'Nyaman seharian'],
                'sizes' => [38, 39, 40, 41, 42, 43, 44], 'colors' => ['Putih', 'Hitam', 'Merah'],
                'sku' => 'AG-CH-008', 'category' => 'Sneakers',
            ],
            'chrisbale-heritage-boot' => [
                'slug' => 'chrisbale-heritage-boot', 'name' => 'CHRISBALE Heritage Boot', 'brand' => 'CHRISBALE',
                'price' => 245, 'old' => null, 'badge' => null, 'rating' => 5, 'review_count' => 28,
                'img' => 'https://images.unsplash.com/photo-1605408499391-636e48a28621?w=800&q=80&auto=format&fit=crop',
                'imgs' => [
                    'https://images.unsplash.com/photo-1605408499391-636e48a28621?w=800&q=80',
                    'https://images.unsplash.com/photo-1605408499391-636e48a28621?w=800&q=80',
                ],
                'desc' => 'Boot warisan dengan desain klasik yang tak lekang waktu. Kulit pilihan dan konstruksi kokoh membuatnya awet dipakai bertahun-tahun.',
                'features' => ['Kulit pull-up eksklusif', 'Sol welt Goodyear', 'Insole kulit', 'Tahan lama', 'Bisa di-resole'],
                'sizes' => [40, 41, 42, 43, 44, 45], 'colors' => ['Cokelat Tua', 'Hitam'],
                'sku' => 'CB-HB-009', 'category' => 'Boots',
            ],
            'agatha-woven-mule' => [
                'slug' => 'agatha-woven-mule', 'name' => 'Agatha Woven Mule', 'brand' => 'Agatha',
                'price' => 135, 'old' => null, 'badge' => 'new', 'rating' => 5, 'review_count' => 47,
                'img' => 'https://images.unsplash.com/photo-1543163521-1bf539c55dd2?w=800&q=80&auto=format&fit=crop',
                'imgs' => [
                    'https://images.unsplash.com/photo-1543163521-1bf539c55dd2?w=800&q=80',
                    'https://images.unsplash.com/photo-1543163521-1bf539c55dd2?w=800&q=80',
                ],
                'desc' => 'Mule anyaman khas Agatha yang anggun. Detail anyaman tangan memberikan tekstur unik — perpaduan sempurna antara tradisi dan gaya modern.',
                'features' => ['Anyaman kulit tangan', 'Insole empuk', 'Sol karet', 'Desain terbuka', 'Ringan'],
                'sizes' => [37, 38, 39, 40, 41], 'colors' => ['Cokelat', 'Krem', 'Hitam'],
                'sku' => 'AG-WM-010', 'category' => 'Loafers',
            ],
            'chrisbale-trail-runner' => [
                'slug' => 'chrisbale-trail-runner', 'name' => 'CHRISBALE Trail Runner', 'brand' => 'CHRISBALE',
                'price' => 175, 'old' => null, 'badge' => 'hot', 'rating' => 5, 'review_count' => 93,
                'img' => 'https://images.unsplash.com/photo-1584735175315-9d5df23be1f2?w=800&q=80&auto=format&fit=crop',
                'imgs' => [
                    'https://images.unsplash.com/photo-1584735175315-9d5df23be1f2?w=800&q=80',
                    'https://images.unsplash.com/photo-1584735175315-9d5df23be1f2?w=800&q=80',
                ],
                'desc' => 'Trail runner siap diajak ke medan apa pun. Grip maksimal, bantalan responsif, dan material tahan air — teman setia petualangan Anda.',
                'features' => ['Upper tahan air', 'Sol Vibram', 'Bantalan React', 'Pelindung jari', 'Reflective detail'],
                'sizes' => [39, 40, 41, 42, 43, 44, 45], 'colors' => ['Hitam', 'Abu-Abu', 'Hijau Army'],
                'sku' => 'CB-TR-011', 'category' => 'Sneakers',
            ],
            'agatha-buckle-slide' => [
                'slug' => 'agatha-buckle-slide', 'name' => 'Agatha Buckle Slide', 'brand' => 'Agatha',
                'price' => 128, 'old' => 165, 'badge' => 'sale', 'rating' => 5, 'review_count' => 112,
                'img' => 'https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?w=800&q=80&auto=format&fit=crop',
                'imgs' => [
                    'https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?w=800&q=80',
                    'https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?w=800&q=80',
                ],
                'desc' => 'Slide dengan detail buckle yang fashion-forward. Nyaman dipakai dan mudah dipadukan dengan berbagai outfit kasual hingga semi-formal.',
                'features' => ['Kulit halus', 'Detail buckle emas', 'Insole empuk', 'Sol karet', 'Desain slide mudah'],
                'sizes' => [37, 38, 39, 40, 41, 42], 'colors' => ['Hitam', 'Cokelat', 'Putih'],
                'sku' => 'AG-BS-012', 'category' => 'Sandal',
            ],
        ];
    }

    public function index($brand = null)
    {
        $allProducts = $this->products();

        return view('products', [
            'brand' => $brand ? ucfirst(strtolower($brand)) : null,
            'allProducts' => $allProducts,
        ]);
    }

    public function show($slug)
    {
        $allProducts = $this->products();

        if (!array_key_exists($slug, $allProducts)) {
            abort(404);
        }

        return view('product-detail', [
            'product' => $allProducts[$slug],
            'allProducts' => $allProducts,
        ]);
    }
}
