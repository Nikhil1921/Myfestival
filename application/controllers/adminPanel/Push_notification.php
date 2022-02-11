<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Push_notification extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    private $name = 'push_notification';
    private $title = 'push notification';
    private $table = "push_notification";
    protected $redirect = "push-notification";

    public function index()
    {
        verify_access($this->name, 'list');
        $data['name'] = $this->name;
        $data['title'] = $this->title;
        $data['url'] = $this->redirect;
        $data['dataTables'] = TRUE;
        $this->template->load(admin('template'), $this->redirect.'/home', $data);
    }

    public function get()
    {
        $fetch_data = $this->main->make_datatables(admin('push_notification_model'));
        $sr = $_POST['start'] + 1;
        $data = array();

        foreach($fetch_data as $row)  
        {  
            $sub_array = array();
            $sub_array[] = $sr;
            $sub_array[] = ucwords($row->notification);
            $sub_array[] = img(['src' => 'assets/images/push-notification/'.$row->image, 'height' => 50, 'width' => 50]);
            
            $action = '<div class="ml-0 table-display row">';
            
            if (check_access($this->name, 'list'))
                $action .= anchor($this->redirect.'/view/'.e_id($row->id), '<i class="fa fa-eye"></i>', 'class="btn btn-outline-info mr-2"');
            if (check_access($this->name, 'update'))
                $action .= anchor($this->redirect.'/update/'.e_id($row->id), '<i class="fa fa-edit"></i>', 'class="btn btn-outline-primary mr-2"');
            if (check_access($this->name, 'delete'))
                $action .= form_open($this->redirect.'/delete', ['id' => e_id($row->id)], ['id' => e_id($row->id)]).form_button([ 'content' => '<i class="fas fa-trash"></i>','type'  => 'button','class' => 'btn btn-outline-danger', 'onclick' => "remove(".e_id($row->id).")"]).form_close();

            $action .= '</div>';
            
            $sub_array[] = $action;
            
            $data[] = $sub_array;
            $sr++;
        }
        
        $csrf_name = $this->security->get_csrf_token_name();
        $csrf_hash = $this->security->get_csrf_hash();

        $output = array(  
            "draw"              => intval($_POST["draw"]),  
            "recordsTotal"      => $this->main->count($this->table, ['is_deleted' => 0]),
            "recordsFiltered"   => $this->main->get_filtered_data(admin('push_notification_model')),
            "data"              => $data,
            $csrf_name          => $csrf_hash
        );
        
        echo json_encode($output);
    }

    public function add()
    {
        verify_access($this->name, 'add');
        $this->form_validation->set_rules($this->validate);
        if ($this->form_validation->run() == FALSE)
        {
            $data['name'] = $this->name;
            $data['title'] = $this->title;
            $data['operation'] = "add";
            $data['url'] = $this->redirect;
            
            return $this->template->load(admin('template'), $this->redirect.'/add', $data);
        }
        else
        {
            $image = $this->image_upload();

            if (!$image['upload']) {
                $data['name'] = $this->name;
                $data['title'] = $this->title;
                $data['operation'] = "add";
                $data['url'] = $this->redirect;
                $data['inputmask'] = TRUE;
                $this->session->set_flashdata('error', $image['error']);
                return $this->template->load(admin('template'), $this->redirect.'/add', $data);
            }else{

                $post = [
                    'notification' => $this->input->post('notification'),
                    'details'      => $this->input->post('details'),
                    'image'        => $image['success']
                ];
                
                $id = $this->main->add($post, $this->table);

                if ($id) $this->send_notification($post['notification'], $post['details'], "assets/images/push-notification/".$image['success']);

                flashMsg($id, ucwords($this->title)." Added Successfully.", ucwords($this->title)." Not Added. Try again.", $this->redirect);
            }
        }
    }

    public function view($id)
    {
        verify_access($this->name, 'list');
        $data['name'] = $this->name;
        $data['title'] = $this->title;
        $data['operation'] = "view";
        $data['url'] = $this->redirect;
        $data['data'] = $this->main->get($this->table, 'notification, details, image', ['id' => d_id($id)]);

        if ($data['data']) 
            return $this->template->load(admin('template'), $this->redirect.'/view', $data);
        else
            return $this->error_404();
    }

    public function edit($id)
    {
        verify_access($this->name, 'update');
        $data['name'] = $this->name;
        $data['id'] = $id;
        $data['title'] = $this->title;
        $data['operation'] = "update";
        $data['url'] = $this->redirect;
        $data['data'] = $this->main->get($this->table, 'id, notification, details, image', ['id' => d_id($id)]);
        
        if ($data['data']) 
        {
            $this->session->set_flashdata('updateId', $id);
            return $this->template->load(admin('template'), $this->redirect.'/update', $data);
        }
        else
            return $this->error_404();
    }

    public function update($id)
    {
        verify_access($this->name, 'update');
        $this->form_validation->set_rules($this->validate);
        
        if ($this->form_validation->run() == FALSE)
        {
            $this->edit($id);
        }
        else
        {
            $updateId = $this->session->updateId;
            
            $image = $this->image_upload($this->input->post('image'));

            if (!$image['upload']) {
                $this->session->set_flashdata('error', strip_tags($this->upload->display_errors()));
                $this->edit($id);
            }else{
                $post = [
                    'notification' => $this->input->post('notification'),
                    'details'      => $this->input->post('details'),
                    'image'        => $image['success']
                ];
                
                $id = $this->main->update(['id' => d_id($updateId)], $post, $this->table);

                if ($id) $this->send_notification($post['notification'], $post['details'], "assets/images/push-notification/".$image['success']);

                flashMsg($id, ucwords($this->title)." Updated Successfully.", ucwords($this->title)." Not Updated. Try again.", $this->redirect);
            }
        }
    }

    public function upload($id)
    {
        verify_access($this->name, 'upload');
        if ($this->input->server('REQUEST_METHOD') === 'GET') 
        {
            $data['name'] = $this->name;
            $data['title'] = $this->title.' images';
            $data['operation'] = "upload";
            $data['url'] = $this->redirect;
            $data['id'] = $id;
            $data['showImages'] = true;
            $data['dataTables'] = true;
            $data['data'] = $this->main->get($this->table, 'multi_image', ['id' => d_id($id)]);
        
            if ($data['data']) 
                return $this->template->load(admin('template'), $this->redirect.'/upload', $data);
            else
                return $this->error_404();
        }else{
            $this->load->helper('string');
            $config = [
                'upload_path'      => "assets/images/push-notification/",
                'allowed_types'    => 'jpg|jpeg|png',
                'file_name'        => random_string('nozero', 5),
                'file_ext_tolower' => TRUE
            ];

            $this->upload->initialize($config);
            
            if (!$this->upload->do_upload("image")) { 
                $return = [
                    'upload' => false,
                    'error'  => strip_tags($this->upload->display_errors())
                ];
            }else{
                $images = $this->main->check($this->table, ['id' => d_id($id)], 'multi_image');
                if (!$images) 
                    $images[] = $this->upload->data("file_name");
                else{
                    $images = explode(',', $images);
                    array_push($images, $this->upload->data("file_name"));
                }
                $this->main->update(['id' => d_id($id)], ['multi_image' => implode(",", $images)], $this->table);

                $return = [
                    'upload'  => true,
                    'success' => "Image uploaded.",
                    'images'  => $images
                ];
            }

            echo json_encode($return);
        }
    }

    public function delete()
    {
        verify_access($this->name, 'delete');
        $id = $this->main->update(['id' => d_id($this->input->post('id'))], ['is_deleted' => 1], $this->table);

        flashMsg($id, ucwords($this->title)." Deleted Successfully.", ucwords($this->title)." Not Deleted. Try again.", $this->redirect);
    }

    protected $validate = [
        [
            'field' => 'notification',
            'label' => 'Notification',
            'rules' => 'required',
            'errors' => [
                'required' => "%s is Required"
            ]
        ],
        [
            'field' => 'details',
            'label' => 'Details',
            'rules' => 'required',
            'errors' => [
                'required' => "%s is Required"
            ]
        ]
    ];

    protected function image_upload($unlink='')
    {
        if (empty($_FILES['image']['name']) && $unlink) 
            $return = [
                'upload'  => true,
                'success' => $unlink
            ];
        else{
            if (empty($_FILES['image']['name']) && !$unlink) 
                $return = [
                    'upload' => false,
                    'error'  => "Please select image to upload"
                ];
            else{
                $config = [
                    'upload_path'      => "assets/images/push-notification/",
                    'allowed_types'    => 'jpg|jpeg|png',
                    'file_name'        => rand(1000, 9999),
                    'file_ext_tolower' => TRUE
                ];

                $this->upload->initialize($config);
                
                if (!$this->upload->do_upload("image")) { 
                    $return = [
                        'upload' => false,
                        'error'  => strip_tags($this->upload->display_errors())
                    ];
                }else{
                    if ($unlink && file_exists($config['upload_path'].$unlink))
                        unlink($config['upload_path'].$unlink);

                    $return = [
                        'upload'  => true,
                        'success' => $this->upload->data("file_name")
                    ];
                }
            }
        }

        return $return;
    }

    protected function send_notification($title, $body, $image)
    {
        foreach ($this->main->getall('users', 'push_token', ['push_token' != NULL]) as $token)
            $send[] = $token['token'];
        
        $url = "https://fcm.googleapis.com/fcm/send";
        $serverKey = 'AAAA3t59HRQ:APA91bGeSIE1HbGaLe6JcgFkUZz403GFJCe2AvfuurH1B4xWJ46r-t-g7n_yQ2vm4jFSiHhcF2wthGWFLOF2V8EJxIUHZwzsLItg59MYc3U-Hdi59TXeHXeTCoBcTlh1vKkt54ymZZvd';
        
        $notification = array('title' =>$title , 'body' => $body, 'sound' => 'default', 'badge' => '1', 'image' => base_url($image));
        $arrayToSend = array('to' => implode(', ', $send), 'notification' => $notification, 'priority'=>'high');
        $json = json_encode($arrayToSend);

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: key='. $serverKey;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_exec($ch);
        curl_close($ch);
        unset($arrayToSend);
        return;
    }
}