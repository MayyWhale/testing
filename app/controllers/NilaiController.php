<?php

/**
 * Nilai Page Controller
 * @category  Controller
 */
class NilaiController extends SecureController
{
	function __construct()
	{
		parent::__construct();
		$this->tablename = "nilai";
	}

	// public function updateNilaiForNewKriteria($id_kriteria)
	// {
	// 	$db = $this->GetModel();
	// 	$nilai_records = $db->get($this->tablename);

	// 	foreach ($nilai_records as $record) {
	// 		$id_nilai = $record['id_nilai'];
	// 		$existing_kriteria = explode(',', $record['id_kriteria']);
	// 		$existing_nilai = explode(',', $record['nilai']);

	// 		// Add the new kriteria id and a default value if it does not exist
	// 		if (!in_array($id_kriteria, $existing_kriteria)) {
	// 			$existing_kriteria[] = $id_kriteria;
	// 			$existing_nilai[] = '...'; // Default value for new kriteria

	// 			$update_data = array(
	// 				'id_kriteria' => implode(',', $existing_kriteria),
	// 				'nilai' => implode(',', $existing_nilai)
	// 			);

	// 			// Update the record
	// 			$db->where('id_nilai', $id_nilai);
	// 			$db->update($this->tablename, $update_data);
	// 		}
	// 	}
	// }
	public function updateNilaiForNewKriteria($id_kriteria)
	{
		$db = $this->GetModel();
		$nilai_records = $db->get($this->tablename);

		foreach ($nilai_records as $record) {
			$id_nilai = $record['id_nilai'];
			$existing_kriteria = explode(',', $record['id_kriteria']);
			$existing_nilai = explode(',', $record['nilai']);

			// Add the new kriteria id and a default value if it does not exist
			if (!in_array($id_kriteria, $existing_kriteria)) {
				$existing_kriteria[] = $id_kriteria;
				$existing_nilai[] = '0'; // Default value, could be average, median, etc.

				$update_data = array(
					'id_kriteria' => implode(',', $existing_kriteria),
					'nilai' => implode(',', $existing_nilai)
				);

				// Update the record
				$db->where('id_nilai', $id_nilai);
				$db->update($this->tablename, $update_data);
			}
		}
		// Panggil calculate_weighted_product dari PerhitunganController
		$perhitunganController = new PerhitunganController();
		$perhitunganController->calculate_weighted_product();
	}

	public function updateNilaiForDeletedKriteria($id_kriteria)
	{
		$db = $this->GetModel();
		$nilai_records = $db->get($this->tablename);

		foreach ($nilai_records as $record) {
			$id_nilai = $record['id_nilai'];
			$existing_kriteria = explode(',', $record['id_kriteria']);
			$existing_nilai = explode(',', $record['nilai']);

			// Find the index of the kriteria to be deleted
			$index = array_search($id_kriteria, $existing_kriteria);

			if ($index !== false) {
				// Remove the kriteria and the corresponding nilai
				unset($existing_kriteria[$index]);
				unset($existing_nilai[$index]);

				// Re-index the arrays to maintain consistency
				$existing_kriteria = array_values($existing_kriteria);
				$existing_nilai = array_values($existing_nilai);

				$update_data = array(
					'id_kriteria' => implode(',', $existing_kriteria),
					'nilai' => implode(',', $existing_nilai)
				);

				// Update the record
				$db->where('id_nilai', $id_nilai);
				$db->update($this->tablename, $update_data);
			}
		}
		// Panggil calculate_weighted_product dari PerhitunganController
		$perhitunganController = new PerhitunganController();
		$perhitunganController->calculate_weighted_product();
	}
	// private function hitung_nilai_batas($kriteria_bobot, $kriteria_kategori, $variabel_bobot)
	// {
	// 	$nilai_max = 1;
	// 	$nilai_min = 1;

	// 	foreach ($kriteria_bobot as $id_kriteria => $bobot) {
	// 		$kategori = $kriteria_kategori[$id_kriteria];
	// 		$nilai_maks = 85; // Nilai maksimum untuk kriteria tanpa sub-kriteria
	// 		$nilai_minim = 0;  // Nilai minimum untuk kriteria tanpa sub-kriteria

	// 		// Periksa apakah kriteria memiliki sub-kriteria
	// 		if (array_key_exists($id_kriteria, $variabel_bobot)) {
	// 			$nilai_maks = max($variabel_bobot[$id_kriteria]);
	// 			$nilai_minim = min($variabel_bobot[$id_kriteria]);
	// 		}

	// 		if ($kategori == 'cost') {
	// 			$nilai_max *= pow($nilai_minim, $bobot);
	// 			$nilai_min *= pow($nilai_maks, $bobot);
	// 		} else {
	// 			$nilai_max *= pow($nilai_maks, $bobot);
	// 			$nilai_min *= pow($nilai_minim, $bobot);
	// 		}
	// 	}

	// 	return array(
	// 		'max' => $nilai_max,
	// 		'min' => $nilai_min
	// 	);
	// }

	// private function beri_rekomendasi($nilai_akhir, $nilai_batas, $nilai_batas_sedang)
	// {
	// 	if ($nilai_akhir < $nilai_batas_sedang) {
	// 		return [
	// 			'label' => 'Rendah',
	// 			'keterangan' => 'Pertimbangkan untuk memilih prodi yang ketetanya lebih rendah (peminatnya lebih sedikit), sesuaikan prodi dengan jurusan SMA mu saat ini (tidak lintas jurusan), dan pilih prodi yang banyak alumni dari sekolah.'
	// 		];
	// 	} elseif ($nilai_akhir < $nilai_batas['max']) {
	// 		return [
	// 			'label' => 'Sedang',
	// 			'keterangan' => 'Perhatikan urutan prodi, dan pertimbangkan prodi dengan ketetatan lebih rendah atau prodi yang sama di PTN lain yang ketetatanya lebih rendah.'
	// 		];
	// 	} else {
	// 		return [
	// 			'label' => 'Tinggi',
	// 			'keterangan' => 'Perhatikan apakah ada siswa lain di sekolah yang memilih prodi dan PTN yang sama. Dengan prestasi sebagus itu dan jaringan alumni yang kuat rekomendasi untuk memilih prodi tersebut cukup tinggi.'
	// 		];
	// 	}
	// }


	/**
	 * List page records
	 * @param $fieldname (filter record by a field) 
	 * @param $fieldvalue (filter field value)
	 * @return BaseView
	 */
	function index($fieldname = null, $fieldvalue = null)
	{
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$fields = array(
			"nilai.id_nilai",
			"nilai.id_alternatif",
			"nilai.id_kriteria",
			"nilai.nilai",
			"data_ptn_prodi.nama_ptn",
			"data_ptn_prodi.nama_prodi",
		);
		$pagination = $this->get_pagination(MAX_RECORD_COUNT); // get pagination

		$user_id = USER_ID; // Mendapatkan user_id dari sesi login

		if (!empty($fieldname)) {
			$db->where($fieldname, $fieldvalue);
		}

		$db->where("nilai.user_id", $user_id); // Menambahkan kondisi untuk memfilter berdasarkan user_id
		$db->join("data_ptn_prodi", "nilai.id_alternatif = data_ptn_prodi.id_alternatif", "LEFT");
		$records = $db->get($tablename, null, $fields);

		// Get all kriteria dynamically from the database
		$kriteria_records = $db->get('kriteria', null, 'id_kriteria');
		$kriteria_ids = [];
		foreach ($kriteria_records as $record) {
			$kriteria_ids[] = $record['id_kriteria'];
		}

		// Group records by id_alternatif
		$grouped_records = [];
		foreach ($records as $record) {
			$id_alternatif = $record['id_alternatif'];
			if (!isset($grouped_records[$id_alternatif])) {
				$grouped_records[$id_alternatif] = [
					'id_nilai' => $record['id_nilai'], // Ensure id_nilai is captured
					'id_alternatif' => $id_alternatif,
					'nama_ptn' => $record['nama_ptn'],
					'nama_prodi' => $record['nama_prodi'],
					'nilai' => []
				];
			}
			$kriteria_recorded = explode(',', $record['id_kriteria']);
			$nilai_values = explode(',', $record['nilai']);

			// Sync with all available kriteria
			foreach ($kriteria_ids as $id_kriteria) {
				$index = array_search($id_kriteria, $kriteria_recorded);
				$nilai_value = $index !== false ? $nilai_values[$index] : '...';

				// Get the name of the variable
				if ($index !== false && is_numeric($nilai_value)) {
					$db->where('id_variabel', $nilai_value);
					$variabel = $db->getOne('variabel', 'nama_variabel');
					$nilai_value = $variabel ? $variabel['nama_variabel'] : $nilai_value;
				}

				$grouped_records[$id_alternatif]['nilai'][$id_kriteria] = $nilai_value;
			}
		}

		// Get total records count
		$total_records = $db->join("data_ptn_prodi", "nilai.id_alternatif = data_ptn_prodi.id_alternatif", "LEFT")
			->where("nilai.user_id", $user_id)
			->getValue($tablename, "count(*)");

		$data = new stdClass;
		$data->records = array_values($grouped_records);
		$data->record_count = count($grouped_records);
		$data->total_records = $total_records;
		$data->kriteria = $db->get('kriteria'); // Get all kriteria for displaying columns

		$this->view->page_title = "Nilai";
		$this->render_view("nilai/list.php", $data);
	}
	/**
	 * View record detail
	 * @param $rec_id (select record by table primary key)
	 * @param $value value (select record by value of field name(rec_id))
	 * @return BaseView
	 */
	function view($rec_id = null, $value = null)
	{
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$fields = array(
			"nilai.id_nilai",
			"nilai.id_alternatif",
			"nilai.id_kriteria",
			"nilai.nilai",
			"data_ptn_prodi.nama_ptn",
			"data_ptn_prodi.nama_prodi",
		);

		if ($value) {
			$db->where($rec_id, urldecode($value)); // select record based on field name
		} else {
			$db->where("nilai.id_nilai", $rec_id); // select record based on primary key
		}

		$db->join("data_ptn_prodi", "nilai.id_alternatif = data_ptn_prodi.id_alternatif", "LEFT");
		$record = $db->getOne($tablename, $fields);

		if ($record) {
			// Parse id_kriteria and nilai fields for display
			$record['id_kriteria'] = !empty($record['id_kriteria']) ? explode(',', $record['id_kriteria']) : [];
			$record['nilai'] = !empty($record['nilai']) ? explode(',', $record['nilai']) : [];

			// Get all kriteria dynamically from the database
			$kriteria_records = $db->get('kriteria', null, 'id_kriteria');
			$kriteria_ids = [];
			foreach ($kriteria_records as $kriteria) {
				$kriteria_ids[] = $kriteria['id_kriteria'];
			}

			// Sync nilai with all available kriteria
			$nilai = [];
			foreach ($kriteria_ids as $id_kriteria) {
				$index = array_search($id_kriteria, $record['id_kriteria']);
				$nilai_value = $index !== false ? $record['nilai'][$index] : '...';
				$nilai[$id_kriteria] = $nilai_value;
			}

			$record['nilai'] = $nilai;

			$page_title = $this->view->page_title = "View Nilai";
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
		return $this->render_view("nilai/view.php", $record);
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

			// Initialize arrays to collect validated data
			$validated_data = array();

			// Validate id_alternatif
			if (!empty($formdata['id_alternatif'])) {
				$id_alternatif = filter_var($formdata['id_alternatif'], FILTER_SANITIZE_NUMBER_INT);

				// Check if the alternative already exists for the user
				$user_id = USER_ID;
				$existing_record = $db->where('id_alternatif', $id_alternatif)
					->where('user_id', $user_id)
					->getOne($tablename);
				if ($existing_record) {
					$this->set_page_error("Alternatif sudah ada sebelumnya, Pilih Alternatif Lain!");
					$this->view->formdata = $formdata; // Keep form data for redisplay
					return $this->render_view("nilai/add.php");
				}

				$validated_data['id_alternatif'] = $id_alternatif;
			} else {
				$this->set_page_error("Masukkan Alternatif");
				$this->view->formdata = $formdata; // Keep form data for redisplay
				return $this->render_view("nilai/add.php");
			}

			// Get all kriteria dynamically from the database
			$kriteria_records = $db->get('kriteria', null, 'id_kriteria');
			$kriteria_ids = [];
			foreach ($kriteria_records as $record) {
				$kriteria_ids[] = $record['id_kriteria'];
			}

			// Validate nilai array
			if (!empty($formdata['nilai']) && is_array($formdata['nilai'])) {
				$nilai_values = [];
				foreach ($kriteria_ids as $id_kriteria) {
					if (isset($formdata['nilai'][$id_kriteria])) {
						$nilai = $formdata['nilai'][$id_kriteria];
						$db->where("id_kriteria", $id_kriteria);
						$kriteria = $db->getOne("kriteria");

						if ($kriteria) {
							$nama_kriteria = $kriteria['nama_kriteria'];
							if ($kriteria['kategori'] == 'benefit' || $kriteria['kategori'] == 'cost') {
								// Validate as decimal with two decimal places
								if (!preg_match('/^\d+(\.\d{2})?$/', $nilai)) {
									$this->set_page_error("Pada: $nama_kriteria. Gunakan Tanda Titik (.) dan Dua Angka Dibelakang Titik");
									continue;
								}
							} else {
								// Validate as string
								$nilai = filter_var($nilai, FILTER_SANITIZE_STRING);
							}

							$nilai_values[] = $nilai;
						} else {
							$this->set_page_error("Invalid Kriteria ID: $id_kriteria.");
						}
					} else {
						$nilai_values[] = ''; // Add empty string if nilai is not set for this kriteria
					}
				}

				$validated_data['id_kriteria'] = implode(',', $kriteria_ids);
				$validated_data['nilai'] = implode(',', $nilai_values);
			} else {
				$this->set_page_error("Masukkan Semua Nilai");
				$this->view->formdata = $formdata; // Keep form data for redisplay
				return $this->render_view("nilai/add.php");
			}

			// If validation passes, insert the nilai entry into the database
			if ($this->validated()) {
				$user_id = USER_ID; // Mendapatkan user_id dari sesi login

				$record = array(
					'id_alternatif' => $validated_data['id_alternatif'],
					'id_kriteria' => $validated_data['id_kriteria'],
					'nilai' => $validated_data['nilai'],
					'user_id' => $user_id // Tambahkan user_id ke setiap entri
				);

				$rec_id = $this->rec_id = $db->insert($tablename, $record);

				if (!$rec_id) {
					$this->set_page_error();
				}
				// // Hitung dan simpan perhitungan setelah data nilai ditambahkan
				// $this->calculate_weighted_product($modeldata['user_id']);
				// Hitung dan simpan perhitungan setelah data nilai ditambahkan
				$perhitunganController = new PerhitunganController();
				$perhitunganController->calculate_weighted_product();

				$this->set_flash_msg("Data Berhasil Ditambahkan", "success");
				return $this->redirect("nilai");
			} else {
				$this->view->formdata = $formdata; // Keep form data for redisplay
			}
		}

		$page_title = $this->view->page_title = "Add New Nilai";
		$this->render_view("nilai/add.php");
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
		// Editable fields
		$fields = $this->fields = array("id_nilai", "id_alternatif", "id_kriteria", "nilai");

		if ($formdata) {
			$postdata = $this->format_request_data($formdata);

			// Validate id_alternatif
			$this->rules_array = array(
				'id_alternatif' => 'required|numeric',
			);
			$this->sanitize_array = array(
				'id_alternatif' => 'sanitize_string',
			);
			$modeldata = $this->modeldata = $this->validate_form($postdata);

			if ($this->validated()) {
				// Get all kriteria dynamically from the database
				$kriteria_records = $db->get('kriteria', null, 'id_kriteria');
				$kriteria_ids = [];
				foreach ($kriteria_records as $record) {
					$kriteria_ids[] = $record['id_kriteria'];
				}

				// Menggabungkan kembali id_kriteria dan nilai ke format string
				if (is_array($postdata['nilai'])) {
					$existing_kriteria = explode(',', $modeldata['id_kriteria']);
					$existing_nilai = explode(',', $modeldata['nilai']);

					// Sync with all available kriteria
					foreach ($kriteria_ids as $id_kriteria) {
						if (!in_array($id_kriteria, $existing_kriteria)) {
							$existing_kriteria[] = $id_kriteria;
							$existing_nilai[] = '...'; // Default value for new kriteria
						}
					}

					$modeldata['id_kriteria'] = implode(',', $existing_kriteria);
					$modeldata['nilai'] = implode(',', $existing_nilai);
				}

				$db->where("nilai.id_nilai", $rec_id);
				$bool = $db->update($tablename, $modeldata);
				$numRows = $db->getRowCount(); // Number of affected rows. 0 = no record field updated
				if ($bool && $numRows) {
					// Panggil calculate_weighted_product dari PerhitunganController
					$perhitunganController = new PerhitunganController();
					$perhitunganController->calculate_weighted_product();
					$this->set_flash_msg("Data Berhasil Diperbarui", "success");
					return $this->redirect("nilai");
				} else {
					if ($db->getLastError()) {
						$this->set_page_error();
					} elseif (!$numRows) {
						// Not an error, but no record was updated
						$page_error = "No record updated";
						$this->set_page_error($page_error);
						$this->set_flash_msg($page_error, "warning");
						return $this->redirect("nilai");
					}
				}
			}
		}

		$db->where("nilai.id_nilai", $rec_id);
		$data = $db->getOne($tablename, $fields);
		if (!$data) {
			$this->set_page_error("Data tidak ditemukan.");
			return $this->redirect("nilai");
		}

		// Parse id_kriteria and nilai fields
		$data['id_kriteria'] = !empty($data['id_kriteria']) ? explode(',', $data['id_kriteria']) : [];
		$data['nilai'] = !empty($data['nilai']) ? explode(',', $data['nilai']) : [];

		$page_title = $this->view->page_title = "Edit Nilai";
		return $this->render_view("nilai/edit.php", $data);
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
		$fields = $this->fields = array("id_nilai", "id_alternatif", "id_kriteria", "nilai");
		$page_error = null;
		if ($formdata) {
			$postdata = array();
			$fieldname = $formdata['name'];
			$fieldvalue = $formdata['value'];
			$postdata[$fieldname] = $fieldvalue;
			$postdata = $this->format_request_data($postdata);
			$this->rules_array = array(
				'id_alternatif' => 'required|numeric',
				'id_kriteria' => 'required',
				'nilai' => 'required',
			);
			$this->sanitize_array = array(
				'id_alternatif' => 'sanitize_string',
				'id_kriteria' => 'sanitize_string',
				'nilai' => 'sanitize_string',
			);
			$this->filter_rules = true; //filter validation rules by excluding fields not in the formdata
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			if ($this->validated()) {
				// Menggabungkan kembali id_kriteria dan nilai ke format string jika diperlukan
				if (is_array($modeldata['id_kriteria'])) {
					$modeldata['id_kriteria'] = implode(',', $modeldata['id_kriteria']);
				}
				if (is_array($modeldata['nilai'])) {
					$modeldata['nilai'] = implode(',', $modeldata['nilai']);
				}

				$db->where("nilai.id_nilai", $rec_id);
				$bool = $db->update($tablename, $modeldata);
				$numRows = $db->getRowCount();
				if ($bool && $numRows) {
					// Panggil calculate_weighted_product dari PerhitunganController
					$perhitunganController = new PerhitunganController();
					$perhitunganController->calculate_weighted_product();
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
		$db->where("nilai.id_nilai", $arr_rec_id, "in");
		$bool = $db->delete($tablename);
		if ($bool) {
			$this->set_flash_msg("Data Berhasil Dihapus", "success");
			// Panggil calculate_weighted_product dari PerhitunganController
			$perhitunganController = new PerhitunganController();
			$perhitunganController->calculate_weighted_product();
		} elseif ($db->getLastError()) {
			$page_error = $db->getLastError();
			$this->set_flash_msg($page_error, "danger");
		}
		return    $this->redirect("nilai");
	}
}
