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
            $path = Storage::disk('public')->put('animal_image/' . $animal->id, $image);
            $filename = explode('/', $path)[count(explode('/', $path)) - 1];

            $animal->animalImage()->create([
                'file_name' => $filename,
                'created_by' => 6,
            ]);
        }
    }

    public function getListAnimalsByType($data)
    {
        $limit = isset($data['limit']) && $data['limit'] != '' ? $data['limit'] : Animal::LIMIT_DEFAULT;
        $page = $data['page'] >= 1 ? $data['page'] : 1;

        if (isset($data['type']) && $data['type'] !== '') {
            $animals = Animal::where('type', $data['type'])->offset(($page - 1) * Animal::LIMIT_DEFAULT);
        } else {
            $animals = Animal::offset(($page - 1) * Animal::LIMIT_DEFAULT);
        }

        //search
        $animals = $this->filterAnimal($animals, $data);
        $animals = $animals->limit($limit)->with('animalImage')->with('status')->orderBy('code', 'ASC')->get();

        // get full image url
        $animals = $animals->map(function ($animal) {
            $animal->animalImage = $animal->animalImage->map(function ($image) {
                $image->path = url('storage/animal_image/' . $image->animal_id . '/' . $image->file_name);

                return $image;
            });

            return $animal;
        });

        return $animals;
    }

    public function getTotalAnimal($data)
    {
        if (isset($type) && $type !== '') {
            $animals = Animal::where('type', $data['type']);
        } else {
            $animals = Animal::where('id', '!=', 'null');
        }
        $animals = $this->filterAnimal($animals, $data);

        return $animals->count();
    }

    private function filterAnimal($animals, $data) {
        if (isset($data['code']) && $data['code'] !== '') {
            $animals->where('code', 'like', '%' . $data['code'] . '%');
        }
        if (isset($data['description']) && $data['description'] !== '') {
            $animals->where('description', 'like', '%' . $data['description'] . '%');
        }
        if (isset($data['note']) && $data['note'] !== '') {
            $animals->where('note', 'like', '%' . $data['note'] . '%');
        }
        if (isset($data['status_id']) && $data['status_id'] !== '') {
            $animals->whereIn('status_ids', $data['status_ids']);
        }
        if (isset($data['receive_date_start']) && $data['receive_date_start'] !== '') {
            $animals->where('receive_date', '>=', $data['receive_date_start']);
        }
        if (isset($data['receive_date_end']) && $data['receive_date_end'] !== '') {
            $animals->where('receive_date', '<=', $data['receive_date_end']);
        }
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
