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
        $formattedTags = $dockerService->getTags($namespace, $repository);
        return $this->json($formattedTags);
    }

    #[Route('/api/docker/tags/namespace/{namespace}/repository/{repository}/tag/{tagName}', name: 'get_docker_tag_details', methods: ['GET'])]
    public function getDockerTagDetails(string $namespace, string $repository, string $tagName, DockerService $dockerService): JsonResponse
    {
        $tagDetails = $dockerService->getTagDetails($namespace, $repository, $tagName);
        return $this->json($tagDetails);
    }
}
