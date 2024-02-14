<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\DockerImage;
use App\Entity\DockerTag;
use App\Entity\SearchHistory;
use App\Repository\DockerImageRepository;
use App\Repository\DockerTagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class DockerService
{
    private const DOCKER_HUB_API_BASE_URL = 'https://hub.docker.com/v2';

    public function __construct(
        private HttpClientInterface $httpClient,
        private EntityManagerInterface $em,
        private DockerImageRepository $dockerImageRepository,
        private DockerTagRepository $dockerTagRepository
    ) {}

    public function getTags(string $namespace, string $repository): array
    {
        $dockerImage = $this->dockerImageRepository->findOneBy(['name' => $namespace, 'repository' => $repository]);
        $imageName = $namespace . '/' . $repository;

        $searchHistoryExists = $this->em->getRepository(SearchHistory::class)->findOneBy([
            'imageName' => $imageName,
            'searchAllTags' => true
        ]);

        if ($searchHistoryExists) {
            $tags = $this->dockerTagRepository->findBy(['image' => $dockerImage]);
            foreach ($tags as $tag) {
                $this->saveSearchHistory($imageName, $tag->getTagName(), true);
            }
            return array_map(fn($tag) => $this->formatTagData($tag, $imageName), $tags);
        }

        $url = self::DOCKER_HUB_API_BASE_URL . "/namespaces/{$namespace}/repositories/{$repository}/tags";
        try {
            $response = $this->httpClient->request('GET', $url);
            $data = $response->toArray();

            if (!$dockerImage) {
                $dockerImage = new DockerImage();
                $dockerImage->setName($namespace);
                $dockerImage->setRepository($repository);
                $this->em->persist($dockerImage);
            }

            $formattedTags = [];
            foreach ($data['results'] as $result) {
                $tag = $this->createOrUpdateTagFromResult($result, $dockerImage);
                $formattedTags[] = $this->formatTagData($tag, $imageName);
                $this->saveSearchHistory($imageName, $tag->getTagName(), true);
            }
            $this->em->flush();

            return $formattedTags;
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to fetch tags from Docker Hub: ' . $e->getMessage());
        }
    }

    public function getTagDetails(string $namespace, string $repository, string $tagName): array
    {
        $dockerImage = $this->dockerImageRepository->findOneBy(['name' => $namespace, 'repository' => $repository]);
        $imageName = $namespace . '/' . $repository;

        if ($dockerImage) {
            $tag = $this->dockerTagRepository->findOneBy(['image' => $dockerImage, 'tagName' => $tagName]);
            if ($tag) {
                $this->saveSearchHistory($imageName, $tag->getTagName(), false);
                return $this->formatTagData($tag, $imageName);
            }
        }

        $url = self::DOCKER_HUB_API_BASE_URL . "/namespaces/{$namespace}/repositories/{$repository}/tags/{$tagName}";
        try {
            $response = $this->httpClient->request('GET', $url);

            $data = $response->toArray();

            if (!$dockerImage) {
                $dockerImage = new DockerImage();
                $dockerImage->setName($namespace);
                $dockerImage->setRepository($repository);
                $this->em->persist($dockerImage);
            }

            $tag = $this->createOrUpdateTagFromResult($data, $dockerImage);
            $this->em->flush();
            $this->saveSearchHistory($imageName, $tag->getTagName(), false);

            return $this->formatTagData($tag, $imageName);
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to fetch tags from Docker Hub: ' . $e->getMessage());
        }
    }

    private function formatTagData(DockerTag $tag, string $imageName): array
    {
        return [
            'imageName' => $imageName,
            'tagName' => $tag->getTagName(),
            'status' => $tag->getStatus(),
            'lastModified' => $tag->getLastModified()->format('c'),
            'architecture' => $tag->getArchitecture(),
            'os' => $tag->getOs(),
            'size' => $tag->getSize()." MB",
        ];
    }

    private function createOrUpdateTagFromResult(
        array $result,
        DockerImage $dockerImage
    ): DockerTag
    {
        $tag = new DockerTag();
        $tag->setTagName($result['name'])
            ->setStatus($result['tag_status'])
            ->setLastModified(new \DateTime($result['tag_last_pushed']))
            ->setArchitecture($result['images'][0]['architecture'])
            ->setOs($result['images'][0]['os'])
            ->setSize(round($result['images'][0]['size'] / 1024 / 1024, 2))
            ->setImage($dockerImage);

        $this->em->persist($tag);

        return $tag;
    }

    public function saveSearchHistory(string $imageName, string $tagName,bool $searchAllTags): void
    {
        $searchHistory = new SearchHistory();
        $searchHistory->setImageName($imageName);
        $searchHistory->setTagName($tagName);
        $searchHistory->setSearchedAt(new \DateTime());
        $searchHistory->setSearchAllTags($searchAllTags);;

        $this->em->persist($searchHistory);
        $this->em->flush();
    }
}
