<?php

namespace App\Controller;

use App\Dto\Request\CommentRequest;
use App\Form\CommentType;
use App\Service\CommentApiService;
use App\Service\PostApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('', name: 'post_')]
class PostController extends AbstractController
{
    #[Route('/', name: 'list')]
    public function list(PostApiService $postApiService): Response
    {
        return $this->render('post/list.html.twig', [
            'posts' => $postApiService->getAll(),
        ]);
    }

    #[Route('/post/{id}', name: 'show')]
    public function show(
        Request $request,
        int $id,
        PostApiService $postApiService,
        CommentApiService $commentApiService
    ): Response {
        $commentRequest = new CommentRequest();
        $form = $this->createForm(CommentType::class, $commentRequest);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $commentRequest->postId = $id;
            $commentApiService->create($commentRequest);

            return $this->redirectToRoute('post_show', ['id' => $id]);
        }

        return $this->render('post/show.html.twig', [
            'post' => $postApiService->get($id),
            'form' => $form->createView(),
        ]);
    }
}
