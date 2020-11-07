<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {   
        $rules = [
            'name' => 'required|string',
            'status' => 'required|integer',
            'place' => 'required',
            'time_receive' => 'required',
            'code' => 'required|unique:animals,code|numeric|min:1'
        ];
	if($this->input('photos')) {
	    $photos = count($this->input('photos'));
            foreach(range(0, $photos) as $index) {
                $rules['photos.' . $index] = 'image|mimes:jpeg,bmp,png|max:5000';
            }
	}
        
        return $rules;
    }


    public function messages()
    {
        return [
            'time-receive.required' => "Bạn bắt buộc phải nhập ngày nhận case",
            'name.required' => 'Bạn phải nhập trường hợp của case',
            'status.required' => 'Bạn phải nhập trạng thái của case',
            'place.required' => 'Bạn phải nhập địa điểm của case',
            'photos.*.image' => 'Ảnh của case cần là đuôi .jpeg, bmp, png hoặc jpg',
            'photos.*.mimes' => 'Ảnh của case cần là đuôi .jpeg, bmp, png hoặc jpg',
            'photos.*.max' => 'Kích thước Ảnh cần nhỏ hơn 2MB',
            'code.required' => 'Bạn cần nhập code của case',
            'code.unique' => 'case này đã tồn tại, hãy nhập vào code khác',
            'code.numeric' => 'Trường code phải là số lớn hơn 1',
            'code.min' => 'Trường code phải là số lớn hơn 1',
        ];
    }
}
