<?php

/**
 * SharedController Controller
 * @category  Controller / Model
 */
class SharedController extends BaseController
{

	/**
	 * user_username_value_exist Model Action
	 * @return array
	 */
	function user_username_value_exist($val)
	{
		$db = $this->GetModel();
		$db->where("username", $val);
		$exist = $db->has("user");
		return $exist;
	}

	/**
	 * user_email_value_exist Model Action
	 * @return array
	 */
	function user_email_value_exist($val)
	{
		$db = $this->GetModel();
		$db->where("email", $val);
		$exist = $db->has("user");
		return $exist;
	}

	/**
	 * user_user_role_id_option_list Model Action
	 * @return array
	 */
	function user_user_role_id_option_list()
	{
		$db = $this->GetModel();
		$sqltext = "SELECT role_id AS value, role_name AS label FROM roles";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
	 * nilai_siswa_k3_option_list Model Action
	 * @return array
	 */
	function nilai_siswa_k3_option_list()
	{
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT nama_variabel AS value,nama_variabel AS label FROM variabel WHERE id_kriteria ='Prestasi' ORDER BY id_variabel ASC";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
	 * nilai_siswa_k4_option_list Model Action
	 * @return array
	 */
	function nilai_siswa_k4_option_list()
	{
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT nama_variabel AS value,nama_variabel AS label FROM variabel WHERE id_kriteria ='Kesesuaian Pilihan' ORDER BY id_variabel ASC";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
	 * nilai_siswa_k5_option_list Model Action
	 * @return array
	 */
	function nilai_siswa_k5_option_list()
	{
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT nama_variabel AS value,nama_variabel AS label FROM variabel WHERE id_kriteria ='Alumni PTN' ORDER BY id_variabel ASC";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
	 * nilai_siswa_k6_option_list Model Action
	 * @return array
	 */
	function nilai_siswa_k6_option_list()
	{
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT nama_variabel AS value,nama_variabel AS label FROM variabel WHERE id_kriteria ='Alumni Prodi' ORDER BY id_variabel ASC";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
	 * nilai_siswa_id_alternatif_option_list Model Action
	 * @return array
	 */
	function nilai_siswa_id_alternatif_option_list()
	{
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT nama_alternatif AS value,nama_alternatif AS label FROM data_ptn_prodi ORDER BY id_alternatif ASC";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
	 * variabel_id_kriteria_option_list Model Action
	 * @return array
	 */
	function variabel_id_kriteria_option_list()
	{
		$db = $this->GetModel();
		$sqltext = "SELECT id_kriteria AS value , nama_kriteria AS label FROM kriteria";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
	 * nilai_id_alternatif_option_list Model Action
	 * @return array
	 */
	function nilai_id_alternatif_option_list()
	{
		$db = $this->GetModel();
		$sqltext = "SELECT id_alternatif AS value , nama_alternatif AS label FROM data_ptn_prodi";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
	 * getcount_siswa Model Action
	 * @return Value
	 */
	function getcount_siswa()
	{
		$db = $this->GetModel();
		$sqltext = "SELECT COUNT(*) AS num FROM siswa";
		$queryparams = null;
		$val = $db->rawQueryValue($sqltext, $queryparams);

		if (is_array($val)) {
			return $val[0];
		}
		return $val;
	}

	/**
	 * getcount_guru Model Action
	 * @return Value
	 */
	function getcount_guru()
	{
		$db = $this->GetModel();
		$sqltext = "SELECT COUNT(*) AS num FROM guru";
		$queryparams = null;
		$val = $db->rawQueryValue($sqltext, $queryparams);

		if (is_array($val)) {
			return $val[0];
		}
		return $val;
	}

	/**
	 * getcount_user Model Action
	 * @return Value
	 */
	function getcount_user()
	{
		$db = $this->GetModel();
		$sqltext = "SELECT COUNT(*) AS num FROM user";
		$queryparams = null;
		$val = $db->rawQueryValue($sqltext, $queryparams);

		if (is_array($val)) {
			return $val[0];
		}
		return $val;
	}

	/**
	 * getcount_kriteria Model Action
	 * @return Value
	 */
	function getcount_kriteria()
	{
		$db = $this->GetModel();
		$sqltext = "SELECT COUNT(*) AS num FROM kriteria";
		$queryparams = null;
		$val = $db->rawQueryValue($sqltext, $queryparams);

		if (is_array($val)) {
			return $val[0];
		}
		return $val;
	}

	/**
	 * getcount_subkriteria Model Action
	 * @return Value
	 */
	function getcount_subkriteria()
	{
		$db = $this->GetModel();
		$sqltext = "SELECT COUNT(*) AS num FROM variabel";
		$queryparams = null;
		$val = $db->rawQueryValue($sqltext, $queryparams);

		if (is_array($val)) {
			return $val[0];
		}
		return $val;
	}

	/**
	 * getcount_dataperguruantinggidanprogramstudi Model Action
	 * @return Value
	 */
	function getcount_dataperguruantinggidanprogramstudi()
	{
		$db = $this->GetModel();
		$sqltext = "SELECT COUNT(*) AS num FROM data_ptn_prodi";
		$queryparams = null;
		$val = $db->rawQueryValue($sqltext, $queryparams);

		if (is_array($val)) {
			return $val[0];
		}
		return $val;
	}

	/**
	 * getcount_nilaisiswa Model Action
	 * @return Value
	 */
	function getcount_nilaisiswa()
	{
		$db = $this->GetModel();
		$sqltext = "SELECT COUNT(*) AS num FROM nilai_siswa";
		$queryparams = null;
		$val = $db->rawQueryValue($sqltext, $queryparams);

		if (is_array($val)) {
			return $val[0];
		}
		return $val;
	}

	/**
	 * getcount_perhitungan Model Action
	 * @return Value
	 */
	function getcount_perhitungan()
	{
		$db = $this->GetModel();
		$sqltext = "SELECT COUNT(*) AS num FROM perhitungan";
		$queryparams = null;
		$val = $db->rawQueryValue($sqltext, $queryparams);

		if (is_array($val)) {
			return $val[0];
		}
		return $val;
	}

	/**
	 * piechart_presentasesiswalolos Model Action
	 * @return array
	 */
	function piechart_presentasesiswalolos()
	{
		$db = $this->GetModel();
		$chart_data = array(
			"labels" => array(
				"Lolos Mengikuti Rekomendasi",
				"Lolos Tidak Mengikuti Rekomendasi",
				"Tidak Lolos Mengikuti Rekomendasi",
				"Tidak Lolos Tidak Mengikuti Rekomendasi"
			),
			"datasets" => array(
				array(
					'data' => array(),
					'backgroundColor' => array(
						'rgba(75, 192, 192, 0.5)',
						'rgba(54, 162, 235, 0.5)',
						'rgba(255, 206, 86, 0.5)',
						'rgba(255, 99, 132, 0.5)'
					)
				)
			)
		);

		// Query untuk menghitung jumlah pada setiap kategori
		$sqltext_lolos_mengikuti = "SELECT COUNT(*) AS count FROM perhitungan WHERE status_lolos = 1 AND mengikuti_rekomendasi = 1";
		$sqltext_lolos_tidak_mengikuti = "SELECT COUNT(*) AS count FROM perhitungan WHERE status_lolos = 1 AND mengikuti_rekomendasi = 0";
		$sqltext_tidak_lolos_mengikuti = "SELECT COUNT(*) AS count FROM perhitungan WHERE status_lolos = 0 AND mengikuti_rekomendasi = 1";
		$sqltext_tidak_lolos_tidak_mengikuti = "SELECT COUNT(*) AS count FROM perhitungan WHERE status_lolos = 0 AND mengikuti_rekomendasi = 0";

		$count_lolos_mengikuti = $db->rawQueryValue($sqltext_lolos_mengikuti);
		$count_lolos_tidak_mengikuti = $db->rawQueryValue($sqltext_lolos_tidak_mengikuti);
		$count_tidak_lolos_mengikuti = $db->rawQueryValue($sqltext_tidak_lolos_mengikuti);
		$count_tidak_lolos_tidak_mengikuti = $db->rawQueryValue($sqltext_tidak_lolos_tidak_mengikuti);

		if (is_array($count_lolos_mengikuti)) {
			$count_lolos_mengikuti = $count_lolos_mengikuti[0];
		}
		if (is_array($count_lolos_tidak_mengikuti)) {
			$count_lolos_tidak_mengikuti = $count_lolos_tidak_mengikuti[0];
		}
		if (is_array($count_tidak_lolos_mengikuti)) {
			$count_tidak_lolos_mengikuti = $count_tidak_lolos_mengikuti[0];
		}
		if (is_array($count_tidak_lolos_tidak_mengikuti)) {
			$count_tidak_lolos_tidak_mengikuti = $count_tidak_lolos_tidak_mengikuti[0];
		}

		$chart_data["datasets"][0]['data'] = array(
			$count_lolos_mengikuti,
			$count_lolos_tidak_mengikuti,
			$count_tidak_lolos_mengikuti,
			$count_tidak_lolos_tidak_mengikuti
		);

		return $chart_data;
	}


	/**
	 * piechart_sebaranpilihanptndanprogramstudi Model Action
	 * @return array
	 */
	function piechart_sebaranpilihanptndanprogramstudi()
	{
		$db = $this->GetModel();
		$chart_data = array(
			"labels" => array(),
			"datasets" => array(),
		);

		//set query result for dataset 1
		$sqltext = "
	SELECT dp.nama_alternatif, COUNT(n.id_nilai) AS count_of_user_id 
	FROM nilai AS n 
	JOIN data_ptn_prodi AS dp ON n.id_alternatif = dp.id_alternatif 
	GROUP BY dp.nama_alternatif";
		$dataset1 = $db->rawQuery($sqltext);
		$dataset_data = array_column($dataset1, 'count_of_user_id');
		$dataset_labels = array_column($dataset1, 'nama_alternatif');
		$chart_data["labels"] = $dataset_labels;
		$chart_data["datasets"][] = array(
			'data' => $dataset_data,
			'backgroundColor' => array('rgba(255, 99, 132, 0.5)', 'rgba(54, 162, 235, 0.5)', 'rgba(255, 206, 86, 0.5)', 'rgba(75, 192, 192, 0.5)', 'rgba(153, 102, 255, 0.5)', 'rgba(255, 159, 64, 0.5)')
		);

		return $chart_data;
	}

	/**
	 * Get Siswa by Alternatif
	 * @return json
	 */
	function get_siswa_by_alternatif()
	{
		$id_alternatif = $_POST['id_alternatif'];
		$db = $this->GetModel();
		$sqltext = "
		SELECT s.nama_siswa, s.kelas_siswa 
		FROM nilai AS n
		JOIN user AS u ON n.user_id = u.id_user
		JOIN siswa AS s ON u.id_user = s.user_id
		WHERE n.id_alternatif = ?";
		$queryparams = [$id_alternatif];
		$result = $db->rawQuery($sqltext, $queryparams);
		echo json_encode($result);
	}


	// Method to get all criteria from the kriteria table
	public function get_kriteria_options()
	{
		$db = $this->GetModel();
		return $db->get('kriteria');
	}

	// Method to get options for a specific criteria
	public function get_options_for_criteria($id_kriteria)
	{
		$db = $this->GetModel();
		$db->where('id_kriteria', $id_kriteria);
		return $db->get('variabel'); // Assuming the options are stored in the variabel table
	}

	public function get_variabel_options($id_kriteria)
	{
		$db = $this->GetModel();
		$db->where("id_kriteria", $id_kriteria);
		$db->orderBy("bobot_variabel", "ASC");
		$results = $db->get("variabel", null, "id_variabel as value, nama_variabel as label");
		return $results;
	}
}
