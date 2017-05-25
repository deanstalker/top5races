<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Meeting;
use AppBundle\Entity\Race;
use AppBundle\Entity\Runner;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGenerator;

/**
 * @Route("/api"))
 *
 * Class RestController
 * @package AppBundle\Controller
 */
class RestController extends Controller
{
    /**
     * @Route("/top5", name="top5")
     */
    public function getTop5Action() {
        $html = @file_get_contents('https://ladbrokes.com.au');

        $crawler = new Crawler($html);
        $races = [];

        $raceListings = $crawler->filter('.race-listing-container > div.race-listing')
            ->each(function(Crawler $node, $i) use (&$races) {
                $meeting = [];
                $race = [];
                list($race['timetogo'], $meeting['type'], $race['description'], $race['name'], $meeting['name'], $race['id'], $url) = $node->extract(
                    [
                        'data-timetogo',
                        'data-racetype',
                        'data-description',
                        'data-meeting-race-name',
                        'data-meeting-name',
                        'id',
                        'onclick'
                    ]
                )[0];
                $race['timetogo'] = (int) $race['timetogo'];
                $race['id'] = (int) str_replace('main-upcoming-','', $race['id']);

                $regex = '/(?:window\.location\.replace\(\')(.*)(?:\'\))/';
                preg_match_all($regex, $url, $matches, PREG_SET_ORDER, 0);

                $race['raceNumber'] = $node->filter('.race-number')->text();

                $meetingEntity = $this->getDoctrine()->getRepository(Meeting::class)->findOneBy(
                    [
                        'location' => $meeting['name'],
                        'type' => $meeting['type']
                    ]
                );
                if (!($meetingEntity instanceof Meeting)) {
                    $meetingEntity = new Meeting();
                    $meetingEntity
                        ->setLocation($meeting['name'])
                        ->setType($meeting['type']);
                    $this->getDoctrine()->getManager()->persist($meetingEntity);
                }

                $raceEntity = $this->getDoctrine()->getRepository(Race::class)->findOneBy(['id' => $race['id']]);
                if (!($raceEntity instanceof Race)) {
                    $raceEntity = new Race();
                    $raceEntity
                        ->setId($race['id'])
                        ->setDescription($race['description'])
                        ->setTimetogo($race['timetogo'])
                        ->setRaceNumber($race['raceNumber'])
                        ->setMeeting($meetingEntity)
                        ->setRaceName($race['name'])
                        ->setLink('https://ladbrokes.com.au'.$matches[0][1])
                    ;
                    $this->getDoctrine()->getManager()->persist($raceEntity);
                }

                if ($race['timetogo'] > time()) {
                    $races[$race['id']] = [
                        'meeting' => $meeting,
                        'race' => $race,
                        'links' => [
                            [
                                'rel' => 'race.source',
                                'href' => 'https://ladbrokes.com.au'.$matches[0][1]
                            ],
                            [
                                'rel' => 'race.view',
                                'href' => $this->generateUrl('race', ['raceId' => $race['id']], UrlGenerator::ABSOLUTE_URL)
                            ]
                        ]
                    ];
                }
            })
        ;



        uasort($races, function($a, $b) {
           if ($a['race']['timetogo'] === $b['race']['timetogo']) {
               return 0;
           }
            return ($a['race']['timetogo'] < $b['race']['timetogo']) ? -1 : 1;
        });

        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse(array_slice($races, 0, 5, false), 200, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/race/{raceId}", name="race")
     *
     * @param $raceId
     */
    public function getRaceAction($raceId) {
        $race = $this->getDoctrine()->getRepository(Race::class)->find($raceId);
        if ($race instanceof Race) {
            // load the runners

            $html = @file_get_contents($race->getLink());

            $crawler = new Crawler($html);

            $runners = [];

            $crawler->filter('div.competitor-view')
                ->first()
                ->filter('table > tbody > tr.competitor')
                ->each(function(Crawler $node, $i) use (&$runners, $race) {
                list($id) = $node->extract(['data-competitorid']);
                $id = (int) $id;

                $saddleNumber = $node->filter('.entrant.data .saddle-number')->text();
                $competitorName = $node->filter('.entrant.data .competitor-name')->text();
                if ($race->getMeeting()->getType() !== 'harness') {
                    $barrierNumber = $node->filter('.entrant.data .barrier-number')->text();
                } else {
                    $barrierNumber = 0;
                }

                $runners[$id] = [
                    'id' => $id,
                    'type' => $race->getMeeting()->getType(),
                    'name' => $competitorName,
                    'barrierNumber' => $barrierNumber,
                    'saddleNumber' => $saddleNumber,
                ];
            });

            foreach ($runners as $runner) {
                if (!($this->getDoctrine()->getRepository(Runner::class)->find($runner['id']) instanceof Runner)) {
                $runnerEntity = new Runner();
                $runnerEntity
                    ->setId($runner['id'])
                    ->setType($race->getMeeting()->getType())
                    ->setName($runner['name'])
                    ->setBarrierNumber((int) $runner['barrierNumber'])
                    ->setSaddleNumber($runner['saddleNumber'])
                    ->setRace($race)
                    ->setPosition(0)
                ;

                $this->getDoctrine()->getManager()->persist($runnerEntity);

                }
            }

            $this->getDoctrine()->getManager()->flush();


            return new JsonResponse([
                'race' => $race,
                'runners' => $runners
            ]);
        } else {
            return new JsonResponse([
                'error' => 'Could not find the race requested'
            ], 500);
        }
    }
}