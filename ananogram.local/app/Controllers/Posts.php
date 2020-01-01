<?php namespace App\Controllers;
use App\Models\PostsModel;
class Posts extends BaseController
{
    protected $helpers = ['form'];
    
    public function index()
    {
        $errors = [];
        $message_type = 'error';
        if($this->session->get('user_id')>0)
        {
            if($this->request->getMethod() == 'post')
            {
                //validate
                if (!$this->validate([
                    'topic'     => 'required|string|max_length[50]',
                    'message'   => 'required|string|max_length[1500]',
                    'post_image'=> 'uploaded[post_image]|max_size[post_image,1024]|mime_in[post_image,image/png,image/jpeg,image/gif]',
                ]))
                {
                    $errors[] = 'Validation Error: '. implode('<br>',$this->validator->getErrors());
                }
                else
                {                
                    $heading    = $this->request->getPost('topic', FILTER_SANITIZE_STRING);
                    $body       = $this->request->getPost('message', FILTER_SANITIZE_STRING);
                    $image      = $this->request->getFile('post_image');
                    if($heading && $body && $image->isValid()){
                        try {
                            $new_image_name = $image->getRandomName();
                            $image->move(FCPATH. 'media/', $new_image_name);
                            if($image->hasMoved())
                            {
                                $post_data = [
                                    'heading'   => $heading,
                                    'body'      => $body,
                                    'image_name'=> $new_image_name                                
                                ];
                                $postModel = new PostsModel();
                                $post_id = $postModel->insert($post_data,TRUE);
                                if((int)$post_id > 0)
                                {
                                    $message_type = 'success';
                                    $errors[] = 'Content posted anonymously.';
                                } else {
                                    $errors[] = 'Unable to save data. Please try again!';
                                }
                            } else
                            {
                                $errors[] = 'Unable to save data. Please try again!';
                            }
                        }
                        catch (CodeIgniter\Images\ImageException $e)
                        {
                            $errors[] = 'Could not edit your image. Please try again!<br>'+ $e->getMessage();
                        }
                    } else {
                        $errors[] = 'All fields are REQUIRED. Please try again!';
                    }
                }
            }
            
            $post_form = form_open_multipart('/posts',['name'=>'user_post','id'=>'user_post']);
            $post_form .= form_upload([
                'name'          => 'post_image',
                'id'            => 'post_image',
                'placeholder'   => 'Post Image',
                'required'      => 'required',
            ]);
            $post_form .= form_input([
                'name'          => 'topic',
                'id'            => 'topic',
                'placeholder'   => 'topic',
                'maxlength'     => '50',
                'required'      => 'required',
            ]);
            $post_form .= form_textarea([
                'name'          => 'message',
                'id'            => 'message',
                'placeholder'   => 'Message',
                'maxlength'     => '1500',
                'required'      => 'required',
            ]);
            $post_form .= form_submit(
                false,
                'SAVE',
                ['class'=>'save-btn','id'=>'post_submit_btn']
            );
            $post_form .= form_close();
            $data = [
                'title'     => 'Post a new story anonymously',
                'post_form' => $post_form,
                'errors'    => (count($errors)>0?'<div class="'. $message_type .'">'. implode('<br>', $errors) .'</div>':'')
            ];
            return $this->templateView('post',$data);
        } else {
            return redirect()->to('/users');
        }
    }
        
    public function create()
    {
        
        $files = $request->getFiles();

// Grab the file by name given in HTML form
if ($files->hasFile('uploadedFile'))
{
        $file = $files->getFile('uploadedfile');

        // Generate a new secure name
        $name = $file->getRandomName();

        // Move the file to it's new home
        $file->move('/path/to/dir', $name);

        echo $file->getSize('mb');      // 1.23
        echo $file->getExtension();     // jpg
        echo $file->getType();          // image/jpg
}

        helper('form');
        $model = new NewsModel();

        if (! $this->validate([
            'title' => 'required|min_length[3]|max_length[255]',
            'body'  => 'required'
        ]))
        {
            echo view('templates/header', ['title' => 'Create a news item']);
            echo view('news/create');
            echo view('templates/footer');

        }
        else
        {
            $model->save([
                'title' => $this->request->getVar('title'),
                'slug'  => url_title($this->request->getVar('title')),
                'body'  => $this->request->getVar('body'),
            ]);
            echo view('news/success');
        }
    }
}
