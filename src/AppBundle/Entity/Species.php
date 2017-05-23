<?php

namespace AppBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="species")
 */
class Species
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="Observation", mappedBy="species")
     */
    private $observations;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Image", mappedBy="species")
     */
    private $images;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     */
    private $latinName;

    /**
     * @ORM\Column(type="string")
     */
    private $family;

    /**
     * @return mixed
     */
    public function getMainImage()
    {
        return $this->mainImage;
    }

    /**
     * @param mixed $mainImage
     */
    public function setMainImage($mainImage)
    {
        $this->mainImage = $mainImage;
    }

    /**
     * @ORM\Column(type="string")
     */
    private $mainImage;

    /**
     * @ORM\Column(type="string")
     */
    private $speciesOrder;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

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
     * Set name
     *
     * @param string $name
     *
     * @return Species
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set latinName
     *
     * @param string $latinName
     *
     * @return Species
     */
    public function setLatinName($latinName)
    {
        $this->latinName = $latinName;

        return $this;
    }

    /**
     * Get latinName
     *
     * @return string
     */
    public function getLatinName()
    {
        return $this->latinName;
    }

    /**
     * Set family
     *
     * @param string $family
     *
     * @return Species
     */
    public function setFamily($family)
    {
        $this->family = $family;

        return $this;
    }

    /**
     * Get family
     *
     * @return string
     */
    public function getFamily()
    {
        return $this->family;
    }

    /**
     * @return mixed
     */
    public function getSpeciesOrder()
    {
        return $this->speciesOrder;
    }

    /**
     * @param mixed $speciesOrder
     */
    public function setSpeciesOrder($speciesOrder)
    {
        $this->speciesOrder = $speciesOrder;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Species
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
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

    public function __construct()
    {
        $this->observations = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    /**
     * Add observation
     *
     * @param \AppBundle\Entity\Observation $observation
     *
     * @return Species
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

    /**
     * Add image
     *
     * @param \AppBundle\Entity\Image $image
     *
     * @return Species
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
