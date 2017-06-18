<?php


namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ObservationRepository")
 * @ORM\Table(name="observation")
 */
class Observation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="observations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Species", inversedBy="observations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $species;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Image", mappedBy="observation")
     * @ORM\JoinColumn(nullable=false)
     */
    private $images;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="date", nullable=false)
     * @Assert\NotBlank(
     *     message = "Proszę wprowadzić datę obserwacji"
     * )
     */
    private $observationDate;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\NotBlank(
     *     message = "Proszę uzupełnić pole"
     * )
     */
    private $location;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\State", inversedBy="observations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $state;

    /**
     * @ORM\Column(type="decimal", precision=8, scale=6, nullable=false)
     * @Assert\NotBlank(
     *     message = "Proszę uzupełnić pole"
     * )
     * @Assert\Range(
     *     min = 49.00,
     *     max = 55.00,
     *     minMessage= "Proszę wprowadzić lokalizację, która jest w granicach Polski",
     *     maxMessage= "Proszę wprowadzić lokalizację, która jest w granicach Polski"
     * )
     */
    private $latitude;

    /**
     * @ORM\Column(type="decimal", precision=8, scale=6, nullable=false)
     * @Assert\NotBlank(
     *     message = "Proszę uzupełnić pole"
     * )
     * @Assert\Range(
     *     min = 14.07,
     *     max = 24.09,
     *     minMessage= "Proszę wprowadzić lokalizację, która jest w granicach Polski",
     *     maxMessage= "Proszę wprowadzić lokalizację, która jest w granicach Polski"
     * )
     */
    private $longitude;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\NotBlank(
     *     message = "Proszę uzupełnić pole"
     * )
     */
    private $description;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->comments = new ArrayCollection();
        $this->images = new ArrayCollection();
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
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param mixed $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Observation
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get observationDate
     *
     * @return \DateTime
     */
    public function getObservationDate()
    {
        return $this->observationDate;
    }

    /**
     * @param mixed $observationDate
     */
    public function setObservationDate($observationDate)
    {
        $this->observationDate = $observationDate;
    }

    /**
     * Get location
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Observation
     */
    public function setUser(\AppBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get species
     *
     * @return \AppBundle\Entity\Species
     */
    public function getSpecies()
    {
        return $this->species;
    }

    /**
     * Set species
     *
     * @param \AppBundle\Entity\Species $species
     *
     * @return Observation
     */
    public function setSpecies(\AppBundle\Entity\Species $species)
    {
        $this->species = $species;

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

    /**
     * Set state
     *
     * @param \AppBundle\Entity\State $state
     *
     * @return Observation
     */
    public function setState(\AppBundle\Entity\State $state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Add image
     *
     * @param \AppBundle\Entity\Image $image
     *
     * @return Observation
     */
    public function addImage(\AppBundle\Entity\Image $image)
    {
        $this->images[] = $image;

        return $this;
    }

    /**
     * Remove image
     *
     * @param \AppBundle\Entity\Image $image
     */
    public function removeImage(\AppBundle\Entity\Image $image)
    {
        $this->images->removeElement($image);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages()
    {
        return $this->images;
    }
}
