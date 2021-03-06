<?php

namespace App\Services;

use App\Models\Animal;
use App\Models\Place;
use App\Models\PlaceHistory;

class PlaceHistoryService
{
    public function getHistoryByPlaceId($placeId) {
        $all = PlaceHistory::where('place_id', $placeId)->with('animal.animalImage')->get();
        $now = Animal::where('place_id', $placeId)->with('animalImage')->get();

        $all = $all->filter(function ($placeRecord) {
            return $placeRecord->animal != null;
        })->toArray();
        $all = array_values($all);

        return [
            'all' => $all,
            'now' => $now,
            'image_prefix_url' => url('storage/animal_image/')
        ];
    }
}
