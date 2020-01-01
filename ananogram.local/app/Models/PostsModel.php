<?php namespace App\Models;

use CodeIgniter\Model;

class PostsModel extends Model
{
    protected $table = 'anano_posts';
    protected $allowedFields = ['heading', 'body', 'image_name'];
    protected $primaryKey = 'id';
    protected $beforeInsert = ['cleanPost'];

    protected function cleanPost(array $data)
    {
        if (!isset($data['data']['heading']) || !isset($data['data']['body']))
        {
            return $data;
        }

        $data['data']['heading'] = htmlentities($data['data']['heading'], ENT_QUOTES, 'UTF-8');
        $data['data']['body'] = nl2br(htmlentities($data['data']['body'], ENT_QUOTES, 'UTF-8'));

        return $data;
    }
}