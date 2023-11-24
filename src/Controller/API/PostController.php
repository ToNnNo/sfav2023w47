<?php

namespace App\Controller\API;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/posts', name: 'api_posts_')]
class PostController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly PostRepository $postRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly ValidatorInterface $validator
    )
    {
    }

    #[Route('', name: 'index', methods: 'GET')]
    public function index(): JsonResponse
    {
        $posts = $this->postRepository->findAll();

        return $this->json($posts, context: [
            AbstractNormalizer::GROUPS => 'post:read:collection'
        ]);
    }

    #[Route('/{id}', name: 'detail', methods: 'GET')]
    public function detail(int $id): JsonResponse
    {
        $post = $this->postRepository->find($id);

        if(!$post) {
            return $this->json(['message' => 'Resource not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($post);
    }

    #[Route('', name: 'create', methods: 'POST')]
    public function create(Request $request): JsonResponse
    {
        // $post = null;
        $data = $request->getContent();
        if( $data === "" ) {
            return $this->json(['message' => 'Request body is empty'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $post = $this->serializer->deserialize($data, Post::class, 'json', [
                AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => false
            ]);
        } catch(\Exception $e) {
            return $this->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        $errors = $this->validator->validate($post);
        if(count($errors) > 0) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($post);
        $this->entityManager->flush();

        return $this->json($post, Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT', 'PATCH'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $data = $request->getContent();
        if( $data === "" ) {
            return $this->json(['message' => 'Request body is empty'], Response::HTTP_BAD_REQUEST);
        }

        $post = $this->postRepository->find($id);

        if(!$post) {
            return $this->json(['message' => 'Resource not found'], Response::HTTP_NOT_FOUND);
        }

        try {
            $this->serializer->deserialize($data, Post::class, 'json', [
                AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => false,
                AbstractNormalizer::OBJECT_TO_POPULATE => $post
            ]);
        } catch(\Exception $e) {
            return $this->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        $errors = $this->validator->validate($post);
        if(count($errors) > 0) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->flush();

        return $this->json($post, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'delete', methods: 'delete')]
    public function delete(int $id): JsonResponse
    {
        $post = $this->postRepository->find($id);

        if(!$post) {
            return $this->json(['message' => 'Resource not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($post);
        $this->entityManager->flush();

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}
