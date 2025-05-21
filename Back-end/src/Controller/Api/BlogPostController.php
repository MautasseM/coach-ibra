<?php

namespace App\Controller\Api;

use App\Entity\BlogPost;
use App\Repository\BlogPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/blogposts', name: 'api_blogposts_')]
class BlogPostController extends AbstractController
{
    private $entityManager;
    private $serializer;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    #[Route('', name: 'list', methods: ['GET'])]
    public function list(BlogPostRepository $repo): JsonResponse
    {
        $posts = $repo->findAll();
        return new JsonResponse($posts, Response::HTTP_OK); // 200 OK for list
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(BlogPostRepository $repo, $id): JsonResponse
    {
        $post = $repo->find($id);
        if (!$post) {
            return new JsonResponse(['error' => 'Not found'], Response::HTTP_NOT_FOUND); // 404 Not Found for show
        }
        return new JsonResponse($post, Response::HTTP_OK); // 200 OK for show
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $blogPost = new BlogPost();
        $blogPost->setTitle($data['title'] ?? null);
        $blogPost->setContent($data['content'] ?? null);

        $errors = $validator->validate($blogPost);
        if (count($errors) > 0) {
            return new JsonResponse(['errors' => (string) $errors], Response::HTTP_BAD_REQUEST); // 400 Bad Request for validation errors
        }

        $this->entityManager->persist($blogPost);
        $this->entityManager->flush();

        return new JsonResponse($blogPost, Response::HTTP_CREATED); // 201 Created for create
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(Request $request, BlogPostRepository $repo, ValidatorInterface $validator, $id): JsonResponse
    {
        $blogPost = $repo->find($id);
        if (!$blogPost) {
            return new JsonResponse(['error' => 'Not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        $blogPost->setTitle($data['title'] ?? $blogPost->getTitle());
        $blogPost->setContent($data['content'] ?? $blogPost->getContent());

        $errors = $validator->validate($blogPost);
        if (count($errors) > 0) {
            return new JsonResponse(['errors' => (string) $errors], Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->flush();

        return new JsonResponse($blogPost, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(BlogPostRepository $repo, $id): JsonResponse
    {
        $blogPost = $repo->find($id);
        if (!$blogPost) {
            return new JsonResponse(['error' => 'Not found'], Response::HTTP_NOT_FOUND); // 404 Not Found for delete
        }

        $this->entityManager->remove($blogPost);
        $this->entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT); // 204 No Content for delete
    }
}
