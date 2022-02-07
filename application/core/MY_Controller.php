<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        if ((!empty($this->session->adminId)))
            $this->id = $this->session->adminId;
        else
            return redirect(admin('login'));
        
        $this->redirect = admin($this->redirect);
        $this->role = $this->session->role;
    }

    public function error_404()
    {
        $this->load->view('error_404');
    }
}
