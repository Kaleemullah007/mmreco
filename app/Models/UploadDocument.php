<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UploadDocument extends Model
{

   use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = 'upload_documents';

    protected $fillable = ['entity_name', 'file_name'];
}
