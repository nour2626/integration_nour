<?php

class User
{
    private int $id;
    private string $userName;
    private int $age;
    private string $email;
    private string $password;
    private ?string $photo;
    private string $role;

    public function __construct($userName, $age, $email, $password, $photo = null, $role = 'user')
    {
        $this->userName = $userName;
        $this->age = $age;
        $this->email = $email;
        $this->password = $password;
        $this->photo = $photo;
        $this->role = $role;
    }

    // Getters and setters for each property
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getUserName()
    {
        return $this->userName;
    }

    public function setUserName($userName)
    {
        $this->userName = $userName;
        return $this;
    }

    public function getAge()
    {
        return $this->age;
    }

    public function setAge($age)
    {
        $this->age = $age;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function setPhoto($photo)
    {
        $this->photo = $photo;
        return $this;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }
}