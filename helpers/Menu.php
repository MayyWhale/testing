<?php
/**
 * Menu Items
 * All Project Menu
 * @category  Menu List
 */

class Menu{
	
	
			public static $navbarsideleft = array(
		array(
			'path' => 'home', 
			'label' => 'Home', 
			'icon' => '<i class="fa fa-home fa-2x"></i>'
		),
		
		array(
			'path' => '/', 
			'label' => 'Master Data', 
			'icon' => '<i class="fa fa-database fa-2x"></i>','submenu' => array(
		array(
			'path' => 'siswa', 
			'label' => 'Siswa', 
			'icon' => '<i class="fa fa-user fa-2x"></i>'
		),
		
		array(
			'path' => 'guru', 
			'label' => 'Guru', 
			'icon' => '<i class="fa fa-users fa-2x"></i>'
		),
		
		array(
			'path' => 'user', 
			'label' => 'User', 
			'icon' => '<i class="fa fa-users fa-2x"></i>'
		)
	)
		),
		
		array(
			'path' => 'data_ptn_prodi', 
			'label' => 'Data PTN Prodi', 
			'icon' => '<i class="fa fa-arrows-alt fa-2x"></i>'
		),
		
		array(
			'path' => 'kriteria', 
			'label' => 'Kriteria', 
			'icon' => '<i class="fa fa-filter fa-2x"></i>'
		),
		
		array(
			'path' => 'nilai', 
			'label' => 'Nilai Siswa', 
			'icon' => '<i class="fa fa-book fa-2x"></i>'
		),
		
		array(
			'path' => 'perhitungan', 
			'label' => 'Perhitungan', 
			'icon' => '<i class="fa fa-calculator fa-2x"></i>'
		)
	);
		
	
	
			public static $kategori = array(
		array(
			"value" => "Benefit", 
			"label" => "Benefit", 
		),
		array(
			"value" => "Cost", 
			"label" => "Cost", 
		),);
		
			public static $level_kepentingan = array(
		array(
			"value" => "1", 
			"label" => "Sangat Tidak Penting", 
		),
		array(
			"value" => "2", 
			"label" => "Tidak Penting", 
		),
		array(
			"value" => "3", 
			"label" => "Cukup Penting", 
		),
		array(
			"value" => "4", 
			"label" => "Penting", 
		),
		array(
			"value" => "5", 
			"label" => "Sangat Penting", 
		),);
		
}