<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\Type\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/")
 * Class HomeManager
 * @package App\Controller
 */
class HomeManager extends AbstractController
{
    /**
     * @Route("/", name="show_home")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $encoder
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     * @throws Exception
     */
    public function show(Request $request, EntityManagerInterface $entityManager,
                         UserPasswordEncoderInterface $encoder, AuthenticationUtils $authenticationUtils)
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('user_home');
        }
        $user = new User();
        $user->setRegistration(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
        $registration_form = $this->createForm(RegistrationType::class, $user);

        $registration_form->handleRequest($request);
        if ($registration_form->isSubmitted() && $registration_form->isValid()) {
            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Vous avez bien été enregistré.');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('home.html.twig', [
            'registration_form' => $registration_form->createView(),
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}