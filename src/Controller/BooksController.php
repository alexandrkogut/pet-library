<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/v1/books')]
class BooksController extends ApiController
{
    public function __construct(
        protected DenormalizerInterface $denormalizer,
        protected EntityManagerInterface $entityManager,
        protected ValidatorInterface $validator,
        protected BookRepository $bookRepository,
    ) {}

    #[Route(name: 'app_get_books_collection', methods: ['get'])]
    public function getBooksCollection(): JsonResponse
    {
        $books = $this->bookRepository->findBy([], ["name" => 'ASC']);

        return $this->json($books);
    }

    /**
     * @throws ExceptionInterface
     */
    #[Route(name: 'app_create_book', methods: ['post'])]
    public function createBook(Request $request): JsonResponse
    {
        /** @var Book $book */
        $book = $this->denormalizer->denormalize($request->toArray(), Book::class);
        $violations = $this->validator->validate($book, null, [Book::GROUP_CREATE]);

        if ($violations->count() > 0) {
            return $this->getErrorResponse($violations);
        }

        $this->entityManager->persist($book);
        $this->entityManager->flush();

        return $this->json($book);
    }

    #[Route('/{id<\d+>}', name: 'app_read_book', methods: ['get'])]
    public function readBook(int $id): JsonResponse
    {
        if (!$book = $this->bookRepository->find($id)) {
            throw new NotFoundHttpException();
        }

        return $this->json($book);
    }

    /**
     * @throws ExceptionInterface
     */
    #[Route('/{id<\d+>}', name: 'app_update_book', methods: ['put'])]
    public function updateBook(int $id, Request $request): JsonResponse
    {
        if (!$book = $this->bookRepository->find($id)) {
            throw new NotFoundHttpException();
        }

        /** @var Book $dto */
        $dto = $this->denormalizer->denormalize($request->toArray(), Book::class);
        $violations = $this->validator->validate($dto, null, [Book::GROUP_UPDATE]);

        if ($violations->count() > 0) {
            return $this->getErrorResponse($violations);
        }

        $book->setName($dto->getName())
            ->setYear($dto->getYear());

        return $this->json($book);
    }

    #[Route('/{id<\d+>}', name: 'app_delete_book', methods: ['delete'])]
    public function deleteBook(int $id): JsonResponse
    {
        if (!$book = $this->bookRepository->find($id)) {
            throw new NotFoundHttpException();
        }

        $this->entityManager->remove($book);
        $this->entityManager->flush();

        return $this->json(['success' => true]);
    }
}
