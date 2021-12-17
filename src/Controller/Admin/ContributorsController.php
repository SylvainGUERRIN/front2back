<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PostController.
 *
 * @Route ("/admin/contributors")
 */
class ContributorsController extends AbstractController
{
    private ManagerRegistry $doctrine;
    private RequestStack $request;

    public function __construct(ManagerRegistry $managerRegistry, RequestStack $request)
    {
        $this->doctrine = $managerRegistry;
        $this->request = $request;
    }

    /**
     * @Route ("/", name="admin_contributors_index")
     */
    public function index(PaginatorInterface $paginator): Response
    {
        //pour être contributeur, il faut faire la demande d'adhésion via le formulaire
        //ensuite l'administrateur doit valider la demande
        //dump($this->doctrine->getRepository(User::class)->findUserWithRequests());
//        dump($this->doctrine->getRepository(User::class)->findUserByRequestContributingOnRequesting('contributor'));
        //die();
        $contributors = [];
        $usersWithRequests = $this->doctrine->getRepository(User::class)->findUserWithRequests();
        foreach ($usersWithRequests as $user) {
            if ($user->getRequests()['contributor'] !== null) {
                $contributors[] = $user;
            }
        }

        $paginateContributors = $paginator->paginate(
            $contributors,
            $this->request->getCurrentRequest()->query->getInt('page', 1),
            10
        );

        //change function to get contributors
        //$contributors = $this->doctrine->getRepository(User::class)->findAll();

        return $this->render('admin/contributors/index.html.twig', [
            'contributors' => $paginateContributors,
        ]);
    }

    /**
     * @param $id
     * @param User $user
     * @return Response
     * @Route ("/{id}/accept", name="admin_accept_new_contributor")
     */
    public function validateUserContributingRequest($id, User $user): Response
    {
        //change function to get contributors
        $userWhoRequestForContributing = $this->doctrine->getRepository(User::class)->findBy(['id' => $id])[0];

        //change requesting array to done
        $requestsForContributing = $userWhoRequestForContributing->getRequests();
        $requestsForContributing['contributor'] = "Cet utilisateur contribue depuis le ". date('d/m/Y');
        $userWhoRequestForContributing->setRequests($requestsForContributing);
        $userWhoRequestForContributing->setRoles(['ROLE_CONTRIBUTOR']);
        $this->doctrine->getManager()->flush();

        $this->addFlash(
            'success',
            "La contribution de <strong>{$userWhoRequestForContributing->getFirstname()}</strong>
                    a bien été prise en compte !"
        );

        return $this->redirectToRoute('admin_contributors_index');
    }

    /**
     * @param $id
     * @param User $user
     * @return Response
     * @Route ("/{id}/decline", name="admin_decline_new_contributor")
     */
    public function declineUserContributingRequest($id, User $user): Response
    {
        //change function to get contributors
        $userWhoRequestForContributing = $this->doctrine->getRepository(User::class)->findBy(['id' => $id])[0];

        //change requesting array to done
        $requestsForContributing = $userWhoRequestForContributing->getRequests();
        $requestsForContributing['contributor'] = "Cet utilisateur a arrêté de contribuer depuis le ". date('d/m/Y');
        $userWhoRequestForContributing->setRequests($requestsForContributing);
        $userWhoRequestForContributing->setRoles(['ROLE_USER']);
        $this->doctrine->getManager()->flush();

        $this->addFlash(
            'success',
            "La contribution de <strong>{$userWhoRequestForContributing->getFirstname()}</strong> a bien été suprimée !"
        );

        return $this->redirectToRoute('admin_contributors_index');
    }
}
