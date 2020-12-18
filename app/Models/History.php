<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    const NOTE_CREATE_CASE = "Tạo mới 1 case";
    const NOTE_ADD_IMAGE = "Thêm Ảnh";
    const NOTE_REMOVE_IMAGE = "Xóa Ảnh";
    const NOTE_EDIT_CODE = "Sửa code";
    const NOTE_EDIT_ADDRESS = "Sửa địa điểm";
    const NOTE_EDIT_NOTE = "Sửa Ghi Chú";
    const NOTE_EDIT_DESCRIPTION = "Sửa mô tả";
    const NOTE_EDIT_STATUS = "Sửa trạng thái";
    const NOTE_EDIT_TYPE = "Sửa loài";
    const NOTE_EDIT_NAME = "Sửa tên";
    const NOTE_EDIT_RECEIVE_PLACE = "Sửa nơi đón";
    const NOTE_EDIT_RECEIVE_DATE = "Sửa ngày đón";
    const NOTE_EDIT_GENDER = "Sửa giới tính";
    const NOTE_EDIT_DATE_OF_BIRTH = "Sửa tuổi";
    const NOTE_EDIT_FOSTER_ID = "Sửa foster";
    const NOTE_EDIT_OWNER_ID = "Sửa chủ nuôi";
    const NOTE_EDIT_PLACE_ID = "Sửa nơi ở hiện tại";
    const NOTE_EDIT_ADD_IMAGE = "Thêm ảnh";
    const NOTE_EDIT_DELETE_IMAGE = "Xóa ảnh";

    protected $fillable = [
        'id',
        'user_id',
        'animal_id',
        'note',
        'attribute',
        'old_value',
        'new_value',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
