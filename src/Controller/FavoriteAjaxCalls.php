<?php

namespace App\Controller;

use App\Entity\Favorite;
use App\Entity\User;
use App\Repository\FavoriteRepository;
use App\Repository\PostRepository;
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
    public function userAddToFavorite(Request $request, PostRepository $postRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $response = new Response();

        if ($request->isXMLHttpRequest() && $user !== null) {
            //get postId with ajax call
            $postId = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
            $post = $postRepository->find(['id' => $postId['id']]);
            //verifier dans la table favorite si il n'y a pas déjà une ligne avec le user_id et post_id
            $favoriteAlreadyExists =
                $this->favoriteRepository->findFavoriteWithUserIdAndPostId($user->getId(), $postId);
            if (empty($favoriteAlreadyExists)) {
                $favorite = new Favorite();
                $favorite->setUser($user);
                $favorite->setPost($post);
                $favorite->setLikedAt(new \DateTime('now'));
                $this->doctrine->getManager()->persist($favorite);
                $this->doctrine->getManager()->flush();

                $response->setContent(
                    json_encode(
                        ["Cette veille a bien été ajoutée à vos favoris."],
                        //$postId[0],
                        JSON_THROW_ON_ERROR
                    )
                );
            } else {
                $this->doctrine->getManager()->remove($favoriteAlreadyExists);
                $this->doctrine->getManager()->flush();
                $response->setContent(
                    json_encode(
                        ["Cette veille a bien été supprimée de vos favoris."],
                        JSON_THROW_ON_ERROR
                    )
                );
            }
            /*$response->setContent(
                json_encode(
                    $postId,
                    JSON_THROW_ON_ERROR
                )
            );*/
        } else {
            $response->setContent(json_encode(["erreur lors de la demande"], JSON_THROW_ON_ERROR));
        }

        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');//set all origins but only for test
        return $response;
    }

}
