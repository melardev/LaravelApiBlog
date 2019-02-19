<?php

namespace App\Dtos;
use Ds\Set;

class SignUpDto
{

    private $name;

    private $username;

    private $email;

    private $role;


    private $password;

    public function getName() {
        return $this->name;
    }

    public function setName(String $name) {
        $this->name = $name;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function setUsername(String $username) {
        $this->username = $username;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function setEmail(String $email) {
        $this->email = $email;
    }

    public function getPassword() : string{
        return $this->password;
    }

    public function setPassword(String $password) {
        $this->password = $password;
    }

    public function getRole(): Set {
        return $this->role;
    }

    public function setRole(Set $role) {
        $this->role = $role;
    }
}