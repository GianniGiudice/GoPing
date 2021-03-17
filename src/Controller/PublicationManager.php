<?php


namespace App\Controller;


use App\Entity\Publication;
use App\Entity\PublicationReaction;
use App\Entity\Reaction;
use App\Entity\User;
use App\Repository\PublicationReactionRepository;
use App\Repository\PublicationRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\TimeBundle\DateTimeFormatter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/publications")
 * Class PublicationManager
 * @package App\Controller
 */
class PublicationManager extends AbstractController
{
    /**
     * @Route("/updateTimeValues", name="update_time_values")
     * @param PublicationRepository $publicationRepository
     * @param DateTimeFormatter $dateTimeFormatter
     * @return JsonResponse
     * @throws \Exception
     */
    public function updateTimeValues(PublicationRepository $publicationRepository, DateTimeFormatter $dateTimeFormatter): JsonResponse
    {
        $publications = $publicationRepository->findAll();
        $data = [];
        foreach ($publications as $publication) {
            $now = new DateTime('now', new \DateTimeZone('Europe/Paris'));
            $data[] = [
                'id' => $publication->getId(),
                'ago' => $dateTimeFormatter->formatDiff($publication->getPublicationDate(), $now, 'fr')
            ];
        }
        return new JsonResponse(json_encode($data), JsonResponse::HTTP_OK);
    }

    /**
     * @Route("/react", name="react_to_publication", methods={"POST"})
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function reactToPublication(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (($label = $request->request->get('label')) &&
            ($already_reacted = $request->request->get('already_reacted')) &&
            ($publication_id = $request->request->get('publication_id'))) {

            // Si la publication existe bien
            /** @var Publication $publication */
            if ($publication = $entityManager->getRepository(Publication::class)->find($publication_id)) {
                // Suppression de la réaction
                if ($already_reacted === "true") {
                    if ($publication_reaction = $entityManager->getRepository(PublicationReaction::class)
                        ->findOneBy(['author' => $this->getUser(), 'publication' => $publication])) {
                        $entityManager->remove($publication_reaction);
                        $entityManager->flush();

                        return new JsonResponse('La réaction a bien été supprimée.', Response::HTTP_OK);
                    }
                } // Ajout d'une réaction
                else {
                    if ($reaction = $entityManager->getRepository(Reaction::class)->findOneBy(['label' => $label])) {
                        // Si l'utilisateur avait déjà réagi à cette publication (avec une autre réaction)
                        /** @var Reaction $reaction */
                        if ($publication_reaction = $entityManager->getRepository(PublicationReaction::class)->findOneBy(['author' => $this->getUser(), 'publication' => $publication])) {
                            /** @var PublicationReaction $publication_reaction */
                            $publication_reaction->setReaction($reaction);
                        }
                        else {
                            // Si l'utilisateur n'avait pas encore réagi à la publication
                            $publication_reaction = new PublicationReaction();
                            $publication_reaction->setAuthor($this->getUser());
                            $publication_reaction->setPublication($publication);
                            $publication_reaction->setReaction($reaction);
                        }

                        $entityManager->persist($publication_reaction);
                        $entityManager->flush();

                        return $this->render('authenticated/publication/include/show_publication.html.twig', [
                            'reactions' => $entityManager->getRepository(Reaction::class)->findAll(),
                            'publication' => $publication
                        ]);
                    }
                }
            }
        }
        return new JsonResponse('Les données sont erronées.', Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}