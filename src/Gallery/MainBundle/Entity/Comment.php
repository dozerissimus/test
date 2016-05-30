<?php

namespace Gallery\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comment
 */
class Comment
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $body;

    /**
     * @var string
     */
    private $autor;

    /**
     * @var \Gallery\MainBundle\Entity\Image
     */
    private $image;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return Comment
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string 
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set autor
     *
     * @param string $autor
     * @return Comment
     */
    public function setAutor($autor)
    {
        $this->autor = $autor;

        return $this;
    }

    /**
     * Get autor
     *
     * @return string 
     */
    public function getAutor()
    {
        return $this->autor;
    }

    /**
     * Set image
     *
     * @param \Gallery\MainBundle\Entity\Image $image
     * @return Comment
     */
    public function setImage(\Gallery\MainBundle\Entity\Image $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \Gallery\MainBundle\Entity\Image 
     */
    public function getImage()
    {
        return $this->image;
    }
    /**
     * @var datetime;
     */
    private $date;


    /**
     * Set date
     *
     * @param \datetime; $date
     * @return Comment
     */
    public function setDate()
    {
        $this->date = new \DateTime();

        return $this;
    }

    /**
     * Get date
     *
     * @return \datetime; 
     */
    public function getDate()
    {
        return $this->date;
    }
    /**
     * @var boolean
     */
    private $is_read;

    /**
     * @var boolean
     */
    private $is_deleted;


    /**
     * Set is_read
     *
     * @param boolean $isRead
     * @return Comment
     */
    public function setIsRead($isRead)
    {
        $this->is_read = $isRead;

        return $this;
    }

    /**
     * Get is_read
     *
     * @return boolean 
     */
    public function getIsRead()
    {
        return $this->is_read;
    }

    /**
     * Set is_deleted
     *
     * @param boolean $isDeleted
     * @return Comment
     */
    public function setIsDeleted($isDeleted)
    {
        $this->is_deleted = $isDeleted;

        return $this;
    }

    /**
     * Get is_deleted
     *
     * @return boolean 
     */
    public function getIsDeleted()
    {
        return $this->is_deleted;
    }
}
