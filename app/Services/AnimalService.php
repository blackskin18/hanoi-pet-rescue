<?php

namespace App\Services;

use App\Models\AnimalImage;
use App\Models\Animal;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class AnimalService
{
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
            'created_by' => 6,
        ]);

        // insert images
        foreach ($images as $image) {
            $path = Storage::disk('public')->put('animal_image/'.$animal->id, $image);
            $filename = explode('/', $path)[count(explode('/', $path)) - 1];

            $animal->animalImage()->create([
                'file_name' => $filename,
                'created_by' => 6,
            ]);
        }
    }

    public function getListAnimalsByType($type, $page, $limit)
    {
        $limit = isset($limit) && $limit != '' ? $limit : Animal::LIMIT_DEFAULT;

        //search


        if (isset($type) && $type !== '') {
            $animals = Animal::where('type', $type)->offset($page * Animal::LIMIT_DEFAULT);
        } else {
            $animals = Animal::offset($page * Animal::LIMIT_DEFAULT);
        }

        $animals = $animals->limit($limit)->with('animalImage')->with('status')->get();

        // get full image url
        $animals = $animals->map(function ($animal) {
            $animal->animalImage = $animal->animalImage->map(function ($image) {
                $image->path = url('storage/animal_image/'.$image->animal_id.'/'.$image->file_name);

                return $image;
            });

            return $animal;
        });

        return $animals;
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
