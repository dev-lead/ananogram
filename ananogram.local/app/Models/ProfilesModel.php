<?php namespace App\Models;

use CodeIgniter\Model;

class ProfilesModel extends Model
{
    protected $table = 'anano_profiles';
    protected $primaryKey = 'id';
    protected $allowedFields = ['first_name', 'last_name', 'dob', 'email', 'password', 'security_question'];
    protected $beforeInsert = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (!isset($data['data']['password']))
        {
            return $data;
        }

        $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);

        return $data;
    }
}