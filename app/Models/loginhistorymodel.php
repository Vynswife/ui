<?php

namespace App\Models;

use CodeIgniter\Model;

class LoginHistoryModel extends Model
{
    protected $table = 'hs_login';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'login_time'];
}