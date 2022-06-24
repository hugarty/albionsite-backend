<?php

namespace App\Controller;

use App\Entity\Alliance;
use App\Repository\AllianceDailyRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class FirstController extends AbstractController
{

  private $allianceRepository;

  public function __construct(AllianceDailyRepository $allianceRepository)
  {
    $this->allianceRepository = $allianceRepository;
  }

  /**
   * @Route("/")
   */
  public function olaasdf(): Response
  {
    // todo colocar um cache aqui ! ! !

    $startDate = new DateTime('2022-06-16T15:03:01.012345Z');
    $endDate = new DateTime('2022-06-24T15:03:01.012345Z');

    return new JsonResponse(
      $this->allianceRepository->findBetwteenDates($startDate, $endDate)
    );
  }


  /**
   * @Route("/ola")
   */
  public function ola(): Response
  {
    $repository = $this->getDoctrine()
      ->getRepository(Alliance::class);

    return new JsonResponse($repository->findAll());
  }
}
