<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Banner extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
    }

	private $name = 'banner';
    private $title = 'banner';
    protected $redirect = "banner";
    protected $table = "banner";

	public function index()
	{
        $data['name'] = $this->name;
		$data['title'] = $this->title;
        $data['url'] = $this->redirect;
        $data['dataTables'] = TRUE;
        $this->template->load(admin('template'), $this->redirect.'/home', $data);
	}

	public function get()
    {
        $fetch_data = $this->main->make_datatables(admin('banner_model'));
        $sr = $_POST['start'] + 1;
        $data = array();

        foreach($fetch_data as $row)
        {  
            $sub_array = array();
            $sub_array[] = $sr;
            $sub_array[] = img(['src' => assets($row->banner), 'height' => 100]);
            
            $action = '<div class="ml-0 table-display row">';
            
            $action .= form_open($this->redirect.'/delete', ['id' => e_id($row->id)], ['id' => e_id($row->id)]).form_button([ 'content' => '<i class="fas fa-trash"></i>','type'  => 'button','class' => 'btn btn-outline-danger', 'onclick' => "remove(".e_id($row->id).")"]).form_close();

            $action .= '</div>';
            $sub_array[] = $action;

            $data[] = $sub_array;
            $sr++;
        }

        $output = array(  
            "draw"             => intval($_POST["draw"]),  
            "recordsTotal"     => $this->main->count($this->table, ['id != ' => 0]),
            "recordsFiltered"  => $this->main->get_filtered_data(admin('banner_model')),
            "data"             => $data
        );
        
        echo json_encode($output);
    }

    public function add()
    {
        if (empty($_FILES['image']['name'])) {
            flashMsg(
                0, '', 'Please Select Banner Image.', $this->redirect
            );
        }else{
            $image = $this->image_upload();
            if (!$image['upload']) {
                flashMsg(
                    0, '', strip_tags($this->upload->display_errors()), $this->redirect
                );
            }else{
                $post = [
                    "banner"    => $image['success']
                ];

                $id = $this->main->add($post, $this->table);

                flashMsg(
                    $id, ucwords($this->name).' Added Successfully.', ucwords($this->name).' Not Added, Please Try Again.', $this->redirect
                );
            }
        }
    }

    public function delete()
    {
        $id = d_id($this->input->post('id'));
        $unlink = assets('images/banner/').$this->main->check($this->table, ['id' => $id], 'banner');
        if ($unlink && file_exists($unlink))
            unlink($unlink);

        $bId = $this->main->delete($this->table, ['id' => $id]);

        flashMsg(
            $bId, ucwords($this->name).' Deleted Successfully.', ucwords($this->name).' Not Deleted, Please Try Again.', $this->redirect
        );
    }

    protected function image_upload($unlink='')
    {
        $config = [
                'upload_path'      => "./assets/images/banner/",
                'allowed_types'    => 'jpg|jpeg|png',
                'file_name'        => rand(1000, 9999),
                'file_ext_tolower' => TRUE
            ];

        $this->upload->initialize($config);
        
        if ($unlink && file_exists($config['upload_path'].$unlink))
            unlink($config['upload_path'].$unlink);

        if (!$this->upload->do_upload("image")) { 
            $return = [
                'upload'=> false
            ];
        }else{
            $return = [
                'upload'  => true,
                'success' => $this->upload->data("file_name")
            ];
        }
        
        return $return;
    }
}