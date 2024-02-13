<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\DockerImage;
use App\Entity\DockerTag;
use App\Repository\DockerImageRepository;
use App\Repository\DockerTagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class DockerTagsController extends AbstractController
{

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    #[Route('/api/docker/tags/namespace/{namespace}/repository/{repository}', name: 'get_docker_tags', methods: ['GET'])]
    public function getDockerTags(
        string $namespace,
        string $repository,
        DockerImageRepository $dockerImageRepository,
        DockerTagRepository $dockerTagRepository,
        EntityManagerInterface $em,
        HttpClientInterface $httpClient
    ): JsonResponse
    {

        $dockerImage = $dockerImageRepository->findOneBy(['name' => $namespace, 'repository' => $repository]);

        if ($dockerImage) {
            $tags = $dockerTagRepository->findBy(['image' => $dockerImage]);
            if (!empty($tags)) {
                return $this->json($tags);
            }
        }

        try {
            $response = $httpClient->request('GET', "https://hub.docker.com/v2/namespaces/{$namespace}/repositories/{$repository}/tags");
            $data = $response->toArray();
        } catch (TransportExceptionInterface $e) {
            return $this->json(['message' => 'No data found for the specified namespace/repository on Dockerhub or external API error.'], Response::HTTP_NOT_FOUND);
        }

        if ($response->getStatusCode() === 200 && !empty($data['results'])) {
            if (!$dockerImage) {
                $dockerImage = new DockerImage();
                $dockerImage->setName($namespace);
                $dockerImage->setRepository($repository);
                $em->persist($dockerImage);
            }

            foreach ($data['results'] as $result) {
                $tag = new DockerTag();
                $tag->setTagName($result['name'])
                    ->setStatus($result['tag_status'])
                    ->setLastModified(new \DateTime($result['tag_last_pushed']))
                    ->setArchitecture($result['images'][0]['architecture'])
                    ->setOs($result['images'][0]['os'])
                    ->setSize(round($result['images'][0]['size'] / 1024 / 1024, 2))
                    ->setImage($dockerImage);

                $em->persist($tag);
            }

            $em->flush();

            return $this->json($data['results']);
        }

        return $this->json(['message' => 'Failed to write to the database due to an internal error or external API failure.'], Response::HTTP_INTERNAL_SERVER_ERROR);

    }
}
