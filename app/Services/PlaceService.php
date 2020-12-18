<?php

namespace App\Services;

use App\Models\Animal;
use App\Models\Place;

class PlaceService
{
    const LIMIT = 20;

    public function createPlace($data)
    {
        // insert Place
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

    public function updatePlace($data, $placeId)
    {
        Place::find($placeId)->update([
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

    public function getHospitals($data) {
        $page = (isset($data['page']) && $data['page'] >= 1) ? $data['page'] : 1;

        $places = Place::where('type', Place::HOSPITAL)->where('parent_id', null)->offset(($page - 1) * self::LIMIT)
                ->with('children.animals')
                ->with('animals');

        //search
        $places = $this->filterPlaces($places, $data);
        $places = $places->limit(self::LIMIT)->get();

        $places->map(function ($place) {
            $place->key = $place->id;
            $place->children->map(function ($child) {
                $child->key = $child->id;
                return $child;
            });
            if(count($place->children) ===0) {
                unset($place->children);
            }

            return $place;
        });
        return $places;
    }

    public function getPlaces($data)
    {
        if ($data['type'] && $data['type'] == Place::HOSPITAL) {
            return $this->getHospitals($data);
        }

        if(isset($data['all']) && $data['all'] === "true") {
            return Place::where('type', $data['type'])->where('parent_id', null)->with('children')->get();
        }

        $page = (isset($data['page']) && $data['page'] >= 1) ? $data['page'] : 1;

        $places = Place::where('type', $data['type'])->offset(($page - 1) * self::LIMIT);

        $places = $places->with('animals');

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

    public function getPlaceBtId($placeId)
    {
        return Place::with(['parent', 'children'])->find($placeId);
    }

    public function deleteById($userId) {
        $place = Place::with(['animals', 'children'])->find($userId);
        if(count($place->animals) > 0) {
            return 'Đang có Case ở địa điểm này, nên không thể xóa';
        }
        if(count($place->children) > 0) {
            return 'Địa điểm này đang có chi nhánh, vui lòng xóa chi nhánh trước';
        } else {
            $place->delete();
            return true;
        }
    }
}
