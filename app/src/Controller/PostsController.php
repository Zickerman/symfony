<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\PostRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class PostsController extends AbstractController
{

    public function __construct(private PostRepository $postRepository)
    {}

    #[Route('/posts', name: 'blog_posts')]
    public function posts(): Response
    {
        $posts = $this->postRepository->findAll();

        return $this->render('posts/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/post/add', name: 'add_post')]
    public function addPost(Request $request, Slugify $slugify, EntityManagerInterface $em): Response
    {
        $post = new Post();

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setSlug($slugify->slugify($post->getTitle()));
            $post->setCreatedAt(new \DateTime());

            $em->persist($post);
            $em->flush();
            return $this->redirectToRoute('blog_posts');
        }

        return $this->render('posts/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/post/{slug}', name: 'detail_post')]
    public function post(string $slug): Response
    {
        $post = $this->postRepository->findOneBy(['slug' => $slug]);

        return $this->render('posts/detail.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/post/{slug}/edit', name: 'edit_post')]
    public function editPost(Request $request, Slugify $slugify, EntityManagerInterface $em): Response
    {
        $post = $this->postRepository->findOneBy(['slug' => $request->get('slug')]);

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setSlug($slugify->slugify($post->getTitle()));
            $em->flush();

            return $this->redirectToRoute('detail_post', [
                'slug' => $post->getSlug()
            ]);
        }

        return $this->render('posts/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/post/{slug}/delete', name: 'delete_post')]
    public function deletePost(Request $request, EntityManagerInterface $em): Response
    {
        $post = $this->postRepository->findOneBy(['slug' => $request->get('slug')]);

        $em->remove($post);
        $em->flush();

        return $this->redirectToRoute('blog_posts');
    }
}
