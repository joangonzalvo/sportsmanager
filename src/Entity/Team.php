<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TeamRepository")
 */
class Team
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     *@ORM\Column(type="string",length=45)
     */
    private $teamname;
    /**
     *@ORM\Column(type="string",length=45)
     */
    private $logo;
    /**
     *@ORM\Column(type="integer")
     */
    private $leagueTitles;
    /**
     *@ORM\Column(type="integer")
     */
    private $otherTitles;
    /**
     *@ORM\Column(type="string",length=90)
     */
    private $teamkey;
    /**
     *@ORM\Column(type="integer")
     */
    private $teamValue;
    /**
    *@ORM\OneToMany(targetEntity="App\Entity\User",mappedBy="team")
    *@ORM\JoinColumn(nullable=true)
    */
    private $users;
    
    public function getUsers(){
    return $this->users;
    }
    /**
    *@ORM\OneToMany(targetEntity="App\Entity\Classification",mappedBy="team")
    *@ORM\JoinColumn(nullable=true)
    */
    private $classifications;
    
    public function getClassifications(){
    return $this->classifications;
    }
    
    
    public function __construct(){
    $this->users=new ArrayCollection();
    $this->classifications=new ArrayCollection();
    }
    function getId() {
        return $this->id;
    }

    function getTeamname() {
        return $this->teamname;
    }

    function getLogo() {
        return $this->logo;
    }

    function getLeagueTitles() {
        return $this->leagueTitles;
    }

    function getOtherTitles() {
        return $this->otherTitles;
    }

    function getTeamkey() {
        return $this->teamkey;
    }

    function getTeamValue() {
        return $this->teamValue;
    }


    function setTeamname($teamname) {
        $this->teamname = $teamname;
    }

    function setLogo($logo) {
        $this->logo = $logo;
    }

    function setLeagueTitles($leagueTitles) {
        $this->leagueTitles = $leagueTitles;
    }

    function setOtherTitles($otherTitles) {
        $this->otherTitles = $otherTitles;
    }

    function setTeamkey($teamkey) {
        $this->teamkey = $teamkey;
    }

    function setTeamValue($teamValue) {
        $this->teamValue = $teamValue;
    }


}
