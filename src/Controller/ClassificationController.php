<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ClassificationController extends Controller
{
    /**
     * @Route("/classification", name="classification")
     */
    public function index()
    {
        return $this->render('classification/index.html.twig', [
            'controller_name' => 'ClassificationController',
        ]);
    }
}
