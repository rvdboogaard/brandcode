<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Post;
use App\Form\PostType;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForumController extends AbstractController
{
    #[Route('/forum', name: 'forum')]
    public function index(AuthorRepository $authorRepository, PostRepository $postRepository): Response
    {
        $authors = $authorRepository->findAll();
        $posts = $postRepository->findAll();
        return $this->render('forum.html.twig', [
            'authors' => $authors,
            'posts' => $posts,
        ]);
    }

    #[Route('/post/{id}', name: 'post')]
    public function post(PostRepository $postRepository, int $id): Response
    {
        $post = $postRepository->find($id);
        $authorName = $post->getAuthor()->getName();

        return $this->render('post.html.twig', [
            'authorName' => $authorName,
            'post' => $post
        ]);
    }

    #[Route('/author/{id}', name: 'author')]
    public function author(AuthorRepository $authorRepository, PostRepository $postRepository, int $id): Response
    {
        $author = $authorRepository->find($id);
        $posts = $postRepository->findBy(['author' => $author]);

        return $this->render('author.html.twig', [
            'author' => $author,
            'posts' => $posts
        ]);
    }

    #[Route('/insert', name: 'insert')]
    public function insert(PostRepository $postRepository, AuthorRepository $authorRepository, Request $request): Response
    {
        $post = new Post();
        $postForm = $this->createForm(PostType::class, $post);
        $postForm->handleRequest($request);

        if ($postForm->isSubmitted() && $postForm->isValid()) {
            $post = $postForm->getData();
            $postRepository->save($post, true);
            $message = 'Your message has been posted to the forum';
            $this->addFlash('success', $message);
        }

        $author = new Author();
        $authorForm = $this->createForm(AuthorType::class, $author);
        $authorForm->handleRequest($request);

        if ($authorForm->isSubmitted() && $authorForm->isValid()) {
            $author = $authorForm->getData();
            $authorRepository->save($author, true);
            $message = 'New author has been created';
            $this->addFlash('success', $message);
        }

        return $this->renderForm('insert.html.twig', [
            'postForm' => $postForm,
            'authorForm' => $authorForm,
        ]);
    }
}
