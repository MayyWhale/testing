<?php 
//check if current user role is allowed access to the pages
$can_add = ACL::is_allowed("siswa/add");
$can_edit = ACL::is_allowed("siswa/edit");
$can_view = ACL::is_allowed("siswa/view");
$can_delete = ACL::is_allowed("siswa/delete");
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
    <div  class="bg-light p-3 mb-3">
        <div class="container">
            <div class="row ">
                <div class="col comp-grid">
                    <h4 class="record-title">Detail Siswa</h4>
                </div>
            </div>
        </div>
    </div>
    <div  class="">
        <div class="container">
            <div class="row ">
                <div class="col-md-12 comp-grid">
                    <?php $this :: display_page_errors(); ?>
                    <div  class="card animated fadeIn page-content">
                        <?php
                        $counter = 0;
                        if(!empty($data)){
                        $rec_id = (!empty($data['id_siswa']) ? urlencode($data['id_siswa']) : null);
                        $counter++;
                        ?>
                        <div id="page-report-body" class="">
                            <table class="table table-hover table-borderless table-striped">
                                <!-- Table Body Start -->
                                <tbody class="page-data" id="page-data-<?php echo $page_element_id; ?>">
                                    <tr  class="td-nis_siswa">
                                        <th class="title"> NIS: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-value="<?php echo $data['nis_siswa']; ?>" 
                                                data-pk="<?php echo $data['id_siswa'] ?>" 
                                                data-url="<?php print_link("siswa/editfield/" . urlencode($data['id_siswa'])); ?>" 
                                                data-name="nis_siswa" 
                                                data-title="Masukkan NIS" 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="text" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['nis_siswa']; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-nama_siswa">
                                        <th class="title"> Nama: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-value="<?php echo $data['nama_siswa']; ?>" 
                                                data-pk="<?php echo $data['id_siswa'] ?>" 
                                                data-url="<?php print_link("siswa/editfield/" . urlencode($data['id_siswa'])); ?>" 
                                                data-name="nama_siswa" 
                                                data-title="Masukkan Nama Siswa" 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="text" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['nama_siswa']; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-kelas_siswa">
                                        <th class="title"> Kelas: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-value="<?php echo $data['kelas_siswa']; ?>" 
                                                data-pk="<?php echo $data['id_siswa'] ?>" 
                                                data-url="<?php print_link("siswa/editfield/" . urlencode($data['id_siswa'])); ?>" 
                                                data-name="kelas_siswa" 
                                                data-title="Masukkan Kelas Siswa" 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="text" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['kelas_siswa']; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-telepon">
                                        <th class="title"> Telepon: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-value="<?php echo $data['telepon']; ?>" 
                                                data-pk="<?php echo $data['id_siswa'] ?>" 
                                                data-url="<?php print_link("siswa/editfield/" . urlencode($data['id_siswa'])); ?>" 
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
                                            <span <?php if($can_edit){ ?> data-pk="<?php echo $data['id_siswa'] ?>" 
                                                data-url="<?php print_link("siswa/editfield/" . urlencode($data['id_siswa'])); ?>" 
                                                data-name="alamat" 
                                                data-title="Masukkan Alamat" 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="textarea" 
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
                            <a class="btn btn-sm btn-info"  href="<?php print_link("siswa/edit/$rec_id"); ?>">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                            <?php } ?>
                            <?php if($can_delete){ ?>
                            <a class="btn btn-sm btn-danger record-delete-btn mx-1"  href="<?php print_link("siswa/delete/$rec_id/?csrf_token=$csrf_token&redirect=$current_page"); ?>" data-prompt-msg="Apakah Anda yakin ingin menghapus data ini ?" data-display-style="modal">
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
