<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    CONST NOTE_CREATE_CASE = "Tạo mới 1 case";
    CONST NOTE_ADD_IMAGE = "Thêm Ảnh";
    CONST NOTE_REMOVE_IMAGE = "Xóa Ảnh";
    CONST NOTE_EDIT_ADDRESS = "Sửa địa điểm";
    CONST NOTE_EDIT_NOTE = "Sửa Ghi Chú";
    CONST NOTE_EDIT_DESCRIPTION = "Sửa mô tả";
    CONST NOTE_EDIT_STATUS = "Sửa trạng thái";
    CONST NOTE_EDIT_TYPE = "Sửa loài";
    CONST NOTE_EDIT_NAME = "Sửa tên";


    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
