<?php
namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Http\Requests\ArticleSearchRequest;
use App\Models\Article;
use App\Repositories\ArticleRepository;
use App\Services\GuardianApiService;
use App\Services\NewsAggregatorService;
use App\Services\NewsApiService;
use App\Services\NewYortTimesApiService;
use App\Services\UserPreferenceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

final class ArticleController extends Controller
{
    private ArticleRepository $articleRepository;
    private UserPreferenceService $userPreferenceService;

    public function __construct(
        ArticleRepository $articleRepository,
        UserPreferenceService $userPreferenceService
    ) {
        $this->articleRepository = $articleRepository;
        $this->userPreferenceService = $userPreferenceService;
    }

    public function index(ArticleSearchRequest $request): JsonResponse|string
    {
        $filters = $request->validated();

        // If no query parameters are provided, use user preferences
        if (empty($filters) && Auth::check()) {
            $userId = Auth::id();
            $preferences = $this->userPreferenceService->getPreferences(userId: $userId);

            if ($preferences) {
                $filters = [
                    'source' => $preferences['sources'],
                    'category' => $preferences['categories'],
                ];
            }
        }

        return response()->json(
            data: $this->articleRepository->getArticles(
                filters: $filters
            )
        );
    }

    public function details(int $id): mixed
    {
        $article = $this->articleRepository->find(id: $id);

        if ($article) {
            return response()->json(
                data: $article,
                status: 200
            );
        }

        return response()->json(
            data: ['message' => 'Article not found'],
            status: 404
        );
    }

    public function fetch(): JsonResponse|string
    {
        $newsService = new NewsAggregatorService(
            newsService: new NewYortTimesApiService()
        );

        $newsService->fetchAndStoreNews();

        return response()->json(data: ['message' => 'Fetching articles...']);
    }
}
