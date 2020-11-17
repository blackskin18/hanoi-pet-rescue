<?php

namespace App\Services;

use App\Models\Animal;
use App\Models\Place;

class PlaceService
{
    const LIMIT = 20;

    public function createPlace($data)
    {
        // insert animal
        Place::create([
            'type' => $data['type'],
            'name' => $data['name'],
            'phone' => $data['phone'] ?? "",
            'address' => $data['address'] ?? "",
            'director_name' => $data['director_name'] ?? "",
            'director_phone' => $data['director_phone'] ?? "",
            "parent_id" => (isset($data['parent_id']) && $data['type'] = Place::HOSPITAL) ? $data['parent_id'] : null,
            'note' => $data['note'] ?? ""
        ]);
    }

    public function getRootHospitals()
    {
        return Place::where('type', Place::HOSPITAL)
            ->where('parent_id', null)
            ->get();
    }

    public function getPlaces($data)
    {
        $page = (isset($data['page']) && $data['page'] >= 1) ? $data['page'] : 1;

        $places = Place::where('type', $data['type'])->offset(($page - 1) * self::LIMIT);

        if ($data['type'] == Place::HOSPITAL) {
            $places = $places->with('parent');
        }

        //search
        $places = $this->filterPlaces($places, $data);
        $places = $places->limit(self::LIMIT)->get();

        return $places;
    }

    private function filterPlaces($animals, $data)
    {
        if (isset($data['name']) && $data['name'] !== '') {
            $animals->where('name', 'like', '%' . $data['name'] . '%');
        }
        if (isset($data['director_name']) && $data['director_name'] !== '') {
            $animals->where('director_name', 'like', '%' . $data['director_name'] . '%');
        }
        if (isset($data['phone']) && $data['phone'] !== '') {
            $animals->where('phone', 'like', '%' . $data['phone'] . '%');
        }
        if (isset($data['address']) && $data['address'] !== '') {
            $animals->where('address', 'like', '%' . $data['address'] . '%');
        }
        return $animals;
    }

    public function getTotalPlaces($data)
    {
        $places = Place::where('type', $data['type']);
        $places = $this->filterPlaces($places, $data);

        return $places->count();
    }
}
