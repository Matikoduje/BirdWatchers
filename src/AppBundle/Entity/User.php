<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @UniqueEntity(fields={"login"}, message="Użytkownik o takim loginie już jest zarejestrowany")
 * @UniqueEntity(fields={"email"}, message="Podany e-mail był już wykorzystany przy rejestracji")
 */
class User implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *
     */
    private $plainPassword;


    /**
     * @var string
     *
     * @ORM\Column(name="login", type="string", length=15, unique=true)
     */
    private $login;

    /**
     * @var string
     *     * @ORM\Column(name="email", type="string", length=50, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="json_array")
     */
    private $roles;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isBaned;

    /**
     * @ORM\Column(type="integer", unique=true)
     * @ORM\OneToOne(targetEntity="UserProfile")
     * @ORM\JoinColumn(name="userProfile", referencedColumnName="id")
     */
    private $userProfile;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createAccountDate;

    public function __construct()
    {
        $this->roles[] = 'ROLE_USER';
        $this->isBaned = 0;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set login
     *
     * @param string $login
     *
     * @return User
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
        $this->password = null;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param mixed $roles
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    public function getSalt()
    {

    }

    // system bezpieczeństwa potrzebuje tej metody
    public function getUsername()
    {
        return $this->login;
    }

    public function eraseCredentials()
    {
        // kasujemy hasło zapisane w plainPassword które służy tylko do tymczasowego przechowania
        $this->plainPassword = null;
    }

    /**
     * Set isBaned
     *
     * @param boolean $isBaned
     *
     * @return User
     */
    public function setIsBaned($isBaned)
    {
        $this->isBaned = $isBaned;

        return $this;
    }

    /**
     * Get isBaned
     *
     * @return boolean
     */
    public function getIsBaned()
    {
        return $this->isBaned;
    }

    /**
     * Set userProfile
     *
     * @param integer $userProfile
     *
     * @return User
     */
    public function setUserProfile($userProfile)
    {
        $this->userProfile = $userProfile;

        return $this;
    }

    /**
     * Get userProfile
     *
     * @return integer
     */
    public function getUserProfile()
    {
        return $this->userProfile;
    }

    /**
     * Set createAccountDate
     *
     * @param \DateTime $createAccountDate
     *
     * @return User
     */
    public function setCreateAccountDate($createAccountDate)
    {
        $this->createAccountDate = $createAccountDate;

        return $this;
    }

    /**
     * Get createAccountDate
     *
     * @return \DateTime
     */
    public function getCreateAccountDate()
    {
        return $this->createAccountDate;
    }
}
