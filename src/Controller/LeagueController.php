<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LeagueController extends Controller
{
    /**
     * @Route("/league/{thisleague}",name="thisleague")
     */
    public function showLeague($thisleague){
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
            ['points' => 'DESC']
        );
        
        return $this->render('league/thisleague.html.twig',[
            'teams' => $teams,
            'users' => $users,
            'actualLeague' => $actualLeague,
            'leagues' => $leagues,
            'classifications' => $classifications
             ]);
    }
    /**
     * @Route("/league/{thisleague}/simulate",name="simulate")
     */
    public function simulateLeague($thisleague){
        $teams = $this->getDoctrine()->getRepository('App:Team')->findAll();
        $repository = $this->getDoctrine()->getRepository('App:League');
        $actualLeague = $repository->findOneBy(['id' => $thisleague]);
        //GET ALL THE CLASSIFICATIONS OF ALL LEAGUES:
        $totalclassifications = $this->getDoctrine()->getRepository('App:Classification');
        //SEARCH FOR THE CLASSIFICATIONS OF THIS LEAGUE ONLY, ORDERED BY POINTS
        $classifications = $totalclassifications->findBy(
            ['league' => $actualLeague],
            ['points' => 'DESC']
        );
        //echo(count($classifications));
        $comp=0;
        foreach ($classifications as $key) {
            $keyid=$key->getId();
           foreach ($classifications as $rival) {
               $rivalid=$rival->getId();
               if($keyid != $rivalid){
                   if($rivalid > $comp){
                       $teamlocal=$key->getTeamId();
                       $teamrival=$rival->getTeamId();
                       if($teamlocal->getTeamValue() > $teamrival->getTeamValue()){
                           $dif=$teamlocal->getTeamValue()-$teamrival->getTeamValue();
                           $dif=$dif+50;
                           if($dif >= 100){
                               $dif=99;                               
                           }
                           
                       }
                       elseif($teamlocal->getTeamValue() < $teamrival->getTeamValue()){

                       }
                       else{

                       }
                   }
               }
            }
            $comp++;
       }
        /*$total=count($classifications);
        $thisTeam = $teams->findOneBy(['id' => 0]);
        $thisTeamClass = $classifications->findOneBy(['id' => 0]);*/
        
        
        return $this->showLeague($thisleague);
    }
    
}
