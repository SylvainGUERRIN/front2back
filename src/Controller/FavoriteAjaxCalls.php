<?php

namespace App\Controller;

use App\Entity\Favorite;
use App\Entity\User;
use App\Repository\FavoriteRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavoriteAjaxCalls extends AbstractController
{
    private ManagerRegistry $doctrine;
    private FavoriteRepository $favoriteRepository;

    public function __construct(
        ManagerRegistry    $managerRegistry,
        FavoriteRepository $favoriteRepository
    ) {
        $this->doctrine = $managerRegistry;
        $this->favoriteRepository = $favoriteRepository;
    }

    /**
     * @Route ("/ajax/user/addToFavorite", name="ajax_user_addToFavorite")
     * @throws \JsonException
     * @throws NonUniqueResultException
     */
    public function userAddToFavorite(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $response = new Response();

        if ($request->isXMLHttpRequest()) {
            //get postId with ajax call
            $postId = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
            //verifier dans la table favorite si il n'y a pas déjà une ligne avec le user_id et post_id
            $favoriteAlreadyExists =
                $this->favoriteRepository->findFavoriteWithUserIdAndPostId($user->getId(), $postId);
            if (empty($favoriteAlreadyExists)) {
                $favorite = new Favorite();
                $favorite->setUser($user);
                $favorite->setPost($postId);
                $favorite->setLikedAt(new \DateTime('now'));
                $this->doctrine->getManager()->persist($favorite);
                $this->doctrine->getManager()->flush();

                $response->setContent(
                    json_encode(
                        ["Cette veille a bien été ajoutée à vos favoris."],
                        JSON_THROW_ON_ERROR
                    )
                );
            } else {
                $response->setContent(
                    json_encode(
                        ["Cette veille a déjà été ajoutée à vos favoris."],
                        JSON_THROW_ON_ERROR
                    )
                );
            }
        } else {
            $response->setContent(json_encode(["erreur lors de la demande"], JSON_THROW_ON_ERROR));
        }

        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');//set all origins but only for test
        return $response;
    }

    /**
     * @Route ("/ajax/user/removeToFavorite", name="ajax_user_removeToFavorite")
     * @throws \JsonException
     * @throws NonUniqueResultException
     */
    public function userRemoveToFavorite(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $response = new Response();

        if ($request->isXMLHttpRequest()) {
            //get postId with ajax call
            $postId = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
            //verifier dans la table favorite si il n'y a pas déjà une ligne avec le user_id et post_id
            $favoriteAlreadyExists =
                $this->favoriteRepository->findFavoriteWithUserIdAndPostId($user->getId(), $postId);
            if (!empty($favoriteAlreadyExists)) {
                $this->doctrine->getManager()->remove($favoriteAlreadyExists);
                $this->doctrine->getManager()->flush();
                $response->setContent(
                    json_encode(
                        ["Cette veille a bien été supprimée de vos favoris."],
                        JSON_THROW_ON_ERROR
                    )
                );
            } else {
                $response->setContent(
                    json_encode(
                        ["Cette veille n'est pas dans vos favoris."],
                        JSON_THROW_ON_ERROR
                    )
                );
            }
        } else {
            $response->setContent(json_encode(["erreur lors de la demande"], JSON_THROW_ON_ERROR));
        }

        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');//set all origins but only for test
        return $response;
    }
}
