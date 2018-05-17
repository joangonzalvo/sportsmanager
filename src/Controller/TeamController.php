<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Team;
use App\Entity\User;
use App\Form\TeamType;
use App\Form\JoinType;

class TeamController extends Controller
{
    /**
     * @Route("/jointeam", name="jointeam")
     */
    public function joinTeam(Request $request)
    {
        $team = new Team();
        
        $form = $this->createForm(JoinType::class, $team);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
           $team=$form->getData();
           $thiskey=$team->getTeamkey();
           
           $repository = $this->getDoctrine()->getRepository('App:Team');
           $thisTeam = $repository->findOneBy(['teamkey' => $thiskey]);
           if (!$thisTeam) {
                throw $this->createNotFoundException(
                    'No team found for key '.$thiskey
                );
            }else{
                $user=$this->getUser();
                $user->setTeam($thisTeam);
                $user->setTeamRole("Team_Member");
                $bm = $this->getDoctrine()->getManager();
                $bm->persist($user);
                $bm->flush();

            }
            return $this->redirectToRoute('homeaction');
       }
       //rendering form
        return $this->render('team/jointeam.html.twig', array(
            'form' => $form->createView(),
        ));
        
    }
    /**
    * @return string
    */
    private function generateUniqueFileName()
    {
    // md5() reduces the similarity of the file names generated by
    // uniqid(), which is based on timestamps
    return md5(uniqid());
    }

    /**
     * @Route("/createteam", name="createteam")
     */
    public function createTeam(Request $request)
    {
        $team = new Team();
        $team->setLeagueTitles(0);
        $team->setOtherTitles(0);
        //Team value default
        $team->setTeamValue(50);

        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
           $team=$form->getData();
           //TEAMKEY
           $mycode="t";
           $rand1=rand(100,999);
           $mycode=$mycode."$rand1"."m".substr($team->getTeamname(),0,3)."c";
           $rand2=rand(10,99);
           $mycode=$mycode."$rand2";
           $team->setTeamkey($mycode);
           //LOGO
            //upload file
            $file=$team->getLogo();
            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
            // moves the file to the directory where brochures are stored
            $file->move(
                    $this->getParameter('pictures_directory'),
                    $fileName
            );
            $team->setLogo($fileName);
            
           $em = $this->getDoctrine()->getManager();
           $em->persist($team);
           $em->flush();
           
           $user=$this->getUser();
           $user->setTeam($team);
           $user->setTeamRole("Team_Owner");
           $bm = $this->getDoctrine()->getManager();
           $bm->persist($user);
           $bm->flush();
           
           return $this->redirectToRoute('homeaction');
       }
       //rendering form
        return $this->render('team/newteam.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
