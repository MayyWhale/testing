<?php

/**
 * Kriteria Page Controller
 * @category  Controller
 */
class KriteriaController extends SecureController
{
    function __construct()
    {
        parent::__construct();
        $this->tablename = "kriteria";
    }

    // Function to calculate total level kepentingan
    private function getTotalLevelKepentingan()
    {
        $db = $this->GetModel();
        $total = $db->getValue($this->tablename, "sum(level_kepentingan)");
        return $total ? $total : 0;
    }

    // Function to update bobot kriteria
    private function updateBobotKriteria()
    {
        $db = $this->GetModel();
        $totalLevelKepentingan = $this->getTotalLevelKepentingan();
        $query = "SELECT id_kriteria, level_kepentingan FROM $this->tablename";
        $kriteria = $db->rawQuery($query);

        foreach ($kriteria as $krit) {
            $bobot = number_format($krit['level_kepentingan'] / $totalLevelKepentingan, 3);
            $db->where('id_kriteria', $krit['id_kriteria']);
            $db->update($this->tablename, ['bobot_kriteria' => $bobot]);
        }
    }
    public function getSubKriteria($id_kriteria)
    {
        $variabelModel = new VariabelModel();
        $subKriteria = $variabelModel->where('id_kriteria', $id_kriteria)->findAll();
        $this->view->render('partials/sub_kriteria_list.php', ['sub_kriteria' => $subKriteria]);
    }


    function index($fieldname = null, $fieldvalue = null)
    {
        $request = $this->request;
        $db = $this->GetModel();
        $tablename = "kriteria";
        $fields = array(
            "id_kriteria",
            "nama_kriteria",
            "kategori",
            "level_kepentingan",
            "bobot_kriteria"
        );
        $pagination = $this->get_pagination(MAX_RECORD_COUNT);

        if (!empty($request->search)) {
            $text = trim($request->search);
            $search_condition = "(
                kriteria.id_kriteria LIKE ? OR 
                kriteria.nama_kriteria LIKE ? OR 
                kriteria.kategori LIKE ? OR 
                kriteria.level_kepentingan LIKE ? OR 
                kriteria.bobot_kriteria LIKE ?
            )";
            $search_params = array(
                "%$text%", "%$text%", "%$text%", "%$text%", "%$text%"
            );
            $db->where($search_condition, $search_params);
            $this->view->search_template = "kriteria/search.php";
        }

        if ($fieldname) {
            $db->where($fieldname, $fieldvalue);
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

        // Load variabel for each kriteria
        foreach ($data->records as &$record) {
            $db->where("id_kriteria", $record['id_kriteria']);
            $record['variabel'] = $db->get("variabel");
        }

        if ($db->getLastError()) {
            $this->set_page_error();
        }
        $page_title = $this->view->page_title = "Kriteria";
        $this->view->report_filename = date('Y-m-d') . '-' . $page_title;
        $this->view->report_title = $page_title;
        $this->view->report_layout = "report_layout.php";
        $this->view->report_paper_size = "A4";
        $this->view->report_orientation = "portrait";
        $this->render_view("kriteria/list.php", $data);
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
            "id_kriteria",
            "nama_kriteria",
            "kategori",
            "level_kepentingan",
            "bobot_kriteria"
        );
        if ($value) {
            $db->where($rec_id, urldecode($value)); //select record based on field name
        } else {
            $db->where("kriteria.id_kriteria", $rec_id);; //select record based on primary key
        }
        $record = $db->getOne($tablename, $fields);
        if ($record) {
            $page_title = $this->view->page_title = "View  Kriteria";
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
        return $this->render_view("kriteria/view.php", $record);
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
            $fields = $this->fields = array("nama_kriteria", "kategori", "level_kepentingan", "bobot_kriteria");
            $postdata = $this->format_request_data($formdata);
            $this->rules_array = [
                'nama_kriteria' => 'required',
                'kategori' => 'required',
                'level_kepentingan' => 'required|numeric',
            ];
            $this->sanitize_array = array(
                'nama_kriteria' => 'sanitize_string',
                'kategori' => 'sanitize_string',
                'level_kepentingan' => 'sanitize_string',
            );
            $modeldata = $this->modeldata = $this->validate_form($postdata);
            if ($this->validated()) {
                $rec_id = $this->rec_id = $db->insert($tablename, $modeldata);
                if ($rec_id) {
                    // Panggil fungsi updateNilaiForNewKriteria setelah menambahkan kriteria baru
                    $nilaiController = new NilaiController();
                    $nilaiController->updateNilaiForNewKriteria($rec_id);
                    $this->updateBobotKriteria();
                    $this->set_flash_msg("Data Berhasil Ditambahkan", "success");
                    return  $this->redirect("kriteria");
                } else {
                    $this->set_page_error();
                }
            }
        }
        $page_title = $this->view->page_title = "Add New Kriteria";
        $this->render_view("kriteria/add.php");
    }

    /**
     * Insert new sub criteria to the database table
     * @param $formdata array() from $_POST
     * @return BaseView
     */

    function addSub($formdata = null)
    {
        if ($formdata) {
            $db = $this->GetModel();
            $tablename = "variabel";
            $request = $this->request;

            // Fillable fields for variabel
            $fields = array("nama_variabel", "id_kriteria", "bobot_variabel");
            $variabel_data = [];
            foreach ($formdata['variabel'] as $variabel) {
                $variabel_data[] = [
                    'nama_variabel' => $variabel['nama_variabel'],
                    'id_kriteria' => $formdata['id_kriteria'],
                    'bobot_variabel' => $variabel['bobot_variabel']
                ];
            }

            if ($db->insertMulti($tablename, $variabel_data)) {
                $this->set_flash_msg("Sub Kriteria Berhasil Ditambahkan", "success");
            } else {
                $this->set_flash_msg("Error adding sub kriteria: " . $db->getLastError(), "danger");
            }

            return $this->redirect("kriteria");
        }
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
        $fields = $this->fields = array("id_kriteria", "nama_kriteria", "kategori", "level_kepentingan", "bobot_kriteria");
        if ($formdata) {
            $postdata = $this->format_request_data($formdata);
            $this->rules_array = array(
                'nama_kriteria' => 'required',
                'kategori' => 'required',
                'level_kepentingan' => 'required',
                // 'bobot_kriteria' => 'required',
            );
            $this->sanitize_array = array(
                'nama_kriteria' => 'sanitize_string',
                'kategori' => 'sanitize_string',
                'level_kepentingan' => 'sanitize_string',
                // 'bobot_kriteria' => 'sanitize_string',
            );
            $modeldata = $this->modeldata = $this->validate_form($postdata);
            if ($this->validated()) {
                $db->where("kriteria.id_kriteria", $rec_id);;
                $bool = $db->update($tablename, $modeldata);
                $numRows = $db->getRowCount(); //number of affected rows. 0 = no record field updated
                if ($bool && $numRows) {
                    $this->updateBobotKriteria();
                    $this->set_flash_msg("Data Berhasil Diperbarui", "success");
                    return $this->redirect("kriteria");
                } else {
                    if ($db->getLastError()) {
                        $this->set_page_error();
                    } elseif (!$numRows) {
                        //not an error, but no record was updated
                        $page_error = "No record updated";
                        $this->set_page_error($page_error);
                        $this->set_flash_msg($page_error, "warning");
                        return    $this->redirect("kriteria");
                    }
                }
            }
        }

        $db->where("kriteria.id_kriteria", $rec_id);;
        $data = $db->getOne($tablename, $fields);
        $page_title = $this->view->page_title = "Edit  Kriteria";
        if (!$data) {
            $this->set_page_error();
        }
        return $this->render_view("kriteria/edit.php", $data);
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
        $fields = $this->fields = array("id_kriteria", "nama_kriteria", "kategori", "level_kepentingan", "bobot_kriteria");
        $page_error = null;
        if ($formdata) {
            $postdata = array();
            $fieldname = $formdata['name'];
            $fieldvalue = $formdata['value'];
            $postdata[$fieldname] = $fieldvalue;
            $postdata = $this->format_request_data($postdata);
            $this->rules_array = array(
                'nama_kriteria' => 'required',
                'kategori' => 'required',
                'level_kepentingan' => 'required',
                // 'bobot_kriteria' => 'required',
            );
            $this->sanitize_array = array(
                'nama_kriteria' => 'sanitize_string',
                'kategori' => 'sanitize_string',
                'level_kepentingan' => 'sanitize_string',
                // 'bobot_kriteria' => 'sanitize_string',
            );
            $this->filter_rules = true; //filter validation rules by excluding fields not in the formdata
            $modeldata = $this->modeldata = $this->validate_form($postdata);
            if ($this->validated()) {
                $db->where("kriteria.id_kriteria", $rec_id);;
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
    // Other methods...

    /**
     * Delete record from the database
     * Support multi delete by separating record id by comma.
     * @return BaseView
     */
    function delete($rec_id = null)
    {
        Csrf::cross_check(); // Validate the CSRF token
        $request = $this->request;
        $db = $this->GetModel();
        $tablename = $this->tablename;
        $this->rec_id = $rec_id;

        // Debugging output
        error_log("Delete method called with rec_id: " . $rec_id);

        $arr_rec_id = array_map('trim', explode(",", $rec_id));
        $db->where("kriteria.id_kriteria", $arr_rec_id, "in");
        $bool = $db->delete($tablename);
        if ($bool) {
            // Panggil fungsi updateNilaiForDeletedKriteria di NilaiController
            $nilaiController = new NilaiController();
            foreach ($arr_rec_id as $id_kriteria) {
                $nilaiController->updateNilaiForDeletedKriteria($id_kriteria);
            }
            error_log("Record deleted successfully.");
            $this->updateBobotKriteria();
            $this->set_flash_msg("Data Berhasil Dihapus", "success");
        } elseif ($db->getLastError()) {
            $page_error = $db->getLastError();
            error_log("Delete error: " . $page_error);
            $this->set_flash_msg($page_error, "danger");
        }
        return $this->redirect("kriteria");
    }
}

// Handle the incoming request and call the appropriate method
$controller = new KriteriaController();
$action = $_GET['action'] ?? '';

if ($action == 'add' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $controller->add($_POST);
} elseif ($action == 'delete') {
    $rec_id = $_GET['rec_id'] ?? null;
    $controller->delete($rec_id);
} elseif ($action == 'addSub' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $controller->addSub($_POST);
} else {
    echo "";
}
