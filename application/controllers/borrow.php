<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Borrow extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->load->model('item_model');
    $this->load->model('borrow_model');
    $this->load->model('settings_model');
    $this->load->model('inventory_model');
    if (!$this->session->userdata('log')) {
      redirect(base_url('login'));
    }
  }

  public function filter($txtfilterwarehouse=false,$txtfilterproject=false,$txtfilterstatus=false,$borrow_date=false,$user=false)
  {
    $data = array();
    $config = array();

    //$filter = $this->input->get();

    $txtfilterwarehouse = (htmlspecialchars($this->input->post('warehouse_id',TRUE))!="")?htmlspecialchars($this->input->post('warehouse_id',TRUE)):$txtfilterwarehouse;
    $user = (htmlspecialchars($this->input->post('taken_by_uid',TRUE))!="")?htmlspecialchars($this->input->post('taken_by_uid',TRUE)):'all';
    $borrow_date = (htmlspecialchars($this->input->post('borrow_date',TRUE))!="")?htmlspecialchars($this->input->post('borrow_date',TRUE)):'all';
    $txtfilterproject = (htmlspecialchars($this->input->post('project_uid',TRUE))!="")?htmlspecialchars($this->input->post('project_uid',TRUE)):$txtfilterproject;
    $txtfilterstatus = (htmlspecialchars($this->input->post('borrow_status',TRUE))!="")?htmlspecialchars($this->input->post('borrow_status',TRUE)):$txtfilterstatus;
    $objuser = $user;
    if ($user !== 'all') {
      $objuser = $this->borrow_model->get_user_by_fingerid($user)->employeename;
    }
    $data['user'] = $objuser;
    $data['warehouse'] = $txtfilterwarehouse;
    $data['user_id'] = $user;
    $data['borrow_date'] = $borrow_date;
    $data['project_filter'] = $txtfilterproject;
    $data['borrow_status'] = $txtfilterstatus;
    $config['full_tag_open'] = '<ul class="pagination">';
    $config['full_tag_close'] = '</ul>';
    $config['num_tag_open'] = '<li>';
    $config['num_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li class="active"><span>';
    $config['cur_tag_close'] = '<span class="sr-only">(current)</span></span></li>';
    $config['prev_tag_open'] = '<li>';
    $config['prev_tag_close'] = '</li>';
    $config['next_tag_open'] = '<li>';
    $config['next_tag_close'] = '</li>';
    $config['first_link'] = '&laquo;';
    $config['prev_link'] = '&lsaquo;';
    $config['last_link'] = '&raquo;';
    $config['next_link'] = '&rsaquo;';
    $config['first_tag_open'] = '<li>';
    $config['first_tag_close'] = '</li>';
    $config['last_tag_open'] = '<li>';
    $config['last_tag_close'] = '</li>';
    $data['borrow_total'] =  $this->borrow_model->count_borrow_filter($txtfilterwarehouse,$txtfilterproject,$txtfilterstatus,$user,$borrow_date);
    $config["base_url"] = base_url() . "borrow/filter/".$txtfilterwarehouse."/".$txtfilterproject."/".$txtfilterstatus."/".$borrow_date."/".$user."/";
    $config['total_rows'] = $data['borrow_total'];
    $config['per_page'] = '10';
    $config['uri_segment'] = '8';
    $this->pagination->initialize($config);

    $data["results"] = $this->borrow_model->fetch_data_borrow_filter($config["per_page"], $this->uri->segment(8),$txtfilterwarehouse,$txtfilterproject,$txtfilterstatus,$user,$borrow_date);

    $data['project'] = $this->settings_model->get_project();
    $data['warehouses'] = $this->settings_model->get_warehouses();

    $data['header'] = $this->load->view('header/head', '', TRUE);
    $data['navigation'] = $this->load->view('header/navigation', $data, TRUE);
    $data['content'] = $this->load->view('content/view_borrow', $data, TRUE);
    $data['footer'] = $this->load->view('footer/footer', '', TRUE);
    $this->load->view('main', $data);

  }

  public function index()
  {
    $data = array();
    $config = array();
    $config['full_tag_open'] = '<ul class="pagination">';
    $config['full_tag_close'] = '</ul>';
    $config['num_tag_open'] = '<li>';
    $config['num_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li class="active"><span>';
    $config['cur_tag_close'] = '<span class="sr-only">(current)</span></span></li>';
    $config['prev_tag_open'] = '<li>';
    $config['prev_tag_close'] = '</li>';
    $config['next_tag_open'] = '<li>';
    $config['next_tag_close'] = '</li>';
    $config['first_link'] = '&laquo;';
    $config['prev_link'] = '&lsaquo;';
    $config['last_link'] = '&raquo;';
    $config['next_link'] = '&rsaquo;';
    $config['first_tag_open'] = '<li>';
    $config['first_tag_close'] = '</li>';
    $config['last_tag_open'] = '<li>';
    $config['last_tag_close'] = '</li>';
    $data['borrow_total'] =  $this->borrow_model->count_borrow();
    $config["base_url"] = base_url() . "borrow/index";
    $config['total_rows'] = $data['borrow_total'];
    $config['per_page'] = '10';
    $config['uri_segment'] = '3';
    $this->pagination->initialize($config);

    $data["results"] = $this->borrow_model->fetch_data_borrow($config["per_page"], $this->uri->segment(3));
    $data['project'] = $this->settings_model->get_project();
    $data['warehouses'] = $this->settings_model->get_warehouses();

    $data['header'] = $this->load->view('header/head', '', TRUE);
    $data['navigation'] = $this->load->view('header/navigation', $data, TRUE);
    $data['content'] = $this->load->view('content/view_borrow', $data, TRUE);
    $data['footer'] = $this->load->view('footer/footer', '', TRUE);
    $this->load->view('main', $data);
  }

  public function add()
  {
    if($this->input->post()){
      $dataBorrow['warehouse_id'] =  $this->input->post('warehouse_id', TRUE);
      $dataBorrow['taken_by_uid'] =  $this->input->post('taken_by_uid', TRUE);
      $dataBorrow['project_uid'] =  $this->input->post('project_uid', TRUE);
      $dataBorrow['note'] =  $this->input->post('note', TRUE);
      $dataBorrow['borrow_status'] =  0;
      $dataBorrow['created_date'] =  date('Y-m-d');
      $dataBorrow['created_by'] =  $this->session->userdata('user_id');
      $dataBorrow['borrow_date'] = $this->input->post('borrow_date',TRUE);
      $dataBorrow['item_id'] = $this->input->post('item_master_id',TRUE);
      $dataBorrow['end_date'] = $this->input->post('item_end_date',TRUE);

      $inventory = $this->inventory_model->get_inventory_by_item($dataBorrow['item_id']);

      $item_borrow = $this->borrow_model->getBorrowedItemCheck($dataBorrow['taken_by_uid']);

      if ($inventory && $inventory->inventory_quantity != 0) {
        $dataItem['inventory_quantity'] = $inventory->inventory_quantity -  1;
        $this->inventory_model->update_inventory($dataItem,$dataBorrow['item_id']);
      }else {
        $message = '<div class="alert alert-danger">Item Out Stock!</div>';
        $this->session->set_flashdata('message', $message);
        redirect(base_url('borrow/add'));
      }

      $borrow_id = $this->borrow_model->saveBorrow($dataBorrow);
      if ($borrow_id) {
        //accesories
        $item_id = $this->input->post('item_id',true);
        $quantities = $this->input->post('quantities',true);
        $end_date = $this->input->post('end_date',true);

        for ($i=0; $i < sizeof($item_id); $i++) {
          $inventory = $this->inventory_model->get_inventory_by_item($item_id[$i]);
          if ($inventory && $inventory->inventory_quantity >= $quantities[$i]) {
            $tmp['item_id'] = $item_id[$i];
            $tmp['quantities'] = $quantities[$i];
            $tmp['end_date'] = $end_date[$i];
            $tmp['borrow_id'] = $borrow_id;
            $this->borrow_model->save_details($tmp);

            $dataItem['inventory_quantity'] = $inventory->inventory_quantity -  $quantities[$i];
            $this->inventory_model->update_inventory($dataItem,$item_id[$i]);
          }
        }
        //

        //software
        $software = $this->input->post('software',true);
        $description = $this->input->post('description',true);
        for ($i=0; $i < sizeof($software) ; $i++) {
          $soft['software_name'] = $software[$i];
          $soft['software_description'] = $description[$i];
          $soft['borrow_id'] = $borrow_id;
          $this->borrow_model->save_software($soft);
        }
        //
        $message = '<div class="alert alert-success">Success</div>';
        $this->session->set_flashdata('message', $message);
        redirect(base_url('borrow'));
      }else {
        $message = '<div class="alert alert-danger">Somethink Wrong!</div>';
        $this->session->set_flashdata('message', $message);
        redirect(base_url('borrow/add'));
      }
    }
    $data = array();
    $data['header'] = $this->load->view('header/head', '', TRUE);
    $data['navigation'] = $this->load->view('header/navigation', $data, TRUE);
    $data['item_accessories'] = $this->inventory_model->item_accessories();
    $data['items'] = $this->inventory_model->itemBorrow();
    $data['project'] = $this->settings_model->get_project();
    $data['warehouses'] = $this->settings_model->get_warehouses();
    $data['content'] = $this->load->view('forms/form_borrow', $data, TRUE);
    $data['footer'] = $this->load->view('footer/footer', '', TRUE);
    $this->load->view('main', $data);
  }

  public function delete($id)
  {
    if (!$this->session->userdata('role')) {
      $message = '<div class="alert alert-danger">You are not allowed to do this!</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('borrow'));
    }
    if (!$id) {
      $message = '<div class="alert alert-danger">Not Found!</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('borrow'));
    }
    $borrow = $this->borrow_model->get_view_borrow($id);
    if (sizeof($borrow) <= 0) {
      $message = '<div class="alert alert-danger">Not Found!</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('borrow'));
    }
    $borrow_details = $this->borrow_model->get_borrow_details($id);

    for ($i=0; $i < sizeof($borrow_details); $i++) {

      if ($borrow_details[$i]) {
        $borrowDetails = $this->borrow_model->get_borrow_details_byid($borrow_details[$i]->borrow_detail_id);
        $inventory = $this->inventory_model->get_inventory_by_item($borrowDetails->item_id);
        if ($inventory) {
          $returnQty = $borrowDetails->quantities - $borrowDetails->return_quantity;
          $dataReturn['return_date'] =  date('Y-m-d');
          $dataReturn['return_quantity'] =  $borrowDetails->return_quantity+$returnQty;
          $this->borrow_model->updateDetails($dataReturn,$borrow_details[$i]->borrow_detail_id[$i]);
          $dataInv['inventory_quantity'] = $inventory->inventory_quantity + $returnQty;
          $this->inventory_model->update_inventory($dataInv,$borrowDetails->item_id);
        }
      }
    }
    if ($borrow->item_id) {
      $dataReturnItem['item_return_date'] = date('Y-m-d');
      $this->borrow_model->updateBorrow($dataReturnItem,$id);
      //
      // //update inventory
      $inventory = $this->inventory_model->get_inventory_by_item($borrow->item_id);
      $dataInv['inventory_quantity'] = $inventory->inventory_quantity + 1;
      $this->inventory_model->update_inventory($dataInv,$borrow->item_id);
    }
    $this->borrow_model->return_borrow($id);

    if (!$this->borrow_model->delete($id)) {
      $message = '<div class="alert alert-danger">An ERROR occurred!</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('borrow'));
    } else {
      $message = '<div class="alert alert-success alert-dismissable">'
      . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
      . 'Deleted!'
      . '</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('borrow'));
    }

  }

  public function modify($id)
  {
    if (!$this->session->userdata('role')) {
      $message = '<div class="alert alert-danger">You are not allowed to do this!</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('borrow'));
    }
    if (!$id) {
      $message = '<div class="alert alert-danger">Not Found!</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('borrow'));
    }

    $data['borrow'] = $this->borrow_model->get_borrow($id);
    if (sizeof($data['borrow']) <= 0) {
      $message = '<div class="alert alert-danger">Not Found!</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('borrow'));
    }
    $data['borrow_details'] = $this->borrow_model->get_borrow_details($id);
    $data['software'] = $this->borrow_model->get_software_details($id);
    $data['header'] = $this->load->view('header/head', '', TRUE);
    $data['navigation'] = $this->load->view('header/navigation', $data, TRUE);
    $data['item_accessories'] = $this->inventory_model->item_accessories();
    $data['project'] = $this->settings_model->get_project();
    $data['warehouses'] = $this->settings_model->get_warehouses();
    $data['items'] = $this->inventory_model->itemBorrow();

    $data['content'] = $this->load->view('forms/form_borrow', $data, TRUE);
    $data['footer'] = $this->load->view('footer/footer', '', TRUE);
    $this->load->view('main', $data);
  }

  public function update($id)
  {
    if (!$this->session->userdata('role')) {
      $message = '<div class="alert alert-danger">You are not allowed to do this!</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('borrow'));
    }
    if (!$id) {
      $message = '<div class="alert alert-danger">Not Found!</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('borrow'));
    }
    $borrow = $this->borrow_model->get_borrow($id);
    if (sizeof($borrow) <= 0) {
      $message = '<div class="alert alert-danger">Not Found!</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('borrow'));
    }
    $dataBorrow['warehouse_id'] =  $this->input->post('warehouse_id', TRUE);
    $dataBorrow['taken_by_uid'] =  $this->input->post('taken_by_uid', TRUE);
    $dataBorrow['project_uid'] =  $this->input->post('project_uid', TRUE);
    $dataBorrow['project_uid'] =  $this->input->post('project_uid', TRUE);
    $dataBorrow['note'] =  $this->input->post('note', TRUE);
    $dataBorrow['borrow_status'] =  0;
    $dataBorrow['borrow_date'] = $this->input->post('borrow_date',TRUE);
    $dataBorrow['end_date'] = $this->input->post('item_end_date',TRUE);
    $dataBorrow['item_id'] = $this->input->post('item_master_id',TRUE);
    if ($borrow->item_id != $dataBorrow['item_id']) {
      $inventory = $this->inventory_model->get_inventory_by_item($borrow->item_id);

      $dataItem['inventory_quantity'] = $inventory->inventory_quantity +  1;
      $this->inventory_model->update_inventory($dataItem,$borrow->item_id);

      $inventory = $this->inventory_model->get_inventory_by_item($dataBorrow['item_id']);
      if ($inventory && $inventory->inventory_quantity != 0) {
        $dataItem['inventory_quantity'] = $inventory->inventory_quantity -  1;
        $this->inventory_model->update_inventory($dataItem,$dataBorrow['item_id']);
      }else {
        $message = '<div class="alert alert-danger">Item Out Stock!</div>';
        $this->session->set_flashdata('message', $message);
        redirect(base_url('borrow/add'));
      }
    }

    $borrow_id = $this->borrow_model->updateBorrow($dataBorrow,$id);

    if ($borrow_id) {
      $item_id = $this->input->post('item_id',true);
      $quantities = $this->input->post('quantities',true);
      $end_date = $this->input->post('end_date',true);
      $borrow_detail_id = $this->input->post('borrow_detail_id',true);

      $currentBorrowDetails = $this->borrow_model->getCurrentBorrowDetails($id);
      if (!empty($currentBorrowDetails))
      {
        foreach ($currentBorrowDetails as $currentBorrowDetail)
        {
          $currentRows[] = $currentBorrowDetail->item_id;      
        }
      }

      if (!empty($borrow_detail_id))
      {
        // update inventory before deleting
        $differentBorrowDetail = array_diff($currentRows, $item_id);

        if ($differentBorrowDetail){
          foreach ($differentBorrowDetail as $value){
            $getBorrowDetailQuantity = $this->borrow_model->getBorrowDetailsQuantityByID($value, $id);
            $getInventoryQuantity = $this->inventory_model->getInventoryQuantityByID(intval($value));
            $updateQuantity = $getInventoryQuantity->inventory_quantity + $getBorrowDetailQuantity;
            $this->inventory_model->updateInventoryModifyBorrow($value, $updateQuantity);
          }
        }
        
        //update
        $i=0;
        $detailsCount = 0;
        $tmpID = array();
        for ($i; $i < sizeof($borrow_detail_id); $i++) {
          if ($borrow_detail_id[$i]) {
            $borrowDetails = $this->borrow_model->get_borrow_details_byid($borrow_detail_id[$i]);
            echo "<br>";
            echo "borrow details:";
            var_dump($borrowDetails);
            $inventory = $this->inventory_model->get_inventory_by_item($borrowDetails->item_id);
            echo "<br>";
            echo "inventory:";
            var_dump($inventory);
            if ($inventory && $inventory->inventory_quantity >= $quantities[$i]) {
              $tmp['item_id'] = $item_id[$i];
              $tmp['quantities'] = $quantities[$i];
              $tmp['end_date'] = $end_date[$i];
              $this->borrow_model->updateDetails($tmp,$borrow_detail_id[$i]);
              $dataItem = array();
              $dif = $quantities[$i] - $borrowDetails->quantities;
              $dataItem['inventory_quantity'] = $inventory->inventory_quantity - $dif;
              $this->inventory_model->update_inventory($dataItem,$item_id[$i]);
            }
            $tmpID[$detailsCount] = $borrow_detail_id[$i];
            $detailsCount++;
          }
          //delete
        }
      
        $deleteDetails = $this->borrow_model->removeBorrowDetails($tmpID,$id);
        $i = $detailsCount;
        //add
        for ($i; $i < sizeof($item_id); $i++) 
        {
          $inventory = $this->inventory_model->get_inventory_by_item($item_id[$i]);
        //echo "inventory_quantity:".$inventory->inventory_quantity."<br>";
        //echo "quantities:".$quantities[$i]."<br>";
        //echo "item_id:".$item_id[$i]."<br>";
          if ($inventory && $inventory->inventory_quantity >= $quantities[$i]) {
            $tmp['item_id'] = $item_id[$i];
            $tmp['quantities'] = $quantities[$i];
            $tmp['end_date'] = $end_date[$i];
            $tmp['borrow_id'] = $id;
            $this->borrow_model->save_details($tmp);
            $dataItem = array();
            $dataItem['inventory_quantity'] = $inventory->inventory_quantity -  $quantities[$i];
            $this->inventory_model->update_inventory($dataItem,$item_id[$i]);
          }
        }      
      }
      else
      {
        foreach ($currentRows as $currentRow){
          $getBorrowDetailQuantity = $this->borrow_model->getBorrowDetailsQuantityByID($currentRow, $id);
          $getInventoryQuantity = $this->inventory_model->getInventoryQuantityByID(intval($currentRow));
          $updateQuantity = $getInventoryQuantity->inventory_quantity + $getBorrowDetailQuantity->quantities;
          $this->inventory_model->updateInventoryModifyBorrow($currentRow, $updateQuantity);
          $this->borrow_model->deleteAllBorrowDetail($currentRow, $id);
        }
      }
      
      //software
      $software = $this->input->post('software',true);
      $description = $this->input->post('description',true);
      $software_id = $this->input->post('software_id',true);
      $tmpID = array();
      //update
      $i = 0;
      $tmpCount = 0;
      for ($i; $i < sizeof($software_id); $i++) {
        if ($software_id[$i]) {
          $soft['software_name'] = $software[$i];
          $soft['software_description'] = $description[$i];
          $soft['borrow_id'] = $id;
          $this->borrow_model->updateSoftware($soft,$software_id[$i]);
          $tmpID[$i] = $software_id[$i];
          $tmpCount++;
        }
      }
      //delete
      $deleteSoftware = $this->borrow_model->removeSoftware($tmpID,$id);
      $i = $tmpCount;
      //add
      for ($i; $i < sizeof($software) ; $i++) {
        $soft['software_name'] = $software[$i];
        $soft['software_description'] = $description[$i];
        $soft['borrow_id'] = $id;
        $this->borrow_model->save_software($soft);
      }
      //
      $message = '<div class="alert alert-success">Success Edit</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('borrow'));
    }else {
      $message = '<div class="alert alert-danger">Somethink Wrong!</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('borrow'));
    }

  }

  public function view($id)
  {
    if (!$this->session->userdata('role')) {
      $message = '<div class="alert alert-danger">You are not allowed to do this!</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('borrow'));
    }
    if (!$id) {
      $message = '<div class="alert alert-danger">Not Found!</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('borrow'));
    }
    $data['header'] = $this->load->view('header/head', '', TRUE);
    $data['navigation'] = $this->load->view('header/navigation', $data, TRUE);
    $data['extra_head'] = $this->load->view('header/borrow', '', TRUE);

    $data['borrow'] = $this->borrow_model->get_view_borrow($id);
    if (isset($data['borrow']) && sizeof($data['borrow']) > 0) {
      $data['borrow_details'] = $this->borrow_model->get_borrow_details($id);
      $data['software'] = $this->borrow_model->get_software_details($id);
      $data['content'] = $this->load->view('content/borrow_details', $data, TRUE);
      $data['footer'] = $this->load->view('footer/footer', '', TRUE);
      $this->load->view('main', $data);
    }else {
      $message = '<div class="alert alert-danger">Not Found!</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('borrow'));
    }
  }

  public function print_view($id)
  {
    if (!$this->session->userdata('role')) {
      $message = '<div class="alert alert-danger">You are not allowed to do this!</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('borrow'));
    }
    if (!$id) {
      $message = '<div class="alert alert-danger">Not Found!</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('borrow'));
    }

    $data['header'] = $this->load->view('header/head', '', TRUE);
    $data['navigation'] = $this->load->view('header/navigation', $data, TRUE);
    $data['extra_head'] = $this->load->view('header/borrow', '', TRUE);

    $data['borrow'] = $this->borrow_model->get_view_borrow($id);
    if (sizeof($data['borrow']) <= 0) {
      $message = '<div class="alert alert-danger">Not Found!</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('borrow'));
    }
    // var_dump($data['borrow']);
    $data['borrow_details'] = $this->borrow_model->get_borrow_details($id);
    $data['software'] = $this->borrow_model->get_software_details($id);
    $data['content'] = $this->load->view('content/borrow_details_print', $data, TRUE);
    $data['footer'] = $this->load->view('footer/footer', '', TRUE);
    $this->load->view('main', $data);
  }

  // public function return_borrow($id)
  // {
  //   $data['borrow'] = $this->borrow_model->get_view_borrow($id);
  //   $data['borrow_details'] = $this->borrow_model->get_borrow_details($id);
  //   if (!$id) {
  //     $message = '<div class="alert alert-danger">Not Found!</div>';
  //     $this->session->set_flashdata('message', $message);
  //     redirect(base_url('borrow'));
  //   }
  //   if (sizeof($data['borrow']) <= 0) {
  //     $message = '<div class="alert alert-danger">Not Found!</div>';
  //     $this->session->set_flashdata('message', $message);
  //     redirect(base_url('borrow'));
  //   }
  //   if($this->input->post()){
  //     $borrow_detail_id = $this->input->post('borrow_detail_id',true);
  //     $return_quantity = $this->input->post('return_quantity',true);
  //     $return_status = $this->input->post('return_status',true);

  //     if ($return_status) {
  //       $dataReturnItem['item_return_date'] = date('Y-m-d');
  //       $this->borrow_model->updateBorrow($dataReturnItem,$id);
  //       //
  //       // //update inventory
  //       $inventory = $this->inventory_model->get_inventory_by_item($data['borrow']->item_id);
  //       $dataInv['inventory_quantity'] = $inventory->inventory_quantity + 1;
  //       $this->inventory_model->update_inventory($dataInv,$data['borrow']->item_id);
  //     }

  //     //details
  //     for ($i=0; $i < sizeof($borrow_detail_id); $i++) {

  //       if ($borrow_detail_id[$i]) {
  //         $borrowDetails = $this->borrow_model->get_borrow_details_byid($borrow_detail_id[$i]);
  //         $inventory = $this->inventory_model->get_inventory_by_item($borrowDetails->item_id);

  //         if ($return_quantity[$i] != '' && $return_quantity[$i] != 0 && $borrowDetails->return_date == '') {
  //           $returnQty = $borrowDetails->return_quantity + $return_quantity[$i];
  //           if ($returnQty >= $borrowDetails->quantities) {
  //             $dataReturn['return_date'] =  date('Y-m-d');
  //             $returnQty = $borrowDetails->quantities;
  //             $return_quantity[$i] = $borrowDetails->quantities - $borrowDetails->return_quantity;
  //           }
  //           $dataReturn['return_quantity'] =  $returnQty;
  //           $this->borrow_model->updateDetails($dataReturn,$borrow_detail_id[$i]);
  //           $dataInv['inventory_quantity'] = $inventory->inventory_quantity + $return_quantity[$i];
  //           $this->inventory_model->update_inventory($dataInv,$borrowDetails->item_id);
  //         }
  //       }

  //     }

  //     $this->borrow_model->return_borrow($id);
  //     $message = '<div class="alert alert-success">Success</div>';
  //     $this->session->set_flashdata('message', $message);
  //     redirect(base_url('borrow'));
  //   }

  public function return_borrow($id)
  {
    $data['borrow'] = $this->borrow_model->get_view_borrow($id);
    $data['borrow_details'] = $this->borrow_model->get_borrow_details($id);
    if (!$id) {
      $message = '<div class="alert alert-danger">Not Found!</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('borrow'));
    }
    if (sizeof($data['borrow']) <= 0) {
      $message = '<div class="alert alert-danger">Not Found!</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('borrow'));
    }
    if($this->input->post()){
      $borrow_detail_id = $this->input->post('borrow_detail_id',true);
      $return_quantity = $this->input->post('return_quantity',true);
      $return_status = $this->input->post('return_status',true);

      if ($return_status) {
        $dataReturnItem['item_return_date'] = date('Y-m-d');
        $dataReturnItem['borrow_status'] = $return_status;
        $this->borrow_model->updateBorrow($dataReturnItem,$id);
        //
        // //update inventory
        $inventory = $this->inventory_model->get_inventory_by_item($data['borrow']->item_id);
        $dataInv['inventory_quantity'] = $inventory->inventory_quantity + 1;
        $this->inventory_model->update_inventory($dataInv,$data['borrow']->item_id);
      }

      //details
      for ($i=0; $i < sizeof($borrow_detail_id); $i++) {

        if ($borrow_detail_id[$i]) {
          $borrowDetails = $this->borrow_model->get_borrow_details_byid($borrow_detail_id[$i]);
          $inventory = $this->inventory_model->get_inventory_by_item($borrowDetails->item_id);

          if ($return_quantity[$i] != '' && $return_quantity[$i] != 0 && $borrowDetails->return_date == '') {
            $returnQty = $borrowDetails->return_quantity + $return_quantity[$i];
            if ($returnQty >= $borrowDetails->quantities) {
              $dataReturn['return_date'] =  date('Y-m-d');
              $returnQty = $borrowDetails->quantities;
              $return_quantity[$i] = $borrowDetails->quantities - $borrowDetails->return_quantity;
            }
            $dataReturn['return_quantity'] =  $returnQty;
            $this->borrow_model->updateDetails($dataReturn,$borrow_detail_id[$i]);
            $dataInv['inventory_quantity'] = $inventory->inventory_quantity + $return_quantity[$i];
            $this->inventory_model->update_inventory($dataInv,$borrowDetails->item_id);
          }
        }

      }

      $this->borrow_model->return_borrow($id);
      $message = '<div class="alert alert-success">Success</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('borrow'));
    }

    $data['header'] = $this->load->view('header/head', '', TRUE);
    $data['navigation'] = $this->load->view('header/navigation', $data, TRUE);
    $data['content'] = $this->load->view('forms/form_return_borrow', $data, TRUE);
    $data['footer'] = $this->load->view('footer/footer', '', TRUE);
    $this->load->view('main', $data);

  }

  public function requestItems(){
    
    $data = array();
    $config = array();

    $config['full_tag_open'] = '<ul class="pagination">';
    $config['full_tag_close'] = '</ul>';
    $config['num_tag_open'] = '<li>';
    $config['num_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li class="active"><span>';
    $config['cur_tag_close'] = '<span class="sr-only">(current)</span></span></li>';
    $config['prev_tag_open'] = '<li>';
    $config['prev_tag_close'] = '</li>';
    $config['next_tag_open'] = '<li>';
    $config['next_tag_close'] = '</li>';
    $config['first_link'] = '&laquo;';
    $config['prev_link'] = '&lsaquo;';
    $config['last_link'] = '&raquo;';
    $config['next_link'] = '&rsaquo;';
    $config['first_tag_open'] = '<li>';
    $config['first_tag_close'] = '</li>';
    $config['last_tag_open'] = '<li>';
    $config['last_tag_close'] = '</li>';
    $data['request_total'] =  $this->borrow_model->countRequestItems($employeeStatus, $employeeName, $company, $dateOfJoin, $dateOfRequest, $uid);
    $config["base_url"] = base_url() . "borrow/requestItems";
    $config['total_rows'] = $data['request_total'];
    $config['per_page'] = '10';
    $config['uri_segment'] = '3';
    $this->pagination->initialize($config);

    $data["results"] = $this->borrow_model->fetchRequestItems($config["per_page"], $this->uri->segment(3));
    $data['header'] = $this->load->view('header/head', '', TRUE);
    $data['navigation'] = $this->load->view('header/navigation', $data, TRUE);
    $data['listEmployeeStatuses'] = $this->borrow_model->get_employeeStatus_list();  
    $data['listApprovals'] = $this->borrow_model->getApproval();  
    $data['content'] = $this->load->view('content/viewRequest', $data, TRUE);
    $data['footer'] = $this->load->view('footer/footer', '', TRUE);
    $this->load->view('main', $data);
  } 
  
  public function requesItemsfilter(){
    $employeeStatus = htmlspecialchars($this->input->post('radioEmployeeStatus', TRUE));
    $employeeName = htmlspecialchars($this->input->post('txtemployeename', TRUE));
    $company = htmlspecialchars($this->input->post('textCompany', TRUE));
    if ($this->input->post('txtDateOfJoin', TRUE) !== "" && $this->input->post('txtDateOfRequest', TRUE) !== "")
    {
      $dateOfJoin = date("Y-m-d", strtotime(str_replace("/", "-", $this->input->post('txtDateOfJoin', TRUE))));
      $dateOfRequest = date("Y-m-d", strtotime(str_replace("/", "-", $this->input->post('txtDateOfRequest', TRUE))));
    }
    $dateOfJoin = $this->input->post('txtDateOfJoin', TRUE);
    $dateOfRequest = $this->input->post('txtDateOfRequest', TRUE);
    $uid = htmlspecialchars($this->input->post('taken_by_uid', TRUE));
    
    $data = array();
    $config = array();

    $config['full_tag_open'] = '<ul class="pagination">';
    $config['full_tag_close'] = '</ul>';
    $config['num_tag_open'] = '<li>';
    $config['num_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li class="active"><span>';
    $config['cur_tag_close'] = '<span class="sr-only">(current)</span></span></li>';
    $config['prev_tag_open'] = '<li>';
    $config['prev_tag_close'] = '</li>';
    $config['next_tag_open'] = '<li>';
    $config['next_tag_close'] = '</li>';
    $config['first_link'] = '&laquo;';
    $config['prev_link'] = '&lsaquo;';
    $config['last_link'] = '&raquo;';
    $config['next_link'] = '&rsaquo;';
    $config['first_tag_open'] = '<li>';
    $config['first_tag_close'] = '</li>';
    $config['last_tag_open'] = '<li>';
    $config['last_tag_close'] = '</li>';
    $data['request_total'] =  $this->borrow_model->countRequestItems($employeeStatus, $employeeName, $company, $dateOfJoin, $dateOfRequest, $uid, 'filter');
    $config["base_url"] = base_url() . "borrow/requestItems";
    $config['total_rows'] = $data['request_total'];
    $config['per_page'] = '10';
    $config['uri_segment'] = '3';
    $this->pagination->initialize($config);

    $data["results"] = $this->borrow_model->fetchRequestItems($config["per_page"], $this->uri->segment(3));
    $data['header'] = $this->load->view('header/head', '', TRUE);
    $data['navigation'] = $this->load->view('header/navigation', $data, TRUE);
    $data['listEmployeeStatuses'] = $this->borrow_model->get_employeeStatus_list();  
    $data['listApprovals'] = $this->borrow_model->getApproval();  
    $data['content'] = $this->load->view('content/viewRequest', $data, TRUE);
    $data['footer'] = $this->load->view('footer/footer', '', TRUE);
    $this->load->view('main', $data);
  } 
  
  public function formRequestItems(){
    if (!$this->session->userdata('role')) {
      exit('<div class="alert alert-danger">Not allowed!</div>');
    }

    // check is there any data sent from formRequestItems
    if($this->input->post()){
      // var_dump($this->input->post());die;
      // load form validation library
      $this->load->library('form_validation');
      // form validation configuration
      $this->form_validation->set_rules('radioEmployeeStatus', 'Employee Status');
      if($this->input->post('taken_by_uid') == ""){
        $this->form_validation->set_rules('txtemployeename', 'Employee Name', 'required|alpha_numeric_spaces');
        $this->form_validation->set_rules('textCompany', 'Company Name', 'required|alpha_numeric_spaces');
        $this->form_validation->set_rules('dropdownDepartment', 'Department', 'required');
        $this->form_validation->set_rules('txtDateOfJoin', 'Date of Join');
        $this->form_validation->set_rules('dropdownDesignation', 'Designation', 'required');
      }
      $this->form_validation->set_rules('txtDateOfRequest', 'Date of Request', 'required');
      $this->form_validation->set_rules('txtPhone', 'Office Direct Line / Mobile No', 'required');
      // if validation error, error message will be displayed
      if ($this->form_validation->run() != false) {
        $requestData['employeeStatus'] = htmlspecialchars($this->input->post('radioEmployeeStatus', true));
        if ($this->input->post('taken_by_uid') == "") {                    
          $requestData['employeeName'] = strtoupper(htmlspecialchars($this->input->post('txtemployeename', true)));
          $requestData['company'] = strtoupper(htmlspecialchars($this->input->post('textCompany', true)));
          $requestData['department'] = $this->input->post('dropdownDepartment', true);
          $requestData['dateOfJoin'] = date("Y-m-d", strtotime(str_replace("/", "-", $this->input->post('txtDateOfJoin', TRUE))));
          $requestData['designation'] = $this->input->post('dropdownDesignation', true);        
          $requestData['DateOfRequest'] = date("Y-m-d", strtotime(str_replace("/", "-", $this->input->post('txtDateOfRequest', TRUE))));
        }else{
          $requestData['uid'] = htmlspecialchars($this->input->post('taken_by_uid', true));
          $requestData['company'] = strtoupper(htmlspecialchars($this->input->post('textCompany', true)));
          $requestData['DateOfRequest'] = date("Y-m-d", strtotime(str_replace("/", "-", $this->input->post('txtDateOfRequest', TRUE))));
        }
        $requestData['phone'] = htmlspecialchars($this->input->post('txtPhone', true));
        // insert data to request table
        $requestID = $this->borrow_model->addNewRequest($requestData);
        // if insert data successfull inserted id will be returned
        if($requestID > 0){
          $this->form_validation->set_rules('optionRequestItems', 'Request Item');
          if($this->input->post('optionRequestItems') == '1'){
            $validationLabel = 'Desktop / Laptop';
            $validationRule = 'required|alpha_numeric_spaces';
          }else if($this->input->post('optionRequestItems') == '2'){
            $validationLabel = 'Email Address';
            $validationRule = 'required|valid_email|is_unique[tblrequestdetails.remarks]';
          } elseif ($this->input->post('optionRequestItems') == '7') {
            $validationRule = 'required|alpha_numeric_spaces';
          }else{
            $validationLabel = 'Items Remark';
            $validationRule = 'required|alpha_numeric_spaces';
          }
          $this->form_validation->set_rules('textRemarks', $validationLabel, $validationRule);

          $itemsRequest = $this->input->post('optionRequestItems', true);
          $itemsRequestRemark = $this->input->post('textRemarks', true);
          // loop for get input data from form in table items and itemsRemark
          for($i = 0; $i < sizeof($itemsRequest); $i++){
            $requestDetails['items'] = $itemsRequest[$i];
            $requestDetails['remarks'] = $itemsRequestRemark[$i];
            $requestDetails['requestID'] = $requestID;
            // var_dump($requestDetails);die;
            // insert data to table requesDetails
            if($this->borrow_model->NewRequestDetails($requestDetails) > 0){
              $message = '<div class = "alert alert-success">Request detail has successfully created</div>';
              $this->session->set_flashdata('message', $message);
            }else{
              $message = '<div class = "alert alert-danger">Create request detail fail!</div>';
              $this->session->set_flashdata('message', $message);
            }
          }
          $message = '<div class = "alert alert-success">Request has successfully created</div>';
          $this->session->set_flashdata('message', $message);
          redirect(base_url('borrow/requestItems'));
        }else{
          $message = '<div class = "alert alert-danger">Create request fail!</div>';
          $this->session->set_flashdata('message', $message);
        }
      }
    }
    $data = array();
    $data['header'] = $this->load->view('header/head', '', TRUE);
    $data['navigation'] = $this->load->view('header/navigation', $data, TRUE);
    $data['listPositions'] = $this->inventory_model->get_position_list();
    $data['listSupervisors'] = $this->inventory_model->get_supervisor_list();
    $data['listDepartments'] = $this->inventory_model->get_department_list();
    $data['listEmployeeStatuses'] = $this->borrow_model->get_employeeStatus_list();
    $data['listRequestItemsSuggestion'] = $this->borrow_model->getRequestItemsSuggestion();
    $data['content'] = $this->load->view('forms/formRequestItems', $data, TRUE);
    $data['footer'] = $this->load->view('footer/footer', '', TRUE);
    $this->load->view('main', $data);
  }

  public function getDesignationByID(){
    $designationID = $this->input->post('id', true);
    if($designationID){
      $data = $this->borrow_model->getDesignationByID($designationID);
    }else{
      $data = $this->inventory_model->get_position_list();
    }
    echo json_encode($data);
  }

  // public function getSuggestion(){
  //   // $data = $this->borrow_model->getSuggestion();
  //   echo json_encode($data);
  // }

  public function requestDetails($id)
  {
    if (!$this->session->userdata('role')) {
      $message = '<div class="alert alert-danger">You are not allowed to do this!</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('borrow/requestDetails'));
    }
    if (!$id) {
      $message = '<div class="alert alert-danger">Not Found!</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('borrow/requestItems'));
    }
    $data['header'] = $this->load->view('header/head', '', TRUE);
    $data['navigation'] = $this->load->view('header/navigation', $data, TRUE);
    $data['extra_head'] = $this->load->view('header/borrow', '',
      TRUE
    );

    $data['request'] = $this->borrow_model->getRequest($id);
    if (isset($data['request']) > 0) {
      $data['requestDetails'] = $this->borrow_model->getRequestDetail($id);
      $data['content'] = $this->load->view('content/requestDetails', $data, TRUE);
      $data['footer'] = $this->load->view('footer/footer', '', TRUE);
      $this->load->view('main', $data);
    } else {
      $message = '<div class="alert alert-danger">Not Found!</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('borrow/requestDetails'));
    }
  }

  public function modifyRequest($id = '')
  {
    if (!$this->session->userdata('role')) {
      $message = '<div class="alert alert-danger">You are not allowed to do this!</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('borrow/modifyRequest'));
    }
    if (!$id) {
      $message = '<div class="alert alert-danger">Not Found!</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('borrow/modifyRequest'));
    }

   

    $data['request'] = $this->borrow_model->getRequest($id);
    if (sizeof($data['request']) <= 0) {
      $message = '<div class="alert alert-danger">Not Found!</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('borrow/requestItems'));
    }
    $data['requestDetails'] = $this->borrow_model->getRequestDetail($id);
    $data['listDepartments'] = $this->inventory_model->get_department_list();
    $data['listPositions'] = $this->inventory_model->get_position_list();
    $data['listEmployeeStatuses'] = $this->borrow_model->get_employeeStatus_list();
    $data['listRequestItemsSuggestion'] = $this->borrow_model->getRequestItemsSuggestion();
    $data['borrow_details'] = $this->borrow_model->get_borrow_details($id);
    $data['header'] = $this->load->view('header/head', '', TRUE);
    $data['navigation'] = $this->load->view('header/navigation', $data, TRUE);
    $data['content'] = $this->load->view('forms/formRequestItems', $data, TRUE);
    $data['footer'] = $this->load->view('footer/footer', '', TRUE);
    $this->load->view('main', $data);
  }

  public function updateRequest()
  {
    if ($this->input->post())
    {
      // load form validation library
      $this->load->library('form_validation');
      // form validation configuration
      $this->form_validation->set_rules('radioEmployeeStatus', 'Employee Status');
      $this->form_validation->set_rules('txtemployeename', 'Employee Name', 'required|alpha_numeric_spaces');
      if ($this->input->post('taken_by_uid') == "") {
        $this->form_validation->set_rules('textCompany', 'Company Name', 'required|alpha_numeric_spaces');
        $this->form_validation->set_rules('dropdownDepartment', 'Department', 'required');
        $this->form_validation->set_rules('txtDateOfJoin', 'Date of Join');
        $this->form_validation->set_rules('dropdownDesignation', 'Designation', 'required');
      }
      $this->form_validation->set_rules('txtDateOfRequest', 'Date of Request', 'required');
      $this->form_validation->set_rules('txtPhone', 'Office Direct Line / Mobile No', 'required');

      // if validation error, error message will be displayed
      if ($this->form_validation->run() != false) 
      {
        $requestData['employeeStatus'] = htmlspecialchars($this->input->post('radioEmployeeStatus', true));
        if ($this->input->post('taken_by_uid') == "") {
          $requestData['employeeName'] = strtoupper(htmlspecialchars($this->input->post('txtemployeename', true)));
          $requestData['company'] = strtoupper(htmlspecialchars($this->input->post('textCompany', true)));
          $requestData['department'] = $this->input->post('dropdownDepartment', true);
          $requestData['dateOfJoin'] = date("Y-m-d", strtotime(str_replace("/", "-", $this->input->post('txtDateOfJoin', TRUE))));
          $requestData['designation'] = $this->input->post('dropdownDesignation', true);
          $requestData['DateOfRequest'] = date("Y-m-d", strtotime(str_replace("/", "-", $this->input->post('txtDateOfRequest', TRUE))));
        } else {
          $requestData['employeeName'] = NULL;
          $requestData['uid'] = htmlspecialchars($this->input->post('taken_by_uid', true));
          $requestData['DateOfRequest'] = date("Y-m-d", strtotime(str_replace("/", "-", $this->input->post('txtDateOfRequest', TRUE))));
        }
        $requestData['phone'] = htmlspecialchars($this->input->post('txtPhone', true));

        $requestID = htmlspecialchars($this->input->post('textRequestID', true));
      
        if ($this->borrow_model->modifyRequest($requestData, $requestID)) 
        {
          $this->borrow_model->delRequestDetail($requestID);
          $this->form_validation->set_rules('optionRequestItems', 'Request Item');

          if ($this->input->post('optionRequestItems') == '1') 
          {
            $validationLabel = 'Desktop / Laptop';
            $validationRule = 'required|alpha_numeric_spaces';
          } 
          else if ($this->input->post('optionRequestItems') == '2') 
          {
            $validationLabel = 'Email Address';
            $validationRule = 'required|valid_email|is_unique[tblrequestdetails.remarks]';
          } 
          elseif ($this->input->post('optionRequestItems') == '7') 
          {
            $validationRule = 'required|alpha_numeric_spaces';
          } 
          else 
          {
            $validationLabel = 'Items Remark';
            $validationRule = 'required|alpha_numeric_spaces';
          }

          $this->form_validation->set_rules('textRemarks', $validationLabel, $validationRule);

          $itemsRequest = $this->input->post('optionRequestItems', true);
          $itemsRequestRemark = $this->input->post('textRemarks', true);
          
          // loop for get input data from form in table items and itemsRemark
          for ($i = 0; $i < sizeof($itemsRequest); $i++) 
          {
            $requestDetails['items'] = $itemsRequest[$i];
            $requestDetails['remarks'] = $itemsRequestRemark[$i];
            $requestDetails['requestID'] = $requestID;

            // insert data to table requesDetails
            if ($this->borrow_model->NewRequestDetails($requestDetails) > 0) 
            {
              $message = '<div class = "alert alert-success">Request detail has successfully modified</div>';
              $this->session->set_flashdata('message', $message);
            } 
            else 
            {
              $message = '<div class = "alert alert-danger">Modify request detail fail!</div>';
              $this->session->set_flashdata('message', $message);
            }
          }

          $message = '<div class = "alert alert-success">Request has successfully modified</div>';
          $this->session->set_flashdata('message', $message);
          redirect(base_url('borrow/requestItems'));
        }
      }
    }
  }

  public function deleteRequest($requestID)
  {
    if (!$this->session->userdata('role')) 
    {
      $message = '<div class="alert alert-danger">You are not allowed to do this!</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('borrow'));
    }
    if (!$requestID) 
    {
      $message = '<div class="alert alert-danger">Not Found!</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('borrow/requestItems'));
    }

    if ($this->borrow_model->delRequest($requestID))
    {
      if (!$this->borrow_model->delRequestDetail($requestID)){        
        $message = '<div class="alert alert-danger">Delete request detail fail!</div>';
        $this->session->set_flashdata('message', $message);
        redirect(base_url('borrow/requestItems'));
      }

      $message = '<div class="alert alert-success">Delete request success!</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('borrow/requestItems'));
    }
    else
    {
      $message = '<div class="alert alert-danger">Delete request fail!</div>';
        $this->session->set_flashdata('message', $message);
        redirect(base_url('borrow/requestItems'));
    }
  }
}
