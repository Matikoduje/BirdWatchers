<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="user_profile")
 */
class UserProfile
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Regex(
     *     pattern="/^[A-ZŁŚŻ][a-ząęćłśżźńó]{2,14}$/",
     *     match=true,
     *     message="Proszę wprowadzić poprawnie imię"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Regex(
     *     pattern="/^[A-ZŁŚŻ][a-ząęćłśżźńó]{2,19}$/",
     *     match=true,
     *     message="Proszę wprowadzić poprawnie nazwisko"
     * )
     */
    private $surname;

    /**
     * @ORM\Column(type="string", nullable=true, length=255)
     * @Assert\File(
     *     mimeTypes= { "image/jpeg" },
     *     mimeTypesMessage= "Zdjęcie musi być w formacie jpeg",
     *     maxSize= "300k",
     *     maxSizeMessage= " Zdjęcie powinno być mniejsze niż 300 kb"
     * )
     * @Assert\Image(
     *     minWidth = 160,
     *     maxWidth = 180,
     *     minHeight = 160,
     *     maxHeight = 180,
     *     minWidthMessage = "Rozmiar zdjęcia musi mieścić się od 160x160 do 180x180",
     *     maxWidthMessage = "Rozmiar zdjęcia musi mieścić się od 160x160 do 180x180",
     *     minHeightMessage = "Rozmiar zdjęcia musi mieścić się od 160x160 do 180x180",
     *     maxHeightMessage= "Rozmiar zdjęcia musi mieścić się od 160x160 do 180x180"
     * )
     */
    private $profilePicture;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Regex(
     *     pattern="/^[A-ZŁŚŻ][a-z-ą ęćłśżźńó]{2,30}$/",
     *     match=true,
     *     message="Proszę wprowadzić poprawną nazwę miasta"
     * )
     */
    private $city;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\State", inversedBy="userProfile")
     * @ORM\JoinColumn(nullable=true)
     */
    private $state;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param mixed $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * @return mixed
     */
    public function getProfilePicture()
    {
        return $this->profilePicture;
    }

    /**
     * @param mixed $profilePicture
     */
    public function setProfilePicture($profilePicture)
    {
        $this->profilePicture = $profilePicture;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * Set state
     *
     * @param \AppBundle\Entity\State $state
     *
     * @return UserProfile
     */
    public function setState(\AppBundle\Entity\State $state = null)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return \AppBundle\Entity\State
     */
    public function getState()
    {
        return $this->state;
    }
}
