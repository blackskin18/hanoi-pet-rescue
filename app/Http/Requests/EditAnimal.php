<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditAnimal extends FormRequest
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
        return [
            'code'          => 'required|integer',
            'images'        => 'image',
            'receive_place' => 'required|string',
            'receive_date'  => 'required|date',
            'name'          => 'string',
            'type'          => 'required',
            'gender'        => 'required',
            'age_month'     => 'integer',
            'age_year'      => 'integer',
            'place_id'      => 'required',
            'description'   => 'string',
            'status'        => 'required',
            'foster_id'     => 'integer',
            'owner_id'      => 'integer',
            'note'          => 'string',
        ];
    }

    public function messages()
    {
        return [
            'images.image'           => 'Ảnh không đúng định dạng (chỉ hỗ trợ các định dạng jpeg, png, bmp, gif, svg, or webp)',
            'code.required'          => 'Hãy nhập code',
            'code.integer'           => 'Code phải là số',
            'code.unique'            => 'Code này đã tồn tại, hãy nhập code khác',
            'receive_place.required' => 'Hãy nhập nơi nhận',
            'receive_date.required'  => 'Hãy nhập ngày nhận',
            'receive_date.date'      => 'Ngày nhận không đúng định dạng',
            'type.required'          => 'Hãy nhập chọn loài',
            'gender.required'        => 'Hãy chọn giới tính của case',
            'status.required'        => 'Hãy chọn trạng thái của case',
            'foster_id.integer'      => 'Lỗi ko tìm thấy foster, liên hệ với kỹ thuật',
            'owner_id.integer'       => 'Lỗi ko tìm thấy foster, liên hệ với kỹ thuật',
            'age_month.integer'      => 'Số tháng tuổi của case phải là số',
            'age_year.integer'       => 'Số năm tuổi của case phải là số',
            'place_id.required'      => 'Vui lòng chọn nơi ở hiện tại',
        ];
    }
}
