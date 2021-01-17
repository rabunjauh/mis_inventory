<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Csv extends CI_Controller {

  function __construct() {
    parent::__construct();
    $this->load->model('csv_model');
    $this->load->model('invoice_model');
    $this->load->model('inventory_model');
    $this->load->model('item_model');
    $this->load->model('borrow_model');
    //        $this->load->spark('csvimport/0.0.1');
    $this->load->library('csvimport');
    //        $this->load->library('my_csv');
    $this->load->helper('download');
    if (!$this->session->userdata('log')) {
      redirect(base_url('login'));
    }
  }

  public function index()
  {
    $data = array();
    $data['header'] = $this->load->view('header/head', '', TRUE);
    $data['navigation'] = $this->load->view('header/navigation', $data, TRUE);
    $data['content'] = $this->load->view('forms/form_csv_upload', $data, TRUE);
    $data['footer'] = $this->load->view('footer/footer', '', TRUE);
    $this->load->view('main', $data);
  }

  public function importcsv()
  {
    $config['upload_path'] = './csv_upload/';
    $config['allowed_types'] = 'csv';
    $config['max_size'] = '2500';
    $config['overwrite'] = TRUE;

    $this->load->library('upload', $config);

    // If upload failed, display error
    if (!$this->upload->do_upload('csv_file')) {
      $message = '<div class="alert alert-danger alert-dismissable">'
      . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
      . $this->upload->display_errors()
      . '</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('csv'));
    } else {
      $file_data = $this->upload->data();

      $file_path =  './csv_upload/'.$file_data['file_name'];
      $csv_array = $this->csvimport->get_array($file_path, "", TRUE);
      // $csv_array = $this->csvimport->get_array($file_path);
      // echo "<pre/>"; print_r($csv_array); exit();

      if ($this->csvimport->get_array($file_path)) {
        $csv_array = $this->csvimport->get_array($file_path);

        foreach ($csv_array as $row) {
          $insert_data = array(
            'item_code'=>$row['item_code'],
            'cat_id'=>$row['cat_id'],
            'item_name'=>$row['item_name'],
            'item_description'=>$row['item_description'],
          );

          $this->barcode($insert_data['item_code']);
          $this->csv_model->insert_csv($insert_data);
        }
        $message = '<div class="alert alert-success alert-dismissable">'
        . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
        . "Csv Data Imported Succesfully"
        . '</div>';
        $this->session->set_flashdata('message', $message);
        redirect(base_url('csv'));
      } else {
        $message = '<div class="alert alert-danger alert-dismissable">'
        . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
        . "Error occured"
        . '</div>';
        $this->session->set_flashdata('message', $message);
        redirect(base_url('csv'));
      }
    }

  }

  public function upload_data()
  {
    $config['upload_path'] = './csv_upload/';
    $config['allowed_types'] = 'csv';
    $config['max_size'] = '2500';
    $config['overwrite'] = TRUE;

    $this->load->library('upload', $config);

    // If upload failed, display error
    if (!$this->upload->do_upload('file_excel')) {
      $message = '<div class="alert alert-danger alert-dismissable">'
      . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
      . $this->upload->display_errors()
      . '</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('csv'));
    } else {
      $file_data = $this->upload->data();
      $file_path =  './csv_upload/'.$file_data['file_name'];
      $csv_array = $this->csvimport->get_array($file_path,"", TRUE);
      if ($csv_array && sizeof($csv_array) > 0) {
        //loop
        foreach ($csv_array as $row) {
          $form_info['code'] = $row['Asset ID'];
          $form_info['description'] = $row['Model'];
          $form_info['name'] = $row['Model'];
          $form_info['category'] = 1;
          $form_info['measurement_id'] = 1;
          $form_info['service_tag'] = $row['Service Tag'];
          $form_info['express_service'] = $row['Express Service'];
          $form_info['machine_type'] = $row['Machine'];
          $form_info['model'] = $row['Model'];
          $form_info['operating_system'] = $row['Operation System'];
          $form_info['processor'] = $row['Processor'];
          $form_info['memory'] = $row['Memory'];
          $form_info['hdd'] = $row['Hard Disk'];
          $form_info['vga'] = $row['VGA'];
          $form_info['computer_name'] = $row['Computer Name'];
          $form_info['product_key_windows'] = $row['Product Key Windows'];
          $form_info['product_key_office'] = $row['Product Key Microsof Office'];
          $form_info['predecessor'] = $row['Ex.'];
          $form_info['item_material_status'] = 1;
          $this->barcode($form_info['item_code']);
          if ($row['Warranty'] == 'Waranty') {
            $form_info['warranty_status'] = 1;
            $form_info['start_warranty'] = date("Y-m-d", strtotime(str_replace("-","/",$row['Start'])));
            $form_info['end_warranty'] = date("Y-m-d", strtotime(str_replace("-","/",$row['End'])));
          }else {
            $form_info['warranty_status'] = 0;
          }
          $this->item_model->save_item($form_info);

        }
          //loop

      }
      $message = '<div class="alert alert-success alert-dismissable">'
      . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
      . "Csv Data Imported Succesfully"
      . '</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('csv'));

    }
  }

  public function download_template()
  {
    $data = file_get_contents("csv_template/item_upload_template.csv"); // Read the file's contents
    $name = 'item_upload_template.csv';

    force_download($name, $data);
  }

  public function download_csv($table_name)
  {
    $this->load->dbutil();
    $query = $this->db->query("SELECT * FROM ".$table_name);
    $data = $this->dbutil->csv_from_result($query);
    $name = $table_name.'.csv';
    force_download($name, $data);
  }

  public function download_inventory($type)
  {
    date_default_timezone_set('Asia/Jakarta');
    $getdate = date("Y-m-d");

    $get_inventory = $this->invoice_model->get_inventory($type);
    if(count($get_inventory)!=0){
      $EXCEL_OUT="";
      $EXCEL_OUT.="<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
      <table style='border:solid 1px #848484' border='1'>
      <tr>
      <th style='border:solid 1px #848484;font-size:16px;' colspan='3'>Inventory Report : ".$getdate."</th>
      </tr>
      <tr>
      <th style='border:solid 1px #848484'>No</th>
      <th style='border:solid 1px #848484'>Asset ID</th>
      <th style='border:solid 1px #848484'>Item Name</th>
      <th style='border:solid 1px #848484'>Description</th>
      <th style='border:solid 1px #848484'>Category</th>
      <th style='border:solid 1px #848484'>Quantity</th>
      <th style='border:solid 1px #848484'>Measurement</th>
      <th style='border:solid 1px #848484'>Damage Quantity</th>
      <th style='border:solid 1px #848484'>Added</th>
      <th style='border:solid 1px #848484'>Updated</th>
      <th style='border:solid 1px #848484'>Service Tag</th>
      <th style='border:solid 1px #848484'>Express Service Code</th>
      <th style='border:solid 1px #848484'>Machine Type</th>
      <th style='border:solid 1px #848484'>Model</th>
      <th style='border:solid 1px #848484'>Operating System</th>
      <th style='border:solid 1px #848484'>Processor</th>
      <th style='border:solid 1px #848484'>Memory</th>
      <th style='border:solid 1px #848484'>Hardisk</th>
      <th style='border:solid 1px #848484'>Computer Name</th>
      <th style='border:solid 1px #848484'>Warranty Status</th>
      <th style='border:solid 1px #848484' colspan='2'>Warranty</th>
      <th style='border:solid 1px #848484'>Product Key Windows</th>
      <th style='border:solid 1px #848484'>Product Key Office</th>
      <th style='border:solid 1px #848484'>Product Key Others</th>
      </tr>";
      $a=1;
      foreach($get_inventory as $dt){
        if($dt->warranty_status == 0){$warranty_status = "No Warranty";}else {$warranty_status = "Warranty";};

        $EXCEL_OUT.="<tr>";
        $EXCEL_OUT.="<td>".$a."</td>";
        $EXCEL_OUT.="<td>".$dt->item_code."</td>";
        $EXCEL_OUT.="<td>".$dt->item_name."</td>";
        $EXCEL_OUT.="<td>".$dt->item_description."</td>";
        $EXCEL_OUT.="<td>".$dt->cat_name."</td>";
        $EXCEL_OUT.="<td>".$dt->inventory_quantity."</td>";
        $EXCEL_OUT.="<td>".$dt->measurement."</td>";
        $EXCEL_OUT.="<td>".$dt->inventory_damage_qtt."</td>";
        $EXCEL_OUT.="<td>".$dt->inventory_added."</td>";
        $EXCEL_OUT.="<td>".$dt->inventory_update."</td>";
        $EXCEL_OUT.="<td>".$dt->service_tag."</td>";
        $EXCEL_OUT.="<td>".$dt->express_service."</td>";
        $EXCEL_OUT.="<td>".$dt->machine_type."</td>";
        $EXCEL_OUT.="<td>".$dt->model."</td>";
        $EXCEL_OUT.="<td>".$dt->operating_system."</td>";
        $EXCEL_OUT.="<td>".$dt->processor."</td>";
        $EXCEL_OUT.="<td>".$dt->memory."</td>";
        $EXCEL_OUT.="<td>".$dt->hdd."</td>";
        $EXCEL_OUT.="<td>".$dt->computer_name."</td>";
        $EXCEL_OUT.="<td>".$warranty_status."</td>";
        $EXCEL_OUT.="<td>".$dt->start_warranty."</td>";
        $EXCEL_OUT.="<td>".$dt->end_warranty."</td>";
        $EXCEL_OUT.="<td>".$dt->product_key_windows."</td>";
        $EXCEL_OUT.="<td>".$dt->product_key_office."</td>";
        $EXCEL_OUT.="<td>".$dt->product_key_others."</td>";
        $EXCEL_OUT.="</tr>";
        $a++;

      }
      $EXCEL_OUT.="</table>";
    }else{
      $EXCEL_OUT="No data found...";
    }

    Header("Content-type: application/vnd.ms-excel; charset=UTF-8");
    Header("Content-Disposition: attachment; filename=inventory_report_".$type."_".date('Y-m-d').".xls");

    echo $EXCEL_OUT;

  }

  public function putQuery($tbl,$key,$val,$action = '=')
  {
    $arr['tabel'] = $tbl;
    $arr['key'] = $key;
    $arr['value'] = $val;
    $arr['action'] = $action;
    return $arr;
  }

  public function download_csv_invoice_purchase()
  {
    date_default_timezone_set('Asia/Jakarta');
    $getdate = date("Y-m-d");
    $dateFrom = $this->input->post('filterDateFrom', TRUE);
    $dateTo = $this->input->post('filterDateTo', TRUE);
    $supplier = $this->input->post('supplier', TRUE);

    if($dateFrom == '' && $dateTo == ''){
      $dateFrom = 'all';
      $dateTo = 'all';
    }elseif ($dateFrom == '' && $dateTo !== '') {
      $dateFrom = $dateTo;
    }elseif ($dateFrom !== '' && $dateTo == '') {
      $dateTo = $dateFrom;
    }

    $filter = [];

    if ($supplier !== 'all') {
      $filter[] = $this->putQuery('ip','supplier_id',$supplier);
    }

    if ($dateFrom !== 'all' && $dateTo !== 'all') {
      $dateFrom = str_replace('/', '-', $dateFrom);
      $dateTo = str_replace('/', '-', $dateTo);
      $filter[] = $this->putQuery('ip','actual_date',date('Y-m-d', strtotime($dateFrom)),'>=');
      $filter[] = $this->putQuery('ip','actual_date',date('Y-m-d', strtotime($dateTo)),'<=');
    }
    $getinvoice_purchase_list = $this->invoice_model->filter_invoice_purchase_detail($filter);

    $getitem = $this->item_model->index();
    if(count($getinvoice_purchase_list)!=0){
      $EXCEL_OUT="";
      $EXCEL_OUT.="<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
      <table style='border:solid 1px #848484' border='1'>
      <tr>
      <th style='border:solid 1px #848484;font-size:16px;' colspan='3'>Purchase Report : ".$getdate."</th>
      </tr>
      <tr>
      <th style='border:solid 1px #848484'>No</th>
      <th style='border:solid 1px #848484'>Supplier</th>
      <th style='border:solid 1px #848484'>Item Details</th>
      <th style='border:solid 1px #848484'>Total Price</th>
      <th style='border:solid 1px #848484'>Date Record</th>
      <th style='border:solid 1px #848484'>Actual Date</th>
      <th style='border:solid 1px #848484'>Created By</th>
      <th style='border:solid 1px #848484'>Note</th>
      </tr>";
      $a=1;
      foreach($getinvoice_purchase_list as $dt){
        $EXCEL_OUT.="<tr>";
        $EXCEL_OUT.="<td>".$a."</td>";
        $EXCEL_OUT.="<td>".$dt->supplier_name."</td>";
        $EXCEL_OUT.="<td><table border=\"1\">
        <tr>
        <th>Item</th>
        <th>Quantity</th>
        <th>Measurement</th>
        <th>Unit Price</th>
        </tr>";
        $exp = explode(",",$dt->item_ids);
        $exp1 = explode(",",$dt->quantities);
        $exp2 = explode(",",$dt->unit_prices);

        $count = count($exp);
        for($i=0;$i<$count;$i++){
          $EXCEL_OUT.="<tr>";
          foreach($getitem as $data){
            if($data->item_id == $exp[$i]){
              $EXCEL_OUT.="<td>".$data->item_name."</td>";
              $EXCEL_OUT.="<td>".$exp1[$i]."</td>";
              $EXCEL_OUT.="<td>".$data->measurement."</td>";
              $EXCEL_OUT.="<td>".$exp2[$i]."</td>";
            }
          }
          $EXCEL_OUT.="</tr>";
        }
        $EXCEL_OUT.="</table></td>";
        $EXCEL_OUT.="<td>".$dt->total_price."</td>";
        $EXCEL_OUT.="<td>".$dt->date."</td>";
        $EXCEL_OUT.="<td>".$dt->actual_date."</td>";
        $EXCEL_OUT.="<td>".$dt->user_full_name."</td>";
        $EXCEL_OUT.="<td>".$dt->note."</td>";
        $EXCEL_OUT.="</tr>";
        $a++;
      }
      $EXCEL_OUT.="</table>";
    }else{
      $EXCEL_OUT="No data found...";
    }

    Header("Content-type: application/vnd.ms-excel; charset=UTF-8");
    Header("Content-Disposition: attachment; filename=invoice_purchase_".$getdate.".xls");

    echo $EXCEL_OUT;

  }

  public function download_csv_invoice_out()
  {
    date_default_timezone_set('Asia/Jakarta');
    $getdate = date("Y-m-d");

    $dateFrom = $this->input->post('filterDateFrom', TRUE);
    $dateTo = $this->input->post('filterDateTo', TRUE);

    $warehouse = $this->input->post('warehouse', TRUE);
    $project = $this->input->post('project', TRUE);
    $cost_center = $this->input->post('cost_center', TRUE);
    $employee = $this->input->post('employee', TRUE);

    if($dateFrom == '' && $dateTo == ''){
      $dateFrom = 'all';
      $dateTo = 'all';
    }elseif ($dateFrom == '' && $dateTo !== '') {
      $dateFrom = $dateTo;
    }elseif ($dateFrom !== '' && $dateTo == '') {
      $dateTo = $dateFrom;
    }

    $filter = [];
    //
    if ($warehouse !== 'all') {
      $filter[] = $this->putQuery('io','warehouse_id',$warehouse);
    }

    if ($dateFrom !== 'all' && $dateTo !== 'all') {
      $dateFrom = str_replace('/', '-', $dateFrom);
      $dateTo = str_replace('/', '-', $dateTo);
      $filter[] = $this->putQuery('io','actual_date',date('Y-m-d', strtotime($dateFrom)),'>=');
      $filter[] = $this->putQuery('io','actual_date',date('Y-m-d', strtotime($dateTo)),'<=');
    }

    if ($project !== 'all') {
      $filter[] = $this->putQuery('io','project_uid',$project);
    }

    if ($cost_center !== 'all') {
      $filter[] = $this->putQuery('io','cost_center_uid',$cost_center);
    }

    if ($employee !== 'all') {
      $filter[] = $this->putQuery('io','taken_by_uid',$employee);
    }
    //
    $getinvoice_sales_list = $this->invoice_model->filter_invoice_sales_detail($filter);
    $getitem = $this->item_model->index();

    if(count($getinvoice_sales_list)!=0){
      $EXCEL_OUT="";
      $EXCEL_OUT.="<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
      <table style='border:solid 1px #848484' border='1'>
      <tr>
      <th style='border:solid 1px #848484;font-size:16px;' colspan='3'>Sales Report: ".$getdate."</th>
      </tr>
      <tr>
      <th style='border:solid 1px #848484'>No</th>
      <th style='border:solid 1px #848484'>Warehouse</th>
      <th style='border:solid 1px #848484'>Taken By</th>
      <th style='border:solid 1px #848484'>Cost Center</th>
      <th style='border:solid 1px #848484'>Department</th>
      <th style='border:solid 1px #848484'>Project</th>
      <th style='border:solid 1px #848484'>Item Details</th>
      <th style='border:solid 1px #848484'>Total Price</th>
      <th style='border:solid 1px #848484'>Date</th>
      <th style='border:solid 1px #848484'>Actual Date</th>
      <th style='border:solid 1px #848484'>Created By</th>
      <th style='border:solid 1px #848484'>Note</th>
      </tr>";
      $a=1;
      foreach($getinvoice_sales_list as $dt){
        $EXCEL_OUT.="<tr>";
        $EXCEL_OUT.="<td>".$a."</td>";
        $EXCEL_OUT.="<td>".$dt->warehouse_name."</td>";
        $EXCEL_OUT.="<td>".$dt->employee_name."</td>";
        $EXCEL_OUT.="<td>".$dt->cost_center."</td>";
        $EXCEL_OUT.="<td>".$dt->employee_dept."</td>";
        $EXCEL_OUT.="<td>".$dt->project."</td>";
        $EXCEL_OUT.="<td><table border=\"1\">
        <tr>
        <th>Item</th>
        <th>Quantity</th>
        <th>Measurement</th>
        <th>Unit Price</th>
        </tr>";
        $exp = explode(",",$dt->item_ids);
        $exp1 = explode(",",$dt->quantities);
        $exp2 = explode(",",$dt->unit_prices);

        $count = count($exp);
        for($i=0;$i<$count;$i++){
          $EXCEL_OUT.="<tr>";
          foreach($getitem as $data){
            if($data->item_id == $exp[$i]){
              $EXCEL_OUT.="<td>".$data->item_name."</td>";
              $EXCEL_OUT.="<td>".$exp1[$i]."</td>";
              $EXCEL_OUT.="<td>".$data->measurement."</td>";
              $EXCEL_OUT.="<td>".$exp2[$i]."</td>";
            }
          }
          $EXCEL_OUT.="</tr>";
        }
        $EXCEL_OUT.="</table></td>";
        $EXCEL_OUT.="<td>".$dt->total_price."</td>";
        $EXCEL_OUT.="<td>".$dt->date."</td>";
        $EXCEL_OUT.="<td>".$dt->actual_date."</td>";
        $EXCEL_OUT.="<td>".$dt->user_full_name."</td>";
        $EXCEL_OUT.="<td>".$dt->note."</td>";
        $EXCEL_OUT.="</tr>";
        $a++;
      }
      $EXCEL_OUT.="</table>";
    }else{
      $EXCEL_OUT="No data found...";
    }

    Header("Content-type: application/vnd.ms-excel; charset=UTF-8");
    Header("Content-Disposition: attachment; filename=invoice_sales".$getdate.".xls");

    echo $EXCEL_OUT;

  }

  public function download_csv_invoice_out_detail()
  {
    date_default_timezone_set('Asia/Jakarta');
    $getdate = date("Y-m-d");

    $dateFrom = $this->input->post('filterDateFrom', TRUE);
    $dateTo = $this->input->post('filterDateTo', TRUE);

    $date = $this->input->post('filterDate', TRUE);
    $warehouse = $this->input->post('warehouse', TRUE);
    $project = $this->input->post('project', TRUE);
    $cost_center = $this->input->post('cost_center', TRUE);
    $employee = $this->input->post('employee', TRUE);

    if($dateFrom == '' && $dateTo == ''){
      $dateFrom = 'all';
      $dateTo = 'all';
    }elseif ($dateFrom == '' && $dateTo !== '') {
      $dateFrom = $dateTo;
    }elseif ($dateFrom !== '' && $dateTo == '') {
      $dateTo = $dateFrom;
    }

    $filter = [];
    //
    if ($warehouse !== 'all') {
      $filter[] = $this->putQuery('io','warehouse_id',$warehouse);
    }

    if ($dateFrom !== 'all' && $dateTo !== 'all') {
      $dateFrom = str_replace('/', '-', $dateFrom);
      $dateTo = str_replace('/', '-', $dateTo);
      $filter[] = $this->putQuery('io','actual_date',date('Y-m-d', strtotime($dateFrom)),'>=');
      $filter[] = $this->putQuery('io','actual_date',date('Y-m-d', strtotime($dateTo)),'<=');
    }

    if ($project !== 'all') {
      $filter[] = $this->putQuery('io','project_uid',$project);
    }

    if ($cost_center !== 'all') {
      $filter[] = $this->putQuery('io','cost_center_uid',$cost_center);
    }

    if ($employee !== 'all') {
      $filter[] = $this->putQuery('io','taken_by_uid',$employee);
    }
    //

    $getinvoice_sales_list = $this->invoice_model->filter_invoice_sales_detail($filter);
    $getitem = $this->item_model->index();
    if(count($getinvoice_sales_list)!=0){
      $EXCEL_OUT="";
      $EXCEL_OUT.="<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
      <table style='border:solid 1px #848484' border='1'>
      <tr>
      <th style='border:solid 1px #848484;font-size:16px;' colspan='3'>Sales Report Detail : ".$getdate."</th>
      </tr>
      <tr>
      <th style='border:solid 1px #848484'>No</th>
      <th style='border:solid 1px #848484'>Warehouse</th>
      <th style='border:solid 1px #848484'>Taken By</th>
      <th style='border:solid 1px #848484'>Cost Center</th>
      <th style='border:solid 1px #848484'>Department</th>
      <th style='border:solid 1px #848484'>Project</th>
      <th style='border:solid 1px #848484'>Item Code</th>
      <th style='border:solid 1px #848484'>Item</th>
      <th style='border:solid 1px #848484'>Quantity</th>
      <th style='border:solid 1px #848484'>Measurement</th>
      <th style='border:solid 1px #848484'>Date</th>
      <th style='border:solid 1px #848484'>Actual Date</th>
      <th style='border:solid 1px #848484'>Created By</th>
      <th style='border:solid 1px #848484'>Note</th>
      </tr>";
      $a=1;
      foreach($getinvoice_sales_list as $dt){
        $exp = explode(",",$dt->item_ids);
        $exp1 = explode(",",$dt->quantities);
        $exp2 = explode(",",$dt->unit_prices);

        $count = count($exp);
        for($i=0;$i<$count;$i++){
          foreach($getitem as $data){
            if($data->item_id == $exp[$i]){
              $EXCEL_OUT.="<tr>";
              $EXCEL_OUT.="<td>".$a."</td>";
              $EXCEL_OUT.="<td>".$dt->warehouse_name."</td>";
              $EXCEL_OUT.="<td>".$dt->employee_name."</td>";
              $EXCEL_OUT.="<td>".$dt->cost_center."</td>";
              $EXCEL_OUT.="<td>".$dt->employee_dept."</td>";
              $EXCEL_OUT.="<td>".$dt->project."</td>";
              $EXCEL_OUT.="<td>".$data->item_code."</td>";
              $EXCEL_OUT.="<td>".$data->item_name."</td>";
              $EXCEL_OUT.="<td>".$exp1[$i]."</td>";
              $EXCEL_OUT.="<td>".$data->measurement."</td>";
              $EXCEL_OUT.="<td>".$dt->date."</td>";
              $EXCEL_OUT.="<td>".$dt->actual_date."</td>";
              $EXCEL_OUT.="<td>".$dt->user_full_name."</td>";
              $EXCEL_OUT.="<td>".$dt->note."</td>";
              $EXCEL_OUT.="</tr>";
              $a++;
            }
          }
        }
      }
      $EXCEL_OUT.="</table>";
    }else{
      $EXCEL_OUT="No data found...";
    }

    Header("Content-type: application/vnd.ms-excel; charset=UTF-8");
    Header("Content-Disposition: attachment; filename=invoice_sales_detail.xls");

    echo $EXCEL_OUT;

  }

  //Barcode generation function, using Zend library
  public function barcode($code)
  {
    $code = strtoupper($code);
    $this->load->library('zend');
    $this->zend->load('Zend/Barcode');
    $img = Zend_Barcode::factory('code39', 'image', array('text' => $code), array())->draw();
    imagejpeg($img, 'barcodes/' . $code . '.jpg', 100);
    imagedestroy($img);
  }
  //End of Barcode generation function

  public function download_borrow()
  {
    $borrow = $this->borrow_model->fetch_data_borrow();
    date_default_timezone_set('Asia/Jakarta');
    $getdate = date("Y-m-d");

    if(count($borrow)!=0){
      $EXCEL_OUT="";
      $EXCEL_OUT.="<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
      <table style='border:solid 1px #848484' border='1'>
      <tr>
      <th style='border:solid 1px #848484;font-size:16px;' colspan='3'>Borrow Report : ".$getdate."</th>
      </tr>
      <tr>
      <th style='border:solid 1px #848484'>No</th>
      <th style='border:solid 1px #848484'>Borrow By</th>
      <th style='border:solid 1px #848484'>Project Name</th>
      <th style='border:solid 1px #848484'>Warehouse Name</th>
      <th style='border:solid 1px #848484'>Borrow Date</th>
      <th style='border:solid 1px #848484'>Borrow Details</th>
      <th style='border:solid 1px #848484'>Note</th>
      <th style='border:solid 1px #848484'>Status</th>
      </tr>";
      $a=1;
      foreach($borrow as $value){

        if ($value->borrow_status == 0) {
          $status = "<p style='color:red;'>Borrowed</p>";
        }else {
          $status = "<p style='color:green;'>Returned</p>";
        }

        $EXCEL_OUT.="<tr>";
        $EXCEL_OUT.="<td>".$a."</td>";
        $EXCEL_OUT.="<td>".$value->employee_name."</td>";
        $EXCEL_OUT.="<td>".$value->project_name."</td>";
        $EXCEL_OUT.="<td>".$value->warehouse_name."</td>";
        $EXCEL_OUT.="<td>".$value->borrow_date."</td>";

        $EXCEL_OUT.="<td><table border=\"1\">
        <tr>
        <th>No</th>
        <th>Items Code</th>
        <th>Items</th>
        <th>Quantity</th>
        <th>Measurement</th>
        <th>Return Date</th>
        <th>Return Status</th>
        </tr>";

        $borrowDetails = $this->borrow_model->get_borrow_details($value->borrow_id);
        $i = 1;
        foreach ($borrowDetails as $valueDetails) {

          if($valueDetails->return_date == ''){$detailStatus = "<p style='color:red;'>Not Return</p>";}else{$detailStatus = "<p style='color:green;'>Return</p>";}

          $EXCEL_OUT.="<tr>";
          $EXCEL_OUT.="<td>".$i."</td>";
          $EXCEL_OUT.="<td>".$valueDetails->item_code."</td>";
          $EXCEL_OUT.="<td>".$valueDetails->item_name."</td>";
          $EXCEL_OUT.="<td>".$valueDetails->quantities."</td>";
          $EXCEL_OUT.="<td>".$valueDetails->measurement."</td>";
          $EXCEL_OUT.="<td>".$valueDetails->end_date."</td>";
          $EXCEL_OUT.="<td>".$detailStatus."</td>";
          $EXCEL_OUT.="</tr>";
          $i++;
        }
        $EXCEL_OUT.="</table></td>";

        $EXCEL_OUT.="<td>".$value->note."</td>";
        $EXCEL_OUT.="<td>".$status."</td>";
        $EXCEL_OUT.="</tr>";
        $a++;
      }
      $EXCEL_OUT.="</table>";
    }else{
      $EXCEL_OUT="No data found...";
    }

    Header("Content-type: application/vnd.ms-excel; charset=UTF-8");
    Header("Content-Disposition: attachment; filename=borrow_report_".date('Y-m-d').".xls");

    echo $EXCEL_OUT;

  }

  public function items_download_stock()
  {
    $item = $this->item_model->fetch_data(null,null,'stock');
    date_default_timezone_set('Asia/Jakarta');
    $getdate = date("Y-m-d");
    if(count($item)!=0){
      $EXCEL_OUT="";
      $EXCEL_OUT.="<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
      <table style='border:solid 1px #848484' border='1'>
      <tr>
      <th style='border:solid 1px #848484;font-size:16px;' colspan='3'>Borrow Report : ".$getdate."</th>
      </tr>
      <tr>
      <th style='border:solid 1px #848484'>No</th>
      <th style='border:solid 1px #848484'>Asset ID</th>
      <th style='border:solid 1px #848484'>Item Name</th>
      <th style='border:solid 1px #848484'>Category</th>
      <th style='border:solid 1px #848484'>Item Description</th>
      <th style='border:solid 1px #848484'>Quantity</th>
      <th style='border:solid 1px #848484'>Borrowed</th>
      <th style='border:solid 1px #848484'>Measurement</th>
      <th style='border:solid 1px #848484'>Service Tag</th>
      <th style='border:solid 1px #848484'>Express Service Code</th>
      <th style='border:solid 1px #848484'>Machine Type</th>
      <th style='border:solid 1px #848484'>Model</th>
      <th style='border:solid 1px #848484'>Operating System</th>
      <th style='border:solid 1px #848484'>Processor</th>
      <th style='border:solid 1px #848484'>Memory</th>
      <th style='border:solid 1px #848484'>Hardisk</th>
      <th style='border:solid 1px #848484'>Computer Name</th>
      <th style='border:solid 1px #848484'>Warranty Status</th>
      <th style='border:solid 1px #848484' colspan='2'>Warranty</th>
      <th style='border:solid 1px #848484'>Product Key Windows</th>
      <th style='border:solid 1px #848484'>Product Key Office</th>
      <th style='border:solid 1px #848484'>Product Key Others</th>

      <th style='border:solid 1px #848484'>Borrow Details</th>
      </tr>";
      $a=1;

      foreach($item as $value){
        $inventory = $this->inventory_model->get_inventory_by_item($value->item_id);
        $borrowDetails = $this->borrow_model->get_borrowDetails_by_item($value->item_id);
        $borrow = $this->borrow_model->get_borrow_by_item($value->item_id);
        if ($borrow) {
          array_push($borrowDetails,$borrow);
        }
        $borrowed = $this->count_borrowed($borrowDetails);


        if($value->maintenance_status == 0){ $maintance_status ="No Need";}else { $maintance_status = "Need Maintenance";};
        if($value->item_calibration_status == 0){$calibration_status = "No Calibration";}else {$calibration_status = "Calibration";};
        if($value->warranty_status == 0){$warranty_status = "No Warranty";}else {$warranty_status = "Warranty";};
        $EXCEL_OUT.="<tr>";
        $EXCEL_OUT.="<td>".$a."</td>";
        $EXCEL_OUT.="<td>".$value->item_code."</td>";
        $EXCEL_OUT.="<td>".$value->item_name."</td>";
        $EXCEL_OUT.="<td>".$value->cat_name."</td>";
        $EXCEL_OUT.="<td>".$value->item_description."</td>";
        $EXCEL_OUT.="<td>".$inventory->inventory_quantity."</td>";
        $EXCEL_OUT.="<td>".$borrowed."</td>";
        $EXCEL_OUT.="<td>".$value->measurement."</td>";
        $EXCEL_OUT.="<td>".$value->service_tag."</td>";
        $EXCEL_OUT.="<td>".$value->express_service."</td>";
        $EXCEL_OUT.="<td>".$value->machine_type."</td>";
        $EXCEL_OUT.="<td>".$value->model."</td>";
        $EXCEL_OUT.="<td>".$value->operating_system."</td>";
        $EXCEL_OUT.="<td>".$value->processor."</td>";
        $EXCEL_OUT.="<td>".$value->memory."</td>";
        $EXCEL_OUT.="<td>".$value->hdd."</td>";
        $EXCEL_OUT.="<td>".$value->computer_name."</td>";
        $EXCEL_OUT.="<td>".$warranty_status."</td>";
        $EXCEL_OUT.="<td>".$value->start_warranty."</td>";
        $EXCEL_OUT.="<td>".$value->end_warranty."</td>";
        $EXCEL_OUT.="<td>".$value->product_key_windows."</td>";
        $EXCEL_OUT.="<td>".$value->product_key_office."</td>";
        $EXCEL_OUT.="<td>".$value->product_key_others."</td>";


        $EXCEL_OUT.="<td><table border=\"1\">
        <tr>
        <th>No</th>
        <th>Borrow By</th>
        <th>Project Name</th>
        <th>Warehouse Name</th>
        <th>Borrow Date</th>
        <th>Note</th>
        <th>Borrow Qty</th>
        <th>Return Qty</th>
        <th>Status</th>
        </tr>";

        $i = 1;
        foreach ($borrowDetails as $valueDetails) {

          if($valueDetails->return_date == ''){$detailStatus = "<p style='color:red;'>Not Return</p>";}else{$detailStatus = "<p style='color:green;'>Return</p>";}

          if (!$valueDetails->quantities) {
            $quantities = 1;
          }else {
            $quantities = $valueDetails->quantities;
          }
          if (!$valueDetails->return_quantity) {
            if (!$valueDetails->return_date) {
              $return_quantity = 0;
            }else {
              $return_quantity = 1;
            }
          }else {
            $return_quantity = $valueDetails->return_quantity;
          }

          $EXCEL_OUT.="<tr>";
          $EXCEL_OUT.="<td>".$i."</td>";
          $EXCEL_OUT.="<td>".$valueDetails->employeename."</td>";
          $EXCEL_OUT.="<td>".$valueDetails->project_name."</td>";
          $EXCEL_OUT.="<td>".$valueDetails->warehouse_name."</td>";
          $EXCEL_OUT.="<td>".$valueDetails->borrow_date."</td>";
          $EXCEL_OUT.="<td>".$valueDetails->note."</td>";
          $EXCEL_OUT.="<td>".$quantities."</td>";
          $EXCEL_OUT.="<td>".$return_quantity."</td>";
          $EXCEL_OUT.="<td>".$detailStatus."</td>";
          $EXCEL_OUT.="</tr>";
          $i++;
        }
        $EXCEL_OUT.="</table></td>";
        $EXCEL_OUT.="</tr>";
        $a++;
      }
      $EXCEL_OUT.="</table>";
    }else{
      $EXCEL_OUT="No data found...";
    }

    Header("Content-type: application/vnd.ms-excel; charset=UTF-8");
    Header("Content-Disposition: attachment; filename=item_stock_report_".date('Y-m-d').".xls");

    echo $EXCEL_OUT;
  }

  public function items_download_consumable()
  {
    $item = $this->item_model->fetch_data(null,null,'consumable');
    date_default_timezone_set('Asia/Jakarta');
    $getdate = date("Y-m-d");
    if(count($item)!=0){
      $EXCEL_OUT="";
      $EXCEL_OUT.="<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
      <table style='border:solid 1px #848484' border='1'>
      <tr>
      <th style='border:solid 1px #848484;font-size:16px;' colspan='3'>Borrow Report : ".$getdate."</th>
      </tr>
      <tr>
      <th style='border:solid 1px #848484'>No</th>
      <th style='border:solid 1px #848484'>Items Code</th>
      <th style='border:solid 1px #848484'>Items</th>
      <th style='border:solid 1px #848484'>Category</th>
      <th style='border:solid 1px #848484'>Item Description</th>
      <th style='border:solid 1px #848484'>Quantity</th>
      <th style='border:solid 1px #848484'>Measurement</th>";

      $a=1;

      foreach($item as $value){
        $inventory = $this->inventory_model->get_inventory_by_item($value->item_id);
        $EXCEL_OUT.="<tr>";
        $EXCEL_OUT.="<td>".$a."</td>";
        $EXCEL_OUT.="<td>".$value->item_code."</td>";
        $EXCEL_OUT.="<td>".$value->item_name."</td>";
        $EXCEL_OUT.="<td>".$value->cat_name."</td>";
        $EXCEL_OUT.="<td>".$value->item_description."</td>";
        $EXCEL_OUT.="<td>".$inventory->inventory_quantity."</td>";
        $EXCEL_OUT.="<td>".$value->measurement."</td>";
        $EXCEL_OUT.="</tr>";
        $a++;
      }
      $EXCEL_OUT.="</table>";
    }else{
      $EXCEL_OUT="No data found...";
    }

    Header("Content-type: application/vnd.ms-excel; charset=UTF-8");
    Header("Content-Disposition: attachment; filename=item_consumable_report_".date('Y-m-d').".xls");

    echo $EXCEL_OUT;

  }

  public function count_borrowed($data)
  {
    $total = 0;
    foreach ($data as $value) {
      if (!$value->return_date) {

        if (!$value->quantities) {
          $quantities = 1;
        }else {
          $quantities = $value->quantities;
        }
        if (!$value->return_quantity) {
          if (!$value->return_date) {
            $return_quantity = 0;
          }else {
            $return_quantity = 1;
          }
        }else {
          $return_quantity = $value->return_quantity;
        }

        $tmp = $quantities - $return_quantity;
        $total += $tmp;
      }
    }
    return $total;
  }

  public function downloadRequest()
  {
    $request = $this->borrow_model->fetchRequestItems();    
    date_default_timezone_set('Asia/Jakarta');
    $getdate = date("Y-m-d");
    if (count($request) != 0) {
      $EXCEL_OUT = "";
      $EXCEL_OUT .= "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
      <table style='border:solid 1px #848484' border='1'>
      <tr>
      <th style='border:solid 1px #848484;font-size:16px;' colspan='3'>Request Report : " . $getdate . "</th>
      </tr>
      <tr>
      <th style='border:solid 1px #848484'>Request ID</th>
      <th style='border:solid 1px #848484'>Employee Status</th>
      <th style='border:solid 1px #848484'>Name</th>
      <th style='border:solid 1px #848484'>Department</th>
      <th style='border:solid 1px #848484'>Designation</th>
      <th style='border:solid 1px #848484'>Company</th>
      <th style='border:solid 1px #848484'>Date Of Join</th>
      <th style='border:solid 1px #848484'>Date Of Request</th>
      <th style='border:solid 1px #848484'>Phone</th>
      <th style='border:solid 1px #848484'>Items</th>
      <th style='border:solid 1px #848484'>Remarks</th>
      </tr>";
      $a = 1;

      foreach ($request as $value) {
        if ($value->uid) {
          $employeeName = $value->employeename;
          $dateOfJoin = $value->join_date;
          $position = $value->positionExisting;
          $department = $value->departmentExisting;
          $company = $value->companyExisting;
        } else {
          $employeeName = $value->employeeName;
          $dateOfJoin = $value->dateOfJoin;
          $position = $value->positiondesc;
          $department = $value->deptdesc;
          $company = $value->company;
        }
        $requestDetails = $this->borrow_model->getRequestDetail($value->requestID);
        foreach ($requestDetails as $detailValue)
        {
          $EXCEL_OUT .= "<tr>";
          $EXCEL_OUT .= "<td>" . $value->requestID . "</td>";
          $EXCEL_OUT .= "<td>" . $value->statusDesc . "</td>";
          $EXCEL_OUT .= "<td>" . $employeeName . "</td>";
          $EXCEL_OUT .= "<td>" . $department . "</td>";
          $EXCEL_OUT .= "<td>" . $position . "</td>";
          $EXCEL_OUT .= "<td>" . $company . "</td>";
          $EXCEL_OUT .= "<td>" . $dateOfJoin . "</td>";
          $EXCEL_OUT .= "<td>" . $value->dateOfRequest . "</td>";
          $EXCEL_OUT .= "<td>" . $value->phone . "</td>";
          $EXCEL_OUT .= "<td>" . $detailValue->suggestion . "</td>";
          $EXCEL_OUT .= "<td>" . $detailValue->remarks . "</td>";
          $EXCEL_OUT .= "</tr>";
        }  
        $a++;
      }
      $EXCEL_OUT .= "</table>";
    } else {
      $EXCEL_OUT = "No data found...";
    }

    Header("Content-type: application/vnd.ms-excel; charset=UTF-8");
    Header("Content-Disposition: attachment; filename=request_report_" . date('Y-m-d') . ".xls");

    // var_dump($EXCEL_OUT);
    echo $EXCEL_OUT;
  }


}
