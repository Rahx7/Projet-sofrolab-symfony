<?php

namespace App\Controller;

use App\Entity\Site;
use App\Form\SiteType;
use App\Repository\SiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/site/crud'), IsGranted("ROLE_ADMIN", message:"vous n'êtes pas autorisé")]
class SiteCrudController2 extends AbstractController
{
    #[Route('/', name: 'app_site_crud_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $sites = $entityManager
            ->getRepository(Site::class)
            ->findAll();

        return $this->render('site_crud/index.html.twig', [
            'sites' => $sites,
        ]);
    }

    #[Route('/new', name: 'app_site_crud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $site = new Site();
        $form = $this->createForm(SiteType::class, $site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($site);
            $entityManager->flush();

            return $this->redirectToRoute('app_site_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('site_crud/new.html.twig', [
            'site' => $site,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_site_crud_show', methods: ['GET'])]
    public function show(Site $site): Response
    {
        return $this->render('site_crud/show.html.twig', [
            'site' => $site,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_site_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Site $site, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SiteType::class, $site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_site_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('site_crud/edit.html.twig', [
            'site' => $site,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_site_crud_delete', methods: ['POST'])]
    public function delete(Request $request, Site $site, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$site->getId(), $request->request->get('_token'))) {
            $entityManager->remove($site);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_site_crud_index', [], Response::HTTP_SEE_OTHER);
    }
}
