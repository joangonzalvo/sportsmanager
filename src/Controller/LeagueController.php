<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Classification;
use App\Entity\League;
use App\Form\LeagueType;

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
    public function simulateRound($thisleague){
        $repository = $this->getDoctrine()->getRepository('App:League');
        $actualLeague = $repository->findOneBy(['id' => $thisleague]);
        //GET ALL THE CLASSIFICATIONS OF ALL LEAGUES:
        $totalclassifications = $this->getDoctrine()->getRepository('App:Classification');
        //SEARCH FOR THE CLASSIFICATIONS OF THIS LEAGUE ONLY, ORDERED BY POINTS
        $classifications = $totalclassifications->findBy(
            ['league' => $actualLeague],
            ['points' => 'DESC']
        );
        $classifications2 = $totalclassifications->findBy(
            ['league' => $actualLeague],
            ['points' => 'DESC']
        );
        /**
         * FIRST WE SIMULATE WINS, LOST AND DRAWS WITHOUT CARING OF THE POINTS
         */
        $count=0;
        $os = array();//Array for save the id of the used id's
        foreach ($classifications as $local) {
            $localid=$local->getId();
            $os[$count]=$local->getId();
            $count++;
           foreach ($classifications2 as $rival) {
               $rivalid=$rival->getId();
               if($localid != $rivalid){
                   if (in_array($rivalid, $os)){}else{
                       $teamlocal=$local->getTeamId();
                       $teamrival=$rival->getTeamId();
                       
                       $dif=($teamlocal->getTeamValue()-$teamrival->getTeamValue())+50;
                           //If diference is more than 100 will be auto 99 dif, to get a "luck" factor. Can only win if rand number is 100
                           if($dif >= 100){
                               $dif=99;                               
                           }
                           $simulation=mt_rand(1,100);
                           //If simulation number is smaller than diference, local team wins. If its the same, its a draw. Else, rival wins.
                           //Win means 3 points and 1 point of value, draw 1 point, lose is 1 point of value less
                           
                           if($simulation < $dif){
                               //LOCAL TEAM WINS
                               $localwins=$local->getWin()+1;
                               $local->setWin($localwins);
                               //Update data
                               $em = $this->getDoctrine()->getManager();
                               $em->persist($local);
                               $em->flush();
                               //RIVAL LOSE
                               $rivallose=$rival->getLost()+1;
                               $rival->setLost($rivallose);
                               //Update data
                               $bm = $this->getDoctrine()->getManager();
                               $bm->persist($rival);
                               $bm->flush();
                               //Now update team value
                               $localvalue=$teamlocal->getTeamValue()+1;
                               $rivalvalue=$teamrival->getTeamValue()-1;
                               if($rivalvalue<=50){
                                   $rivalvalue=50;
                               }
                               $teamlocal->setTeamValue($localvalue);
                               $teamrival->setTeamValue($rivalvalue);
                               $em2 = $this->getDoctrine()->getManager();
                               $em2->persist($teamlocal);
                               $em2->flush();
                               $bm2 = $this->getDoctrine()->getManager();
                               $bm2->persist($teamrival);
                               $bm2->flush();
                               //echo($teamlocal->getTeamName()." vs ".$teamrival->getTeamName()."LOCAL WINS<br>");
                               
                           }
                           if($simulation == $dif){
                               //LOCAL DRAW
                               $localdraw=$local->getDraw()+1;
                               $local->setDraw($localdraw);
                               //Update data
                               $em = $this->getDoctrine()->getManager();
                               $em->persist($local);
                               $em->flush();
                               //RIVAL DRAW
                               $rivaldraw=$rival->getDraw()+1;
                               $rival->setDraw($rivaldraw);
                               //Update data
                               $bm = $this->getDoctrine()->getManager();
                               $bm->persist($rival);
                               $bm->flush();
                               //No need of update values
                               //echo($teamlocal->getTeamName()." vs ".$teamrival->getTeamName()." DRAW <br>");
                               
                           }
                           if($simulation > $dif){
                               //LOCAL LOSE
                               $locallose=$local->getLost()+1;
                               $local->setLost($locallose);
                               //Update data
                               $em = $this->getDoctrine()->getManager();
                               $em->persist($local);
                               $em->flush();
                               //RIVAL WIN
                               $rivalwin=$rival->getWin()+1;
                               $rival->setWin($rivalwin);
                               //Update data
                               $bm = $this->getDoctrine()->getManager();
                               $bm->persist($rival);
                               $bm->flush();
                               //Now update team value
                               $localvalue=$teamlocal->getTeamValue()-1;
                               if($localvalue<=50){
                                   $localvalue=50;
                               }
                               $rivalvalue=$teamrival->getTeamValue()+1;
                               $teamlocal->setTeamValue($localvalue);
                               $teamrival->setTeamValue($rivalvalue);
                               $em2 = $this->getDoctrine()->getManager();
                               $em2->persist($teamlocal);
                               $em2->flush();
                               $bm2 = $this->getDoctrine()->getManager();
                               $bm2->persist($teamrival);
                               $bm2->flush();
                               //echo($teamlocal->getTeamName()." vs ".$teamrival->getTeamName()." RIVAL WIN<br>");
                               
                           }
                           
                   }}//!LOCALID!RIVALID
               }
        }
        /**
         * NOW WE SET THE POINTS FOR EACH TEAM
         */
        foreach ($classifications as $thisclas) {
            $wins=($thisclas->getWin())*3;
            $draws=$thisclas->getDraw();
            $total=$wins+$draws;
            $thisclas->setPoints($total);
            
            $rm = $this->getDoctrine()->getManager();
            $rm->persist($thisclas);
            $rm->flush();
        }
        
        
        //END
        return $this->showLeague($thisleague);
    }
    /**
     * @Route("/league/{thisleague}/reset",name="reset")
     */
    public function resetLeague($thisleague){
        $repository = $this->getDoctrine()->getRepository('App:League');
        $actualLeague = $repository->findOneBy(['id' => $thisleague]);
        //GET ALL THE CLASSIFICATIONS OF ALL LEAGUES:
        $totalclassifications = $this->getDoctrine()->getRepository('App:Classification');
        //SEARCH FOR THE CLASSIFICATIONS OF THIS LEAGUE ONLY, ORDERED BY POINTS
        $classifications = $totalclassifications->findBy(
            ['league' => $actualLeague],
            ['points' => 'DESC']
        );
        foreach ($classifications as $local) {
            $local->setPoints(0);
            $local->setWin(0);
            $local->setLost(0);
            $local->setDraw(0);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($local);
            $em->flush();
        }
        return $this->showLeague($thisleague);
    }
    /**
     * @Route("/league/{thisleague}/complete",name="complete")
     */
    public function completetLeague($thisleague){
        $repository = $this->getDoctrine()->getRepository('App:League');
        $actualLeague = $repository->findOneBy(['id' => $thisleague]);
        //GET ALL THE CLASSIFICATIONS OF ALL LEAGUES:
        $totalclassifications = $this->getDoctrine()->getRepository('App:Classification');
        //SEARCH FOR THE CLASSIFICATIONS OF THIS LEAGUE ONLY, ORDERED BY POINTS
        $classifications = $totalclassifications->findBy(
            ['league' => $actualLeague],
            ['points' => 'DESC']
        );
        $flag=0;
        foreach ($classifications as $local) {
            if($flag==0){
            $winteam=$local->getTeam();
            $trophies=$winteam->getLeagueTitles()+1;
            $winteam->setLeagueTitles($trophies);
            $flag=1;
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($winteam);
            $em->flush();
            }
        }
        return $this->resetLeague($thisleague);
    }
    /**
     * @Route("/league/{thisleague}/{thisteam}/join",name="join")
     */
    public function joinLeague($thisleague, $thisteam){
        $teams = $this->getDoctrine()->getRepository('App:Team');
        $team = $teams->findOneBy(['id' => $thisteam]);
        $repository = $this->getDoctrine()->getRepository('App:League');
        $actualLeague = $repository->findOneBy(['id' => $thisleague]);
        //Security method to not repeat the join to a league classification
        $repository2 = $this->getDoctrine()->getRepository(Classification::class);
        $exist = $repository2->findOneBy([
            'team' => $team,
            'league' => $actualLeague,
        ]);
        if (!$exist) {
            //If the team has no classification in this league:
            $class = new Classification();
        
            $class->setLeague($actualLeague);
            $class->setTeam($team);
            $class->setDraw(0);
            $class->setLost(0);
            $class->setWin(0);
            $class->setPoints(0);

            $em = $this->getDoctrine()->getManager();
            $em->persist($class);
            $em->flush();
        
        }
        
        return $this->showLeague($thisleague);
    }
    /**
     * @Route("/league/{thisleague}/{thisteam}/leave",name="leave")
     */
    public function leaveLeague($thisleague, $thisteam){
        $teams = $this->getDoctrine()->getRepository('App:Team');
        $team = $teams->findOneBy(['id' => $thisteam]);
        $repository = $this->getDoctrine()->getRepository('App:League');
        $actualLeague = $repository->findOneBy(['id' => $thisleague]);
        //Security method to not repeat the join to a league classification
        $repository2 = $this->getDoctrine()->getRepository(Classification::class);
        $exist = $repository2->findOneBy([
            'team' => $team,
            'league' => $actualLeague,
        ]);
        if ($exist) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($exist);
            $entityManager->flush();
        }
        
        return $this->redirectToRoute('homeaction');
    }
    /**
     * @Route("/newleague", name="newleague")
     */
    public function newLeague(Request $request)
    {
        $teams = $this->getDoctrine()->getRepository('App:Team')->findAll();
        $users = $this->getDoctrine()->getRepository('App:User')->findAll();
        $leagues = $this->getDoctrine()->getRepository('App:League')->findAll();
        $name='demo';
        
        $league = new League();
        $s=date("Y-m-d H:i:s");
        $date = date_create_from_format('Y-m-d H:i:s', $s);
        $date->getTimestamp();
        
        $league->setDatestart($date);
        
        $form = $this->createForm(LeagueType::class, $league);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
           $league=$form->getData();
           
            
           $em = $this->getDoctrine()->getManager();
           $em->persist($league);
           $em->flush();
           
           return $this->redirectToRoute('homeaction');
       }
       //rendering form
        return $this->render('league/newleague.html.twig', array(
            'form' => $form->createView(),
            'teams' => $teams,
            'users' => $users,
            'leagues' => $leagues,
            'name'=> $name
            
        ));
    }
    
}//ENDCLASS
