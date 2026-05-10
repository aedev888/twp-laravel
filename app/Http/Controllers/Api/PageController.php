<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PageService;
use App\Http\Resources\PageResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PageController extends Controller
{
    protected PageService $pageService;

    public function __construct(PageService $pageService)
    {
        $this->pageService = $pageService;
    }

    public function index(Request $request): JsonResponse
    {
        $taxonomySlug = $request->query('taxonomy_slug');
        $pages = $this->pageService->getPaginatedPages(15, $taxonomySlug);
        
        return response()->json(PageResource::collection($pages)->response()->getData(true));
    }

    public function show(string $slug): JsonResponse
    {
        $page = $this->pageService->getPageBySlug($slug);
        
        if (!$page) {
            return response()->json(['message' => 'Page not found'], 404);
        }

        return response()->json(new PageResource($page));
    }
}
