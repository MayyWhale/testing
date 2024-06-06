<?php

/**
 * Data_ptn_prodi Page Controller
 * @category  Controller
 */
class Data_ptn_prodiController extends SecureController
{
	function __construct()
	{
		parent::__construct();
		$this->tablename = "data_ptn_prodi";
	}

	/**
	 * Custom function to combine 'nama_ptn' and 'nama_prodi' into 'nama_alternatif'
	 * @param $data array
	 * @return array
	 */
	function namaAlternatif($data)
	{
		foreach ($data as $key => $record) {
			$data[$key]['nama_alternatif'] = $record['nama_ptn'] . ' - ' . $record['nama_prodi'];
		}
		return $data;
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
		$fields = array(
			"id_alternatif",
			"nama_ptn",
			"nama_prodi",
			"nama_alternatif"
		);
		$pagination = $this->get_pagination(MAX_RECORD_COUNT); // get current pagination e.g array(page_number, page_limit)

		//search table record
		if (!empty($request->search)) {
			$text = trim($request->search);
			$search_condition = "(
				data_ptn_prodi.id_alternatif LIKE ? OR 
				data_ptn_prodi.nama_ptn LIKE ? OR 
				data_ptn_prodi.nama_prodi LIKE ? OR 
				data_ptn_prodi.nama_alternatif LIKE ?
			)";
			$search_params = array(
				"%$text%", "%$text%", "%$text%", "%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			//template to use when ajax search
			$this->view->search_template = "data_ptn_prodi/search.php";
		}

		// Add ordering by nama_ptn and nama_prodi
		$db->orderBy("nama_ptn", "ASC");
		$db->orderBy("nama_prodi", "ASC");

		if (!empty($request->orderby)) {
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		} else {
			$db->orderBy("data_ptn_prodi.id_alternatif", ORDER_TYPE);
		}
		if ($fieldname) {
			$db->where($fieldname, $fieldvalue); //filter by a single field name
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
		$page_title = $this->view->page_title = "Data Ptn Prodi";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$this->render_view("data_ptn_prodi/list.php", $data); //render the full page
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
			"id_alternatif",
			"nama_ptn",
			"nama_prodi",
			"nama_alternatif"
		);
		if ($value) {
			$db->where($rec_id, urldecode($value)); //select record based on field name
		} else {
			$db->where("data_ptn_prodi.id_alternatif", $rec_id);; //select record based on primary key
		}
		$record = $db->getOne($tablename, $fields);
		if ($record) {
			$page_title = $this->view->page_title = "View  Data Ptn Prodi";
			$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
			$this->view->report_title = $page_title;
			$this->view->report_layout = "report_layout.php";
			$this->view->report_paper_size = "A4";
			$this->view->report_orientation = "portrait";
		} else {
			if ($db->getLastError()) {
				$this->set_page_error();
			} else {
				$this->set_page_error("No record found");
			}
		}
		return $this->render_view("data_ptn_prodi/view.php", $record);
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
			$fields = $this->fields = array("nama_ptn", "nama_prodi", "nama_alternatif");
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'nama_ptn' => 'required',
				'nama_prodi' => 'required',
			);
			$this->sanitize_array = array(
				'nama_ptn' => 'sanitize_string',
				'nama_prodi' => 'sanitize_string',
				'nama_alternatif' => 'sanitize_string',
			);
			// Create 'nama_alternatif' from 'nama_ptn' and 'nama_prodi'
			$postdata['nama_alternatif'] = $postdata['nama_ptn'] . ' - ' . $postdata['nama_prodi'];
			$this->filter_vals = true; //set whether to remove empty fields
			$modeldata = $this->modeldata = $this->validate_form($postdata);

			if ($this->validated()) {
				// Check if the PTN and Prodi combination already exists
				$db->where('nama_ptn', $modeldata['nama_ptn']);
				$db->where('nama_prodi', $modeldata['nama_prodi']);
				$existing_record = $db->getOne($tablename);

				if ($existing_record) {
					$this->set_page_error("Data PTN dan Prodi sudah ada!");
				} else {
					$rec_id = $this->rec_id = $db->insert($tablename, $modeldata);
					if ($rec_id) {
						$this->set_flash_msg("Data Berhasil Ditambahkan", "success");
						return $this->redirect("data_ptn_prodi");
					} else {
						$this->set_page_error();
					}
				}
			}
		}
		$page_title = $this->view->page_title = "Add New Data Ptn Prodi";
		$this->render_view("data_ptn_prodi/add.php");
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
		$fields = $this->fields = array("id_alternatif", "nama_ptn", "nama_prodi", "nama_alternatif");
		if ($formdata) {
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'nama_ptn' => 'required',
				'nama_prodi' => 'required',
			);
			$this->sanitize_array = array(
				'nama_ptn' => 'sanitize_string',
				'nama_prodi' => 'sanitize_string',
				'nama_alternatif' => 'sanitize_string',
			);
			// Create 'nama_alternatif' from 'nama_ptn' and 'nama_prodi'
			$postdata['nama_alternatif'] = $postdata['nama_ptn'] . ' - ' . $postdata['nama_prodi'];
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			if ($this->validated()) {
				$db->where("data_ptn_prodi.id_alternatif", $rec_id);;
				$bool = $db->update($tablename, $modeldata);
				$numRows = $db->getRowCount(); //number of affected rows. 0 = no record field updated
				if ($bool && $numRows) {
					// $this->namaAlternatif();
					$this->set_flash_msg("Data Berhasil Diperbarui", "success");
					return $this->redirect("data_ptn_prodi");
				} else {
					if ($db->getLastError()) {
						$this->set_page_error();
					} elseif (!$numRows) {
						//not an error, but no record was updated
						$page_error = "No record updated";
						$this->set_page_error($page_error);
						$this->set_flash_msg($page_error, "warning");
						return	$this->redirect("data_ptn_prodi");
					}
				}
			}
		}
		$db->where("data_ptn_prodi.id_alternatif", $rec_id);;
		$data = $db->getOne($tablename, $fields);
		$page_title = $this->view->page_title = "Edit  Data Ptn Prodi";
		if (!$data) {
			$this->set_page_error();
		}
		return $this->render_view("data_ptn_prodi/edit.php", $data);
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
		$fields = $this->fields = array("id_alternatif", "nama_ptn", "nama_prodi", "nama_alternatif");
		$page_error = null;
		if ($formdata) {
			$postdata = array();
			$fieldname = $formdata['name'];
			$fieldvalue = $formdata['value'];
			$postdata[$fieldname] = $fieldvalue;
			$postdata = $this->format_request_data($postdata);
			$this->rules_array = array(
				'nama_ptn' => 'required',
				'nama_prodi' => 'required',
			);
			$this->sanitize_array = array(
				'nama_ptn' => 'sanitize_string',
				'nama_prodi' => 'sanitize_string',
				'nama_alternatif' => 'sanitize_string',
			);
			$this->filter_rules = true; //filter validation rules by excluding fields not in the formdata
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			if ($this->validated()) {
				$db->where("data_ptn_prodi.id_alternatif", $rec_id);;
				$bool = $db->update($tablename, $modeldata);
				$numRows = $db->getRowCount();
				if ($bool && $numRows) {
					$this->namaAlternatif();
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
		$db->where("data_ptn_prodi.id_alternatif", $arr_rec_id, "in");
		$bool = $db->delete($tablename);
		if ($bool) {
			$this->set_flash_msg("Data Berhasil Dihapus", "success");
		} elseif ($db->getLastError()) {
			$page_error = $db->getLastError();
			$this->set_flash_msg($page_error, "danger");
		}
		return	$this->redirect("data_ptn_prodi");
	}
}
