<?php

namespace Jasny;

/**
 * Invitation code
 */
class InviteCode
{
    /**
     * Directory with invite codes
     * @var string
     */
    static protected $dir;

    /**
     * @var string
     */
    public $code;

    /**
     * Set the dir with invite codes
     * 
     * @param string $dir
     */
    public static function setDir($dir)
    {
        self::$dir = $dir;
    }
    
    /**
     * Get directory with invite codes
     */
    public static function getDir()
    {
        return self::$dir;
    }
    
    /**
     * Class constructor
     * 
     * @param type $code
     */
    public function __construct($code)
    {
        if (!static::getDir()) throw new \Exception("The directory with invite codes isn't set");
        
        $this->code = $code;
    }
    
    /**
     * Get the filename for the invite code
     * 
     * @return string
     */
    protected function getFilename()
    {
        return rtrim(static::getDir(), '/') . '/' . $this->code;
    }
    
    /**
     * Check the invite code
     * 
     * @return boolean
     */
    public function isValid()
    {
        return file_exists($this->getFilename());
    }
    
    /**
     * Check if the invite code has already been used
     * 
     * @return boolean
     */
    public function isUsed()
    {
        if (!file_exists($this->getFilename())) return false;
        
        return filesize($this->getFilename()) > 0;
    }
    
    /**
     * Mark as used
     * 
     * @param string $user
     */
    public function useBy($user)
    {
        if (!$this->isValid()) throw new \Exception("Invalid invitation code");
        if ($this->isUsed()) throw new \Exception("Invitation has already been used");
        
        file_put_contents($this->getFilename(), $user . "\n" . date('c'));
    }
    
    /**
     * Cast code to string
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->code;
    }
}
