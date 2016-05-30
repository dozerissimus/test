<?php

namespace Gallery\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;

class User extends BaseUser
{
    protected $id;
    protected $name;
    protected $home;
    protected $avatar;
    protected $ip;
    protected $bidthdate;
    protected $gender;
    protected $register;
    
    public $file;
    public $temp_name;
    public $temp_md5;



    public function __construct()
    {
        parent::__construct();
        // your own logic
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
     * Set name
     *
     * @param string $name
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Set avatar
     *
     * @param string $avatar
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string 
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return User
     */
    public function setIp()
    {
        $this->ip = '127.0.0.1';

        return $this;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set bidthdate
     *
     * @param \DateTime $bidthdate
     * @return User
     */
    public function setBidthdate($bidthdate)
    {
        $this->bidthdate = $bidthdate;

        return $this;
    }

    /**
     * Get bidthdate
     *
     * @return \DateTime 
     */
    public function getBidthdate()
    {
        return $this->bidthdate;
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set register
     *
     * @param \DateTime $register
     * @return User
     */
    public function setRegister()
    {
        if (!$this->getRegister())
        {
            $this->register = new \DateTime;
        }

        return $this;
    }

    /**
     * Get register
     *
     * @return \DateTime 
     */
    public function getRegister()
    {
        return $this->register;
    }

    /**
     * Set home
     *
     * @param string $home
     * @return User
     */
    public function setHome($home)
    {
        $this->home = $home;

        return $this;
    }

    /**
     * Get home
     *
     * @return string 
     */
    public function getHome()
    {
        return $this->home;
    }
    
    protected function getUploadDir()
    {
        return 'uploads/avatars/';
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
        return null === $this->avatar ? null : $this->getUploadDir().'/'.$this->avatar;
    }
 
    public function getAbsolutePath()
    {
        return null === $this->avatar ? null : $this->getUploadRootDir().'/'.$this->avatar;
    }

    public function preUpload()
    {
        if (null !== $this->file) 
        {
        // do whatever you want to generate a unique name
            //$this->avatar = uniqid().'.'.$this->file->guessExtension();
            $this->avatar = md5_file($this->file).'.'.$this->file->guessExtension();
        }
    }

    public function upload()
    {
        if (null === $this->file) 
        {
            return;
        }
 
        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->file->move($this->getUploadRootDir(), $this->avatar);
        
        unset($this->file);
    }

    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) 
        {
            unlink($file);
        }
    }

    /**
     * @var boolean
     */
    private $for_update;


    /**
     * Set for_update
     *
     * @param boolean $forUpdate
     * @return User
     */
    public function setForUpdate()
    {
        $this->for_update = 0;

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

    public function saveTempFile()
    {
        if (null === $this->temp_md5)
        {
            return;
        }
        else if ($this->avatar !== $this->temp_md5)
        {
            $file = $this->temp_name;
            rename($this->getTmpUploadRootDir().$file, $this->getUploadRootDir().$file);
            $this->avatar = $file;
        }
        else
        {
            unlink($this->getTmpUploadRootDir().$file);
        }
    }
}
