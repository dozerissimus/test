<?php

namespace Gallery\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gallery\MainBundle\Entity\Category;
use Gallery\UserBundle\Entity\User;

/**
 * Image
 */
class Image
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var string
     */
    private $autor;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $filename;

    /**
     * @var boolean
     */
    private $for_update;

    /**
     * @var \Gallery\MainBundle\Entity\Category
     */
    private $category;
    
    private $comments;


    public $file;
    public $temp_name;
    public $temp_md5;
    public $choice_category;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }
    
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
     * Set date
     *
     * @param \DateTime $date
     * @return Image
     */
    public function setDate()
    {
        $this->date = new \DateTime();

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set autor
     *
     * @param string $autor
     * @return Image
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
     * Set description
     *
     * @param string $description
     * @return Image
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Image
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set for_update
     *
     * @param boolean $forUpdate
     * @return Image
     */
    public function setForUpdate($forUpdate)
    {
        $this->for_update = $forUpdate;

        return $this;
    }

    /**
     * Get for_update
     *
     * @return boolean 
     */
    public function getForUpdate()
    {
        return $this->for_update;
    }

    /**
     * Set category
     *
     * @param \Gallery\MainBundle\Entity\Category $category
     * @return Image
     */
    public function setCategory(Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Gallery\MainBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }
    
    public function saveTempFile()
    {
        if (null === $this->temp_md5)
        {
            return;
        }
        else 
        {
            $file_src = $this->getTmpUploadRootDir().$this->temp_name;
            $filename = $this->temp_md5;
            $file_dest = $this->getUploadRootDir().$filename;
            rename($file_src, $file_dest);
            $this->filename = $filename;
        }
    }
        
    protected function getUploadDir()
    {
        return 'uploads/images/';
    }
 
    protected function getUploadRootDir()
    {
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }
    
    public function getTmpUploadDir()
    {
        return 'uploads/temp/';
    }
 
    public function getTmpUploadRootDir()
    {
        return __DIR__.'/../../../../web/'.$this->getTmpUploadDir();
    }
 
    public function getWebPath()
    {
        return null === $this->filename ? null : $this->getUploadDir().'/'.$this->filename;
    }
 
    public function getAbsolutePath()
    {
        return null === $this->filename ? null : $this->getUploadRootDir().'/'.$this->filename;
    }

    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) 
        {
            unlink($file);
        }
    }

    /**
     * Set filename
     *
     * @param string $filename
     * @return Image
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string 
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Add comments
     *
     * @param \Gallery\MainBundle\Entity\Comment $comments
     * @return Image
     */
    public function addComment(\Gallery\MainBundle\Entity\Comment $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param \Gallery\MainBundle\Entity\Comment $comments
     */
    public function removeComment(\Gallery\MainBundle\Entity\Comment $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }
}
