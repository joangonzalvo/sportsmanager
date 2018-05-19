<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
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
    
    
}//ENDCONTROLLER
