<?php

namespace App\Services;

use App\Models\AnimalImage;
use App\Models\Animal;
use App\Models\History;
use App\Models\Place;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AnimalService
{
    private $historyService;

    public function __construct(HistoryService $historyService)
    {
        $this->historyService = $historyService;
    }

    public function createAnimal($data)
    {
        $images = $data['images'] ?? [];
        $code = isset($data['code']) && $data['code'] ? $data['code'] : Animal::max('code');
        $codeFull = $this->generateCode($data, $code);
        if (isset($data['branch_id']) && isset($data['place_id']) && $data['place_id'] && $data['branch_id']) {
            $placeId = $data['branch_id'];
        } else {
            $placeId = $data['place_id'];
        }

        // insert animal
        $animal = Animal::create([
            'code'          => $code,
            'code_full'     => $codeFull,
            'name'          => $data['name'] ?? '',
            'description'   => $data['description'] ?? '',
            'status'        => $data['status'],
            'type'          => $data['type'],
            'receive_place' => $data['receive_place'] ?? '',
            'receive_date'  => $data['receive_date'] ?? '',
            'gender'        => $data['gender'],
            'date_of_birth' => $this->detectBirth($data['age_year'] ?? 0, $data['age_month'] ?? 0, $data['age_date'] ?? 0),
            'note'          => $data['note'] ?? '',
            'foster_id'     => $data['foster_id'] ?? 0,
            'owner_id'      => $data['owner_id'] ?? 0,
            'place_id'      => $placeId,
            'place_type'    => $data['place_type'],
            'created_by'    => Auth()->user()->id,
        ]);

        // insert images
        $this->insertImages($animal, $images);

        // log history
        $this->historyService->createAnimal($animal);
    }

    public function editAnimal($data, $id)
    {
        $images = $data['images_add'] ?? [];
        $oldImages = $data['old_images'] ?? [];
        $code = isset($data['code']) && $data['code'] ? $data['code'] : Animal::max('code');
        $codeFull = $this->generateCode($data, $code);
        if (isset($data['branch_id']) && isset($data['place_id']) && $data['place_id'] && $data['branch_id']) {
            $placeId = $data['branch_id'];
        } else {
            $placeId = $data['place_id'];
        }

        //update animal data
        $animal = Animal::find($id);
        Animal::find($id)->update([
            'code'          => $code,
            'code_full'     => $codeFull,
            'name'          => $data['name'] ?? '',
            'description'   => $data['description'] ?? '',
            'status'        => $data['status'],
            'type'          => $data['type'],
            'receive_place' => $data['receive_place'] ?? '',
            'receive_date'  => $data['receive_date'] ?? '',
            'gender'        => $data['gender'],
            'date_of_birth' => $this->detectBirth($data['age_year'] ?? 0, $data['age_month'] ?? 0, $data['age_date'] ?? 0),
            'note'          => $data['note'] ?? '',
            'foster_id'     => $data['foster_id'] ?? null,
            'owner_id'      => $data['owner_id'] ?? null,
            'place_id'      => $placeId,
            'place_type'    => $data['place_type'],
        ]);

        // delete image
        $this->deleteImages($oldImages, $id);

        // insert images
        $this->insertImages($animal, $images, true);

        // log edit animal
        $newAnimal = Animal::find($id);
        $this->historyService->editAnimal($animal, $newAnimal);
    }

    private function deleteImages($oldImages, $animalId)
    {
        $oldImages = collect($oldImages)->map(function ($image) {
            $arr = explode('/', $image);

            return $arr[count($arr) - 1];
        });

        // log delete image
        $imageToDelete = AnimalImage::where('animal_id', $animalId)->whereNotIn('file_name', $oldImages)->get();
        $this->historyService->deleteImages($animalId, $imageToDelete);
        AnimalImage::where('animal_id', $animalId)->whereNotIn('file_name', $oldImages)->delete();
    }

    private function insertImages($animal, $images, $addLog = false)
    {
        foreach ($images as $image) {
            $path = Storage::disk('public')->put('animal_image/'.$animal->id, $image);
            $filename = explode('/', $path)[count(explode('/', $path)) - 1];

            // log add image
            if ($addLog) {
                $this->historyService->saveLog($animal->id, 'image', '', $filename, History::NOTE_EDIT_ADD_IMAGE);
            }

            $animal->animalImage()->create([
                'file_name'  => $filename,
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
        $animals = $animals->limit($limit)->with('animalImage')->with('place')->with('status')->orderBy('code', 'DESC')->get();

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

    public function getTotalAnimal($data)
    {
        if (isset($data['type']) && $data['type'] !== '') {
            $animals = Animal::where('type', $data['type']);
        } else {
            $animals = Animal::where('id', '!=', 'null');
        }
        $animals = $this->filterAnimal($animals, $data);

        return $animals->count();
    }

    private function filterAnimal($animals, $data)
    {
        if (isset($data['code']) && $data['code'] !== '') {
            $animals->where('code_full', 'like', '%'.$data['code'].'%');
        }
        if (isset($data['description']) && $data['description'] !== '') {
            $animals->where('description', 'like', '%'.$data['description'].'%');
        }
        if (isset($data['note']) && $data['note'] !== '') {
            $animals->where('note', 'like', '%'.$data['note'].'%');
        }
        if (isset($data['name']) && $data['name'] !== '') {
            $animals->where('name', 'like', '%'.$data['name'].'%');
        }
        if (isset($data['place']) && $data['place'] !== '') {
            $animals->where('place_id', $data['place']);
        }
        if (isset($data['status']) && $data['status'] !== '') {
            $animals->whereIn('status', $data['status']);
        }
        if (isset($data['receive_date_start']) && $data['receive_date_start'] !== '') {
            $animals->where('receive_date', '>=', $data['receive_date_start']);
        }
        if (isset($data['receive_date_end']) && $data['receive_date_end'] !== '') {
            $animals->where('receive_date', '<=', $data['receive_date_end']);
        }

        return $animals;
    }

    private function generateCode($data, $code)
    {
        $year = substr((new Carbon($data['receive_date']))->year, 2);
        $type = $data['type'] == Animal::TYPE_DOG ? 'D' : ($data['type'] == Animal::TYPE_CAT ? 'C' : 'O');
//        $gender = $data['gender'] == Animal::GENDER_M ? 'M' : ($data['gender'] == Animal::GENDER_F ? 'F' : 'O');

        if(!$code) {
            $code = $this->getCodeToCreate();
        }

        return $year.$type.$code;
    }

    public function getCodeToCreate() {
        return  Animal::max('code') + 1;
    }

    private function detectBirth($year, $month, $days)
    {
        $date = Carbon::now();
        $date->sub($year, 'year');
        $date->sub($month, 'month');
        $date->subDays($days);

        return $date->isoFormat('Y-M-D');
    }

    public function getAnimalByCode($code)
    {
        $animal = Animal::with([
            'status',
            'animalImage',
            'foster',
            'place',
            'owner',
        ])->where('code', $code)->first();

        $id = $animal->id;
        $animal->animal_image = $animal->animalImage->map(function ($image) {
            $image->path = url('storage/animal_image/'.$image->animal_id.'/'.$image->file_name);

            return $image;
        });

        $histories = History::where('animal_id', $id)->with('user:id,name')->orderBy('created_at', 'desc')->get();
        foreach ($histories as &$history) {
            if($history->attribute === 'place_id' || $history->attribute === 'foster_id' || $history->attribute === 'owner_id') {
                $old = Place::find($history->old_value);
                $new = Place::find($history->new_value);
                $history->old_value = $old ?? $history->old_value;
                $history->new_value = $new ?? $history->new_value;
            }
            if($history->attribute === 'image') {
                $history->old_value = $history->old_value ?  url('storage/animal_image/'.$id.'/'.$history->old_value) : '';
                $history->new_value = $history->new_value ?  url('storage/animal_image/'.$id.'/'.$history->new_value) : '';
            }
        }

        $animal->history = $histories;

        return $animal;
    }



    public function deleteById($id)
    {
        Animal::find($id)->delete();
    }

    public function getReportData($startTime, $endTime)
    {
        $reportStatus = DB::table('animals')->selectRaw('type, status, count(*) as count');
        $reportPlace = DB::table('animals')->selectRaw('type, place_type, count(*) as count');
        $reportType = DB::table('animals')->selectRaw('type, count(*) as count');
        $count = DB::table('animals')->selectRaw('count(*) as count');

        if(!$startTime) {
            $reportStatus = $reportStatus->whereDate('receive_date','<', $endTime);
            $reportPlace = $reportPlace->whereDate('receive_date','<', $endTime);
            $reportType = $reportType->whereDate('receive_date','<', $endTime);
            $count = $count->whereDate('receive_date','<', $endTime);
        } else {
            $reportStatus->whereBetween('receive_date', [$startTime,$endTime]);
            $reportPlace->whereBetween('receive_date', [$startTime,$endTime]);
            $reportType->whereBetween('receive_date', [$startTime,$endTime]);
            $count->whereBetween('receive_date', [$startTime,$endTime]);
        }

        $reportStatus = $reportStatus->groupBy(['type', 'status'])->get();
        $reportPlace = $reportPlace->groupBy(['type', 'place_type'])->get();
        $reportType = $reportType->groupBy(['type'])->get();
        $count = $count->get();

        return [
            'report_by_status' => $reportStatus,
            'report_by_place'  => $reportPlace,
            'report_by_type'   => $reportType,
            'count'            => $count[0]->count,
        ];
    }
}
