<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $products = Product::where('status', 'published')->latest()->get();
        
        $content = view('seo.sitemap', compact('products'))->render();
        
        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }

    public function robots(): Response
    {
        $content = "User-agent: *\n";
        $content .= "Allow: /\n";
        $content .= "\nSitemap: " . url('/sitemap.xml');

        return response($content, 200)
            ->header('Content-Type', 'text/plain');
    }
}
