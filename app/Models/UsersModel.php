<?php namespace App\Models;
 
use CodeIgniter\Model;
 
class UsersModel extends Model{
    protected $table = 'users';
    protected $allowedFields = ['id','id_contact','pseudo','password'];
}