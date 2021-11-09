<?php

namespace App\Controller\Admin;

use App\Entity\Tag;
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
//        $tag = new Tag();
//        $form = $this->createForm(TagType::class, $tag);
//        $form->handleRequest($this->request->getCurrentRequest());
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $this->doctrine->getManager()->persist($tag);
//            $this->doctrine->getManager()->flush();
//
//            return $this->redirectToRoute('admin_tags_dashboard');
//        }

        return $this->render('admin/tags/create.html.twig', [
//            'form' => $form->createView(),
        ]);
    }
}
