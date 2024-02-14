<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SearchHistoryRepository;

class SearchHistoryController extends AbstractController
{
    #[Route('/api/search/history/namespace/{namespace}/repository/{repository}', name: 'search_history_by_image', methods: ['GET'])]
    public function searchHistoryByImageName(string $namespace, string $repository, SearchHistoryRepository $searchHistoryRepository): JsonResponse
    {
        $imageName = $namespace . '/' . $repository;
        $history = $searchHistoryRepository->findByImageName($imageName);

        if (empty($history)) {
            return $this->json(['message' => 'No search history found for this image (' . $imageName . ').'], 404);
        }

        return $this->json($history);
    }

    #[Route('/api/search/history/tag/{tagName}', name: 'search_history_by_tag', methods: ['GET'])]
    public function searchHistoryByTagName(string $tagName, SearchHistoryRepository $searchHistoryRepository): JsonResponse
    {
        $history = $searchHistoryRepository->findByTagName($tagName);

        if (empty($history)) {
            return $this->json(['message' => 'No search history found for this tag (' . $tagName . ').'], 404);
        }

        return $this->json($history);
    }

    #[Route('/api/search/history/namespace/{namespace}/repository/{repository}/tag/{tagName}', name: 'search_history_by_tag_and_image', methods: ['GET'])]
    public function searchHistoryByImageNameAndTag(string $namespace, string $repository, string $tagName, SearchHistoryRepository $searchHistoryRepository): JsonResponse
    {
        $imageName = $namespace . '/' . $repository;
        $history = $searchHistoryRepository->findByImageNameAndTagName($imageName, $tagName);

        if (empty($history)) {
            return $this->json(['message' => 'No search history found for this image (' . $imageName . ') and tag ('.$tagName.') combination.'], 404);
        }

        return $this->json($history);
    }
}
