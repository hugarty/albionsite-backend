<?php

namespace App\Controller;

use App\Dto\ItemDto;
use App\Entity\Guild;
use App\Entity\Alliance;
use App\Repository\AllianceDailyRepository;
use App\Repository\AllianceWeeklyRepository;
use App\Repository\GuildDailyRepository;
use Doctrine\Persistence\ManagerRegistry;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{

  private $managerRegistry;
  private $allianceDailyRepository;
  private $allianceWeeklyRepository;
  private $guildDailyRepository;
  private $guildDailyTitles = [
    "fame" => "Fame",
    "killFame" => "Kill Fame", 
    "deathFame" => "Death Fame", 
    "gvgKills" => "Gvg Kills", 
    "gvgDeaths" => "Gvg Deaths", 
    "kills" => "Kills", 
    "deaths" => "Deaths", 
    "ratio" => "Ratio", 
    "memberCount" => "Members Total"
  ];
  private $allianceDailyTitles = [
    "guildCount" => "Guilds Total",
    "memberCount" => "Members Total"
  ];
  private $allianceWeeklyTitles = [
    "territories" => "Weekly Territories", 
    "castles" => "Weekly Castles"
  ];

  public function __construct(
    ManagerRegistry $managerRegistry,
    AllianceDailyRepository $allianceDailyRepository,
    AllianceWeeklyRepository $allianceWeeklyRepository,
    GuildDailyRepository $guildDailyRepository
  ) {
    $this->managerRegistry = $managerRegistry;
    $this->allianceDailyRepository = $allianceDailyRepository;
    $this->allianceWeeklyRepository = $allianceWeeklyRepository;
    $this->guildDailyRepository = $guildDailyRepository;
  }

  /**
   * @Route("/")
   */
  public function getTopFiveFameGuildDaily(): Response
  {
    $results = $this->guildDailyRepository->getFiveMostFamousGuildDaily();
    $response = $this->transformResultsInResponseChart($this->guildDailyTitles, $results);
    return new JsonResponse($response);
  }

  /**
   * @Route("/search/options")
   */
  public function guilds(): Response
  {
    $mapper = function ($item) {
      return ["id" => $item->id, "name" => $item->name];
    };

    $repository = $this->managerRegistry->getRepository(Guild::class);
    $guildResult = $repository->findAll();
    $guilds = array_map($mapper, $guildResult);

    $repository = $this->managerRegistry->getRepository(Alliance::class);
    $allianceResult = $repository->findAll();
    $alliances = array_map($mapper, $allianceResult);

    $response = ["guilds" => $guilds, "alliances" => $alliances];
    return new JsonResponse($response);
  }

  /**
   * @Route("/search/guilds")
   */
  public function searchGuilds(Request $resquest): Response
  {
    $startDate = $resquest->query->get('startDate');
    $endDate = $resquest->query->get('endDate');
    $ids = explode(',', $resquest->query->get('ids'));
    $this->validateInputs($startDate, $endDate, $ids);

    $results = $this->guildDailyRepository->getFromIds($ids, new DateTime($startDate), new DateTime($endDate));
    $responseArray = $this->transformResultsInResponseChart($this->guildDailyTitles, $results);
    return new JsonResponse($responseArray);
  }

  /**
   * @Route("/search/alliances")
   */
  public function searchAlliances(Request $resquest): Response
  {
    $startDate = $resquest->query->get('startDate');
    $endDate = $resquest->query->get('endDate');
    $ids = explode(',', $resquest->query->get('ids'));
    $this->validateInputs($startDate, $endDate, $ids);
    
    $resultsDaily = $this->allianceDailyRepository->getFromIds($ids, new DateTime($startDate), new DateTime($endDate));
    $response = $this->transformResultsInResponseChart($this->allianceDailyTitles, $resultsDaily);
    $resultsWeekly = $this->allianceWeeklyRepository->getFromIds($ids, new DateTime($startDate), new DateTime($endDate));
    $response = $this->transformResultsInResponseChart($this->allianceWeeklyTitles, $resultsWeekly, $response);
    return new JsonResponse($response);
  }

  private function validateInputs($startDate, $endDate, $ids){
    $datePattern = '/^\d{4}-\d{2}-\d{2}/';
    if (!preg_match($datePattern, $startDate) || !preg_match($datePattern, $endDate)) {
      throw new BadRequestHttpException("Client does not sent startDate or endDate. The accepted format is YYYY-mm-dd.");
    }
    $idsMissingErrorMessage = "Client does not sent IDs or format is invalid.";
    if ($ids == null || count($ids) === 0) {
      throw new BadRequestHttpException($idsMissingErrorMessage);
    }
    foreach($ids as $id) {
      if (!is_numeric($id)){
        throw new BadRequestHttpException($idsMissingErrorMessage);
      }
    }
  }

  private function transformResultsInResponseChart ($titles, $items, $response = []) {
    foreach ($titles as $propertyName => $tableName) {
      foreach ($items as $item) {
        $this->addIfKeyNotExists($response, $tableName, []);
        $table = &$response[$tableName];
        $this->addIfKeyNotExists($table, $item["id"], new ItemDto($item["name"]));
        $this->addValueToItem($table[$item["id"]], $item, $propertyName);
      }
    }
    return $response;
  }

  private function addIfKeyNotExists (&$array, $key, $value) {
    if (!array_key_exists($key, $array)) {
      $array[$key] = $value;
    }
  }

  private function addValueToItem (&$itemOnTable, $item, $propertyName) {
    $dateString = $item[0]->date->format('d/m/Y');
    $itemOnTable->values[$dateString] = $item[0]->$propertyName;
  }
}
