<?php

/**
 * Siswa Page Controller
 * @category  Controller
 */
class SiswaController extends SecureController
{
	function __construct()
	{
		parent::__construct();
		$this->tablename = "siswa";
	}
	/**
	 * List page records
	 * @param $fieldname (filter record by a field) 
	 * @param $fieldvalue (filter field value)
	 * @return BaseView
	 */
	function index($fieldname = null, $fieldvalue = null)
	{
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$fields = array("id_siswa", "nis_siswa", "nama_siswa", "kelas_siswa");

		$pagination = $this->get_pagination(MAX_RECORD_COUNT); // get current pagination e.g array(page_number, page_limit)

		// search table record
		if (!empty($request->search)) {
			$text = trim($request->search);
			$search_condition = "(
                siswa.id_siswa LIKE ? OR 
                siswa.nis_siswa LIKE ? OR 
                siswa.nama_siswa LIKE ? OR 
                siswa.kelas_siswa LIKE ? OR 
                siswa.telepon LIKE ? OR 
                siswa.alamat LIKE ? OR 
                siswa.user_id LIKE ?
            )";
			$search_params = array(
				"%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%"
			);
			// setting search conditions
			$db->where($search_condition, $search_params);
			// template to use when ajax search
			$this->view->search_template = "siswa/search.php";
		}

		$kelas_order = [
			"XII MIPA", "XI MIPA", "X MIPA",
			"XII IPS", "XI IPS", "X IPS",
			"XII BAHASA", "XI BAHASA", "X BAHASA"
		];

		// ORDER BY kelas_siswa with specific order and then by nama_siswa alphabetically
		$db->orderBy("FIELD(kelas_siswa, '" . implode("','", $kelas_order) . "')");
		$db->orderBy("nama_siswa", "ASC");

		if ($fieldname) {
			$db->where($fieldname, $fieldvalue); // filter by a single field name
		}

		$tc = $db->withTotalCount();
		$records = $db->get($tablename, $pagination, $fields);
		$records_count = count($records);
		$total_records = intval($tc->totalCount);
		$page_limit = $pagination[1];
		$total_pages = ceil($total_records / $page_limit);

		$data = new stdClass;
		$data->records = $records;
		$data->record_count = $records_count;
		$data->total_records = $total_records;
		$data->total_page = $total_pages;

		if ($db->getLastError()) {
			$this->set_page_error();
		}

		$page_title = $this->view->page_title = "Siswa";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";

		$this->render_view("siswa/list.php", $data); // render the full page
	}
	/**
	 * View record detail 
	 * @param $rec_id (select record by table primary key) 
	 * @param $value value (select record by value of field name(rec_id))
	 * @return BaseView
	 */
	function view($rec_id = null, $value = null)
	{
		$request = $this->request;
		$db = $this->GetModel();
		$rec_id = $this->rec_id = urldecode($rec_id);
		$tablename = $this->tablename;
		$fields = array(
			"id_siswa",
			"nis_siswa",
			"nama_siswa",
			"kelas_siswa",
			"telepon",
			"alamat"
		);
		if ($value) {
			$db->where($rec_id, urldecode($value)); //select record based on field name
		} else {
			$db->where("siswa.id_siswa", $rec_id);; //select record based on primary key
		}
		$record = $db->getOne($tablename, $fields);
		if ($record) {
			$page_title = $this->view->page_title = "Detail Siswa";
		} else {
			if ($db->getLastError()) {
				$this->set_page_error();
			} else {
				$this->set_page_error("No record found");
			}
		}
		return $this->render_view("siswa/view.php", $record);
	}
	/**
	 * Insert new record to the database table
	 * @param $formdata array() from $_POST
	 * @return BaseView
	 */
	function add($formdata = null)
	{
		if ($formdata) {
			$db = $this->GetModel();
			$tablename = $this->tablename;
			$request = $this->request;
			//fillable fields
			$fields = $this->fields = array("nis_siswa", "nama_siswa", "kelas_siswa", "telepon", "alamat");
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'nis_siswa' => 'required',
				'nama_siswa' => 'required',
				'kelas_siswa' => 'required',
			);
			$this->sanitize_array = array(
				'nis_siswa' => 'sanitize_string',
				'nama_siswa' => 'sanitize_string',
				'kelas_siswa' => 'sanitize_string',
				'telepon' => 'sanitize_string',
				'alamat' => 'sanitize_string',
			);
			$this->filter_vals = true; //set whether to remove empty fields
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			if ($this->validated()) {
				$rec_id = $this->rec_id = $db->insert($tablename, $modeldata);
				if ($rec_id) {
					$this->set_flash_msg("Data Berhasil Ditambahkan", "success");
					return	$this->redirect("siswa");
				} else {
					$this->set_page_error();
				}
			}
		}
		$page_title = $this->view->page_title = "Tambah Data Siswa";
		$this->render_view("siswa/add.php");
	}
	/**
	 * Update table record with formdata
	 * @param $rec_id (select record by table primary key)
	 * @param $formdata array() from $_POST
	 * @return array
	 */
	function edit($rec_id = null, $formdata = null)
	{
		$request = $this->request;
		$db = $this->GetModel();
		$this->rec_id = $rec_id;
		$tablename = $this->tablename;
		//editable fields
		$fields = $this->fields = array("id_siswa", "nis_siswa", "nama_siswa", "kelas_siswa", "telepon", "alamat");
		if ($formdata) {
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'nis_siswa' => 'required',
				'nama_siswa' => 'required',
				'kelas_siswa' => 'required',
			);
			$this->sanitize_array = array(
				'nis_siswa' => 'sanitize_string',
				'nama_siswa' => 'sanitize_string',
				'kelas_siswa' => 'sanitize_string',
				'telepon' => 'sanitize_string',
				'alamat' => 'sanitize_string',
			);
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			if ($this->validated()) {
				$db->where("siswa.id_siswa", $rec_id);;
				$bool = $db->update($tablename, $modeldata);
				$numRows = $db->getRowCount(); //number of affected rows. 0 = no record field updated
				if ($bool && $numRows) {
					$this->set_flash_msg("Data Berhasil Diperbarui", "success");
					return $this->redirect("siswa");
				} else {
					if ($db->getLastError()) {
						$this->set_page_error();
					} elseif (!$numRows) {
						//not an error, but no record was updated
						$page_error = "No record updated";
						$this->set_page_error($page_error);
						$this->set_flash_msg($page_error, "warning");
						return	$this->redirect("siswa");
					}
				}
			}
		}
		$db->where("siswa.id_siswa", $rec_id);;
		$data = $db->getOne($tablename, $fields);
		$page_title = $this->view->page_title = "Edit  Siswa";
		if (!$data) {
			$this->set_page_error();
		}
		return $this->render_view("siswa/edit.php", $data);
	}
	/**
	 * Update single field
	 * @param $rec_id (select record by table primary key)
	 * @param $formdata array() from $_POST
	 * @return array
	 */
	function editfield($rec_id = null, $formdata = null)
	{
		$db = $this->GetModel();
		$this->rec_id = $rec_id;
		$tablename = $this->tablename;
		//editable fields
		$fields = $this->fields = array("id_siswa", "nis_siswa", "nama_siswa", "kelas_siswa", "telepon", "alamat");
		$page_error = null;
		if ($formdata) {
			$postdata = array();
			$fieldname = $formdata['name'];
			$fieldvalue = $formdata['value'];
			$postdata[$fieldname] = $fieldvalue;
			$postdata = $this->format_request_data($postdata);
			$this->rules_array = array(
				'nis_siswa' => 'required',
				'nama_siswa' => 'required',
				'kelas_siswa' => 'required',
			);
			$this->sanitize_array = array(
				'nis_siswa' => 'sanitize_string',
				'nama_siswa' => 'sanitize_string',
				'kelas_siswa' => 'sanitize_string',
				'telepon' => 'sanitize_string',
				'alamat' => 'sanitize_string',
			);
			$this->filter_rules = true; //filter validation rules by excluding fields not in the formdata
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			if ($this->validated()) {
				$db->where("siswa.id_siswa", $rec_id);;
				$bool = $db->update($tablename, $modeldata);
				$numRows = $db->getRowCount();
				if ($bool && $numRows) {
					return render_json(
						array(
							'num_rows' => $numRows,
							'rec_id' => $rec_id,
						)
					);
				} else {
					if ($db->getLastError()) {
						$page_error = $db->getLastError();
					} elseif (!$numRows) {
						$page_error = "No record updated";
					}
					render_error($page_error);
				}
			} else {
				render_error($this->view->page_error);
			}
		}
		return null;
	}
	/**
	 * Delete record from the database
	 * Support multi delete by separating record id by comma.
	 * @return BaseView
	 */
	function delete($rec_id = null)
	{
		Csrf::cross_check();
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$this->rec_id = $rec_id;
		//form multiple delete, split record id separated by comma into array
		$arr_rec_id = array_map('trim', explode(",", $rec_id));
		$db->where("siswa.id_siswa", $arr_rec_id, "in");
		$bool = $db->delete($tablename);
		if ($bool) {
			$this->set_flash_msg("Data Berhasil Dihapus", "success");
		} elseif ($db->getLastError()) {
			$page_error = $db->getLastError();
			$this->set_flash_msg($page_error, "danger");
		}
		return	$this->redirect("siswa");
	}
}
