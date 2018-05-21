<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    /**
     * @Route("/adminpanel",name="adminpanel")
     */
    public function admin(){
        $leagues = $this->getDoctrine()->getRepository('App:League')->findAll();
        
        return $this->render('admin/panel.html.twig', array(
            'leagues' => $leagues
        ));
    }
    /**
     * @Route("/adminpanel/users",name="adminusers")
     */
    public function adminUsers(){
        $users = $this->getDoctrine()->getRepository('App:User')->findAll();
        $leagues = $this->getDoctrine()->getRepository('App:League')->findAll();
        $teams = $this->getDoctrine()->getRepository('App:Team')->findAll();
        
        return $this->render('admin/users.html.twig',[
            'users' => $users,
            'teams' => $teams,
            'leagues' => $leagues]);
    }
    /**
     * @Route("/adminpanel/teams",name="adminteams")
     */
    public function adminTeams(){
        $users = $this->getDoctrine()->getRepository('App:User')->findAll();
        $leagues = $this->getDoctrine()->getRepository('App:League')->findAll();
        $teams = $this->getDoctrine()->getRepository('App:Team')->findAll();
        
        return $this->render('admin/teams.html.twig',[
            'users' => $users,
            'teams' => $teams,
            'leagues' => $leagues]);
    }
        /**
     * @Route("/adminpanel/leagues",name="adminleagues")
     */
    public function adminLeagues(){
        $leagues = $this->getDoctrine()->getRepository('App:League')->findAll();
        $teams = $this->getDoctrine()->getRepository('App:Team')->findAll();
        
        return $this->render('admin/leagues.html.twig',[
            'teams' => $teams,
            'leagues' => $leagues]);
    }
    
}//ENDCONTROLLER
