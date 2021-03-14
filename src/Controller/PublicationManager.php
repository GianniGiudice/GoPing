<?php


namespace App\Controller;


use App\Repository\PublicationRepository;
use DateTime;
use Knp\Bundle\TimeBundle\DateTimeFormatter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PublicationManager extends AbstractController
{
    /**
     * @Route("/publications/updateTimeValues", name="update_time_values")
     * @param PublicationRepository $publicationRepository
     * @param DateTimeFormatter $dateTimeFormatter
     * @return JsonResponse
     * @throws \Exception
     */
    public function updateTimeValues(PublicationRepository $publicationRepository, DateTimeFormatter $dateTimeFormatter)
    {
        $publications = $publicationRepository->findAll();
        $data = [];
        foreach ($publications as $publication) {
            $now = new DateTime('now', new \DateTimeZone('Europe/Paris'));
            $data[] = [
                'id' => $publication->getId(),
                'ago' => $dateTimeFormatter->formatDiff($publication->getPublicationDate(), $now)
            ];
        }
        return new JsonResponse(json_encode($data), JsonResponse::HTTP_OK);
    }
}