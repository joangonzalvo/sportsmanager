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
        foreach ($classifications as $local) {
            $localid=$local->getId();
           foreach ($classifications as $rival) {
               $rivalid=$rival->getId();
               if($localid != $rivalid){
                   if($rivalid > $comp){
                       $teamlocal=$local->getTeamId();
                       $teamrival=$rival->getTeamId();
                       if($teamlocal->getTeamValue() != $teamrival->getTeamValue()){
                           $dif=$teamlocal->getTeamValue()-$teamrival->getTeamValue();
                           $dif=$dif+50;
                           //If diference is more than 100 will be auto 99 dif, to get a "luck" factor. Can only win if rand number is 100
                           if($dif >= 100){
                               $dif=99;                               
                           }
                           $simulation=mt_rand(1,100);
                           //If simulation number is smaller than diference, local team wins. If its the same, its a draw. Else, rival wins.
                           //Win means 3 points and 1 point of value, draw 1 point, lose is 1 point of value less
                           
                           if($simulation <= $dif){
                               //LOCAL TEAM WINS
                               $localpoints=$local->getPoints()+3;
                               $localwins=$local->getWin()+1;
                               $local->setPoints($localpoints);
                               $local->setWin($localwins);
                               //Update data
                               $em = $this->getDoctrine()->getManager();
                               $em->persist($local);
                               $em->flush();
                               //RIVAL LOSE
                               $rivallose=$rival->getLost()+1;
                               $rival->setLost($rivallose);
                               //Update data
                               $em->persist($rival);
                               $em->flush();
                               //Now update team value
                               $localvalue=$teamlocal->getTeamValue()+1;
                               $rivalvalue=$teamrival->getTeamValue()-1;
                               $teamlocal->setTeamValue($localvalue);
                               $teamrival->setTeamValue($rivalvalue);
                               $em->persist($teamlocal);
                               $em->flush();
                               $em->persist($teamrival);
                               $em->flush();
                               
                           }elseif($simulation == $dif){
                               //LOCAL DRAW
                               $localpoints=$local->getPoints()+1;
                               $localdraw=$local->getDraw()+1;
                               $local->setPoints($localpoints);
                               $local->setDraw($localdraw);
                               //Update data
                               $em = $this->getDoctrine()->getManager();
                               $em->persist($local);
                               $em->flush();
                               //RIVAL DRAW
                               $rivalpoints=$rival->getPoints()+1;
                               $rivaldraw=$rival->getDraw()+1;
                               $rival->setPoints($rivalpoints);
                               $rival->setDraw($rivaldraw);
                               //Update data
                               $em->persist($rival);
                               $em->flush();
                               //No need of update values
                               
                           }else{
                               //LOCAL LOSE
                               $locallose=$local->getLost()+1;
                               $local->setLost($locallose);
                               //Update data
                               $em = $this->getDoctrine()->getManager();
                               $em->persist($local);
                               $em->flush();
                               //RIVAL WIN
                               $rivalwin=$rival->getWin()+1;
                               $rivalpoints=$rival->getPoints()+1;
                               $rival->setPoints($rivalpoints);
                               $rival->setWin($rivalwin);
                               //Update data
                               $em->persist($rival);
                               $em->flush();
                               //Now update team value
                               $localvalue=$teamlocal->getTeamValue()-1;
                               $rivalvalue=$teamrival->getTeamValue()+1;
                               $teamlocal->setTeamValue($localvalue);
                               $teamrival->setTeamValue($rivalvalue);
                               $em->persist($teamlocal);
                               $em->flush();
                               $em->persist($teamrival);
                               $em->flush();
                               
                           }
                           
                       }
                       else{//TEAM VALUES ARE THE SAME

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
