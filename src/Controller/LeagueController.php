<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LeagueController extends Controller
{
    /**
     * @Route("/league/{thisleague}",name="thisleague")
     */
    public function indexAction($thisleague){
        $teams = $this->getDoctrine()->getRepository('App:Team')->findAll();
        $users = $this->getDoctrine()->getRepository('App:User')->findAll();
        $leagues = $this->getDoctrine()->getRepository('App:League')->findAll();
        $repository = $this->getDoctrine()->getRepository('App:League');
        $actualLeague = $repository->findOneBy(['id' => $thisleague]);
        
        //GET ALL THE CLASSIFICATIONS OF ALL LEAGUES:
        $totalclassifications = $this->getDoctrine()->getRepository('App:Classification');
        //SEARCH FOR THE CLASSIFICATIONS OF THIS LEAGUE ONLY, ORDERED BY POINTS
        $classifications = $totalclassifications->findBy(
            ['league' => $actualLeague],
            ['points' => 'ASC']
        );
        
        return $this->render('league/thisleague.html.twig',[
            'teams' => $teams,
            'users' => $users,
            'actualLeague' => $actualLeague,
            'leagues' => $leagues,
            'classifications' => $classifications
             ]);
    }
}
