<?php 
//check if current user role is allowed access to the pages
$can_add = ACL::is_allowed("kriteria/add");
$can_edit = ACL::is_allowed("kriteria/edit");
$can_view = ACL::is_allowed("kriteria/view");
$can_delete = ACL::is_allowed("kriteria/delete");
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
                    <h4 class="record-title">View  Kriteria</h4>
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
                        $rec_id = (!empty($data['id_kriteria']) ? urlencode($data['id_kriteria']) : null);
                        $counter++;
                        ?>
                        <div id="page-report-body" class="">
                            <table class="table table-hover table-borderless table-striped">
                                <!-- Table Body Start -->
                                <tbody class="page-data" id="page-data-<?php echo $page_element_id; ?>">
                                    <tr  class="td-nama_kriteria">
                                        <th class="title"> Nama Kriteria: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-value="<?php echo $data['nama_kriteria']; ?>" 
                                                data-pk="<?php echo $data['id_kriteria'] ?>" 
                                                data-url="<?php print_link("kriteria/editfield/" . urlencode($data['id_kriteria'])); ?>" 
                                                data-name="nama_kriteria" 
                                                data-title="Enter Nama Kriteria" 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="text" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['nama_kriteria']; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-kategori">
                                        <th class="title"> Kategori: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-source='<?php echo json_encode_quote(Menu :: $kategori); ?>' 
                                                data-value="<?php echo $data['kategori']; ?>" 
                                                data-pk="<?php echo $data['id_kriteria'] ?>" 
                                                data-url="<?php print_link("kriteria/editfield/" . urlencode($data['id_kriteria'])); ?>" 
                                                data-name="kategori" 
                                                data-title="Pilih Kategori ..." 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="select" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['kategori']; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-level_kepentingan">
                                        <th class="title"> Level Kepentingan: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-source='<?php echo json_encode_quote(Menu :: $level_kepentingan); ?>' 
                                                data-value="<?php echo $data['level_kepentingan']; ?>" 
                                                data-pk="<?php echo $data['id_kriteria'] ?>" 
                                                data-url="<?php print_link("kriteria/editfield/" . urlencode($data['id_kriteria'])); ?>" 
                                                data-name="level_kepentingan" 
                                                data-title="Pilih Level Kepentingan..." 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="select" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['level_kepentingan']; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-bobot_kriteria">
                                        <th class="title"> Bobot Kriteria: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-value="<?php echo $data['bobot_kriteria']; ?>" 
                                                data-pk="<?php echo $data['id_kriteria'] ?>" 
                                                data-url="<?php print_link("kriteria/editfield/" . urlencode($data['id_kriteria'])); ?>" 
                                                data-name="bobot_kriteria" 
                                                data-title="Bobot Otomatis" 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="text" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['bobot_kriteria']; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                                <!-- Table Body End -->
                            </table>
                        </div>
                        <div class="p-3 d-flex">
                            <?php if($can_edit){ ?>
                            <a class="btn btn-sm btn-info"  href="<?php print_link("kriteria/edit/$rec_id"); ?>">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                            <?php } ?>
                            <?php if($can_delete){ ?>
                            <a class="btn btn-sm btn-danger record-delete-btn mx-1"  href="<?php print_link("kriteria/delete/$rec_id/?csrf_token=$csrf_token&redirect=$current_page"); ?>" data-prompt-msg="Apakah Anda yakin ingin menghapus data ini ?" data-display-style="modal">
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
