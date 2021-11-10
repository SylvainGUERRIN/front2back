<?php

namespace App\Controller\Admin;

use App\Entity\Tag;
use App\Form\Tags\TagType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PostController.
 *
 * @Route ("/admin/tags")
 */
class TagController extends AbstractController
{
    private ManagerRegistry $doctrine;
    private RequestStack $request;

    public function __construct(ManagerRegistry $managerRegistry, RequestStack $request)
    {
        $this->doctrine = $managerRegistry;
        $this->request = $request;
    }

    /**
     * @Route("/dashboard", name="admin_tags_dashboard")
     */
    public function index(): Response
    {
        return $this->render('admin/tags/dashboard.html.twig', [
            'tags' => $this->doctrine->getRepository(Tag::class)->findAll(),
        ]);
    }

    /**
     * @Route("/create", name="admin_tags_create")
     */
    public function create(): Response
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($this->request->getCurrentRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            $this->doctrine->getManager()->persist($tag);
            $this->doctrine->getManager()->flush();

            return $this->redirectToRoute('admin_tags_dashboard');
        }

        return $this->render('admin/tags/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="admin_tags_edit")
     */
    public function edit(Tag $tag): Response
    {
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($this->request->getCurrentRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            $this->doctrine->getManager()->flush();

            return $this->redirectToRoute('admin_tags_dashboard');
        }

        return $this->render('admin/tags/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_tags_delete")
     */
    public function delete(Tag $tag): Response
    {
        $this->doctrine->getManager()->remove($tag);
        $this->doctrine->getManager()->flush();

        return $this->redirectToRoute('admin_tags_dashboard');
    }

}
