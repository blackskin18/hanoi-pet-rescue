<?php

namespace App\Exports;

use App\Services\AnimalService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use function Symfony\Component\String\s;

class ReportExport implements FromView
{
    private $data;

    private $reportType;

    private $dateTime;

    const  DOG = '1';

    const  CAT = '2';

    const  OTHER = '3';

    const    SAVING = '1';

    const  POST_FIND_OWNER = '2';

    const  FOUND_OWNER = '3';

    const  DIED = '4';

    const  READY_FIND_OWNER = '5';

    const HOSPITAL = '1';

    const COMMON_HOME = '2';

    const FOSTER = '3';

    const OWNER = '4';

    public function __construct($data, $reportType, $dateTime)
    {
        $this->reportType = $reportType;
        $this->data = $data;
        $this->dateTime = $dateTime;
    }

    public function view(): View
    {

        //http://localhost:8000/api/animal-test/1?start_time=2020/01/01&end_time=2020/12/31
        if ($this->reportType == 1) {
            $arr = explode('/', $this->dateTime);
            $date = implode('/', array_reverse($arr));

            return view('exports.report_progressive', [
                'date' => $date,

                'dog_common_home' => $this->findReportPlace(self::DOG, self::COMMON_HOME),
                'dog_hospital'    => $this->findReportPlace(self::DOG, self::HOSPITAL),
                'dog_foster'      => $this->findReportPlace(self::DOG, self::FOSTER),
                'dog_owner'       => $this->findReportPlace(self::DOG, self::OWNER),
                'dog_died'        => $this->findReportStatus(self::DOG, self::DIED),
                'dog_saving'      => $this->findReportStatus(self::DOG, self::SAVING),
                'dog'             => $this->countByType(self::DOG),

                'cat_common_home' => $this->findReportPlace(self::CAT, self::COMMON_HOME),
                'cat_hospital'    => $this->findReportPlace(self::CAT, self::HOSPITAL),
                'cat_foster'      => $this->findReportPlace(self::CAT, self::FOSTER),
                'cat_owner'       => $this->findReportPlace(self::CAT, self::OWNER),
                'cat_died'        => $this->findReportStatus(self::CAT, self::DIED),
                'cat_saving'      => $this->findReportStatus(self::CAT, self::SAVING),
                'cat'             => $this->countByType(self::CAT),

                'other_common_home' => $this->findReportPlace(self::OTHER, self::COMMON_HOME),
                'other_hospital'    => $this->findReportPlace(self::OTHER, self::HOSPITAL),
                'other_foster'      => $this->findReportPlace(self::OTHER, self::FOSTER),
                'other_owner'       => $this->findReportPlace(self::OTHER, self::OWNER),
                'other_died'        => $this->findReportStatus(self::OTHER, self::DIED),
                'other_saving'      => $this->findReportStatus(self::OTHER, self::SAVING),
                'other'             => $this->countByType(self::OTHER),

                'count_common_home' => $this->getCountByPlace(self::COMMON_HOME),
                'count_hospital'    => $this->getCountByPlace(self::HOSPITAL),
                'count_foster'      => $this->getCountByPlace(self::FOSTER),
                'count_owner'       => $this->getCountByPlace(self::OWNER),
                'count_died'        => $this->getCountByStatus(self::DIED),
                'count_saving'      => $this->getCountByStatus(self::SAVING),
                'count'             => $this->data['count'],
            ]);
        } else {
            return view('exports.report', [
                'dog'             => $this->countByType(self::DOG),
                'dog_common_home' => $this->findReportPlace(self::DOG, self::COMMON_HOME),
                'dog_hospital'    => $this->findReportPlace(self::DOG, self::HOSPITAL),
                'dog_foster'      => $this->findReportPlace(self::DOG, self::FOSTER),
                'dog_found_owner' => $this->findReportStatus(self::DOG, self::FOUND_OWNER),
                'dog_died'        => $this->findReportStatus(self::DOG, self::DIED),

                'cat'             => $this->countByType(self::CAT),
                'cat_common_home' => $this->findReportPlace(self::CAT, self::COMMON_HOME),
                'cat_hospital'    => $this->findReportPlace(self::CAT, self::HOSPITAL),
                'cat_foster'      => $this->findReportPlace(self::CAT, self::FOSTER),
                'cat_found_owner' => $this->findReportStatus(self::CAT, self::FOUND_OWNER),
                'cat_died'        => $this->findReportStatus(self::CAT, self::DIED),

                'other'             => $this->countByType(self::OTHER),
                'other_common_home' => $this->findReportPlace(self::OTHER, self::COMMON_HOME),
                'other_hospital'    => $this->findReportPlace(self::OTHER, self::HOSPITAL),
                'other_foster'      => $this->findReportPlace(self::OTHER, self::FOSTER),
                'other_found_owner' => $this->findReportStatus(self::OTHER, self::FOUND_OWNER),
                'other_died'        => $this->findReportStatus(self::OTHER, self::DIED),

                'count'             => $this->data['count'],
                'count_common_home' => $this->getCountByPlace(self::COMMON_HOME),
                'count_hospital'    => $this->getCountByPlace(self::HOSPITAL),
                'count_foster'      => $this->getCountByPlace(self::FOSTER),
                'count_found_owner' => $this->getCountByStatus(self::FOUND_OWNER),
                'count_died'        => $this->getCountByStatus(self::DIED),
            ]);
        }
    }

    private function findReportStatus($type, $status)
    {
        $datas = $this->data['report_by_status'];

        if (count($datas) > 0) {
            foreach ($datas as $data) {
                if ($data->type == $type && $data->status == $status) {
                    return $data->count;
                }
            }
        }

        return 0;
    }

    private function findReportPlace($type, $place)
    {
        $datas = $this->data['report_by_place'];

        if (count($datas) > 0) {
            foreach ($datas as $data) {
                if ($data->type == $type && $data->place_type == $place) {
                    return $data->count;
                }
            }
        }

        return 0;
    }

    private function countByType($type)
    {
        $datas = $this->data['report_by_type'];

        if (count($datas) > 0) {
            foreach ($datas as $data) {
                if ($data->type == $type) {
                    return $data->count;
                }
            }
        }

        return 0;
    }

    private function getCountByStatus($status)
    {
        $datas = $this->data['report_by_status'];

        $result = 0;
        foreach ($datas as $data) {
            if ($data->status == $status) {
                $result += $data->count;
            }
        }

        return $result;
    }

    private function getCountByPlace($placeType)
    {
        $datas = $this->data['report_by_place'];

        $result = 0;
        foreach ($datas as $data) {
            if ($data->place_type == $placeType) {
                $result += $data->count;
            }
        }

        return $result;
    }
}
