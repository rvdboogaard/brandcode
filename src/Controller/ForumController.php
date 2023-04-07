<?php

namespace App\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
            'controller_name' => 'ForumController',
        ]);
    }

    #[Route('/post/{id}', name: 'post')]
    public function post(PostRepository $postRepository, Author $author): Response
    {
        $posts = $postRepository->findBy(['author' => $author]);

        return $this->render('post.html.twig', [
            'posts' => $posts
        ]);
    }
}
