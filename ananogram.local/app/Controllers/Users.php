<?php namespace App\Controllers;

use App\Models\ProfilesModel;
class Users extends BaseController
{
    protected $helpers = ['form'];
    
    public function index()
    {
        $signup_form = form_open('/users/signup',['name'=>'user_signup','id'=>'user_signup']);
        $signup_form .= form_input([
            'name'          => 'first_name',
            'id'            => 'firstname',
            'placeholder'   => 'First Name',
            'maxlength'     => '50',
            'required'      => 'required',
        ]);
        $signup_form .= form_input([
            'name'          => 'last_name',
            'id'            => 'lastname',
            'placeholder'   => 'Last Name',
            'maxlength'     => '50',
            'required'      => 'required',
        ]);
        $signup_form .= form_label('Date of Birth', 'dob');
        $signup_form .= form_input([
            'name'          => 'dob',
            'id'            => 'dob',
            'placeholder'   => 'Date of Birth',
            'required'      => 'required',
        ],false, false, 'date');
        $signup_form .= form_input([
            'name'          => 'email',
            'id'            => 'signup_email',
            'placeholder'   => 'Email Address',
            'maxlength'     => '255',
            'required'      => 'required',
        ],false, false, 'email');
        $signup_form .= form_password([
            'name'          => 'password',
            'id'            => 'signup_password',
            'placeholder'   => 'Password',
            'maxlength'     => '255',
            'required'      => 'required',
        ]);
        $signup_form .= form_label('<strong>SECURITY QUESTION:</strong><br>What is your favorite color?', 'security_question');
        $signup_form .= form_input([
            'name'          => 'security_question',
            'id'            => 'security_question',
            'placeholder'   => 'Favorite Color',
            'maxlength'     => '255',
            'required'      => 'required',
        ]);
        $signup_form .= form_submit(
            false,
            'CREATE ACCOUNT',
            ['class'=>'submit-btn','id'=>'signup_submit_btn']
        );
        $signup_form .= form_close();
        
        $login_form = form_open('/users/login',['name'=>'user_login','id'=>'user_login']);
        $login_form .= form_input([
            'name'          => 'email',
            'id'            => 'login_email',
            'placeholder'   => 'Email Address',
            'maxlength' => '255',
            'required'      => 'required',
        ],false, false, 'email');
        $login_form .= form_password([
            'name'          => 'login_password',
            'id'            => 'password',
            'placeholder'   => 'Password',
            'maxlength'     => '255',
            'required'      => 'required',
        ]);
        $login_form .= form_submit(
            false,
            'LOGIN',
            ['class'=>'submit-btn','id'=>'login_submit_btn']
        );
        $login_form .= form_close();
        
        $data = [
            'title'         => 'Login or Create an account',
            'signup_form'   => $signup_form,
            'login_form'    => $login_form
        ];
        
        return $this->templateView('user',$data);
    }
    
    public function signup()
    {
        if ($this->request->isAJAX())
        {
            $data = [];
            //validate
            if (!$this->validate([
                'first_name'        => 'required|alpha_space|max_length[50]',
                'last_name'         => 'required|alpha_space|max_length[50]',
                'dob'               => 'required|valid_date[Y-m-d]|max_length[15]',
                'email'             => "required|valid_email|is_unique[anano_profiles.email]|max_length[255]",
                'password'          => "required|max_length[255]",
                'security_question' => "required|max_length[255]",
            ]))
            {
                $data = ['status'=>'failure','message'=>implode('<br>',$this->validator->getErrors())];
            }
            else
            {
                //save data
                $userModel = new ProfilesModel();
                $user_data = [
                    'first_name'        => $this->request->getPost('first_name', FILTER_SANITIZE_STRING),
                    'last_name'         => $this->request->getPost('last_name', FILTER_SANITIZE_STRING),
                    'dob'               => $this->request->getPost('dob', FILTER_SANITIZE_STRING),
                    'email'             => $this->request->getPost('email', FILTER_SANITIZE_EMAIL),
                    'password'          => $this->request->getPost('password', FILTER_SANITIZE_STRING),
                    'security_question' => $this->request->getPost('security_question', FILTER_SANITIZE_STRING),
                ];
                
                $user_id = $userModel->insert($user_data,TRUE);
                if((int)$user_id > 0)
                {
                    $this->authenticate($user_id);
                    $data = ['status'=>'success','message'=>'Welcome!'];
                } else {
                    $data = ['status'=>'failure','message'=>'Unable to save data. Please try again!'];
                }
            }
            
            $this->response->setStatusCode(200)
                            ->setHeader('Content-type', 'application/json')
                            ->noCache()
                            ->setJSON($data)
                            ->send();
        }
        else
        {
            return redirect()->to('/users');
        }
    }
    
    public function login()
    {
        if ($this->request->isAJAX())
        {
            $data = [];
            //validate
            if (!$this->validate([
                'email'             => "required|valid_email|max_length[255]",
                'login_password'          => "required|max_length[255]"
            ]))
            {
                $data = ['status'=>'failure','message'=>implode('<br>',$this->validator->getErrors())];
            }
            else
            {
                //save data
                $userModel = new ProfilesModel();
                $existing_user = $userModel->where('email', $this->request->getPost('email', FILTER_SANITIZE_EMAIL))
                                           ->first();
                if(is_array($existing_user))
                {
                    if(password_verify($this->request->getPost('login_password', FILTER_SANITIZE_STRING), $existing_user['password'])){
                        $this->authenticate($existing_user['id']);
                        $data = ['status'=>'success','message'=>'Welcome Back!'];
                    }
                    else
                    {
                        $data = ['status'=>'failure','message'=>'Please check your credentials and try again!'];
                    }
                } else {
                    $data = ['status'=>'failure','message'=>'User not found. Please register and try again!'];
                }
            }
            
            $this->response->setStatusCode(200)
                            ->setHeader('Content-type', 'application/json')
                            ->noCache()
                            ->setJSON($data)
                            ->send();
        }
        else
        {
            return redirect()->to('/users');
        }
    }
    
    protected function authenticate(int $user_id):bool
    {
        if((int) $user_id > 0){
            $this->session->set('user_id',$user_id);
            $this->session->set('logged_in',time());
            return TRUE;
        }
    }
    
    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/users');
    }

}
