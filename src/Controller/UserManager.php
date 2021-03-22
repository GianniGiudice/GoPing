<?php


namespace App\Controller;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserManager
 * @package App\Controller
 * @Route("/user")
 */
class UserManager extends AbstractController
{
    /**
     * @Route("/{id}", name="user_profile", requirements={"id"="\d+"})
     * @param User $user
     * @return Response
     */
    public function show(User $user)
    {
        return $this->render('authenticated/user/profile.html.twig', [
            'user' => $user
        ]);
    }
}