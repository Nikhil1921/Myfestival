<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    private $name = 'category';
    private $title = 'category';
    private $table = "category";
    protected $redirect = "category";

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
        $fetch_data = $this->main->make_datatables(admin('category_model'));
        $sr = $_POST['start'] + 1;
        $data = array();

        foreach($fetch_data as $row)  
        {  
            $sub_array = array();
            $sub_array[] = $sr;
            $sub_array[] = ucwords($row->cat_name);
            $sub_array[] = img(['src' => 'assets/images/category/'.$row->image, 'height' => 50, 'width' => 50]);
            
            $action = '<div class="ml-0 table-display row">';
            
            if (check_access($this->name, 'list'))
                $action .= anchor($this->redirect.'/view/'.e_id($row->id), '<i class="fa fa-eye"></i>', 'class="btn btn-outline-info mr-2"');
            if (check_access($this->name, 'upload'))
                $action .= anchor($this->redirect.'/upload/'.e_id($row->id), '<i class="fa fa-image"></i>', 'class="btn btn-outline-secondary mr-2"');
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
            "recordsFiltered"   => $this->main->get_filtered_data(admin('category_model')),
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
                    'cat_name' => $this->input->post('cat_name'),
                    'image'    => $image['success']
                ];
                
                $id = $this->main->add($post, $this->table);

                flashMsg($id, ucwords($this->title)." Added Successfully.", ucwords($this->title)." Not Added. Try again.", $this->redirect.'/upload/'.e_id($id));
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
        $data['data'] = $this->main->get($this->table, 'cat_name, image', ['id' => d_id($id)]);

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
        $data['data'] = $this->main->get($this->table, 'id, cat_name, image', ['id' => d_id($id)]);
        
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
                    'cat_name' => $this->input->post('cat_name'),
                    'image'    => $image['success']
                ];
                
                $id = $this->main->update(['id' => d_id($updateId)], $post, $this->table);

                flashMsg($id, ucwords($this->title)." Updated Successfully.", ucwords($this->title)." Not Updated. Try again.", $this->redirect.'/upload/'.$updateId);
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
                'upload_path'      => "./assets/images/category/",
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

    public function showImages($id)
    {
        $images = $this->main->check($this->table, ['id' => d_id($id)], 'multi_image');
        if ($images) {
            $images = explode(",", $images);
            foreach ($images as $k => $v) {
                $image[$k]['url'] = assets('images/category/');
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
        $images = $this->main->check($this->table, ['id' => d_id($id)], 'multi_image');
        $images = explode(",", $images);
        $key = array_search($img, $images);
        unset($images[$key]);
        if (file_exists("./assets/images/category/".$img))
            unlink("./assets/images/category/".$img);

        $imgs = ($images) ? implode(",", $images) : null;
        $this->main->update(['id' => d_id($id)], ['multi_image' => $imgs], $this->table);
        echo json_encode(['error' => false, 'success' => 'Image removed.']);
    }

    public function delete()
    {
        verify_access($this->name, 'delete');
        $id = $this->main->update(['id' => d_id($this->input->post('id'))], ['is_deleted' => 1], $this->table);

        flashMsg($id, ucwords($this->title)." Deleted Successfully.", ucwords($this->title)." Not Deleted. Try again.", $this->redirect);
    }

    protected $validate = [
        [
            'field' => 'cat_name',
            'label' => 'Category Name',
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
                    'upload_path'      => "./assets/images/category/",
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
}