<?php if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
class start extends CI_Controller {
    public function index() {
        $date = array (
                "name" => "好奇",
                  "title" => "手藝"
        );
        $this->load->view ( 'start_view', $date );
    }
}