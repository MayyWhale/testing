<?php
// Check if current user role is allowed access to the pages
$can_add = ACL::is_allowed("kriteria/add");
$can_edit = ACL::is_allowed("kriteria/edit");
$can_view = ACL::is_allowed("kriteria/view");
$can_delete = ACL::is_allowed("kriteria/delete");
$can_add_variabel = ACL::is_allowed("variabel/add");
?>
<?php
$comp_model = new SharedController;
$page_element_id = "list-page-" . random_str();
$current_page = $this->set_current_page_link();
$csrf_token = Csrf::$token;
// Page Data From Controller
$view_data = $this->view_data;
$records = $view_data->records;
$record_count = $view_data->record_count;
$total_records = $view_data->total_records;
$field_name = $this->route->field_name;
$field_value = $this->route->field_value;
$view_title = $this->view_title;
$show_header = $this->show_header;
$show_footer = $this->show_footer;
$show_pagination = $this->show_pagination;

// Set default values if not set
$show_header = isset($show_header) ? $show_header : true;
$show_footer = isset($show_footer) ? $show_footer : true;
$show_pagination = isset($show_pagination) ? $show_pagination : true;
$field_name = isset($field_name) ? $field_name : '';
$field_value = isset($field_value) ? $field_value : '';
$total_records = isset($total_records) ? $total_records : 0;
$record_count = isset($record_count) ? $record_count : 0;

// Function to convert level to description
function get_level_description($level)
{
    $descriptions = [
        1 => 'Sangat Tidak Penting',
        2 => 'Tidak Penting',
        3 => 'Cukup Penting',
        4 => 'Penting',
        5 => 'Sangat Penting',
    ];
    return isset($descriptions[$level]) ? $descriptions[$level] : 'Tidak diketahui';
}

// Function to convert kategori to description
function get_kategori($kategori)
{
    $descriptions = [
        'cost' => 'Cost',
        'benefit' => 'Benefit',
    ];
    return isset($descriptions[$kategori]) ? $descriptions[$kategori] : 'Tidak diketahui';
}

?>

<section class="page" id="<?php echo $page_element_id; ?>" data-page-type="list" data-display-type="table" data-page-url="<?php print_link($current_page); ?>">
    <?php if ($show_header == true) { ?>
        <div class="bg-light p-3 mb-3">
            <div class="container-fluid">
                <div class="row ">
                    <div class="col ">
                        <h4 class="record-title">Kriteria</h4>
                    </div>
                    <div class="col-sm-3 ">
                        <?php if ($can_add) { ?>
                            <a class="btn btn btn-primary my-1" href="<?php print_link("kriteria/add") ?>">
                                <i class="fa fa-plus"></i>
                                Tambah Kriteria
                            </a>
                        <?php } ?>
                    </div>
                    <div class="col-sm-4 ">
                        <form class="search" action="<?php print_link('kriteria'); ?>" method="get">
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
                            <!-- Page bread crumbs components-->
                            <?php if (!empty($field_name) || !empty($_GET['search'])) { ?>
                                <hr class="sm d-block d-sm-none" />
                                <nav class="page-header-breadcrumbs mt-2" aria-label="breadcrumb">
                                    <ul class="breadcrumb m-0 p-1">
                                        <?php if (!empty($field_name)) { ?>
                                            <li class="breadcrumb-item">
                                                <a class="text-decoration-none" href="<?php print_link('kriteria'); ?>">
                                                    <i class="fa fa-angle-left"></i>
                                                </a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <?php echo (get_value("tag") ? get_value("tag")  :  make_readable($field_name)); ?>
                                            </li>
                                            <li class="breadcrumb-item active text-capitalize font-weight-bold">
                                                <?php echo (get_value("label") ? get_value("label")  :  make_readable(urldecode($field_value))); ?>
                                            </li>
                                        <?php } ?>
                                        <?php if (get_value("search")) { ?>
                                            <li class="breadcrumb-item">
                                                <a class="text-decoration-none" href="<?php print_link('kriteria'); ?>">
                                                    <i class="fa fa-angle-left"></i>
                                                </a>
                                            </li>
                                            <li class="breadcrumb-item text-capitalize">
                                                Search
                                            </li>
                                            <li class="breadcrumb-item active text-capitalize font-weight-bold"><?php echo get_value("search"); ?></li>
                                        <?php } ?>
                                    </ul>
                                </nav>
                                <!--End of Page bread crumbs components-->
                            <?php } ?>
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
                        <div id="kriteria-list-records">
                            <?php
                            $counter = 0;
                            if (!empty($records)) {
                                foreach ($records as $data) {
                                    $rec_id = !empty($data['id_kriteria']) ? urlencode($data['id_kriteria']) : null;
                                    $nama_kriteria = isset($data['nama_kriteria']) ? $data['nama_kriteria'] : 'No data available';
                                    $kategori = isset($data['kategori']) ? $data['kategori'] : 'No data available';
                                    $level = isset($data['level_kepentingan']) ? $data['level_kepentingan'] : 'No data available';
                                    $bobot_kriteria = isset($data['bobot_kriteria']) ? $data['bobot_kriteria'] : 'No data available';

                                    $counter++;
                            ?>
                                    <div class="card bg-light mb-3">
                                        <div class="card-header">
                                            <div class="row">
                                                <div class="col">
                                                    <h5 class="card-title"><?php echo $nama_kriteria; ?></h5>
                                                </div>
                                                <div class="col text-right">
                                                    <?php if ($can_edit) { ?>
                                                        <a class="btn btn-sm btn-info has-tooltip" title="Edit This Record" href="<?php print_link("kriteria/edit/$rec_id"); ?>">
                                                            <i class="fa fa-edit"></i> Edit
                                                        </a>
                                                    <?php } ?>
                                                    <?php if ($can_delete) { ?>
                                                        <a class="btn btn-sm btn-danger has-tooltip record-delete-btn" title="Delete this record" href="<?php print_link("kriteria/delete/$rec_id/?csrf_token=$csrf_token&redirect=$current_page"); ?>" data-prompt-msg="Apakah Anda yakin ingin menghapus data ini ?" data-display-style="modal">
                                                            <i class="fa fa-times"></i>
                                                            Delete
                                                        </a>
                                                    <?php } ?>
                                                    <?php if ($can_add_variabel && $nama_kriteria != 'Nilai Raport' && $nama_kriteria != 'Nilai Mapel Pendukung') { ?>
                                                        <!-- Add the "Tambah Variabel" button -->
                                                        <a class="btn btn-sm btn-primary" href="<?php print_link("variabel/add?id_kriteria=$rec_id") ?>">
                                                            <i class="fa fa-plus"></i>
                                                            Tambah Sub Kriteria
                                                        </a>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <strong>Kategori:</strong> <?php echo get_kategori($kategori); ?><br>
                                                    <strong>Level Kepentingan Kriteria:</strong> <?php echo get_level_description($level); ?><br>
                                                    <strong>Bobot Kriteria:</strong> <?php echo $bobot_kriteria; ?><br>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if ($nama_kriteria != 'Nilai Raport' && $nama_kriteria != 'Nilai Mapel Pendukung') { ?>
                                            <div class="card-body">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Nama Sub Kriteria</th>
                                                            <th>Bobot</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="sub-kriteria-list-<?php echo $rec_id; ?>">
                                                        <?php
                                                        $variabel_counter = 0;
                                                        // Sort sub criteria by bobot_variabel
                                                        usort($data['variabel'], function ($a, $b) {
                                                            return $a['bobot_variabel'] <=> $b['bobot_variabel'];
                                                        });
                                                        if (!empty($data['variabel'])) {
                                                            foreach ($data['variabel'] as $variabel) {
                                                                $variabel_counter++;
                                                        ?>
                                                                <tr>
                                                                    <td><?php echo $variabel_counter; ?></td>
                                                                    <td><?php echo isset($variabel['nama_variabel']) ? $variabel['nama_variabel'] : 'No data available'; ?></td>
                                                                    <td><?php echo isset($variabel['bobot_variabel']) ? $variabel['bobot_variabel'] : 'No data available'; ?></td>
                                                                    <td>
                                                                        <?php if ($can_edit) { ?>
                                                                            <a class="btn btn-sm btn-info" href="<?php print_link("variabel/edit/{$variabel['id_variabel']}"); ?>">
                                                                                <i class="fa fa-edit"></i> Edit
                                                                            </a>
                                                                        <?php } ?>
                                                                        <?php if ($can_delete) { ?>
                                                                            <a class="btn btn-sm btn-danger has-tooltip record-delete-btn" title="Delete this record" href="<?php print_link("variabel/delete/{$variabel['id_variabel']}?csrf_token=$csrf_token&redirect=$current_page"); ?>" data-prompt-msg="Apakah Anda yakin ingin menghapus data ini ?" data-display-style="modal">
                                                                                <i class="fa fa-times"></i>
                                                                                Delete
                                                                            </a>
                                                                        <?php } ?>
                                                                    </td>
                                                                </tr>
                                                            <?php
                                                            }
                                                        } else {
                                                            ?>
                                                            <tr>
                                                                <td colspan="4" class="text-center">No data available</td>
                                                            </tr>
                                                        <?php
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php
                                }
                            } else {
                                ?>
                                <div class="text-muted p-3">
                                    <i class="fa fa-ban"></i> No record found
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                        <?php if ($show_footer == true) { ?>
                            <div class="">
                                <div class="row justify-content-center">
                                    <div class="col-md-auto justify-content-center">
                                        <div class="p-3 d-flex justify-content-between">
                                            <?php if ($can_delete) { ?>
                                                <button data-prompt-msg="Apakah Anda yakin ingin menghapus data ini ?" data-display-style="modal" data-url="<?php print_link("kriteria/delete/{sel_ids}/?csrf_token=$csrf_token&redirect=$current_page"); ?>" class="btn btn-sm btn-danger btn-delete-selected d-none">
                                                    <i class="fa fa-times"></i> Delete
                                                </button>
                                            <?php } ?>
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
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>