<?php
class PerhitunganController extends SecureController
{
	function __construct()
	{
		parent::__construct();
		$this->tablename = "perhitungan";
	}
	public function updateNilaiForNewKriteria($id_kriteria)
	{
		$db = $this->GetModel();
		$nilai_records = $db->get("nilai");

		foreach ($nilai_records as $record) {
			$id_nilai = $record['id_nilai'];
			$existing_kriteria = explode(',', $record['id_kriteria']);
			$existing_nilai = explode(',', $record['nilai']);

			if (!in_array($id_kriteria, $existing_kriteria)) {
				$existing_kriteria[] = $id_kriteria;
				$existing_nilai[] = '0';

				$update_data = array(
					'id_kriteria' => implode(',', $existing_kriteria),
					'nilai' => implode(',', $existing_nilai)
				);

				$db->where('id_nilai', $id_nilai);
				$db->update("nilai", $update_data);
			}
		}

		$this->calculate_weighted_product();
	}

	public function updateNilaiForDeletedKriteria($id_kriteria)
	{
		$db = $this->GetModel();
		$nilai_records = $db->get("nilai");

		foreach ($nilai_records as $record) {
			$id_nilai = $record['id_nilai'];
			$existing_kriteria = explode(',', $record['id_kriteria']);
			$existing_nilai = explode(',', $record['nilai']);

			$index = array_search($id_kriteria, $existing_kriteria);

			if ($index !== false) {
				unset($existing_kriteria[$index]);
				unset($existing_nilai[$index]);

				$existing_kriteria = array_values($existing_kriteria);
				$existing_nilai = array_values($existing_nilai);

				$update_data = array(
					'id_kriteria' => implode(',', $existing_kriteria),
					'nilai' => implode(',', $existing_nilai)
				);

				$db->where('id_nilai', $id_nilai);
				$db->update("nilai", $update_data);
			}
		}

		$this->calculate_weighted_product();
	}

	private function hitung_nilai_batas($kriteria_bobot, $kriteria_kategori, $variabel_bobot)
	{
		$nilai_max = 1;
		$nilai_min = 1;

		foreach ($kriteria_bobot as $id_kriteria => $bobot) {
			$kategori = $kriteria_kategori[$id_kriteria];
			$nilai_maks = 85; // Nilai maksimum untuk kriteria tanpa sub-kriteria
			$nilai_minim = 0.01;  // Nilai minimum untuk kriteria tanpa sub-kriteria

			// Periksa apakah kriteria memiliki sub-kriteria
			if (array_key_exists($id_kriteria, $variabel_bobot)) {
				$nilai_maks = max($variabel_bobot[$id_kriteria]);
				$nilai_minim = min($variabel_bobot[$id_kriteria]);
			}

			if ($kategori == 'cost' || $kategori == 'benefit') {
				// 	$nilai_max *= pow($nilai_minim, $bobot);
				// 	$nilai_min *= pow($nilai_maks, $bobot);
				// } else {
				$nilai_max *= pow($nilai_maks, $bobot);
				$nilai_min *= pow($nilai_minim, $bobot);
			}
		}

		return array(
			'max' => $nilai_max,
			'min' => $nilai_min
		);
	}

	private function beri_rekomendasi($nilai_akhir, $nilai_batas, $nilai_batas_sedang)
	{
		if ($nilai_akhir < $nilai_batas_sedang) {
			return [
				'label' => 'Rendah',
				'keterangan' => 'Pertimbangkan untuk memilih prodi yang ketetanya lebih rendah (peminatnya lebih sedikit), sesuaikan prodi dengan jurusan SMA mu saat ini (tidak lintas jurusan), dan pilih prodi yang banyak alumni dari sekolah.'
			];
		} elseif ($nilai_akhir >= $nilai_batas_sedang && $nilai_akhir <= $nilai_batas['max']) {
			return [
				'label' => 'Sedang',
				'keterangan' => 'Perhatikan urutan prodi, dan pertimbangkan prodi dengan ketetatan lebih rendah atau prodi yang sama di PTN lain yang ketetatanya lebih rendah.'
			];
		} else {
			return [
				'label' => 'Tinggi',
				'keterangan' => 'Perhatikan apakah ada siswa lain di sekolah yang memilih prodi dan PTN yang sama. Dengan prestasi sebagus itu dan jaringan alumni yang kuat rekomendasi untuk memilih prodi tersebut cukup tinggi.'
			];
		}
	}

	public function calculate_weighted_product()
	{
		$db = $this->GetModel();

		// Hapus semua data dari tabel perhitungan sebelum memasukkan data baru
		$db->delete('perhitungan');

		// Ambil data nilai dari tabel nilai
		$nilai_records = $db->get('nilai');

		// Debugging
		// var_dump($nilai_records);

		// Ambil data kriteria untuk mendapatkan bobot dan kategori
		$kriteria_records = $db->get('kriteria');
		$kriteria_bobot = array_column($kriteria_records, 'bobot_kriteria', 'id_kriteria');
		$kriteria_kategori = array_column($kriteria_records, 'kategori', 'id_kriteria');
		// Debugging
		// var_dump($kriteria_bobot);
		// var_dump($kriteria_kategori);

		// Ambil data variabel untuk mendapatkan bobot sub kriteria
		$variabel_records = $db->get('variabel');
		$variabel_bobot = array();
		foreach ($variabel_records as $variabel) {
			$variabel_bobot[$variabel['id_kriteria']][$variabel['id_variabel']] = $variabel['bobot_variabel'];
		}
		// Debugging
		// var_dump($variabel_bobot);

		// Hitung nilai batas dinamis
		$nilai_batas = $this->hitung_nilai_batas($kriteria_bobot, $kriteria_kategori, $variabel_bobot);
		$nilai_batas_sedang = ($nilai_batas['max'] + $nilai_batas['min']) / 2;

		// Debugging
		// var_dump($nilai_batas);
		// var_dump($nilai_batas_sedang);

		// Inisialisasi variabel untuk menyimpan hasil perhitungan
		$hasil_perhitungan = array();
		foreach ($nilai_records as $nilai) {
			$id_alternatif = $nilai['id_alternatif'];
			$nilai_kriteria = explode(',', $nilai['nilai']);
			$id_kriteria = explode(',', $nilai['id_kriteria']);

			// Hitung nilai akhir dengan metode Weighted Product
			$nilai_akhir = 1;
			foreach ($nilai_kriteria as $index => $nilai_k) {
				if (isset($kriteria_bobot[$id_kriteria[$index]])) {
					$bobot = $kriteria_bobot[$id_kriteria[$index]];
					$kategori = $kriteria_kategori[$id_kriteria[$index]];

					// Jika kriteria adalah cost, bobotnya negatif
					if ($kategori == 'cost') {
						$bobot = -$bobot;
					}

					// Jika nilai_k adalah id_variabel, gunakan bobot variabel
					if (isset($variabel_bobot[$id_kriteria[$index]]) && isset($variabel_bobot[$id_kriteria[$index]][$nilai_k])) {
						$nilai_k = $variabel_bobot[$id_kriteria[$index]][$nilai_k];
					}

					$nilai_akhir *= pow($nilai_k, $bobot);
				}
			}

			// Simpan hasil perhitungan sementara
			$hasil_perhitungan[] = array(
				'id_alternatif' => $id_alternatif,
				'nilai_akhir' => $nilai_akhir,
				'user_id' => $nilai['user_id']
			);
		}
		// Debugging
		// var_dump($hasil_perhitungan);

		// Urutkan hasil perhitungan berdasarkan nilai akhir secara descending
		usort($hasil_perhitungan, function ($a, $b) {
			return $b['nilai_akhir'] <=> $a['nilai_akhir'];
		});

		// Simpan hasil perhitungan ke tabel perhitungan
		foreach ($hasil_perhitungan as $rank => $hasil) {
			$rekomendasi = $this->beri_rekomendasi($hasil['nilai_akhir'], $nilai_batas, $nilai_batas_sedang);
			$db->insert('perhitungan', array(
				'id_alternatif' => $hasil['id_alternatif'],
				'nilai_akhir' => $hasil['nilai_akhir'],
				'ranking' => $rank + 1,
				'rekomendasi' => $rekomendasi['label'],
				'keterangan' => $rekomendasi['keterangan'],
				'user_id' => $hasil['user_id']
			));
		}
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
			"perhitungan.id_perhitungan",
			"perhitungan.id_alternatif",
			"perhitungan.nilai_akhir",
			"perhitungan.ranking",
			"perhitungan.rekomendasi",
			"perhitungan.keterangan",
			"perhitungan.user_id",
			"perhitungan.status_lolos",
			"perhitungan.mengikuti_rekomendasi",
			"data_ptn_prodi.nama_alternatif",
			"siswa.nama_siswa",
			"siswa.kelas_siswa",
			"siswa.nis_siswa"
		);

		$pagination = $this->get_pagination(MAX_RECORD_COUNT);
		$user_id = USER_ID;
		$user_role_id = USER_ROLE;

		if (!empty($request->search)) {
			$text = trim($request->search);
			$search_condition = "(
				perhitungan.id_perhitungan LIKE ? OR 
				perhitungan.id_alternatif LIKE ? OR 
				perhitungan.nilai_akhir LIKE ? OR 
				perhitungan.ranking LIKE ? OR 
				perhitungan.rekomendasi LIKE ? OR 
				perhitungan.keterangan LIKE ? OR 
				perhitungan.user_id LIKE ? OR 
				perhitungan.status_lolos LIKE ? OR 
				perhitungan.mengikuti_rekomendasi LIKE ? OR
				data_ptn_prodi.nama_alternatif LIKE ? OR
				siswa.nama_siswa LIKE ? OR
				siswa.kelas_siswa LIKE ? OR
				siswa.nis_siswa LIKE ?
			)";
			$search_params = array(
				"%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%",
				"%$text%", "%$text%", "%$text%"
			);
			$db->where($search_condition, $search_params);
			$this->view->search_template = "perhitungan/search.php";
		}

		if ($fieldname) {
			$db->where($fieldname, $fieldvalue);
		}

		if ($user_role_id == 3) {
			$db->where("perhitungan.user_id", $user_id);
		}

		$db->join("data_ptn_prodi", "perhitungan.id_alternatif = data_ptn_prodi.id_alternatif", "LEFT");
		$db->join("siswa", "perhitungan.user_id = siswa.user_id", "LEFT");
		$tc = $db->withTotalCount();
		$records = $db->get($tablename, $pagination, $fields);

		// var_dump($records); // Debugging data awal
		$records_count = count($records);
		$total_records = intval($tc->totalCount);
		$page_limit = $pagination[1];
		$total_pages = ceil($total_records / $page_limit);

		// Mengambil data kriteria dan variabel untuk menghitung nilai batas
		$kriteria_records = $db->get('kriteria');
		$kriteria_bobot = array_column($kriteria_records, 'bobot_kriteria', 'id_kriteria');
		$kriteria_kategori = array_column($kriteria_records, 'kategori', 'id_kriteria');

		$variabel_records = $db->get('variabel');
		$variabel_bobot = array();
		foreach ($variabel_records as $variabel) {
			$variabel_bobot[$variabel['id_kriteria']][$variabel['id_variabel']] = $variabel['bobot_variabel'];
		}

		// Hitung nilai batas dinamis
		$nilai_batas = $this->hitung_nilai_batas($kriteria_bobot, $kriteria_kategori, $variabel_bobot);
		$nilai_batas_sedang = ($nilai_batas['max'] + $nilai_batas['min']) / 2;

		// Menghitung ulang ranking dan rekomendasi berdasarkan nilai_akhir untuk user yang login
		usort($records, function ($a, $b) {
			return $b['nilai_akhir'] <=> $a['nilai_akhir'];
		});

		foreach ($records as $index => &$record) {
			$record['ranking'] = $index + 1;
			$rekomendasi = $this->beri_rekomendasi($record['nilai_akhir'], $nilai_batas, $nilai_batas_sedang);
			$record['rekomendasi'] = $rekomendasi['label'];
			$record['keterangan'] = $rekomendasi['keterangan'];
		}
		// var_dump($records); // Debugging data awal
		// Fungsi untuk mengurutkan kelas
		function sortKelas($kelas)
		{
			$kelas_order = [
				"XII MIPA" => 1,
				"XI MIPA" => 2,
				"X MIPA" => 3,
				"XII IPS" => 4,
				"XI IPS" => 5,
				"X IPS" => 6,
				"XII BAHASA" => 7,
				"XI BAHASA" => 8,
				"X BAHASA" => 9,
			];
			foreach ($kelas_order as $key => $value) {
				if (strpos($kelas, $key) === 0) {
					return $value;
				}
			}
			return 100; // Return a large number for any class not in the list
		}

		// Kelompokkan data berdasarkan nama siswa dan urutkan berdasarkan kelas dan nama siswa
		// Group records by 'nama_siswa'
		// Pengelompokan data berdasarkan nis_siswa
		$grouped_records = [];
		foreach ($records as $record) {
			$nis_siswa = $record['nis_siswa'];

			if (!isset($grouped_records[$nis_siswa])) {
				$grouped_records[$nis_siswa] = [
					'siswa_info' => [
						'nama_siswa' => $record['nama_siswa'],
						'kelas_siswa' => $record['kelas_siswa'],
						'nis_siswa' => $record['nis_siswa']
					],
					'perhitungan' => []
				];
			}

			// Tambahkan data perhitungan ke dalam array siswa
			$grouped_records[$nis_siswa]['perhitungan'][] = $record;
		}


		// Debugging pengelompokan
		var_dump($grouped_records);

		// Debugging pengelompokan
		// var_dump($grouped_records);


		// Ensure no duplicates in grouped records
		// var_dump($grouped_records);
		// Ubah menjadi array numerik untuk memudahkan pengurutan
		$grouped_records = array_values($grouped_records);
		// Urutkan data berdasarkan kelas dan nama siswa
		usort($grouped_records, function ($a, $b) {
			$kelas_cmp = sortKelas($a['siswa_info']['kelas_siswa']) <=> sortKelas($b['siswa_info']['kelas_siswa']);
			if ($kelas_cmp === 0) {
				return strcmp(strtolower($a['siswa_info']['nama_siswa']), strtolower($b['siswa_info']['nama_siswa']));
			}
			return $kelas_cmp;
		});

		$data = new stdClass;
		$data->grouped_records = $grouped_records;
		$data->record_count = $records_count;
		$data->total_records = $total_records;
		$data->total_page = $total_pages;
		$data->nilai_batas = $nilai_batas; // Menambahkan nilai batas ke data
		$data->nilai_batas_sedang = $nilai_batas_sedang; // Menambahkan nilai batas sedang ke data


		if ($db->getLastError()) {
			$this->set_page_error();
		}

		if ($db->getLastError()) {
			$this->set_page_error();
		}

		$page_title = $this->view->page_title = "Perhitungan";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";

		// Render view berdasarkan role
		if ($user_role_id == 1 || $user_role_id == 2) {
			$this->render_view("perhitungan/list_guru.php", $data);
		} else {
			$this->render_view("perhitungan/list_siswa.php", $data);
		}
	}

	/**
	 * Update Status Lolos
	 * @param int $id_perhitungan
	 */
	public function update_status_lolos()
	{
		if (!ACL::is_allowed("perhitungan/update_status_lolos")) {
			$this->redirect("errors/forbidden");
		}

		$db = $this->GetModel();
		$id_perhitungan = isset($_POST['id_perhitungan']) ? $_POST['id_perhitungan'] : null;
		$status_lolos = isset($_POST['status_lolos']) ? $_POST['status_lolos'] : null;

		// Debugging
		var_dump($id_perhitungan, $status_lolos);

		if ($id_perhitungan !== null && $status_lolos !== null) {
			$data = array('status_lolos' => $status_lolos);

			$db->where('id_perhitungan', $id_perhitungan);
			if ($db->update('perhitungan', $data)) {
				$this->set_flash_msg("Status Lolos berhasil diperbarui", "success");
			} else {
				$this->set_flash_msg("Gagal memperbarui Status Lolos", "danger");
			}
		} else {
			$this->set_flash_msg("Data tidak valid", "danger");
		}

		return $this->redirect('perhitungan');
	}


	public function update_mengikuti_rekomendasi()
	{
		if (!ACL::is_allowed("perhitungan/update_mengikuti_rekomendasi")) {
			$this->redirect("errors/forbidden");
		}

		$db = $this->GetModel();
		// Gunakan PHP built-in untuk mengambil data POST
		$id_perhitungan = isset($_POST['id_perhitungan']) ? $_POST['id_perhitungan'] : null;
		$mengikuti_rekomendasi = isset($_POST['mengikuti_rekomendasi']) ? $_POST['mengikuti_rekomendasi'] : null;

		// Debugging
		var_dump($id_perhitungan, $mengikuti_rekomendasi);

		if ($id_perhitungan !== null && $mengikuti_rekomendasi !== null) {
			$data = array('mengikuti_rekomendasi' => $mengikuti_rekomendasi);

			$db->where('id_perhitungan', $id_perhitungan);
			if ($db->update('perhitungan', $data)) {
				$this->set_flash_msg("Status Mengikuti Rekomendasi berhasil diperbarui", "success");
			} else {
				$this->set_flash_msg("Gagal memperbarui Status Mengikuti Rekomendasi", "danger");
			}
		} else {
			$this->set_flash_msg("Data tidak valid", "danger");
		}

		return $this->redirect('perhitungan');
	}
	// 	function view($rec_id = null, $value = null)
	// 	{
	// 		$request = $this->request;
	// 		$db = $this->GetModel();
	// 		$rec_id = $this->rec_id = urldecode($rec_id);
	// 		$tablename = $this->tablename;
	// 		$fields = array(
	// 			"perhitungan.id_perhitungan",
	// 			"perhitungan.id_alternatif",
	// 			"perhitungan.nilai_akhir",
	// 			"perhitungan.ranking",
	// 			"perhitungan.rekomendasi",
	// 			"perhitungan.user_id",
	// 			"perhitungan.status_lolos",
	// 			"perhitungan.mengikuti_rekomendasi",
	// 			"data_ptn_prodi.nama_alternatif"  // Join field for alternative name
	// 		);
	// 		if ($value) {
	// 			$db->where($rec_id, urldecode($value));
	// 		} else {
	// 			$db->where("perhitungan.id_perhitungan", $rec_id);
	// 		}
	// 		$db->join("data_ptn_prodi", "perhitungan.id_alternatif = data_ptn_prodi.id_alternatif", "LEFT");
	// 		$record = $db->getOne($tablename, $fields);
	// 		if ($record) {
	// 			$page_title = $this->view->page_title = "View  Perhitungan";
	// 			$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
	// 			$this->view->report_title = $page_title;
	// 			$this->view->report_layout = "report_layout.php";
	// 			$this->view->report_paper_size = "A4";
	// 			$this->view->report_orientation = "portrait";
	// 		} else {
	// 			if ($db->getLastError()) {
	// 				$this->set_page_error();
	// 			} else {
	// 				$this->set_page_error("No record found");
	// 			}
	// 		}
	// 		return $this->render_view("perhitungan/view.php", $record);
	// 	}
}
