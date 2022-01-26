<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BadgeController extends AbstractController
{
    // penser à limiter les choix de l'administrateur sur le nom de l'action et le delimiteur de l'action
    // faire deux select pour ses champs
    // nom de l'action: limiter à comments, posts, favorite, user (mail alert, activate, contributor), badge
    // delimiteur de l'action: limiter à true, false ou un entier
}
