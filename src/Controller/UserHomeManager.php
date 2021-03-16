<?php


namespace App\Controller;


use App\Entity\Publication;
use App\Form\Type\Authenticated\PublicationType;
use App\Repository\PublicationRepository;
use App\Repository\ReactionRepository;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserHomeManager
 * @package App\Controller
 * @Route("/user")
 * @IsGranted("ROLE_USER")
 */
class UserHomeManager extends AbstractController
{
    /**
     * @Route("/", name="user_home")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param PublicationRepository $publicationRepository
     * @param ReactionRepository $reactionRepository
     * @return Response
     * @throws \Exception
     */
    public function home(Request $request, EntityManagerInterface $entityManager,
                         PublicationRepository $publicationRepository, ReactionRepository $reactionRepository)
    {
        $publication = new Publication();
        $form = $this->createForm(PublicationType::class, $publication);
        $publication->setAuthor($this->getUser());
        $publication->setPublicationDate(new DateTime('now', new DateTimeZone('Europe/Paris')));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($publication);
            $entityManager->flush();
            $publication = new Publication();
            $form = $this->createForm(PublicationType::class, $publication);
        }
        return $this->render('authenticated/home.html.twig', [
            'form' => $form->createView(),
            'publications' => $publicationRepository->findAll(),
            'reactions' => $reactionRepository->findAll()
        ]);
    }
}