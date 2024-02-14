<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\DockerService;

class DockerTagsController extends AbstractController
{
    #[Route('/api/docker/tags/namespace/{namespace}/repository/{repository}', name: 'get_docker_tags', methods: ['GET'])]
    public function getDockerTags(string $namespace, string $repository, DockerService $dockerService): JsonResponse
    {
        try {
            $formattedTags = $dockerService->getTags($namespace, $repository);

            if (empty($formattedTags)) {
                return $this->json(['message' => 'No tags found for this image.'], 404);
            }

            return $this->json($formattedTags);
        } catch (\RuntimeException $e) {
            return $this->json(['message' => $e->getMessage()], 500);
        }
    }

    #[Route('/api/docker/tags/namespace/{namespace}/repository/{repository}/tag/{tagName}', name: 'get_docker_tag_details', methods: ['GET'])]
    public function getDockerTagDetails(string $namespace, string $repository, string $tagName, DockerService $dockerService): JsonResponse
    {
        try {
            $tagDetails = $dockerService->getTagDetails($namespace, $repository, $tagName);

            if (empty($tagDetails)) {
                return $this->json(['message' => 'No details found for this image and tag.'], 404);
            }

            return $this->json($tagDetails);
        } catch (\RuntimeException $e) {
            return $this->json(['message' => $e->getMessage()], 500);
        }
    }
}
