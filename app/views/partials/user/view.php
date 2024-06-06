<?php 
//check if current user role is allowed access to the pages
$can_add = ACL::is_allowed("user/add");
$can_edit = ACL::is_allowed("user/edit");
$can_view = ACL::is_allowed("user/view");
$can_delete = ACL::is_allowed("user/delete");
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
                    <h4 class="record-title">Detail Pengguna</h4>
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
                        $rec_id = (!empty($data['id_user']) ? urlencode($data['id_user']) : null);
                        $counter++;
                        ?>
                        <div id="page-report-body" class="">
                            <table class="table table-hover table-borderless table-striped">
                                <!-- Table Body Start -->
                                <tbody class="page-data" id="page-data-<?php echo $page_element_id; ?>">
                                    <tr  class="td-username">
                                        <th class="title"> Username: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-value="<?php echo $data['username']; ?>" 
                                                data-pk="<?php echo $data['id_user'] ?>" 
                                                data-url="<?php print_link("user/editfield/" . urlencode($data['id_user'])); ?>" 
                                                data-name="username" 
                                                data-title="Masukkan Username" 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="text" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['username']; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-nama_pengguna">
                                        <th class="title"> Nama Pengguna: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-value="<?php echo $data['nama_pengguna']; ?>" 
                                                data-pk="<?php echo $data['id_user'] ?>" 
                                                data-url="<?php print_link("user/editfield/" . urlencode($data['id_user'])); ?>" 
                                                data-name="nama_pengguna" 
                                                data-title="Masukkan Nama Pengguna" 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="text" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['nama_pengguna']; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-user_role_id">
                                        <th class="title"> Level Pengguna: </th>
                                        <td class="value">
                                            <a size="sm" class="btn btn-sm btn-primary page-modal" href="<?php print_link("roles/view/" . urlencode($data['user_role_id'])) ?>">
                                                <i class="fa fa-eye"></i> <?php echo $data['roles_role_name'] ?>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr  class="td-email">
                                        <th class="title"> Email: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-value="<?php echo $data['email']; ?>" 
                                                data-pk="<?php echo $data['id_user'] ?>" 
                                                data-url="<?php print_link("user/editfield/" . urlencode($data['id_user'])); ?>" 
                                                data-name="email" 
                                                data-title="Masukkan Email" 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="email" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['email']; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-photo">
                                        <th class="title"> Photo: </th>
                                        <td class="value"><?php Html :: page_img($data['photo'],400,400,1); ?></td>
                                    </tr>
                                    <tr  class="td-nis">
                                        <th class="title"> NIS: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-value="<?php echo $data['nis']; ?>" 
                                                data-pk="<?php echo $data['id_user'] ?>" 
                                                data-url="<?php print_link("user/editfield/" . urlencode($data['id_user'])); ?>" 
                                                data-name="nis" 
                                                data-title="Masukkan NIS" 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="text" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['nis']; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-nip">
                                        <th class="title"> NIP: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-value="<?php echo $data['nip']; ?>" 
                                                data-pk="<?php echo $data['id_user'] ?>" 
                                                data-url="<?php print_link("user/editfield/" . urlencode($data['id_user'])); ?>" 
                                                data-name="nip" 
                                                data-title="Masukkan NIP" 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="text" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['nip']; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                                <!-- Table Body End -->
                            </table>
                        </div>
                        <div class="p-3 d-flex">
                            <?php if($can_edit){ ?>
                            <a class="btn btn-sm btn-info"  href="<?php print_link("user/edit/$rec_id"); ?>">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                            <?php } ?>
                            <?php if($can_delete){ ?>
                            <a class="btn btn-sm btn-danger record-delete-btn mx-1"  href="<?php print_link("user/delete/$rec_id/?csrf_token=$csrf_token&redirect=$current_page"); ?>" data-prompt-msg="Apakah Anda yakin ingin menghapus data ini ?" data-display-style="modal">
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
