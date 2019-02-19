<?php
namespace App\Dtos;
class LoginDto
{


    private $username;
    private $password;

    public function getUsername() {
        return $this->username;
    }

    public function setUsername(String $username) {
        $this->username = $username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword(String $password) {
        $this->password = $password;
    }
}