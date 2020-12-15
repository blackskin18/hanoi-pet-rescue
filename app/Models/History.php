<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    CONST NOTE_CREATE_CASE = "Tạo mới 1 case";
    CONST NOTE_ADD_IMAGE = "Thêm Ảnh";
    CONST NOTE_REMOVE_IMAGE = "Xóa Ảnh";
    CONST NOTE_EDIT_CODE = "Sửa code";
    CONST NOTE_EDIT_ADDRESS = "Sửa địa điểm";
    CONST NOTE_EDIT_NOTE = "Sửa Ghi Chú";
    CONST NOTE_EDIT_DESCRIPTION = "Sửa mô tả";
    CONST NOTE_EDIT_STATUS = "Sửa trạng thái";
    CONST NOTE_EDIT_TYPE = "Sửa loài";
    CONST NOTE_EDIT_NAME = "Sửa tên";
    CONST NOTE_EDIT_RECEIVE_PLACE = "Sửa nơi đón";
    CONST NOTE_EDIT_RECEIVE_DATE = "Sửa ngày đón";
    CONST NOTE_EDIT_GENDER = "Sửa giới tính";
    CONST NOTE_EDIT_DATE_OF_BIRTH = "Sửa tuổi";
    CONST NOTE_EDIT_FOSTER_ID = "Sửa foster";
    CONST NOTE_EDIT_OWNER_ID = "Sửa chủ nuôi";
    CONST NOTE_EDIT_PLACE_ID = "Sửa nơi ở hiện tại";
    CONST NOTE_EDIT_ADD_IMAGE = "Thêm ảnh";
    CONST NOTE_EDIT_DELETE_IMAGE = "Xóa ảnh";


    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
