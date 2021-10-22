<?= $this->extend('backend/layouts/main') ?>

<?= $this->section('content') ?>
<div class="row clearfix">
    <div class="col-12">
        <form method="POST" action="" id="form-dang-tin">
            <?= csrf_field() ?>
            <section class="card card-fluid">
                <h5 class="card-header">
                    <?php if (in_groups(array("admin", "editor"))) : ?>
                        <div class="d-inline-block w-100">
                            <button type="submit" name="dangtin" class="btn btn-sm btn-primary float-right">Save</button>
                        </div>
                    <?php endif ?>
                </h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <!-- <div class="form-group row">
                                <b class="col-12 col-lg-2 col-form-label">Hiển thị ở trang chủ:</b>
                                <div class="col-12 col-lg-4 pt-1">
                                    <div class="switch-button switch-button-xs switch-button-success">
                                        <input type="hidden" class="input-tmp" name="is_home" value="0">
                                        <input type="checkbox" id="switch3" name="is_home" value="1">
                                        <span>
                                            <label for="switch3"></label>
                                        </span>
                                    </div>
                                </div>
                                <b class="col-12 col-lg-2 col-form-label">Loại:</b>
                                <div class="col-12 col-lg-4 pt-1">
                                    <select name="type" class="form-control form-control-sm">
                                        <option value="1">Loại 1</option>
                                        <option value="2">Loại 2</option>
                                    </select>
                                </div>
                            </div>-->

                            <!-- <div class="form-group row">
                                <b class="col-12 col-lg-2 col-form-label">Đăng nhập:</b>
                                <div class="col-12 col-lg-4 pt-1">
                                    <div class="switch-button switch-button-xs switch-button-success">
                                        <input type="hidden" class="input-tmp" name="must_login" value="0">
                                        <input type="checkbox" id="switch4" name="must_login" value="1">
                                        <span>
                                            <label for="switch4"></label>
                                        </span>
                                    </div>
                                </div>
                            </div> -->
                            <div class="tab-content">
                                <div id="menu0" class="tab-pane active">
                                    <div class="form-group row">
                                        <b class="col-12 col-lg-2 col-form-label">Tên:<i class="text-danger">*</i></b>
                                        <div class="col-12 col-lg-4 pt-1">
                                            <input class="form-control form-control-sm" type='text' name="name_vi" required="" placeholder="Tên" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <b class="col-12 col-lg-2 col-form-label">Mô tả:</b>
                                        <div class="col-12">
                                            <textarea class="form-control" name="description_vi"></textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-md-4">
                            <div class="form-group row">
                                <div class="col-12 pt-2 text-center">
                                    <div class="card no-shadow border">
                                        <div class="card-header">
                                            
                                        </div>
                                        <div class="card-body">

                                            <div id="image_url" class="image_ft"></div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-12 pt-2 text-center">
                                    <div class="card no-shadow border">
                                        <div class="card-header">
                                            Banner Image
                                        </div>
                                        <div class="card-body">
                                            <div id="banner_img" class="image_ft"></div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
            </section>
        </form>
    </div>
</div>

<div class="row mt-5">
    <div class="col-12">
        <div class="card card-fluid">
            <div class="card-header" style="z-index: 1000;">
                Doument
                <div class="ml-auto">
                    <select class="form-control document_add" multiple>
                    </select>
                    <button class="btn btn-success btn-sm add_document">
                        Add
                    </button>
                </div>

                <div style="margin-left:auto;">
                    <a class="btn btn-sm btn-success" id='scan' href="#"><i class="fas fa-qrcode"></i> Quét QR</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive-md">
                    <table id="quanlytin" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Mã</th>
                                <th>Tiêu đề</th>
                                <th>Trạng thái</th>
                                <th>Đính kèm</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($documents as $row) : ?>
                                <tr>
                                    <td>
                                        <a class="" href="<?= base_url() ?>/admin/document/edit/<?= $row->id ?>"><?= $row->code . "." . ($row->version < 10 ? "0" . $row->version : $row->version) ?></a>
                                    </td>
                                    <td>
                                        <a class="" href="<?= base_url() ?>/admin/document/edit/<?= $row->id ?>"><?= $row->name_vi ?></a>
                                    </td>
                                    <td>
                                        <?= isset($row->status->name) ? $row->status->name : $row->status_id; ?>
                                    </td>
                                    <td>
                                        <?php foreach ($row->files as $file) : ?>
                                            <div class="">
                                                <div class="file-icon" data-type="<?= $file->ext ?>"></div>
                                                <a href="<?= $file->url ?>" download="<?= $file->name ?>"><?= $file->name ?></a>
                                            </div>
                                        <?php endforeach ?>
                                    </td>
                                </tr>

                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
<div style="height: 300px">
</div>
<?= $this->endSection() ?>


<!-- Style --->
<?= $this->section("style") ?>
<style>
    .document_add {
        width: 900px;
    }

    @media only screen and (max-width: 1100px) {
        .document_add {
            width: 400px;
        }

    }

    @media only screen and (max-width: 600px) {
        .document_add {
            width: 150px;
        }

    }

    @media only screen and (max-width: 400px) {
        .document_add {
            width: 150px;
        }

    }
</style>
<link rel="stylesheet" href="<?= base_url("assets/lib/chosen/chosen.min.css") ?> " ?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.11.3/b-2.0.1/b-colvis-2.0.1/b-html5-2.0.1/b-print-2.0.1/datatables.min.css"/>
 <link rel="stylesheet" href="<?= base_url("assets/lib/qrcode/qrcode.css") ?> " ?>

<?= $this->endSection() ?>

<!-- Script --->
<?= $this->section('script') ?>


<script src="<?= base_url("assets/lib/chosen/chosen.jquery.js") ?>"></script>
<script src="<?= base_url("assets/lib/ajaxchosen/chosen.ajaxaddition.jquery.js") ?>"></script>
 <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
 <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
 <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.11.3/b-2.0.1/b-colvis-2.0.1/b-html5-2.0.1/b-print-2.0.1/datatables.min.js"></script>
 
<script src="<?= base_url('assets/lib/datatables/jquery.highlight.js') ?>"></script>

<script src="<?= base_url('assets/lib/qrcode/instascan.min.js') ?>"></script>
<div id="div_video" class="d-none">
    <video id="preview"></video>
    <div class="custom-scanner"></div>
    <button class="btn btn-sm btn-secondary change_cam"><i class="fas fa-sync-alt"></i></button>
</div>
<script type='text/javascript'>
    var tin = <?= json_encode($tin) ?>;
    var controller = '<?= $controller ?>';
    fillForm($("#form-dang-tin"), tin);
    $(document).ready(function() {

        // $("select[multiple]").chosen();
        // $(".image_ft").imageFeature();
        let data = {};
        data['<?= csrf_token() ?>'] = "<?= csrf_hash() ?>";
        data['documents_disable'] = <?= json_encode($documents_disable) ?>;
        $(".document_add").ajaxChosen({
            dataType: 'json',
            type: 'POST',
            url: path + "admin/category/documentlist",
            data: data
        }, {
            loadingImg: path + 'public/img/loading.gif'
        }, {
            allow_single_deselect: true
        });
        $('#quanlytin').DataTable({
            dom: '<"row"<"col-sm-12 col-md-6"B><"col-sm-12 col-md-6"f>rt<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            buttons: [
                'excel'
            ]
        });
        //$('.edit').froalaEditor({
        //    heightMin: 200,
        //    heightMax: 500, // Set the image upload URL.
        //    imageUploadURL: '<?= base_url() ?>admin/uploadimage',
        //    // Set request type.
        //    imageUploadMethod: 'POST',
        //    // Set max image size to 5MB.
        //    imageMaxSize: 5 * 1024 * 1024,
        //    // Allow to upload PNG and JPG.
        //    imageAllowedTypes: ['jpeg', 'jpg', 'png', 'gif'],
        //    htmlRemoveTags: [],
        //});
        $.validator.setDefaults({
            debug: true,
            success: "valid"
        });
        $("#form-dang-tin").validate({
            highlight: function(input) {
                $(input).parents('.form-line').addClass('error');
            },
            unhighlight: function(input) {
                $(input).parents('.form-line').removeClass('error');
            },
            errorPlacement: function(error, element) {
                $(element).parents('.form-group').append(error);
            },
            submitHandler: function(form) {
                form.submit();
                return false;
            }
        });
        // $('#nestable').nestedSortable({
        //     forcePlaceholderSize: true,
        //     items: 'li',
        //     opacity: .6,
        //     maxLevels: 1,
        //     placeholder: 'dd-placeholder',
        // });
        $(".add_document").click(function() {

            let document = $(".document_add").val();
            let category_id = tin['id'];
            $.ajax({
                type: "POST",
                data: {
                    data: JSON.stringify(document),
                    category_id: category_id,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                url: path + "admin/" + controller + "/adddocumentcategory",
                success: function(msg) {
                    // alert("Success!");
                    location.reload();
                }
            })
        });




        let scanner = new Instascan.Scanner({
            video: document.getElementById('preview')
        });
        scanner.addListener('scan', function(content) {
            let anArray = content.split("/");
            let code = anArray.pop();
            let category_id = tin['id'];
            $.ajax({
                type: "POST",
                data: {
                    category_id: category_id,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                url: path + "admin/" + controller + "/adddocument/" + code,
                success: function(msg) {
                    alert("Đã thêm vào danh mục!");
                }
            })
            // location.href = content;
        });
        var select_cam = 0;
        $(".change_cam").click(function() {
            select_cam++;
            if (select_cam > cameras.length)
                select_cam = 0;
            $("#scan").trigger("click");
        })
        $("#scan").click(function() {
            if (cameras.length > 0) {
                let cam = cameras[select_cam];
                if (cam.name.indexOf("back") != -1) {
                    scanner.mirror = false
                } else {
                    scanner.mirror = true
                }
                scanner.start(cam);
                $("#div_video").removeClass("d-none");
                if (cameras.length == 1) {
                    $(".change_cam").addClass("d-none");
                }
            } else {
                alert('Không tìm thấy camera.');
                console.log('No cameras found.');
            }
        });
        var cameras = [];
        Instascan.Camera.getCameras().then(function(c) {
            cameras = c;
            if (cameras.length > 1) {
                select_cam = cameras.length - 1;
            }
        }).catch(function(e) {
            console.log(e);
        });
    });
</script>

<?= $this->endSection() ?>