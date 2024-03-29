<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Entity\Program;
use App\Form\ActorType;
use App\Repository\ActorRepository;
use App\Repository\ProgramRepository;
use App\Service\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/actor')]
class ActorController extends AbstractController
{
    #[Route('/', name: 'actor_index', methods: ['GET'])]
    public function index(ActorRepository $actorRepository): Response
    {
        return $this->render('actor/index.html.twig', [
            'actors' => $actorRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'actor_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Slugify $slugify): Response
    {
        $actor = new Actor();
        $form = $this->createForm(ActorType::class, $actor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Slug
            $slug = $slugify->generate($actor->getName());
            $actor->setSlug($slug);

            $entityManager->persist($actor);
            $entityManager->flush();

            return $this->redirectToRoute('actor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('actor/new.html.twig', [
            'actor' => $actor,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'actor_show', methods: ['GET'])]
    public function show(Actor $actor): Response
    {

        return $this->render('actor/show.html.twig', [
            'actor' => $actor,
        ]);
    }

    #[Route('/{slug}/edit', name: 'actor_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Actor $actor, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ActorType::class, $actor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('actor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('actor/edit.html.twig', [
            'actor' => $actor,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'actor_delete', methods: ['POST'])]
    public function delete(Request $request, Actor $actor, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$actor->getId(), $request->request->get('_token'))) {
            $entityManager->remove($actor);
            $entityManager->flush();
        }

        return $this->redirectToRoute('actor_index', [], Response::HTTP_SEE_OTHER);
    }
}
