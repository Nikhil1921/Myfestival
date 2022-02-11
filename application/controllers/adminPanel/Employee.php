<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Employee extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
    }

	private $name = 'employee';
    private $title = 'employee';
    private $table = "admins";
    protected $redirect = "employee";

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
        $fetch_data = $this->main->make_datatables(admin('admins_model'));
        $sr = $_POST['start'] + 1;
        $data = array();

        foreach($fetch_data as $row)  
        {  
            $sub_array = array();
            $sub_array[] = $sr;
            $sub_array[] = ucwords($row->name);
            $sub_array[] = $row->mobile;
            $sub_array[] = $row->email;

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
            "recordsTotal"      => $this->main->count($this->table, ['role' => 'Employee', 'is_deleted' => 0]),
            "recordsFiltered"   => $this->main->get_filtered_data(admin('admins_model')),
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
                'name'        => $this->input->post('name'),
                'mobile'      => $this->input->post('mobile'),
                'email'       => $this->input->post('email'),
                'password'    => my_crypt($this->input->post('password')),
                'otp'         => 1234,
                'last_update' => date('Y-m-d H:i:s'),
                'role'        => 'Employee',
            ];
            
            $id = $this->main->add($post, $this->table);

            flashMsg($id, ucwords($this->title)." Added Successfully.", ucwords($this->title)." Not Added. Try again.", $this->redirect);
         
        }
	}

	public function view($id)
    {
        verify_access($this->name, 'list');
        $data['name'] = $this->name;
        $data['title'] = $this->title;
        $data['operation'] = "view";
        $data['url'] = $this->redirect;
        $data['data'] = $this->main->get($this->table, 'name, mobile, email', ['id' => d_id($id)]);

        if ($data['data']) 
            return $this->template->load(admin('template'), $this->redirect.'/view', $data);
        else
            return $this->error_404();
    }

    public function update($id)
    {
        verify_access($this->name, 'update');
        $this->form_validation->set_rules($this->validate);
        
        if ($this->form_validation->run() == FALSE)
        {
            $data['name'] = $this->name;
            $data['id'] = $id;
            $data['title'] = $this->title;
            $data['operation'] = "update";
            
            $data['url'] = $this->redirect;
            $data['data'] = $this->main->get($this->table, 'id, name, mobile, email', ['id' => d_id($id)]);
            
            if ($data['data']) 
            {
                $this->session->set_flashdata('updateId', $id);
                return $this->template->load(admin('template'), $this->redirect.'/update', $data);
            }
            else
                return $this->error_404();
        }
        else
        {
            $updateId = $this->session->updateId;

            $post = [
                'name'        => $this->input->post('name'),
                'mobile'      => $this->input->post('mobile'),
                'email'       => $this->input->post('email')
            ];
            
            if ($this->input->post('password')) 
                $post['password'] = my_crypt($this->input->post('password'));
            
            $id = $this->main->update(['id' => d_id($updateId)], $post, $this->table);

            flashMsg($id, ucwords($this->title)." Updated Successfully.", ucwords($this->title)." Not Updated. Try again.", $this->redirect);
            
        }
    }

	public function delete()
	{
        verify_access($this->name, 'delete');
        $id = $this->main->update(['id' => d_id($this->input->post('id'))], ['is_deleted' => 1], $this->table);

		flashMsg($id, ucwords($this->title)." Deleted Successfully.", ucwords($this->title)." Not Deleted. Try again.", $this->redirect);
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
            'field' => 'name',
            'label' => 'Name',
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
}