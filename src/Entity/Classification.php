<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClassificationRepository")
 */
class Classification
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     *@ORM\Column(type="integer")
     */
    private $points;
    /**
     *@ORM\Column(type="integer")
     */
    private $win;
    /**
     *@ORM\Column(type="integer")
     */
    private $lost;
    /**
     *@ORM\Column(type="integer")
     */
    private $draw;
    /**
     * @ORM\ManyToOne(targetEntity= "App\Entity\Team",inversedBy="classifications")
     * @ORM\JoinColumn(nullable=false)
     */
    private $team;
    function getTeamId(){
        return $this->team;
    }
    function setTeam($team){
        $this->team=$team;
    }
    function getTeam(){
        return $this->team;
    }
    
    /**
     * @ORM\ManyToOne(targetEntity= "App\Entity\League",inversedBy="classifications")
     * @ORM\JoinColumn(nullable=false)
     */
    private $league;
    function getLeagueId(){
        return $this->league;
    }
    function getLeague(){
        return $this->league;
    }
    function setLeague($league){
        $this->post=$league;
    }
    function getId() {
        return $this->id;
    }

    function getPoints() {
        return $this->points;
    }

    function getWin() {
        return $this->win;
    }

    function getLost() {
        return $this->lost;
    }

    function getDraw() {
        return $this->draw;
    }
    function setPoints($points) {
        $this->points = $points;
    }

    function setWin($win) {
        $this->win = $win;
    }

    function setLost($lost) {
        $this->lost = $lost;
    }

    function setDraw($draw) {
        $this->draw = $draw;
    }


    


}
