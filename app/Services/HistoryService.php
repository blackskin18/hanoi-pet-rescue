<?php

namespace App\Services;

use App\Models\Animal;
use App\Models\History;
use App\Models\Place;
use App\Models\Status;
use App\Models\User;

class HistoryService
{

    public function createAnimal($animalId)
    {
        History::create([
            'user_id' => Auth()->user()->id,
            'animal_id' => $animalId,
            'note' => History::NOTE_CREATE_CASE,
            'attribute' => 'create_case'
        ]);
    }


    public function editAnimal($oldAnimal, $newValue) {
        if($oldAnimal->code != $newValue->code) {
            $this->saveLog($oldAnimal->id, 'code', $oldAnimal->code, $newValue->code, History::NOTE_EDIT_CODE);
        }
        if($oldAnimal->name != $newValue->name) {
            $this->saveLog($oldAnimal->id, 'name', $oldAnimal->name, $newValue->name, History::NOTE_EDIT_NAME);
        }
        if($oldAnimal->description != $newValue->description) {
            $this->saveLog($oldAnimal->id, 'description', $oldAnimal->description, $newValue->description, History::NOTE_EDIT_DESCRIPTION);
        }
        if($oldAnimal->status != $newValue->status) {
            $this->saveLog($oldAnimal->id, 'status', $this->getStatusName($oldAnimal->status), $this->getStatusName($oldAnimal->status), History::NOTE_EDIT_STATUS);
        }
        if($oldAnimal->type != $newValue->type) {
            $this->saveLog($oldAnimal->id, 'type', $this->getTypeName($oldAnimal->type), $this->getTypeName($newValue->type), History::NOTE_EDIT_TYPE);
        }
        if($oldAnimal->receive_place != $newValue->receive_place) {
            $this->saveLog($oldAnimal->id, 'receive_place', $oldAnimal->receive_place, $newValue->receive_place, History::NOTE_EDIT_RECEIVE_PLACE);
        }
        if($oldAnimal->receive_date != $newValue->receive_date) {
            $this->saveLog($oldAnimal->id, 'receive_date', $oldAnimal->receive_date, $newValue->receive_date, History::NOTE_EDIT_RECEIVE_DATE);
        }
        if($oldAnimal->gender != $newValue->gender) {
            $this->saveLog($oldAnimal->id, 'gender', $this->getGenderName($oldAnimal->gender), $this->getGenderName($newValue->gender), History::NOTE_EDIT_GENDER);
        }
        if($oldAnimal->date_of_birth != $newValue->date_of_birth) {
            $this->saveLog($oldAnimal->id, 'date_of_birth', $this->renderAge($oldAnimal->date_of_birth), $this->renderAge($newValue->date_of_birth), History::NOTE_EDIT_DATE_OF_BIRTH);
        }
        if($oldAnimal->note != $newValue->note) {
            $this->saveLog($oldAnimal->id, 'note', $oldAnimal->note, $newValue->note, History::NOTE_EDIT_NOTE);
        }
        if($oldAnimal->foster_id != $newValue->foster_id) {
            $this->saveLog($oldAnimal->id, 'foster_id', $oldAnimal->foster_id, $newValue->foster_id, History::NOTE_EDIT_FOSTER_ID);
        }
        if($oldAnimal->owner_id != $newValue->owner_id) {
            $this->saveLog($oldAnimal->id, 'owner_id', $oldAnimal->owner_id, $newValue->owner_id, History::NOTE_EDIT_OWNER_ID);
        }
        if($oldAnimal->place_id != $newValue->place_id) {
            $this->saveLog($oldAnimal->id, 'place_id', $oldAnimal->place_id, $newValue->place_id, History::NOTE_EDIT_PLACE_ID);
        }
    }

    public function deleteImages($animalId, $images) {
        foreach ($images as $image) {
            $this->saveLog($animalId, 'image', $image->file_name, '', History::NOTE_EDIT_DELETE_IMAGE);
        }
    }

    private function getStatusName($statusId) {
        return Status::find($statusId)->name;
    }

    private function getTypeName($type) {
        if($type === Animal::TYPE_DOG) return 'Chó';
        elseif($type === Animal::TYPE_CAT) return 'Mèo';
        elseif($type === Animal::TYPE_OTHER) return 'Khác';
    }

    private function getGenderName($type) {
        if($type === Animal::GENDER_F) return 'Cái';
        elseif($type === Animal::GENDER_M) return 'Đực';
        elseif($type === Animal::GENDER_O) return 'Khác';
    }

    private function renderAge($date) {
        $result = '';
        $birthday = strtotime($date);
        $dateNow = strtotime('now');

        $abs = ($dateNow - $birthday);
        $year = floor($abs/(60*60*24*365));
        $month = floor(($abs%(60*60*24*365))/(60*60*24*30));
        if($year > 0) {
            $result .= ($year.' tuổi ');
        }
        if($month > 0) {
            $result .= $month . ' tháng';
        }
        return $result;
    }

    public function saveLog($animalId, $attribute, $oldValue, $newValue, $note)
    {
        $history = new History;
        $history->user_id = Auth()->user()->id;
        $history->animal_id = $animalId;
        $history->attribute = $attribute;
        $history->old_value = $oldValue;
        $history->new_value = $newValue;
        $history->note = $note;
        $history->save();
    }
}
