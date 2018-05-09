<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 */
class Post
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
    private $title;
    /**
     *@ORM\Column(type="text")
     */
    private $content;
    /**
     *@ORM\Column(type="string",length=45)
     */
    private $category;
    /**
     *@ORM\Column(type="datetime")
     */
    private $create_date;
    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\User",inversedBy="posts")
    * @ORM\JoinColumn(nullable=true)
    */
    private $user;
    public function getUserid(){
    return $this->user;
    }
    function setUser($user) {
        $this->user = $user;
    }
    function getId() {
        return $this->id;
    }

    function getTitle() {
        return $this->title;
    }

    function getContent() {
        return $this->content;
    }

    function getCategory() {
        return $this->category;
    }

    function getCreate_date() {
        return $this->create_date;
    }

    function getCreatedate() {
        return $this->create_date;
    }

    function setTitle($title) {
        $this->title = $title;
    }

    function setContent($content) {
        $this->content = $content;
    }

    function setCategory($category) {
        $this->category = $category;
    }

    function setCreate_date($create_date) {
        $this->create_date = $create_date;
    }
    function setCreatedate($create_date) {
        $this->create_date = $create_date;
    }



}
