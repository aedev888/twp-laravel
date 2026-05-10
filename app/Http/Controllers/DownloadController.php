<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DownloadController extends Controller
{
    public function __construct(
        protected ProductService $productService
    ) {}

    public function download(Product $product, Request $request)
    {
        $user = $request->user();

        if (!$this->productService->canDownload($user, $product)) {
            return response()->json([
                'message' => 'You do not have permission to download this product. Please register or subscribe.',
            ], Response::HTTP_FORBIDDEN);
        }

        if ($product->file_path) {
            $filePath = storage_path('app/public/' . $product->file_path);
            if (file_exists($filePath)) {
                $this->productService->recordDownload($user, $product);
                return response()->download($filePath, basename($product->file_path));
            }
        }

        $media = $product->getFirstMedia('file');

        if (!$media) {
            return response()->json([
                'message' => 'File not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        $this->productService->recordDownload($user, $product);

        return response()->download($media->getPath(), $media->file_name);
    }
}
