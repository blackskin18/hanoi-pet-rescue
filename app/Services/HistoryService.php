<?php

namespace App\Services;

use App\Models\History;
use App\Models\Place;
use App\Models\User;

class HistoryService
{

    public function AddCreateCaseHistory($animalId)
    {
        History::create([
            'user_id' => Auth()->user()->id,
            'animal_id' => $animalId,
            'note' => History::NOTE_CREATE_CASE,
        ]);
    }

    public function addEditCaseHistory($oldAnimal, $newAnimal) {
        
    }
}
