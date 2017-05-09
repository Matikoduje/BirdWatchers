<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
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
     * @ORM\OneToOne(targetEntity="UserProfile")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userProfile;

    /**
     * @var string
     *
     * @ORM\Column(name="login", type="string", length=15, unique=true)
     */
    private $login;

    /**
     * @var string
     * @ORM\Column(name="email", type="string", length=50, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="Observation", mappedBy="user")
     */
    private $observations;

    /**
     * @ORM\Column(name="roles", type="json_array")
     */
    private $roles;

    /**
     * @ORM\Column(name="is_baned", type="boolean")
     */
    private $isBaned;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    public function __construct()
    {
        $this->roles[] = 'ROLE_USER';
        $this->isBaned = 0;
        $this->createdAt = new \DateTime();
        $this->comments = new ArrayCollection();
        $this->observations = new ArrayCollection();
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
        return $this;
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
     * Set createAccountDate
     *
     * @param \DateTime $createAccountDate
     *
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createAccountDate
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }


    /**
     * Set userProfile
     *
     * @param \AppBundle\Entity\UserProfile $userProfile
     *
     * @return User
     */
    public function setUserProfile(\AppBundle\Entity\UserProfile $userProfile)
    {
        $this->userProfile = $userProfile;

        return $this;
    }

    /**
     * Get userProfile
     *
     * @return \AppBundle\Entity\UserProfile
     */
    public function getUserProfile()
    {
        return $this->userProfile;
    }

    /**
     * Add observation
     *
     * @param \AppBundle\Entity\Observation $observation
     *
     * @return User
     */
    public function addObservation(\AppBundle\Entity\Observation $observation)
    {
        $this->observations[] = $observation;

        return $this;
    }

    /**
     * Remove observation
     *
     * @param \AppBundle\Entity\Observation $observation
     */
    public function removeObservation(\AppBundle\Entity\Observation $observation)
    {
        $this->observations->removeElement($observation);
    }

    /**
     * Get observations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getObservations()
    {
        return $this->observations;
    }
}
