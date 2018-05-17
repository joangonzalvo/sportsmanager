<?php



namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of DefaultController
 *
 * @author linux
 */
class DefaultController extends Controller{
    
    /**
     * @Route("/",name="homeaction")
     */
    public function indexAction($name='demo'){
        $teams = $this->getDoctrine()->getRepository('App:Team')->findAll();
        $users = $this->getDoctrine()->getRepository('App:User')->findAll();
        $leagues = $this->getDoctrine()->getRepository('App:League')->findAll();
        
        return $this->render('default/index.html.twig',[
            'teams' => $teams,
            'users' => $users,
            'leagues' => $leagues,
            'name'=> $name]);
    }
}
