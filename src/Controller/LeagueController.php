<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LeagueController extends Controller
{
    /**
     * @Route("/league", name="league")
     */
    public function index()
    {
        return $this->render('league/index.html.twig', [
            'controller_name' => 'LeagueController',
        ]);
    }
}
