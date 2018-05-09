<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LeagueRepository")
 */
class League
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
    private $name;
    /**
     *@ORM\Column(type="string",length=45)
     */
    private $type;
    /**
     *@ORM\Column(type="datetime")
     */
    private $date_start;
    /**
    *@ORM\OneToMany(targetEntity="App\Entity\Classification",mappedBy="league")
    *@ORM\JoinColumn(nullable=true)
    */
    private $classifications;
    
    public function getClassifications(){
    return $this->classifications;
    }
    
    
    public function __construct(){
    $this->classifications=new ArrayCollection();
    }
    function getId() {
        return $this->id;
    }

    function getName() {
        return $this->name;
    }

    function getType() {
        return $this->type;
    }

    function getDate_start() {
        return $this->date_start;
    }
    function getDatestart() {
        return $this->date_start;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setType($type) {
        $this->type = $type;
    }

    function setDate_start($date_start) {
        $this->date_start = $date_start;
    }
    function setDatestart($date_start) {
        $this->date_start = $date_start;
    }


    

}
