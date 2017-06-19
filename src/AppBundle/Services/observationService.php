<?php

namespace AppBundle\Services;


use AppBundle\Entity\Observation;

class observationService
{
    public function serializeObservation(Observation $observation)
    {
        $images = $observation->getImages();
        $imgPath = array();
        foreach ($images as $image) {
            $imgPath[] = $image->getPath() . $image->getName();
        }
        return array(
            'longitude' => $observation->getLongitude(),
            'latitude' => $observation->getLatitude(),
            'description' => $observation->getDescription(),
            'userName' => $observation->getUser()->getLogin(),
            'dateCreate' => $observation->getCreatedAt()->format('Y-m-d'),
            'dateO' => $observation->getObservationDate()->format('Y-m-d'),
            'species' => $observation->getSpecies()->getName(),
            'state' => $observation->getState()->getName(),
            'location' => $observation->getLocation(),
            'images' => $imgPath,
            'speciesId' => $observation->getSpecies()->getId()
        );
    }

    public function serializeObservationsMain($observations, $observationsCounts = null)
    {
        if ($observationsCounts != null) {
            $data = array(
                'observations' => array(),
                'counts' => array());
            foreach ($observationsCounts as $observationCount) {
                $data['counts'][] = $this->serializeCounts($observationCount);
            }
        } else {
            $data = array(
                'observations' => array()
            );
        }

        foreach ($observations as $observation) {
            $data['observations'][] = $this->serializeObservations($observation);
        }
        return $data;
    }

    public function serializeObservations(Observation $observation)
    {
        return array(
            'longitude' => $observation->getLongitude(),
            'latitude' => $observation->getLatitude(),
            'species' => $observation->getSpecies()->getName(),
            'id' => $observation->getId(),
            'dateO' => $observation->getObservationDate()->format('Y-m-d'),
            'location' => $observation->getLocation(),
        );
    }

    public function serializeCounts($observationCount)
    {
        switch ($observationCount['name']) {
            case 'Małopolskie':
                return array(
                    'count' => $observationCount['1'],
                    'id' => 5
                );
            case 'Dolnośląskie':
                return array(
                    'count' => $observationCount['1'],
                    'id' => 6
                );
            case 'Lubelskie':
                return array(
                    'count' => $observationCount['1'],
                    'id' => 7
                );
            case 'Opolskie':
                return array(
                    'count' => $observationCount['1'],
                    'id' => 10
                );
            case 'Podlaskie':
                return array(
                    'count' => $observationCount['1'],
                    'id' => 11
                );
            case 'Pomorskie':
                return array(
                    'count' => $observationCount['1'],
                    'id' => 12
                );
            case 'Śląskie':
                return array(
                    'count' => $observationCount['1'],
                    'id' => 13
                );
            case 'Podkarpackie':
                return array(
                    'count' => $observationCount['1'],
                    'id' => 14
                );
            case 'Warmińsko-mazurskie':
                return array(
                    'count' => $observationCount['1'],
                    'id' => 15
                );
            case 'Zachodniopomorskie':
                return array(
                    'count' => $observationCount['1'],
                    'id' => 16
                );
            case 'Świętokrzyskie':
                return array(
                    'count' => $observationCount['1'],
                    'id' => 2
                );
            case 'Łódzkie':
                return array(
                    'count' => $observationCount['1'],
                    'id' => 1
                );
            case 'Wielkopolskie':
                return array(
                    'count' => $observationCount['1'],
                    'id' => 3
                );
            case 'Kujawsko-pomorskie':
                return array(
                    'count' => $observationCount['1'],
                    'id' => 4
                );
            case 'Lubuskie':
                return array(
                    'count' => $observationCount['1'],
                    'id' => 8
                );
            case 'Mazowieckie':
                return array(
                    'count' => $observationCount['1'],
                    'id' => 9
                );
        }
    }
}