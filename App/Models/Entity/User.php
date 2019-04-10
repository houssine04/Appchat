<?php

namespace App\Models\Entity;

class User
{
    private $id;
    private $username;
    private $password;
    private $last_seen;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return User
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastSeen()
    {
        return $this->last_seen;
    }

    /**
     * @param mixed $last_seen
     * @return User
     */
    public function setLastSeen($last_seen)
    {
        $this->last_seen = $last_seen;
        return $this;
    }



}