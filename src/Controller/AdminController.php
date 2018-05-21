<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Form\RegisterType;
use App\Entity\Team;
use App\Form\TeamAdminType;

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
    /**
     * @Route("/adminpanel/{thisid}/edituser",name="adminedituser")
     */
    public function editUser(Request $request, UserPasswordEncoderInterface $passwordEncoder, $thisid)
    {
        $leagues = $this->getDoctrine()->getRepository('App:League')->findAll();
        $user=$this->getUser();
        if($user->getRole()=="ROLE_ADMIN"){
            //this user
                $repository = $this->getDoctrine()->getRepository('App:User');
                $thisuser = $repository->findOneBy(['id' => $thisid]);
                //Security pass so u cant delete users with admin role.
                if($thisuser->getRole()!="ROLE_ADMIN")
                {
                //creating the form
                $form = $this->createForm(RegisterType::class, $thisuser);

                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    // encoding password, first we get password in plaintext and then
                    // we encode it.
                    $password=$passwordEncoder->encodePassword($thisuser, $thisuser->getPassword());
                    $thisuser->setPassword($password);
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($thisuser);
                    $entityManager->flush();            
                    return $this->redirectToRoute('adminusers');
                }
                //rendering form
                return $this->render('admin/edituser.html.twig', array(
                    'form' => $form->createView(),
                    'leagues' => $leagues
                ));
            }
        }

    }//END EDIT USER
    /**
     * @Route("/adminpanel/{thisid}/deleteuser",name="admindeleteuser")
     */
    public function deleteUser(Request $request, $thisid)
    {
           $deleted=0;
           //this user
           $user=$this->getUser();
           if($user->getRole()=="ROLE_ADMIN"){
           $repository = $this->getDoctrine()->getRepository('App:User');
           $thisuser = $repository->findOneBy(['id' => $thisid]);
           //You cant delete admin user
           if($thisuser->getRole() != "ROLE_ADMIN"){
           //In case the user is a Team owner, will find another member of the team to pass the owner role.
           if($thisuser->getTeamRole()=="Team_Owner"){
               $flag=0;
               
               $teams = $this->getDoctrine()->getRepository('App:Team')->findAll();
               $users = $this->getDoctrine()->getRepository('App:User')->findAll();
               foreach ($teams as $team) {
                    if($team == $thisuser->getTeamid()){
                        foreach($users as $usercomp){
                            if($usercomp->getTeamid()==$team && $usercomp->getTeamRole() != "Team_Owner"){
                                if($flag==0){
                                    $flag=1;
                                    $usercomp->setTeamRole("Team_Owner");
                                    $entityManager = $this->getDoctrine()->getManager();
                                    $entityManager->persist($usercomp);
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
                        
                        $entityManager2 = $this->getDoctrine()->getManager();
                        $entityManager2->remove($thisuser);
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
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($thisuser);
                $entityManager->flush(); 
           }
            
    }
    }
            return $this->adminUsers();
         
    }//END DELETE USER
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
     * @Route("/adminpanel/{thisid}/editteam",name="admineditteam")
     */
    public function editTeam(Request $request, $thisid)
    {
        $leagues = $this->getDoctrine()->getRepository('App:League')->findAll();
        $user=$this->getUser();
        if($user->getRole()=="ROLE_ADMIN"){
           $repository = $this->getDoctrine()->getRepository('App:Team');
           $thisteam = $repository->findOneBy(['id' => $thisid]);
            $team = new Team();
            //creating the form
            $form = $this->createForm(TeamAdminType::class, $team);
            $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) {
           $team=$form->getData();
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
            //Update this team:
            $thisteam->setTeamname($team->getTeamname());
            $thisteam->setLogo($team->getLogo());
            
           $em = $this->getDoctrine()->getManager();
           $em->persist($thisteam);
           $em->flush();
            }
            return $this->render('admin/editteam.html.twig', array(
                    'form' => $form->createView(),
                    'leagues' => $leagues
                ));
        }
        
    }//END EDIT Team
    
        /**
     * @Route("/adminpanel/{thisid}/deleteteam",name="admindeleteam")
     */
    public function deleteTeam(Request $request, $thisid)
    {
           $deleted=0;
           //this user
           $user=$this->getUser();
           if($user->getRole()=="ROLE_ADMIN"){
               $repository = $this->getDoctrine()->getRepository('App:Team');
               $thisteam = $repository->findOneBy(['id' => $thisid]);
               
               $users = $this->getDoctrine()->getRepository('App:User')->findAll();
               foreach ($users as $user){
                   if($thisteam == $user->getTeamid()){
                       $user->setTeam(null);
                       $user->setTeamRole("none");
                       $em = $this->getDoctrine()->getManager();
                       $em->persist($user);
                       $em->flush();
                   }
               }
               $cs = $this->getDoctrine()->getRepository('App:Classification')->findAll();
               foreach($cs as $c){
                            if($c->getTeam()==$thisteam){
                                $entityManager = $this->getDoctrine()->getManager();
                                $entityManager->remove($c);
                                $entityManager->flush();
                            }
                        }
                $entityManager3 = $this->getDoctrine()->getManager();
                $entityManager3->remove($thisteam);
                $entityManager3->flush();
               
           }
            return $this->adminTeams();
         
    }//END DELETE USER
    
    
}//ENDCONTROLLER
