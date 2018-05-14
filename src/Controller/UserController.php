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
    
    
}//ENDCONTROLLER
