<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
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
    private $username;
    /**
     *@ORM\Column(type="string",length=90)
     */
    private $password;
    /**
     *@ORM\Column(type="string",length=45)
     */
    private $role;
    /**
     *@ORM\Column(type="string",length=45)
     */
    private $email;
    
    /**
     *@ORM\Column(type="string",length=45)
     */
    private $teamRole;
    /**
    *@ORM\OneToMany(targetEntity="App\Entity\Post",mappedBy="user")
    *@ORM\JoinColumn(nullable=true)
    */
    private $posts;
    
    public function getPosts(){
    return $this->posts;
    }

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\Team",inversedBy="users")
    * @ORM\JoinColumn(nullable=true)
    */
    private $team;
    public function getTeamid(){
    return $this->team;
    }
    function setTeam($team) {
        $this->team = $team;
    }
    
        
    public function __construct(){
    $this->posts=new ArrayCollection();
    }
    function getId() {
        return $this->id;
    }

    function getUsername() {
        return $this->username;
    }

    function getPassword() {
        return $this->password;
    }

    function getRole() {
        return $this->role;
    }

    function getEmail() {
        return $this->email;
    }

    function getTeamRole() {
        return $this->teamRole;
    }

    function setUsername($username) {
        $this->username = $username;
    }

    function setPassword($password) {
        $this->password = $password;
    }

    function setRole($role) {
        $this->role = $role;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setTeamRole($teamRole) {
        $this->teamRole = $teamRole;
    }

    public function eraseCredentials() {
        
    }

    public function getRoles() {
        
    }

    public function getSalt() {
        
    }

}
