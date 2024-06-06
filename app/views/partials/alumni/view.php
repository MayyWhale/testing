<?php 
//check if current user role is allowed access to the pages
$can_add = ACL::is_allowed("alumni/add");
$can_edit = ACL::is_allowed("alumni/edit");
$can_view = ACL::is_allowed("alumni/view");
$can_delete = ACL::is_allowed("alumni/delete");
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
                    <h4 class="record-title">Detail  Alumni</h4>
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
                        $rec_id = (!empty($data['id_alumni']) ? urlencode($data['id_alumni']) : null);
                        $counter++;
                        ?>
                        <div id="page-report-body" class="">
                            <table class="table table-hover table-borderless table-striped">
                                <!-- Table Body Start -->
                                <tbody class="page-data" id="page-data-<?php echo $page_element_id; ?>">
                                    <tr  class="td-nis_alumni">
                                        <th class="title"> Nis Alumni: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-value="<?php echo $data['nis_alumni']; ?>" 
                                                data-pk="<?php echo $data['id_alumni'] ?>" 
                                                data-url="<?php print_link("alumni/editfield/" . urlencode($data['id_alumni'])); ?>" 
                                                data-name="nis_alumni" 
                                                data-title="Masukkan Nis Alumni" 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="text" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['nis_alumni']; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-nama_alumni">
                                        <th class="title"> Nama Alumni: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-value="<?php echo $data['nama_alumni']; ?>" 
                                                data-pk="<?php echo $data['id_alumni'] ?>" 
                                                data-url="<?php print_link("alumni/editfield/" . urlencode($data['id_alumni'])); ?>" 
                                                data-name="nama_alumni" 
                                                data-title="Masukkan Nama Alumni" 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="text" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['nama_alumni']; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-kelas_alumni">
                                        <th class="title"> Kelas Alumni: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-value="<?php echo $data['kelas_alumni']; ?>" 
                                                data-pk="<?php echo $data['id_alumni'] ?>" 
                                                data-url="<?php print_link("alumni/editfield/" . urlencode($data['id_alumni'])); ?>" 
                                                data-name="kelas_alumni" 
                                                data-title="Masukkan Kelas Alumni" 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="text" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['kelas_alumni']; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-tahun_lulus">
                                        <th class="title"> Tahun Lulus: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-value="<?php echo $data['tahun_lulus']; ?>" 
                                                data-pk="<?php echo $data['id_alumni'] ?>" 
                                                data-url="<?php print_link("alumni/editfield/" . urlencode($data['id_alumni'])); ?>" 
                                                data-name="tahun_lulus" 
                                                data-title="Masukkan Tahun Lulus" 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="text" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['tahun_lulus']; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-universitas_saat_ini">
                                        <th class="title"> Universitas Saat Ini: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-value="<?php echo $data['universitas_saat_ini']; ?>" 
                                                data-pk="<?php echo $data['id_alumni'] ?>" 
                                                data-url="<?php print_link("alumni/editfield/" . urlencode($data['id_alumni'])); ?>" 
                                                data-name="universitas_saat_ini" 
                                                data-title="Masukkan Universitas Saat Ini" 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="text" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['universitas_saat_ini']; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-prodi_saat_ini">
                                        <th class="title"> Prodi Saat Ini: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-value="<?php echo $data['prodi_saat_ini']; ?>" 
                                                data-pk="<?php echo $data['id_alumni'] ?>" 
                                                data-url="<?php print_link("alumni/editfield/" . urlencode($data['id_alumni'])); ?>" 
                                                data-name="prodi_saat_ini" 
                                                data-title="Masukkan Prodi Saat Ini" 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="text" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['prodi_saat_ini']; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-jalur_masuk">
                                        <th class="title"> Jalur Masuk: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-value="<?php echo $data['jalur_masuk']; ?>" 
                                                data-pk="<?php echo $data['id_alumni'] ?>" 
                                                data-url="<?php print_link("alumni/editfield/" . urlencode($data['id_alumni'])); ?>" 
                                                data-name="jalur_masuk" 
                                                data-title="Masukkan Jalur Masuk" 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="text" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['jalur_masuk']; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-telepon">
                                        <th class="title"> Telepon: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-value="<?php echo $data['telepon']; ?>" 
                                                data-pk="<?php echo $data['id_alumni'] ?>" 
                                                data-url="<?php print_link("alumni/editfield/" . urlencode($data['id_alumni'])); ?>" 
                                                data-name="telepon" 
                                                data-title="Masukkan Nomor Telepon" 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="text" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['telepon']; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-alamat">
                                        <th class="title"> Alamat: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-value="<?php echo $data['alamat']; ?>" 
                                                data-pk="<?php echo $data['id_alumni'] ?>" 
                                                data-url="<?php print_link("alumni/editfield/" . urlencode($data['id_alumni'])); ?>" 
                                                data-name="alamat" 
                                                data-title="Masukkan Alamat" 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="text" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['alamat']; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                                <!-- Table Body End -->
                            </table>
                        </div>
                        <div class="p-3 d-flex">
                            <?php if($can_edit){ ?>
                            <a class="btn btn-sm btn-info"  href="<?php print_link("alumni/edit/$rec_id"); ?>">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                            <?php } ?>
                            <?php if($can_delete){ ?>
                            <a class="btn btn-sm btn-danger record-delete-btn mx-1"  href="<?php print_link("alumni/delete/$rec_id/?csrf_token=$csrf_token&redirect=$current_page"); ?>" data-prompt-msg="Apakah Anda yakin ingin menghapus data ini ?" data-display-style="modal">
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
