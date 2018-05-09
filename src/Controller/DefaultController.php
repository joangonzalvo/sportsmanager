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
        
        return $this->render('default/index.html.twig',[
            'name'=> $name]);
    }
}
