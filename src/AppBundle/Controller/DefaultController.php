<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Race;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/")
 *
 * Class DefaultController
 * @package AppBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @Route("", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("race/{raceId}", name="race.show")
     */
    public function raceAction($raceId, Request $request) {
        $race = $this->getDoctrine()->getRepository(Race::class)->find($raceId);

        return $this->render('default/race.html.twig', [
            'race' => $race
        ]);
    }
}
