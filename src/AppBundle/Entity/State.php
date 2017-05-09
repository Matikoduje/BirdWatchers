<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="state")
 */
class State
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Observation", mappedBy="state")
     */
    private $observations;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\UserProfile", mappedBy="state")
     */
    private $userProfile;

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
     * @return State
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

    public function __construct()
    {
        $this->observations = new ArrayCollection();
        $this->userProfile = new ArrayCollection();
    }

    /**
     * Add observation
     *
     * @param \AppBundle\Entity\Observation $observation
     *
     * @return State
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
     * Add userProfile
     *
     * @param \AppBundle\Entity\UserProfile $userProfile
     *
     * @return State
     */
    public function addUserProfile(\AppBundle\Entity\UserProfile $userProfile)
    {
        $this->userProfile[] = $userProfile;

        return $this;
    }

    /**
     * Remove userProfile
     *
     * @param \AppBundle\Entity\UserProfile $userProfile
     */
    public function removeUserProfile(\AppBundle\Entity\UserProfile $userProfile)
    {
        $this->userProfile->removeElement($userProfile);
    }

    /**
     * Get userProfile
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserProfile()
    {
        return $this->userProfile;
    }
}
