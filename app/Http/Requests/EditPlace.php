<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class EditPlace extends FormRequest
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
    public function rules(Request $request)
    {
        $rules = [
            'address'        => 'required|max:255',
            'name'           => 'required|max:255',
            'phone'          => 'required|max:15|min:9',
            'director_phone' => 'max:15|min:9',
            'type'           => 'required',
        ];

        if ($request->get('root_hospital')) {
            $rules['parent_id'] = 'required';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'parent_id.required' => 'Hãy chọn phòng khám gốc',
            'address.required'   => 'Hãy nhập địa chỉ',
            'name.required'      => 'Hãy nhập tên địa điểm',
            'phone.required'     => 'Hãy nhập số điện thoại',

            'phone.max'          => 'Số điện thoại tối đa 15 ký tự',
            'phone.min'          => 'Số điện thoại tối thiểu 9 ký tự',
            'director_phone.max' => 'Số điện thoại tối đa 15 ký tự',
            'director_phone.min' => 'Số điện thoại tối thiểu 9 ký tự',

            'name.max'    => 'Tên tối đa 255 ký tự',
            'address.max' => 'Địa chỉ tối đa 255 ký tự',
        ];
    }
}
