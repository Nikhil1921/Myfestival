<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('api');
        // mobile();
    }

    private $table = 'users';

    public function banner()
    {
        get();

        if($row = $this->main->getall('banner', 'CONCAT("'.assets('images/banner/').'", banner) banner', ['id != ' => 0]))
        {
            $response['row'] = $row;
            $response['error'] = FALSE;
            $response['message'] ="Banner List Successful.";
            echoRespnse(200, $response);
        }
        else 
        {
            $response["error"] = TRUE;
            $response['message'] = "Banner List Not Successful.";
            echoRespnse(400, $response);
        }
    }

    public function sendOtp()
    {
        post();
        verifyRequiredParams(['mobile']);
        $post = ['mobile' => $this->input->post('mobile')];
        $mob = $this->main->get('otp', 'mobile', ['mobile' => $post['mobile']]);
        $post['otp'] = rand(1000, 9999);
        $post['otp'] = 1234;
        $post['created_at'] = date('Y-m-d H:i:s');

        if ($mob) 
            $row = $this->main->update(['mobile' => $post['mobile']], $post, 'otp');
        else
            $row = $this->main->add($post, 'otp');

        if($row)
        {
            $response['error'] = FALSE;
            $response['message'] ="OTP Sent Successfully.";
            echoRespnse(200, $response);
        }
        else 
        {
            $response["error"] = TRUE;
            $response['message'] = "OTP Not Sent. Please Try Again.";
            echoRespnse(400, $response);
        }
    }

    public function checkOtp()
    {
        post();
        verifyRequiredParams(['mobile', 'otp']);
        $post = [
            'mobile'         => $this->input->post('mobile'),
            'otp'            => $this->input->post('otp'),
            'created_at >= ' => date('Y-m-d H:i:s', strtotime('-5 minutes'))
        ];

        $row = $this->main->get('otp', 'mobile, otp', $post);

        if($row)
        {
            $this->main->delete('otp', $row);
            $response['row'] = $this->main->get($this->table, 'id', ['mobile' => $post['mobile']]);
            $response['error'] = FALSE;
            $response['message'] ="OTP Checked Successfully.";
            echoRespnse(200, $response);
        }
        else 
        {
            $response["error"] = TRUE;
            $response['message'] = "OTP Not Checked. Please Try Again.";
            echoRespnse(400, $response);
        }
    }

    public function signup()
    {
        post();
        verifyRequiredParams(['mobile', 'otp', 'fullname', 'password', 'email']);
        $post = [
            'mobile'         => $this->input->post('mobile'),
            'otp'            => $this->input->post('otp'),
            'created_at >= ' => date('Y-m-d H:i:s', strtotime('-5 minutes'))
        ];

        $row = $this->main->get('otp', 'mobile, otp', $post);

        if($row)
        {
            $this->main->delete('otp', $row);
            $user = $this->main->get($this->table, 'id', ['mobile' => $post['mobile'], 'is_deleted' => 0]);
            
            $insert = [
                'fullname' => $this->input->post('fullname'),
                'mobile'   => $this->input->post('mobile'),
                'email'    => $this->input->post('email'),
                'password' => my_crypt($this->input->post('password'))
            ];

            if ($user) 
                $this->main->update(['id' => $user['id']], $insert, $this->table);
            else
                $this->main->add($insert, $this->table);

            $response['error'] = FALSE;
            $response['message'] ="Signup Successful.";
            echoRespnse(200, $response);
        }
        else 
        {
            $response["error"] = TRUE;
            $response['message'] = "Signup Not Successful. Please Try Again.";
            echoRespnse(400, $response);
        }
    }

    public function login()
    {
        post();
        verifyRequiredParams(['mobile', 'password']);
        $post = [
            'mobile'     => $this->input->post('mobile'),
            'password'   => my_crypt($this->input->post('password')),
            'is_deleted' => 0
        ];

        if($row = $this->main->get($this->table, 'id, fullname, mobile, email, frame', $post))
        {
            if($row['frame'] != 'No Frame') {
                foreach (explode(',', $row['frame']) as $k => $v) 
                    $frames[$k]['img'] = assets('images/frame/').$v;
                $row['frame'] = $frames;
            }else
                $row['frame'] = [];
                
            $response['row'] = $row;
            $response['error'] = FALSE;
            $response['message'] ="Login Successful.";
            echoRespnse(200, $response);
        }
        else 
        {
            $response["error"] = TRUE;
            $response['message'] = "Login Not Successful. Please Try Again.";
            echoRespnse(400, $response);
        }
    }

    public function profile()
    {
        get();
        $api = authenticate($this->table);

        if($row = $this->main->get($this->table, 'id, fullname, mobile, email, frame', ['id' => $api]))
        {
            if($row['frame'] != 'No Frame') {
                foreach (explode(',', $row['frame']) as $k => $v) 
                    $frames[$k]['img'] = assets('images/frame/').$v;
                $row['frame'] = $frames;
            }else
                $row['frame'] = [];

            $response['row'] = $row;
            $response['error'] = FALSE;
            $response['message'] ="Profile Successful.";
            echoRespnse(200, $response);
        }
        else 
        {
            $response["error"] = TRUE;
            $response['message'] = "Profile Not Successful. Please Try Again.";
            echoRespnse(400, $response);
        }
    }

    public function updateProfile()
    {
        post();
        $api = authenticate($this->table);
        verifyRequiredParams(['mobile', 'fullname', 'email']);
        
        $post = [
            'fullname' => $this->input->post('fullname'),
            'mobile'   => $this->input->post('mobile'),
            'email'    => $this->input->post('email')
        ];

        if ($this->main->get($this->table, 'id', ['id != ' => $api, 'is_deleted' => 0, 'mobile' => $post['mobile']])) 
        {
            $response["error"] = TRUE;
            $response['message'] = "Mobile Already Exist.";
            echoRespnse(400, $response);
        }

        if ($this->input->post('password'))
            $post['password'] = my_crypt($this->input->post('password'));

        if($this->main->update(['id' => $api], $post, $this->table))
        {
            $response['row'] = $post;
            $response['error'] = FALSE;
            $response['message'] ="Profile Updated.";
            echoRespnse(200, $response);
        }
        else 
        {
            $response["error"] = TRUE;
            $response['message'] = "Profile Not Updated. Please Try Again.";
            echoRespnse(400, $response);
        }
    }

    public function upcoming()
    {
        get();
        $api = authenticate($this->table);

        if($row = $this->main->getall('upcoming', 'id, event, CONCAT("'.assets('images/event/').'", image) image, event_date', ['event_date >= ' => date('Y-m-d'), 'is_deleted' => 0]))
        {
            $response['row'] = $row;
            $response['error'] = FALSE;
            $response['message'] ="Upcoming Events Successful.";
            echoRespnse(200, $response);
        }
        else 
        {
            $response["error"] = TRUE;
            $response['message'] = "Upcoming Events Not Successful. Please Try Again.";
            echoRespnse(400, $response);
        }
    }

    public function upcomingSingle()
    {
        get();
        $api = authenticate($this->table);
        verifyRequiredParams(['id']);
        $id = $this->input->get('id');

        if($row = $this->main->get('upcoming', 'multi_image', ['id' => $id]))
        {
            if ($row['multi_image']) {
                foreach (explode(",", $row['multi_image']) as $k => $v) 
                    $images[$k]['image'] = assets('images/event/').$v;
            }else
                $images = [];

            $row['multi_image'] = $images;
            $response['row'] = $row;
            $response['error'] = FALSE;
            $response['message'] ="Upcoming Events Successful.";
            echoRespnse(200, $response);
        }
        else 
        {
            $response["error"] = TRUE;
            $response['message'] = "Upcoming Events Not Successful. Please Try Again.";
            echoRespnse(400, $response);
        }
    }

    public function category()
    {
        get();
        $api = authenticate($this->table);

        if($row = $this->main->getall('category', 'id, cat_name, CONCAT("'.assets('images/category/').'", image) image', ['is_deleted' => 0]))
        {
            $response['row'] = $row;
            $response['error'] = FALSE;
            $response['message'] ="Category List Successful.";
            echoRespnse(200, $response);
        }
        else 
        {
            $response["error"] = TRUE;
            $response['message'] = "Category List Not Successful. Please Try Again.";
            echoRespnse(400, $response);
        }
    }

    public function categorySingle()
    {
        get();
        $api = authenticate($this->table);
        verifyRequiredParams(['id']);
        $id = $this->input->get('id');

        if($row = $this->main->get('category', 'multi_image', ['id' => $id]))
        {
            if ($row['multi_image']) {
                foreach (explode(",", $row['multi_image']) as $k => $v) 
                    $images[$k]['image'] = assets('images/category/').$v;
            }else
                $images = [];

            $row['multi_image'] = $images;
            $response['row'] = $row;
            $response['error'] = FALSE;
            $response['message'] ="Category Successful.";
            echoRespnse(200, $response);
        }
        else 
        {
            $response["error"] = TRUE;
            $response['message'] = "Category Not Successful. Please Try Again.";
            echoRespnse(400, $response);
        }
    }

    public function updatePassword()
    {
        post();
        $api = authenticate($this->table);
        verifyRequiredParams(['password']);
        
        $post['password'] = my_crypt($this->input->post('password'));

        if($this->main->update(['id' => $api], $post, $this->table))
        {
            $user = $this->main->get($this->table, 'id, fullname, mobile, email, frame', ['id' => $api]);
            $user['frame'] = ($user['frame'] != 'No Frame') ? assets('images/frame/').$user['frame'] : $user['frame'];
            $response['row'] = $user;
            $response['error'] = FALSE;
            $response['message'] ="Password Updated.";
            echoRespnse(200, $response);
        }
        else 
        {
            $response["error"] = TRUE;
            $response['message'] = "Password Not Updated. Please Try Again.";
            echoRespnse(400, $response);
        }
    }

    public function notification()
    {
        get();
        $api = authenticate($this->table);

        if($row = $this->main->getall('upcoming', 'id, event, CONCAT("'.assets('images/event/').'", image) image, event_date', ['event_date = ' => date('Y-m-d', strtotime('+1 days')), 'is_deleted' => 0]))
        {
            $response['row'] = $row;
            $response['error'] = FALSE;
            $response['message'] ="Notification Successful.";
            echoRespnse(200, $response);
        }
        else 
        {
            $response["error"] = TRUE;
            $response['message'] = "Notification Not Successful. Please Try Again.";
            echoRespnse(400, $response);
        }
    }
}