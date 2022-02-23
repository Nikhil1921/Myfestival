<?php defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Users extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
    }

	private $name = 'users';
    private $title = 'Users';
    private $table = "users";
    protected $redirect = "users";

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
        $fetch_data = $this->main->make_datatables(admin('users_model'));
        $sr = $_POST['start'] + 1;
        $data = array();

        foreach($fetch_data as $row)  
        {  
            $sub_array = array();
            $sub_array[] = $sr;
            $sub_array[] = ucwords($row->fullname);
            $sub_array[] = $row->mobile;
            $sub_array[] = $row->email;
            if (check_access($this->name, 'upload'))
                $sub_array[] = $row->user_type;
            if (check_access($this->name, 'upload'))
                $sub_array[] = '<input type="checkbox" name="user_type" '.($row->user_type == 'Paid' ? "checked" : "").' data-bootstrap-switch data-off-color="danger" data-on-color="success" onchange="change_user_type('.e_id($row->id).', \''.($row->user_type == 'Paid' ? "Unpaid" : "Paid").'\')">';

            $action = '<div class="ml-0 table-display row">';
            
            if (check_access($this->name, 'list'))
                $action .= anchor($this->redirect.'/view/'.e_id($row->id), '<i class="fa fa-eye"></i>', 'class="btn btn-outline-info mr-2"');
            if (check_access($this->name, 'upload') && $row->user_type == 'Paid')
                $action .= anchor($this->redirect.'/upload/'.e_id($row->id), '<i class="fa fa-image"></i>', 'class="btn btn-outline-secondary mr-2"');
            if (check_access($this->name, 'update'))
                $action .= anchor($this->redirect.'/update/'.e_id($row->id), '<i class="fa fa-edit"></i>', 'class="btn btn-outline-primary mr-2"');
            if (check_access($this->name, 'delete') && $row->user_type != 'Paid')
                $action .= form_open($this->redirect.'/delete', ['id' => e_id($row->id)], ['id' => e_id($row->id)]).form_button([ 'content' => '<i class="fas fa-trash"></i>','type'  => 'button','class' => 'btn btn-outline-danger', 'onclick' => "remove(".e_id($row->id).")"]).form_close();
            
            $action .= '</div>';
            
            $sub_array[] = $action;

            $data[] = $sub_array;  
            $sr++;
        }
        
        $csrf_name = $this->security->get_csrf_token_name();
        $csrf_hash = $this->security->get_csrf_hash();
        
        $where = ['is_deleted' => 0];
        
        if ($this->session->role != 'Admin') 
            $where['created_by'] = $this->session->adminId;
            
        $output = array(  
            "draw"              => intval($_POST["draw"]),  
            "recordsTotal"      => $this->main->count($this->table, $where),
            "recordsFiltered"   => $this->main->get_filtered_data(admin('users_model')),
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
            $post = [
                'fullname'    => $this->input->post('fullname'),
                'mobile'      => $this->input->post('mobile'),
                'email'       => $this->input->post('email'),
                'password'    => my_crypt($this->input->post('password')),
                'created_by'  => $this->session->adminId
            ];
            
            $id = $this->main->add($post, $this->table);

            flashMsg($id, ucwords($this->title)." Added Successfully.", ucwords($this->title)." Not Added. Try again.", $this->redirect);
            /* $image = $this->image_upload();

            if (!$image['upload']) {
                $data['name'] = $this->name;
                $data['title'] = $this->title;
                $data['operation'] = "add";
                $data['url'] = $this->redirect;
                $this->session->set_flashdata('error', strip_tags($this->upload->display_errors()));
                return $this->template->load(admin('template'), $this->redirect.'/add', $data);
            }else{

                $post = [
                    'fullname'    => $this->input->post('fullname'),
                    'mobile'      => $this->input->post('mobile'),
                    'email'       => $this->input->post('email'),
                    'password'    => my_crypt($this->input->post('password')),
                    'created_by'  => $this->session->adminId,
                    'frame'       => $image['success']
                ];
                
                $id = $this->main->add($post, $this->table);

                flashMsg($id, ucwords($this->title)." Added Successfully.", ucwords($this->title)." Not Added. Try again.", $this->redirect);
            } */
        }
	}

	public function view($id)
    {
        verify_access($this->name, 'list');
        $data['name'] = $this->name;
        $data['title'] = $this->title;
        $data['operation'] = "view";
        $data['url'] = $this->redirect;
        $data['data'] = $this->main->get($this->table, 'fullname, mobile, email, frame', ['id' => d_id($id)]);

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
        $data['data'] = $this->main->get($this->table, 'id, fullname, mobile, email, frame', ['id' => d_id($id)]);
		
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
            $post = [
                'fullname'    => $this->input->post('fullname'),
                'mobile'      => $this->input->post('mobile'),
                'email'       => $this->input->post('email')
            ];
            
            if ($this->input->post('password')) 
                $post['password'] = my_crypt($this->input->post('password'));
            
            $id = $this->main->update(['id' => d_id($updateId)], $post, $this->table);

            flashMsg($id, ucwords($this->title)." Updated Successfully.", ucwords($this->title)." Not Updated. Try again.", $this->redirect);
            
            /* $image = $this->image_upload($this->input->post('frame'));

            if (!$image['upload']) {
                $this->session->set_flashdata('error', strip_tags($this->upload->display_errors()));
                $this->edit($id);
            }else{

                $post = [
                    'fullname'    => $this->input->post('fullname'),
                    'mobile'      => $this->input->post('mobile'),
                    'email'       => $this->input->post('email'),
                    'frame'       => $image['success']
                ];
                
                if ($this->input->post('password')) 
                    $post['password'] = my_crypt($this->input->post('password'));
                
                $id = $this->main->update(['id' => d_id($updateId)], $post, $this->table);

                flashMsg($id, ucwords($this->title)." Updated Successfully.", ucwords($this->title)." Not Updated. Try again.", $this->redirect);
            } */
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
            $data['data'] = $this->main->get($this->table, 'frame', ['id' => d_id($id)]);
        
            if ($data['data']) 
                return $this->template->load(admin('template'), $this->redirect.'/upload', $data);
            else
                return $this->error_404();
        }else{
            $this->load->helper('string');
            $config = [
                'upload_path'      => "./assets/images/frame/",
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
                $images = $this->main->check($this->table, ['id' => d_id($id)], 'frame');
                if (!$images || $images == 'No Frame') 
                    $image[] = $this->upload->data("file_name");
                else{
                    $image = explode(',', $images);
                    array_push($image, $this->upload->data("file_name"));
                }
                $this->main->update(['id' => d_id($id)], ['frame' => implode(",", $image)], $this->table);

                $return = [
                    'upload'  => true,
                    'success' => "Image uploaded.",
                    'images'  => $image
                ];
            }

            echo json_encode($return);
        }
    }

    public function showImages($id)
    {
        $images = $this->main->check($this->table, ['id' => d_id($id)], 'frame');
        if ($images && $images != 'No Frame') {
            $images = explode(",", $images);
            foreach ($images as $k => $v) {
                $image[$k]['url'] = assets('images/frame/');
                $image[$k]['image'] = $v;
            }
        }else
            $image = '';

        echo json_encode(['images'  => $image]);
    }

    public function removeImage()
    {
        $id = $this->input->post('id');
        $img = $this->input->post('img');
        $images = $this->main->check($this->table, ['id' => d_id($id)], 'frame');
        $images = explode(",", $images);
        $key = array_search($img, $images);
        unset($images[$key]);
        if (file_exists("./assets/images/frame/".$img))
            unlink("./assets/images/frame/".$img);

        $imgs = ($images) ? implode(",", $images) : 'No Frame';
        $this->main->update(['id' => d_id($id)], ['frame' => $imgs], $this->table);
        echo json_encode(['error' => false, 'success' => 'Image removed.']);
    }

	public function delete()
	{
        verify_access($this->name, 'delete');
        $id = $this->main->update(['id' => d_id($this->input->post('id'))], ['is_deleted' => 1], $this->table);

		flashMsg($id, ucwords($this->title)." Deleted Successfully.", ucwords($this->title)." Not Deleted. Try again.", $this->redirect);
	}

    public function import()
    {
        verify_access($this->name, 'import');
        if(!empty($_FILES["import"]["name"])):
            set_time_limit(0);
            $object = \PhpOffice\PhpSpreadsheet\IOFactory::load($_FILES["import"]["tmp_name"]);
            foreach($object->getWorksheetIterator() as $worksheet):
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();
                for($row=2; $row <= $highestRow; $row++):
                    $mobile = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    if (! $this->main->check($this->table, ['mobile' => $mobile], 'id')) {
                        $post[] = [
                            'fullname' 		=> $worksheet->getCellByColumnAndRow(1, $row)->getValue(),
                            'mobile' 		=> $mobile,
                            'password'      => my_crypt(123456),
                            'created_by'    => $this->session->adminId
                        ];
                    }
                endfor;
            endforeach;
            
            if ($post && $this->main->import_excel($post, $this->table))
                $response = [
                    'message' => "$this->title added.",
                    'status' => true
                ];
            else
                $response = [
                    'message' => "$this->title not added. Try again.",
                    'status' => false
                ];
        else:
            $response = [
                    'message' => "Select excel file to upload.",
                    'status' => false
                ];
        endif;

        flashMsg($response['status'], $response['message'], $response['message'], $this->redirect);
    }

    public function password_check($str)
    {   
        if (empty($this->session->updateId) && !$str)
        {
            $this->form_validation->set_message('password_check', '%s is Required');
            return FALSE;
        } else
            return TRUE;
    }

    protected $validate = [
        [
            'field' => 'fullname',
            'label' => 'Full Name',
            'rules' => 'required',
            'errors' => [
                'required' => "%s is Required"
            ]
        ],
        [
            'field' => 'mobile',
            'label' => 'Mobile',
            'rules' => 'required|numeric|exact_length[10]|callback_mobile_check',
            'errors' => [
                'required' => "%s is Required",
                'exact_length' => "%s is invalid",
                'numeric' => "%s is invalid"
            ]
        ],
        [
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'required|valid_email|callback_email_check',
            'errors' => [
                'required' => "%s is Required",
                'valid_email' => "%s is invalid",
            ]
        ],
        [
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'callback_password_check'
        ]
    ];

    public function mobile_check($str)
    {   
        $id = $this->session->updateId;
        
        if ((!empty($id) && $this->main->check($this->table, ['mobile' => $str, 'id != ' => d_id($id), 'is_deleted' => 0], 'id')) || (empty($id) && $this->main->check($this->table, ['mobile' => $str, 'is_deleted' => 0], 'id')))
        {
            $this->form_validation->set_message('mobile_check', 'The %s is already in use');
            return FALSE;
        } else
            return TRUE;
    }

    public function email_check($str)
    {   
        $id = $this->session->updateId;
        
        if ((!empty($id) && $this->main->check($this->table, ['email' => $str, 'id != ' => d_id($id), 'is_deleted' => 0], 'id')) || (empty($id) && $this->main->check($this->table, ['email' => $str, 'is_deleted' => 0], 'id')))
        {
            $this->form_validation->set_message('email_check', 'The %s is already in use');
            return FALSE;
        } else
            return TRUE;
    }

    protected function image_upload($unlink='')
    {
        if (empty($_FILES['frame']['name'])) {
            $return = [
                'upload'  => true,
                'success' => ($unlink) ? $unlink : 'No Frame'
            ];
        }else{
            $config = [
                'upload_path'      => "./assets/images/frame/",
                'allowed_types'    => 'jpg|jpeg|png',
                'file_name'        => rand(1000, 9999),
                'file_ext_tolower' => TRUE
            ];

            $this->upload->initialize($config);
            
            if (!$this->upload->do_upload("frame")) { 
                $return = [
                    'upload'=> false
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

        return $return;
    }

    public function change_user_type()
    {
        $id = d_id($this->input->post('id'));
        $user_type = $this->input->post('user_type');
        if ($this->main->update(['id' => $id], ['user_type' => $user_type], $this->table)) {
            $response = [
                'error' => false,
                'message' => "User status changed"
            ];    
        }else
            $response = [
                'error' => true,
                'message' => "User status not changed"
            ];
        
        die(json_encode($response));
    }
}