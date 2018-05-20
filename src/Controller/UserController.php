<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Entity\User;
use App\Form\RegisterType;

class UserController extends Controller
{
    public function __construct() {
        $this->session= new Session();
    }
    
    /**
     * @Route("/register",name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();        
        //rol
        $user->setRole('ROLE_USER');
        $user->setTeamRole('none');
        $user->setTeam(null);
        
        //creating the form
        $form = $this->createForm(RegisterType::class, $user);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encoding password, first we get password in plaintext and then
    // we encode it.
            $password=$passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $status="User registred";
            $this->session->getFlashBag()->add("status",$status);
            
            return $this->redirectToRoute('homeaction');
        }
        //rendering form
        return $this->render('user/regform.html.twig', array(
            'form' => $form->createView(),
        ));
         
    }
    
    /**
     * @Route("/login",name="login")
     */
    public function login(Request $request,AuthenticationUtils $authUtils){
        $error=$authUtils->getLastAuthenticationError();
        //last Username
        $lastUsername=$authUtils->getLastUsername();
        
        return $this->render('user/login.html.twig',[
            'error'=>$error,
            'last_username'=>$lastUsername
        ]);
    }
    
    public function logout(){
        $this->redirectToRoute('logout');
    }
    
    /**
     * @Route("/profile",name="profile")
     */
    public function myprofile($name='demo'){
        $teams = $this->getDoctrine()->getRepository('App:Team')->findAll();
        $users = $this->getDoctrine()->getRepository('App:User')->findAll();
        $leagues = $this->getDoctrine()->getRepository('App:League')->findAll();
        
        return $this->render('user/profile.html.twig',[
            'teams' => $teams,
            'users' => $users,
            'leagues' => $leagues,
            'name'=> $name]);
    }
    /**
     * @Route("/profile/edituser",name="edituser")
     */
    public function editUser(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $leagues = $this->getDoctrine()->getRepository('App:League')->findAll();
            //this user
           $user=$this->getUser();
        
        //creating the form
        $form = $this->createForm(RegisterType::class, $user);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encoding password, first we get password in plaintext and then
    // we encode it.
            $password=$passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();            
            return $this->redirectToRoute('profile');
        }
        //rendering form
        return $this->render('user/edituser.html.twig', array(
            'form' => $form->createView(),
            'leagues' => $leagues
        ));
         
    }
    /**
     * @Route("/profile/deleteuser",name="deleteuser")
     */
    public function deleteUser(Request $request, TokenStorageInterface $tokenStorage)
    {
            $deleted=0;
           //this user
           $user=$this->getUser();
           //In case the user is a Team owner, will find another member of the team to pass the owner role.
           if($user->getTeamRole()=="Team_Owner"){
               $flag=0;
               
               $teams = $this->getDoctrine()->getRepository('App:Team')->findAll();
               $users = $this->getDoctrine()->getRepository('App:User')->findAll();
               foreach ($teams as $team) {
                    if($team == $user->getTeamid()){
                        foreach($users as $thisuser){
                            if($thisuser->getTeamid()==$team && $thisuser->getTeamRole() != "Team_Owner"){
                                if($flag==0){
                                    $flag=1;
                                    $thisuser->setTeamRole("Team_Owner");
                                    $entityManager = $this->getDoctrine()->getManager();
                                    $entityManager->persist($user);
                                    $entityManager->flush();
                                }
                            }
                        }
                        if($flag==0){//Means there is no more members in the team to get the owner, team will be auto deleted
                        //First we need to delete the team from all the classifications
                        $cs = $this->getDoctrine()->getRepository('App:Classification')->findAll();
                        foreach($cs as $c){
                            if($c->getTeam()==$team){
                                $entityManager = $this->getDoctrine()->getManager();
                                $entityManager->remove($c);
                                $entityManager->flush();
                            }
                        }
                        $tokenStorage->setToken(null);
                        
                        $entityManager2 = $this->getDoctrine()->getManager();
                        $entityManager2->remove($user);
                        $entityManager2->flush();
                        $deleted=1;
                            
                        $entityManager3 = $this->getDoctrine()->getManager();
                        $entityManager3->remove($team);
                        $entityManager3->flush();
                        }
                    }
                } 
           }
           if($deleted==0){
               $tokenStorage->setToken(null);
               
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($user);
                $entityManager->flush(); 
           }
            
            
            return $this->redirectToRoute('homeaction');
         
    }
    
    
}//ENDCONTROLLER
