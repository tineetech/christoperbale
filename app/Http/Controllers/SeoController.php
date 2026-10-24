<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Produk;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    private function siteUrl(): string
    {
        return rtrim((string) (env('SEO_URL') ?: config('app.url')), '/');
    }

    public function sitemap()
    {
        $base = $this->siteUrl();
        $now = now()->toAtomString();

        $urls = [
            ['loc' => $base . '/', 'lastmod' => $now, 'changefreq' => 'daily', 'priority' => '1.0'],
            ['loc' => $base . '/products', 'lastmod' => $now, 'changefreq' => 'daily', 'priority' => '0.9'],
            ['loc' => $base . '/about', 'lastmod' => $now, 'changefreq' => 'monthly', 'priority' => '0.5'],
            ['loc' => $base . '/contact', 'lastmod' => $now, 'changefreq' => 'monthly', 'priority' => '0.5'],
        ];

        $brands = Brand::where('status_brand', 'aktif')->get(['nama_brand']);
        foreach ($brands as $brand) {
            $urls[] = [
                'loc' => $base . '/products/' . strtolower($brand->nama_brand),
                'lastmod' => $now,
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ];
        }

        $products = Produk::where('status', 'aktif')
            ->orderByDesc('updated_at')
            ->get(['slug', 'updated_at']);
        foreach ($products as $product) {
            $urls[] = [
                'loc' => $base . '/product/' . $product->slug,
                'lastmod' => optional($product->updated_at)->toAtomString() ?: $now,
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ];
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $url) {
            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . e($url['loc']) . '</loc>' . "\n";
            $xml .= '    <lastmod>' . e($url['lastmod']) . '</lastmod>' . "\n";
            $xml .= '    <changefreq>' . e($url['changefreq']) . '</changefreq>' . "\n";
            $xml .= '    <priority>' . e($url['priority']) . '</priority>' . "\n";
            $xml .= '  </url>' . "\n";
        }
        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'text/xml; charset=UTF-8']);
    }

    public function robots()
    {
        $base = $this->siteUrl();

        $lines = [
            'User-agent: *',
            'Allow: /',
            '',
            'Disallow: /dashboard',
            'Disallow: /checkout',
            'Disallow: /cart',
            'Disallow: /wishlist',
            'Disallow: /login',
            'Disallow: /register',
            'Disallow: /forgot-password',
            'Disallow: /reset-password',
            'Disallow: /auth/',
            'Disallow: /midtrans/',
            '',
            'Sitemap: ' . $base . '/sitemap.xml',
        ];

        return response(implode("\n", $lines) . "\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
