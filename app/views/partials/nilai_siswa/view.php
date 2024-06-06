<?php 
//check if current user role is allowed access to the pages
$can_add = ACL::is_allowed("nilai_siswa/add");
$can_edit = ACL::is_allowed("nilai_siswa/edit");
$can_view = ACL::is_allowed("nilai_siswa/view");
$can_delete = ACL::is_allowed("nilai_siswa/delete");
?>
<?php
$comp_model = new SharedController;
$page_element_id = "view-page-" . random_str();
$current_page = $this->set_current_page_link();
$csrf_token = Csrf::$token;
//Page Data Information from Controller
$data = $this->view_data;
//$rec_id = $data['__tableprimarykey'];
$page_id = $this->route->page_id; //Page id from url
$view_title = $this->view_title;
$show_header = $this->show_header;
$show_edit_btn = $this->show_edit_btn;
$show_delete_btn = $this->show_delete_btn;
$show_export_btn = $this->show_export_btn;
?>
<section class="page" id="<?php echo $page_element_id; ?>" data-page-type="view"  data-display-type="table" data-page-url="<?php print_link($current_page); ?>">
    <?php
    if( $show_header == true ){
    ?>
    <div  class="bg-light p-3 mb-3">
        <div class="container">
            <div class="row ">
                <div class="col ">
                    <h4 class="record-title">Detail  Nilai Siswa</h4>
                </div>
            </div>
        </div>
    </div>
    <?php
    }
    ?>
    <div  class="">
        <div class="container">
            <div class="row ">
                <div class="col-md-12 comp-grid">
                    <?php $this :: display_page_errors(); ?>
                    <div  class="card animated fadeIn page-content">
                        <?php
                        $counter = 0;
                        if(!empty($data)){
                        $rec_id = (!empty($data['id_nilai']) ? urlencode($data['id_nilai']) : null);
                        $counter++;
                        ?>
                        <div id="page-report-body" class="">
                            <table class="table table-hover table-borderless table-striped">
                                <!-- Table Body Start -->
                                <tbody class="page-data" id="page-data-<?php echo $page_element_id; ?>">
                                    <tr  class="td-id_alternatif">
                                        <th class="title"> Alternatif: </th>
                                        <td class="value">
                                            <div class="inline-page">
                                                <a class=" open-page-inline" href="<?php print_link(); ?>">
                                                    <?php echo $data['id_alternatif'] ?>
                                                </a>
                                                <div class="page-content reset-grids d-none animated fadeIn"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr  class="td-k1">
                                        <th class="title"> Nilai Raport: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-value="<?php echo $data['k1']; ?>" 
                                                data-pk="<?php echo $data['id_nilai'] ?>" 
                                                data-url="<?php print_link("nilai_siswa/editfield/" . urlencode($data['id_nilai'])); ?>" 
                                                data-name="k1" 
                                                data-title="Gunakan Tanda Titik (.)" 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="text" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['k1']; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-k2">
                                        <th class="title"> Nilai Mapel Pendukung: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-value="<?php echo $data['k2']; ?>" 
                                                data-pk="<?php echo $data['id_nilai'] ?>" 
                                                data-url="<?php print_link("nilai_siswa/editfield/" . urlencode($data['id_nilai'])); ?>" 
                                                data-name="k2" 
                                                data-title="Gunakan Tanda Titik (.)" 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="text" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['k2']; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-k3">
                                        <th class="title"> Prestasi: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-source='<?php print_link('api/json/nilai_siswa_k3_option_list'); ?>' 
                                                data-value="<?php echo $data['k3']; ?>" 
                                                data-pk="<?php echo $data['id_nilai'] ?>" 
                                                data-url="<?php print_link("nilai_siswa/editfield/" . urlencode($data['id_nilai'])); ?>" 
                                                data-name="k3" 
                                                data-title="Pilih Prestasi ..." 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="select" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['k3']; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-k4">
                                        <th class="title"> Kesesuain Pilihan: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-source='<?php print_link('api/json/nilai_siswa_k4_option_list'); ?>' 
                                                data-value="<?php echo $data['k4']; ?>" 
                                                data-pk="<?php echo $data['id_nilai'] ?>" 
                                                data-url="<?php print_link("nilai_siswa/editfield/" . urlencode($data['id_nilai'])); ?>" 
                                                data-name="k4" 
                                                data-title="Pilih Kesesuaian ..." 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="select" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['k4']; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-k5">
                                        <th class="title"> Alumni PTN: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-source='<?php print_link('api/json/nilai_siswa_k5_option_list'); ?>' 
                                                data-value="<?php echo $data['k5']; ?>" 
                                                data-pk="<?php echo $data['id_nilai'] ?>" 
                                                data-url="<?php print_link("nilai_siswa/editfield/" . urlencode($data['id_nilai'])); ?>" 
                                                data-name="k5" 
                                                data-title="Pilih Alumni ..." 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="select" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['k5']; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-k6">
                                        <th class="title"> Alumni Prodi: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-source='<?php print_link('api/json/nilai_siswa_k6_option_list'); ?>' 
                                                data-value="<?php echo $data['k6']; ?>" 
                                                data-pk="<?php echo $data['id_nilai'] ?>" 
                                                data-url="<?php print_link("nilai_siswa/editfield/" . urlencode($data['id_nilai'])); ?>" 
                                                data-name="k6" 
                                                data-title="Pilih Prodi ..." 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="select" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['k6']; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                                <!-- Table Body End -->
                            </table>
                        </div>
                        <div class="p-3 d-flex">
                            <?php if($can_edit){ ?>
                            <a class="btn btn-sm btn-info"  href="<?php print_link("nilai_siswa/edit/$rec_id"); ?>">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                            <?php } ?>
                            <?php if($can_delete){ ?>
                            <a class="btn btn-sm btn-danger record-delete-btn mx-1"  href="<?php print_link("nilai_siswa/delete/$rec_id/?csrf_token=$csrf_token&redirect=$current_page"); ?>" data-prompt-msg="Apakah Anda yakin ingin menghapus data ini ?" data-display-style="modal">
                                <i class="fa fa-times"></i> Delete
                            </a>
                            <?php } ?>
                        </div>
                        <?php
                        }
                        else{
                        ?>
                        <!-- Empty Record Message -->
                        <div class="text-muted p-3">
                            <i class="fa fa-ban"></i> No Record Found
                        </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
