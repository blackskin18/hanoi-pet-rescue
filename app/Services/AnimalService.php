<?php

namespace App\Services;

use App\Models\AnimalImage;
use App\Models\Animal;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;


class AnimalService {
    public function createAnimal($data)
    {
        $images = $data['images'];
        $code = $this->generateCode();

        // insert animal
        $animal = Animal::create([
            'code' => $code,
            'name' => $data['name'],
            'description' => $data['description'],
            'status' => $data['status'],
            'type' => $data['type'],
            'receice_place' => $data['receice_place'],
            'receive_date' => $data['receive_date'],
            'place' => $data['place'],
            'date_of_birth' => $this->detectBirth($data['age_year'], $data['age_month']),
            'note' => $data['note'],
            'created_by' => 6
        ]);

        // insert images
        foreach($images as $image) {
            $path = Storage::disk('public')->put('animal_image/'.$animal->id, $image);
            $filename = explode('/', $path)[count(explode('/', $path )) - 1];

            $animal->animalImage()->create([
                //'animal_id' => $animal->id,
                'file_name' => $filename,
                'created_by' => 6
            ]);
        }
    }

    private function generateCode()
    {
        return Animal::max('code') + 1;
    }

    private function detectBirth($year, $month)
    {
        $date = Carbon::now();
        $date->sub($year, 'year');
        $date->sub($month, 'month');
        return $date->isoFormat('Y-M-D');
    }

}
