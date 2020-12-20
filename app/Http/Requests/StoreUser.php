<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StoreUser extends FormRequest
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
            'name'    => 'required|string|max:45',
            'address' => 'string|max:100',
            'phone'   => 'max:15|min:9',
            'note'    => 'string|max:255',
            'email'   => 'required|email|unique:users',
            'roles'   => 'required',
            'roles.*' => 'exists:roles,id',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'email.required' => 'Vui lòng nhập địa chỉ mail',
            'email.email'    => 'Email không đúng',
            'email.unique'   => 'Email đã tồn tại',
            'name.required'  => 'Vui lòng nhập tên người dùng',
            'phone.max'      => 'Số điện thoại tối đa 15 ký tự',
            'phone.min'      => 'Số điện thoại tối thiểu 9 ký tự',
            'name.max'       => 'Tên tối đa 45 ký tự',
            'note.max'       => 'Ghi chú tối đa 255 ký tự',
            'address.max'    => 'Địa chỉ tối đa 100 ký tự',
            'roles.required' => 'Vui lòng chọn chuyên môn của người dùng',
            'roles.exists'   => 'chuyên môn không tồn tại',

        ];
    }
}
