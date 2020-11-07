<?php

namespace App\Services;

use App\Models\Animal;
use Illuminate\Support\Carbon;

class AnimalService {
    public function __construct()
    {
    }


    public function createAnimal($data)
    {
        $code = $this->generateCode();

        Animal::create([
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
    }

    private function generateCode()
    {
        return Animal::max('code');
    }

    private function detectBirth($year, $month)
    {
        $date = Carbon::now();
        $date->sub($year, 'year');
        $date->sub($month, 'month');
        return $date ->isoFormat('Y-M-D');
    }

}
