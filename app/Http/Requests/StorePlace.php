<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StorePlace extends FormRequest
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
            'address' => 'required',
            'name'    => 'required',
            'phone'   => 'required|digits_between:9,12|unique:places',
            'type'    => 'required',
        ];

        if ($request->get('root_hospital')) {
            $rules['parent_id'] = 'required';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'parent_id.required'   => 'Hãy chọn phòng khám gốc',
            'address.required'     => 'Hãy nhập địa chỉ',
            'name.required'        => 'Hãy nhập tên địa điểm',
            'phone.required'       => 'Hãy nhập số điện thoại',
            'phone.digits_between' => 'Số điện thoại chưa đúng',
            'phone.unique'         => 'Số điên thoại này đã bị trùng',
        ];
    }
}
