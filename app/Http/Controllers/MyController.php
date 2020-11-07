<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Hospital;

use App\AnimalHospital;

use App\User;

use App\AnimalFoster;

use App\Animal;

use App\AnimalImage;

include('simple_html_dom.php');



class MyController extends Controller

{

    public function addData($code) {

    	$id = null;

    	$name = null;

    	$age = null;

    	$address = null;

    	$status = null;

    	$note = null;

    	$type = null;

    	$place = null;

    	$date = null;



    	$ch = curl_init(); // your curl instance

    	$url = 'http://pet--rescue.appspot.com/case/edit?case_id='.$code;

    	curl_setopt_array($ch, [	CURLOPT_URL => $url,

    							 	CURLOPT_COOKIE => "ACSID=~AJKiYcHvPnF33Hf9Q6CNXR7UlY2AZnLQLdhWaEHbKHunpZxlt4gNT-hYTqEzNBeHNBCA3oXrh-HXm4GY6wcBGScSpDRO0ivH_TIzyXTUvHh78HZhiyEGlQ3a0RqbSgt4moTNkeGYJ3rRSMyaM5QwKWxkzr0xEnHwsEKw7NnrUzDuiXLusgDg_S6l6BZaXOOZ7_QoKwdCu-BV6NB6W7gFbeNTng6WuoQi636OBkgjzv-FzFulHcbjSqzoK4TwxBpGk5nBPe6KXGR94tlb4skFte3nfXLTIRtsaU5JCYVRuXwztBYrC4svrMmq1rvCUotYZFZx_GlGS_EG_Fp-E5x3OFYgtPzVVHNz1w", CURLOPT_RETURNTRANSFER => true]);

    	$result = curl_exec($ch);



    	$html = str_get_html($result);



    	// code

    	// $elementText = $html->find('h1', 0)->innertext ?? "null";

        // $code = (int) substr(trim($elementText), 4);

	    echo $code."<br>";

	    // dd($code)

	    //name

		$elementText = $html->find('input#petCase', 0)->value ??  "null";

		$name = $elementText;

		// echo $name."<br>";

		//age

		$age = 0;

		//address

		$elementText = $html->find('input#petAddress', 0)->value ?? null;

		$address = $elementText;

		// echo $address."<br>";

		//status

		$elementText = $html->find('select#petStatus option[selected]',0)->value ?? 1;

		$status = $elementText;

		if($status == "not_ready") {

			$status = 1;

		}

		if($status == "ready") {

			$status = 2;

		}

		if($status == "found") {

			$status = 3;

		}

		if($status == "lost") {

			$status = 4;

		}

		if($status == "dead") {

			$status = 4;

		}

		// echo $status."<br>";



		//note

		$elementText = $html->find('textarea#petDes',0)->innertext ?? null;

		$note = $elementText;

		// echo $note."<br>";



		$elementText = $html->find('textarea#petTreatment',0)->innertext ?? null;

		$description = $elementText;

		// echo $description."<br>";



		//type

		$elementText = $html->find('select#petSpecies option[selected]',0)->value ?? null;

		$type = $elementText;

		if($type == "cat") {

			$type = "mèo";

		} else if($type == "dog")  {

			$type = "mèo";

		} else {

			$type = $html->find('input#pet_breed', 0)->value ?? null;

		}

		// echo $type."<br>";



		$day = (int) $html->find('input#receiptDayInp',0)->value ?? 1;

		$month = (int) $html->find('input#receiptMonthInp',0)->value ?? 1;

		$year = (int) $html->find('input#receiptYearInp',0)->value ?? 1;



		if($day < 10) {

			$day = "0".$day;

		}

		if($month < 10) {

			$month = "0".$month;

		}

		$date = $year."-".$month."-".$day." 00:00:00";

		// echo $date."<br>";





		$elementText = $html->find('select#petPlace option[selected]', 0)->value ?? "null";

		$place = $elementText;

		if($place == "hospital") {

			$place = 'hospital';

		}



		if($place == "foster") {

			$place = 'volunteer';

		}

		if($place == "other") {

			$place = $html->find('input[name=foster_other]', 0)->value ?? null;

		}

		if($place == "shared_house") {

			$place = 'commonHome';

		}

		//

		$userCreatName = $html->find('#hospital_foster_log > div > table > tbody > tr > td:nth-child(2) > div > ul > li:nth-last-child(1) > span.foster_ref', 0)->innertext ?? null;

		if($userCreatName) {

			$userCreat = User::where('name', $userCreatName)->first();

			if(!$userCreat) {

				$userCreat = User::find(1);

			}

		} else {

			$userCreat = User::find(1);

		}



		//----------------------------------------------------------------

		$animal = new Animal();

		$animal->code = $code;

		$animal->name = $name;

		$animal->age = $age;

		$animal->description = $description;

		$animal->address = $address;

		$animal->status = $status;

		$animal->note = $note;

		$animal->type = $type;

		$animal->place = $place;

		$animal->time_receive = $date;

		$animal->created_by = $userCreat->id;

		$animal->save();



		if($place == 'hospital') {

			$hospitalName = $html->find('select#petHospital option[selected]', 0)->value ?? "null";

			$hospital = Hospital::where('name', $hospitalName)->first();

			if($hospital) {

			 	$animalHospital = new AnimalHospital();

	            $animalHospital->animal_id = $animal->id;

	            $animalHospital->hospital_id = $hospital->id;

	            $animalHospital->note = "";

	            $animalHospital->save();

			}

			// dd($hospital);

		}

		if($place == 'volunteer'){



			$fos = $html->find('#infoFoster select#petFoster option[selected]', 0)->value ?? "null";

			if($fos == "other"){

				$posOther = $html->find('input[name=foster_other]', 0)->value ?? "";

				$poster = User::where('name', $posOther)->first();

				// dd($poster);

				if($poster) {

					$animalFosters = new AnimalFoster();

		            $animalFosters->animal_id = $animal->id;

		            $animalFosters->foster_id = $poster->id;

		            $animalFosters->note = "";

		            $animalFosters->save();

				} else {

					$animalFosters = new AnimalFoster();

		            $animalFosters->animal_id = $animal->id;

		            $animalFosters->foster_id = 80;

		            $animalFosters->note = $posOther;

		            $animalFosters->save();

				}

			} else {

				$volunteer = User::where('email', $fos)->first();

				if($volunteer) {

					$animalFosters = new AnimalFoster();

		            $animalFosters->animal_id = $animal->id;

		            $animalFosters->foster_id = $volunteer->id;

		            $animalFosters->note = "";

		            $animalFosters->save();

				} else {

					$animalFosters = new AnimalFoster();

		            $animalFosters->animal_id = $animal->id;

		            $animalFosters->foster_id = 80;

		            $animalFosters->note = $fos;

		            $animalFosters->save();

				}

			}

		}



    }







    public function addAllData($id) {

		// $this->addData(2126);

		$animals = Animal::all();

		foreach ($animals as $key => $animal) {

			if($animals[$key]->description == 'None') {

				$animals[$key]->description = NULL;

				$animals[$key]->save();

			} if($animals[$key]->place == 'null') {

				$animals[$key]->place = NULL;

				$animals[$key]->save();

			}

		}

    }



    public function addMultiImage($idStart) {

    	$a = $idStart;

    	// for ($i=$a; $i < $a+10; $i++) {

    		$animal = Animal::where('code', $a)->first();

    		$this->addOneImage($idStart);

    	// }

    }



    public function addOneImage($code) {
		$animal = Animal::where('code', $code)->first();
		// echo $animal->id;die;
		$ch = curl_init(); // your curl instance

    	$url = 'http://pet--rescue.appspot.com/case/edit?case_id='.$code;
    	curl_setopt_array($ch, [	CURLOPT_URL => $url,

    							 	CURLOPT_COOKIE => "ACSID=~AJKiYcG-7BIoi614_c9BbV8fd6PUPDr4oDk_CLSNeVQevxj55UBOXRRY2ihNHoZZIsXJkrrDfdQoLktlEquEu6Q3FV5PddkGAVXWsI5-rHpuNtlraD_vIGGjLQpn-f9l3CclInri75tpUwycnqdHyWHrkoFVqn5SCZSttyub0T8CEjxhPrIwBmx8l7SyjQFBEuSv8viIuzf2ichsuuYHLl_IEqq01BTAf2dubZVjXudMfu9qark1egNXQ5EwVmTrQ0hHljuA-ZtP55zGxgVtet0g_HclWXJ9ZQDvNQ0Y7HeIt6Y1buQKGwiIau1AA7M3GKHhNUvW3lnZx8UdIo9vpnstXTGzgao3Yg", CURLOPT_RETURNTRANSFER => true]);

    	$result = curl_exec($ch);

    	$html = str_get_html($result);

    	$allImg = $html->find('img');

    	$public_link = '/home/okv08coo9fkv/public_html/hanoipetrescue/animal_image/';

    	foreach ($allImg as $key => $img) {

    		if($key == 0) continue;

    		$fileName = 'case_image_'.$key.'.png';

    		$animalImage = new AnimalImage();

    		$animalImage->animal_id = $animal->id;

    		$animalImage->file_name = $fileName;

    		$animalImage->hospital_id = NULL;

    		$animalImage->foster_id = NULL;

    		$animalImage->host_id = NULL;

    		$animalImage->transporter_id = NULL;

    		$animalImage->created_by = NULL;

    		$animalImage->save();

    		// echo $fileName;

    		// echo $public_link.$fileName.'<br>';

    		$url = 'http://pet--rescue.appspot.com'.$img->src;
    		// dd($url);
    		// echo $public_link.'<br>';

    		// echo $public_link.$animal->id.'/'.$fileName.'<br>';die;
    		// echo $url .'<br>';die;
    		if (!file_exists($public_link.$animal->id)) {
			    mkdir($public_link.$animal->id, 0755, true);
			}
    		$this->grab_image($public_link.$animal->id.'/'.$fileName, $url);

    	}

    }





    public function grab_image($saveto , $url){
    	// echo $saveto .'<br>';
    	// echo $url;die;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_COOKIE, "ACSID=~AJKiYcG-7BIoi614_c9BbV8fd6PUPDr4oDk_CLSNeVQevxj55UBOXRRY2ihNHoZZIsXJkrrDfdQoLktlEquEu6Q3FV5PddkGAVXWsI5-rHpuNtlraD_vIGGjLQpn-f9l3CclInri75tpUwycnqdHyWHrkoFVqn5SCZSttyub0T8CEjxhPrIwBmx8l7SyjQFBEuSv8viIuzf2ichsuuYHLl_IEqq01BTAf2dubZVjXudMfu9qark1egNXQ5EwVmTrQ0hHljuA-ZtP55zGxgVtet0g_HclWXJ9ZQDvNQ0Y7HeIt6Y1buQKGwiIau1AA7M3GKHhNUvW3lnZx8UdIo9vpnstXTGzgao3Yg");

        curl_setopt($ch, CURLOPT_HEADER, 0);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);

        $raw=curl_exec($ch);

        curl_close ($ch);

        if(file_exists($saveto)){

            unlink($saveto);

        }

        $fp = fopen($saveto,'x+');

        fwrite($fp, $raw);

        fclose($fp);
    }

    public function addManyImage(){
    	return view('data/add_image');
    }

}

