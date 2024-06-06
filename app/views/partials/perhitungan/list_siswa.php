<?php
$can_view = ACL::is_allowed("perhitungan/view");
?>
<?php
$comp_model = new SharedController;
$page_element_id = "list-page-" . random_str();
$current_page = $this->set_current_page_link();
$csrf_token = Csrf::$token;
$view_data = $this->view_data;
$grouped_records = $view_data->grouped_records;
// $records = $view_data->records;
$record_count = $view_data->record_count;
$total_records = $view_data->total_records;
$field_name = $this->route->field_name;
$field_value = $this->route->field_value;
$view_title = $this->view_title;
$show_header = $this->show_header;
$show_footer = $this->show_footer;
$show_pagination = $this->show_pagination;
$nilai_batas = $view_data->nilai_batas;
$nilai_batas_sedang = $view_data->nilai_batas_sedang;


?>
<style>
    .label-tinggi {
        background-color: green;
        color: white;
        padding: 5px;
        border-radius: 5px;
        text-align: center;
    }

    .label-sedang {
        background-color: yellow;
        color: black;
        padding: 5px;
        border-radius: 5px;
        text-align: center;
    }

    .label-rendah {
        background-color: red;
        color: white;
        padding: 5px;
        border-radius: 5px;
        text-align: center;
    }
</style>

<section class="page" id="<?php echo $page_element_id; ?>" data-page-type="list" data-display-type="table" data-page-url="<?php print_link($current_page); ?>">
    <?php if ($show_header == true) { ?>
        <div class="bg-light p-3 mb-3 text-center">
            <div class="container-fluid">
                <div class="row ">
                    <div class="col ">
                        <h4 class="record-title">Perhitungan</h4>
                    </div>
                    <div class="col-sm-4">
                        <form class="search" action="<?php print_link('perhitungan'); ?>" method="get">
                            <div class="input-group">
                                <input value="<?php echo get_value('search'); ?>" class="form-control" type="text" name="search" placeholder="Search" />
                                <div class="input-group-append">
                                    <button class="btn btn-primary"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-12 comp-grid">
                        <div class="">
                            <?php
                            if (!empty($field_name) || !empty($_GET['search'])) {
                            ?>
                                <hr class="sm d-block d-sm-none" />
                                <nav class="page-header-breadcrumbs mt-2" aria-label="breadcrumb">
                                    <ul class="breadcrumb m-0 p-1">
                                        <?php
                                        if (!empty($field_name)) {
                                        ?>
                                            <li class="breadcrumb-item">
                                                <a class="text-decoration-none" href="<?php print_link('perhitungan'); ?>">
                                                    <i class="fa fa-angle-left"></i>
                                                </a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <?php echo (get_value("tag") ? get_value("tag")  :  make_readable($field_name)); ?>
                                            </li>
                                            <li class="breadcrumb-item active text-capitalize font-weight-bold">
                                                <?php echo (get_value("label") ? get_value("label")  :  make_readable(urldecode($field_value))); ?>
                                            </li>
                                        <?php
                                        }
                                        ?>
                                        <?php
                                        if (get_value("search")) {
                                        ?>
                                            <li class="breadcrumb-item">
                                                <a class="text-decoration-none" href="<?php print_link('perhitungan'); ?>">
                                                    <i class="fa fa-angle-left"></i>
                                                </a>
                                            </li>
                                            <li class="breadcrumb-item text-capitalize">
                                                Search
                                            </li>
                                            <li class="breadcrumb-item active text-capitalize font-weight-bold"><?php echo get_value("search"); ?></li>
                                        <?php
                                        }
                                        ?>
                                    </ul>
                                </nav>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-md-12 comp-grid">
                    <?php $this::display_page_errors(); ?>
                    <div class="animated fadeIn page-content">
                        <div id="perhitungan-list-records">
                            <div class="alert alert-info">
                                <p><strong>Nilai Batas Maksimum:</strong> <?php echo $nilai_batas['max']; ?></p>
                                <p><strong>Nilai Batas Minimum:</strong> <?php echo $nilai_batas['min']; ?></p>
                                <p><strong>Nilai Batas Sedang:</strong> <?php echo $nilai_batas_sedang; ?></p>
                            </div>
                            <div id="page-report-body" class="table-responsive">
                                <?php if (!empty($grouped_records)) { ?>
                                    <?php foreach ($grouped_records as $data) { ?>
                                        <div class="siswa-section">
                                            <h5><?php echo $data['siswa_info']['nama_siswa']; ?> (<?php echo $data['siswa_info']['nis_siswa']; ?>) - <?php echo $data['siswa_info']['kelas_siswa']; ?></h5>
                                            <table class="table table-striped table-sm text-left">
                                                <thead class="table-header bg-light">
                                                    <tr>
                                                        <th class="td-sno">#</th>
                                                        <th class="td-nama_alternatif">Alternatif</th>
                                                        <th class="td-nilai_akhir">Nilai Akhir</th>
                                                        <th class="td-ranking">Ranking</th>
                                                        <th class="td-rekomendasi">Rekomendasi</th>
                                                        <th class="td-keterangan">Keterangan Rekomendasi</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="page-data" id="page-data-<?php echo $page_element_id; ?>">
                                                    <?php
                                                    $counter = 0;
                                                    foreach ($data['perhitungan'] as $record) {
                                                        $counter++;
                                                    ?>
                                                        <tr>
                                                            <th class="td-sno"><?php echo $counter; ?></th>
                                                            <td class="td-nama_alternatif"><?php echo $record['nama_alternatif']; ?></td>
                                                            <td class="td-nilai_akhir"><?php echo $record['nilai_akhir']; ?></td>
                                                            <td class="td-ranking"><?php echo $record['ranking']; ?></td>
                                                            <td class="td-rekomendasi">
                                                                <span class="<?php echo 'label-' . strtolower($record['rekomendasi']); ?>">
                                                                    <?php echo $record['rekomendasi']; ?>
                                                                </span>
                                                            </td>
                                                            <td class="td-keterangan"><?php echo $record['keterangan']; ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <hr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <h4 class="bg-light text-center border-top text-muted animated bounce p-3">
                                        <i class="fa fa-ban"></i> No record found
                                    </h4>
                                <?php } ?>
                            </div>
                            <?php
                            if ($show_footer && !empty($grouped_records)) {
                            ?>
                                <div class="border-top mt-2">
                                    <div class="row justify-content-center">
                                        <div class="col-md-auto justify-content-center">
                                            <div class="p-3 d-flex justify-content-between">
                                                <div class="dropup export-btn-holder mx-1">
                                                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fa fa-save"></i> Export
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <?php $export_print_link = $this->set_current_page_link(array('format' => 'print')); ?>
                                                        <a class="dropdown-item export-link-btn" data-format="print" href="<?php print_link($export_print_link); ?>" target="_blank">
                                                            <img src="<?php print_link('assets/images/print.png') ?>" class="mr-2" /> PRINT
                                                        </a>
                                                        <?php $export_pdf_link = $this->set_current_page_link(array('format' => 'pdf')); ?>
                                                        <a class="dropdown-item export-link-btn" data-format="pdf" href="<?php print_link($export_pdf_link); ?>" target="_blank">
                                                            <img src="<?php print_link('assets/images/pdf.png') ?>" class="mr-2" /> PDF
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <?php
                                            if ($show_pagination == true) {
                                                $pager = new Pagination($total_records, $record_count);
                                                $pager->route = $this->route;
                                                $pager->show_page_count = true;
                                                $pager->show_record_count = true;
                                                $pager->show_page_limit = true;
                                                $pager->limit_count = $this->limit_count;
                                                $pager->show_page_number_list = true;
                                                $pager->pager_link_range = 5;
                                                $pager->render();
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>