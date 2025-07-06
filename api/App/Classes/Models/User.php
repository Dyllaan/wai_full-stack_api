<?php
/**
 * User is used to handle user data, including login and registration
 * Implements the CrudInterface to allow for easy database interaction
 * @package App\Classes\Models
 * @author Louis Figes
 * @generated Github CoPilot was used during the creation of this code
 */
namespace App\Classes\Models;

use Firebase\JWT\JWT as JWT;

class User extends \App\Classes\CrudModel implements \App\Interfaces\CrudInterface
{
    private $name;
    private $email;
    private $password;
    private $token;

    public function exists() {
        if($this->getId() != null) {
            $data = $this->getDb()->createSelect()->select("*")->from("account")->where(["id = '".$this->getId()."'"])->execute();
            if (count($data) == 0) {
                return false;
            } else {
                return true;
            }
        } elseif($this->getEmail() != null) {
            $data = $this->getDb()->createSelect()->select("*")->from("account")->where(["email = '".$this->getEmail()."'"])->execute();
            if (count($data) == 0) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    /**
     * User constructor, sets the name, email and password
     * @param $name
     * @param $email
     * @param $password
     */
    public function login() 
    {
        $cons[] = "email = '$this->email'";
        $data = $this->getDb()->createSelect()->select("*")->from("account")->where($cons)->execute();
        if (count($data) == 0) {
            throw new \App\ClientErrorException(401, ['message' => 'Invalid email']);
        } else {
            if(!$this->checkPassword($this->password, $data[0]['password'])) {
                throw new \App\ClientErrorException(401, ['message' => 'Invalid password']);
            } else {
                $this->setId($data[0]['id']);
                $this->setName($data[0]['name']);
            }
        }
    }

    /**
     * register is used to register a user
     * @param $db
     * @return void
     * @throws \App\ClientErrorException
     */
    public function register() 
    {
        if($this->exists()) {
            throw new \App\ClientErrorException(400, ['message' => "User already exists"]);
        } else {
            $this->save();
        }
    }

    /**
     * 
     * Checks if the content is savable or updatable
     * Requirements:
     * - Name must be at least 3 characters
     * - Name must be less than 30 characters
     * - Email must be less than 60 characters
     * - Email must be valid
     * - Password must be at least 8 characters
     * - Password must be less than 100 characters
     */
    private function checkSavable()
    {
        $errors = [];

        if (empty($this->getName())) {
            $errors[] = "Missing name";
        }

        if (empty($this->getEmail())) {
            $errors[] = "Missing email";
        }

        if (empty($this->getPassword())) {
            $errors[] = "Missing password";
        } elseif (strlen($this->getPassword()) < 8) {
            $errors[] = "Password must be at least 8 characters";
        } elseif (strlen($this->getPassword()) > 100) {
            $errors[] = "Password must be less than 100 characters";
        }

        if (!filter_var($this->getEmail(), FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email";
        } elseif (strlen($this->getEmail()) > 60) {
            $errors[] = "Email must be less than 60 characters";
        }

        if (strlen($this->getName()) < 3) {
            $errors[] = "Name must be at least 3 characters";
        } elseif (strlen($this->getName()) > 30) {
            $errors[] = "Name must be less than 30 characters";
        }

        if (!empty($errors)) {
            throw new \App\ClientErrorException(400, ['errors' => $errors]);
        }
        return true;
    }

    public function save() 
    {
        if($this->checkSavable()) {
            $this->password = password_hash($this->password, PASSWORD_DEFAULT);
            $id = $this->getDb()->createInsert()->insert('account')->
            cols('name, email, password')->
            values([$this->getName(), $this->getEmail(), $this->getPassword()])->
            execute();
            if($id != null) {
                $this->setId($id);
                return $this->toArray();
            }
        } else {
            throw new \App\ClientErrorException(400, ['message' => "Could not save user"]);
        }
    }

    public function get() 
    {
        $data = $this->getDb()->createSelect()->select("*")->from("account")->where(["id = '".$this->getId()."'"])->execute();
        if (count($data) == 0) {
            throw new \App\ClientErrorException(404, ['message' => "User does not exist"]);
        } else {
            $this->setName($data[0]['name']);
            $this->setEmail($data[0]['email']);
        } 
    }

    /**
     * Checks if the current object is different than the one in the database
     * If it is, it updates the database
     * @generated Github CoPilot was used during the creation of this code
     */
    public function update() 
    {
        if(!$this->exists()) {
            throw new \App\ClientErrorException(400, ['message' => "User does not exist"]);
        }
        $data = $this->getDb()->createSelect()->select("*")->from("account")->where(["id = '".$this->getId()."'"])->execute();
        if (count($data) == 0) {
            throw new \App\ClientErrorException(404, ['message' => "User does not exist"]);
        } else {
            $changed = [];
            if($this->getName() != $data[0]['name']) {
                $changed['name'] = $this->getName();
            }
            if($this->getEmail() != $data[0]['email']) {
                $changed['email'] = $this->getEmail();
            }
            if($this->getPassword() != null) {
                $changed['password'] = password_hash($this->getPassword(), PASSWORD_DEFAULT);
            }
            if($changed != []) {
                $this->getDb()->createUpdate()->update('account')->set($changed)->where(["id = '".$this->getId()."'"])->execute();
                return ['message' => "User updated"];
            } else {
                return ['message' => "No changes"];
            }
        }
    }
    

    public function delete() 
    {
        if($this->exists()) {
            $this->getDb()->createDelete()->from('account')->where(["id = '".$this->getId()."'"])->execute();
            return ['message' => "User deleted"];
        } else {
            throw new \App\ClientErrorException(400, ['message' => "User does not exist"]);
        }
    }

    public function getNotes() {
        if($this->exists()) {
            return $this->getDb()->createSelect()->attach('chi2023')->select("*")->from("notes")->join('content', ' notes.content_id = content.id')->where(["account_id = '".$this->getId()."'"])->execute();
        } else {
            throw new \App\ClientErrorException(400, ['message' => "User does not exist"]);
        }
    }

    public function getFavourites() {
        if($this->exists()) {
            return $this->getDb()->createSelect()->attach('chi2023')->select("*")->from("favourites")->join('content', ' favourites.content_id = content.id')->where([" account_id = '".$this->getId()."'"])->execute();
        } else {
            throw new \App\ClientErrorException(400, ['message' => "User does not exist"]);
        }
    }

    public function toArray() {
        $user['user'] = [
            'id' => $this->getId(),
            'name' => $this->name,
            'email' => $this->email,
        ];
        $jwt = $this->generateJWT($this->getId());
        $user['jwt'] = $jwt;
        return $user;
    }

    /**
     * checkPassword is used to check if the password is correct
     * @param $password
     * @param $hash
     * @return bool
     * @throws \App\ClientErrorException
     */
    private function checkPassword($password, $hash) 
    {
        if (password_verify($this->password, $hash)) {
            return true;
        } else {
            throw new \App\ClientErrorException(401, ['message' => 'Invalid password']);
        }
    }

    /**
     * verifyToken is used to verify the JWT token
     */
    public function verifyToken() {
        $token = new \App\Classes\Models\Token();
        if($token->isValid()) {
            $this->setId($token->getUserId());
            $this->get();
            return true;
        } else {
            throw new \App\ClientErrorException(401, ['message' => 'Invalid token']);
        }
    }

    /**
     * generateJWT is used to generate a JWT token
     * @param $id
     * @return string
     */
    private function generateJWT($id) 
    { 
        $secretKey = SECRET;

        $iat = time();
        $exp = strtotime('+1 hour', $iat);
        $iss = $_SERVER['HTTP_HOST'];
        $payload = [
            'id' => $id,
            'iat' => $iat,
            'exp' => $exp,
            'iss' => $iss
        ];
        $jwt = JWT::encode($payload, $secretKey, 'HS256');
        return $jwt;
    }

    public function getToken() {
        return $this->token;
    }

    public function setToken($jwt) {
        $this->token = $token;
    }

    public function getName() 
    {
        return $this->name;
    }

    public function setName($name) 
    {
        $this->name = $name;
    }

    public function getEmail() 
    {
        return $this->email;
    }

    public function setEmail($email) 
    {
        $this->email = $email;
    }

    public function getPassword() 
    {
        return $this->password;
    }

    public function setPassword($password) 
    {
        $this->password = $password;
    }
}
