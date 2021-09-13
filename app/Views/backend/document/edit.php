<?= $this->extend('backend/layouts/main') ?>


<?= $this->section('content') ?>
<div class="row clearfix mb-5">
    <div class="col-12">
        <form method="POST" action="" id="form-dang-tin">
            <?= csrf_field() ?>
            <section class="card card-fluid">
                <h5 class="card-header">
                    <div class="d-inline-block w-100">
                        <button type="submit" name="dangtin" class="btn btn-sm btn-primary float-right">Save</button>
                    </div>
                </h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group row">
                                <b class="col-12 col-lg-2 col-form-label">Mã tài liệu:<span class="text-danger">*</span></b>
                                <div class="col-12 col-lg-4 pt-1">
                                    <input class="form-control form-control-sm" type='text' name="code" required="" placeholder="Mã tài liệu" />

                                </div>
                                <b class="col-12 col-lg-2 col-form-label">Tiêu đề:<span class="text-danger">*</span></b>
                                <div class="col-12 col-lg-4 pt-1">
                                    <input class="form-control form-control-sm" type='text' name="name_vi" required="" placeholder="Tiêu đề" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <b class="col-12 col-lg-2 col-form-label">Trạng thái:<span class="text-danger">*</span></b>
                                <div class="col-12 col-lg-4 pt-1">
                                    <select class="form-control form-control-sm" name="status_id" required="" <?= $tin->status_id == 4 ? "disabled" : "" ?>>
                                        <?php foreach ($status as $row) : ?>
                                            <option value="<?= $row->id ?>" <?= $row->no_delete ? "disabled" : "" ?>><?= $row->name ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <b class="col-12 col-lg-2 col-form-label">Ngày hiệu lực:</b>
                                <div class="col-12 col-lg-4 pt-1">
                                    <input class="form-control form-control-sm" type='date' name="date_effect" />
                                </div>
                                <b class="col-12 col-lg-2 col-form-label">Ngày hết hạn:</b>
                                <div class="col-12 col-lg-4 pt-1">
                                    <input class="form-control form-control-sm" type='date' name="date_expire" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <b class="col-12 col-form-label">Mô tả sơ lược:</b>
                                <div class="col-12 pt-1">
                                    <textarea class="form-control" name="description_vi"></textarea>
                                </div>
                            </div>
                            <!-- <div class="tab-content">
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
                            </div> -->
                        </div>
                        <div class="col-md-4">
                            <div class="form-group row">
                                <div class="col-12 pt-2 pt-md-0">
                                    <div class="card no-shadow border">
                                        <div class="card-header">
                                            QR code
                                        </div>
                                        <div class="card-body">
                                            <a href="<?= base_url($tin->image_url) ?>" target="_blank" class="text-center d-block">
                                                <img src="<?= base_url($tin->image_url) ?>" class="img-fluid w-50" />
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 pt-2">
                                    <div class="card no-shadow border">
                                        <div class="card-header">
                                            Danh mục
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <?= $category ?>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-12 pt-2">
                                    <div class="card no-shadow border">
                                        <div class="card-header">
                                            Đính kèm
                                            <div style="margin-left:auto;">
                                                <a class="btn btn-sm btn-success text-white float-right button_file">Thêm</a>
                                                <input type="file" class="d-none file_document" multiple>
                                            </div>
                                        </div>
                                        <div class="card-body list_file">
                                            <div class="text-center no_item">
                                                Không có đính kèm
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-inline-block w-100">
                        <button type="submit" name="dangtin" class="btn btn-sm btn-primary float-right">Save</button>
                    </div>
                </div>
            </section>
        </form>
    </div>
</div>
<template class="file_template">
    <div class="mb-2 file_box">
        <div class="file-icon" data-type="{{ext}}"></div>
        <a href="{{url}}" download="{{name}}">{{name}}</a>
        <input type="hidden" value="{{id}}" name="files[]" />
        <a class="text-danger ml-2 remove_file" href="javascript:void(0)"><i class="fas fa-trash-alt"></i></a>
    </div>
</template>


<div class="row clearfix">
    <div class="col-12">
        <section class="card card-fluid">
            <h5 class="card-header drag-handle">
                Danh sách mượn tài liệu
                <?php if ($tin->status_id != 4) : ?>
                    <div style="margin-left:auto;">
                        <a class="btn btn-danger btn-sm" data-target="#loan-modal" data-toggle="modal" href="">Cho mượn</a>
                    </div>
                <?php endif ?>
            </h5>
            <div class="card-body">
                <div class="table-responsive-md">
                    <table id="quanlytin" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Trạng thái</th>
                                <th>Người cho mượn</th>
                                <th>Ngày cho mượn</th>
                                <th>Ghi chú</th>
                                <th>Người nhận lại</th>
                                <th>Trạng thái</th>
                                <th>Ngày trả</th>
                                <th>Ghi chú</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>

<div aria-hidden="true" aria-labelledby="form-modalLabel" class="modal fade" id="loan-modal" role="dialog" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="comment-modalLabel">
                    Mượn tài liệu
                </h4>
            </div>
            <div class="modal-body">
                <div class="main">
                    <!--<p>Sign up once and watch any of our free demos.</p>-->
                    <form id="form-modal" action="<?= base_url("admin/document/loan") ?>" method="POST">

                        <?= csrf_field() ?>
                        <input type="hidden" name="document_id" value="<?= $tin->id ?>" />
                        <input type="hidden" name="user_id" value="<?= user_id() ?>" />
                        <div class="form-group">
                            <b class="form-label">Ngày mượn:<i class="text-danger">*</i></b>
                            <div class="form-line">
                                <input class="form-control" type='date' name="date_loan" required="" value="<?= date('Y-m-d') ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <b class="form-label">Ghi chú:</b>
                            <div class="form-line">
                                <textarea rows="4" class="form-control" name="note_loan"></textarea>
                            </div>
                        </div>
                        <button class="btn btn-primary waves-effect" type="submit" name="cap_nhat">Cập nhật</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div aria-hidden="true" aria-labelledby="form-modalLabel" class="modal fade" id="receive-modal" role="dialog" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="comment-modalLabel">
                    Nhận tài liệu
                </h4>
            </div>
            <div class="modal-body">
                <div class="main">
                    <!--<p>Sign up once and watch any of our free demos.</p>-->
                    <form id="form-modal" action="<?= base_url("admin/document/receive") ?>" method="POST">

                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="0" id="id" />
                        <input type="hidden" name="document_id" value="<?= $tin->id ?>" />
                        <input type="hidden" name="user_id_receive" value="<?= user_id() ?>" />
                        <div class="form-group">
                            <b class="form-label">Trạng thái tài liệu:<i class="text-danger">*</i></b>
                            <div class="form-line">
                                <select class="form-control" name="status_id_return" required="">
                                    <?php foreach ($status as $row) : ?>
                                        <option value="<?= $row->id ?>" <?= $row->no_delete ? "disabled" : "" ?>><?= $row->name ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <b class="form-label">Ngày trả:<i class="text-danger">*</i></b>
                            <div class="form-line">
                                <input class="form-control" type='date' name="date_return" required="" value="<?= date('Y-m-d') ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <b class="form-label">Ghi chú:</b>
                            <div class="form-line">
                                <textarea rows="4" class="form-control" name="note_return"></textarea>
                            </div>
                        </div>
                        <button class="btn btn-primary waves-effect" type="submit" name="cap_nhat">Cập nhật</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>


<!-- Style --->
<?= $this->section("style") ?>
<link rel="stylesheet" href="<?= base_url("assets/lib/chosen/chosen.min.css") ?> " ?>

<link rel="stylesheet" href="<?= base_url("assets/lib/datatables/datatables.min.css") ?> " ?>
<?= $this->endSection() ?>

<!-- Script --->
<?= $this->section('script') ?>


<script src="<?= base_url('assets/lib/datatables/datatables.min.js') ?>"></script>
<script src="<?= base_url('assets/lib/datatables/jquery.highlight.js') ?>"></script>
<script src="<?= base_url("assets/lib/chosen/chosen.jquery.js") ?>"></script>
<script src="<?= base_url("assets/lib/mustache/mustache.min.js") ?>"></script>
<!-- <script src="<?= base_url("assets/lib/image_feature/jquery.image_v2.js") ?>"></script> -->

<!--<script src="https://cdn.ckeditor.com/ckeditor5/12.3.1/classic/ckeditor.js"></script>-->
<!-- <script src="<?= base_url("assets/lib/ckfinder/ckfinder.js") ?>"></script> -->
<!-- <script src="<?= base_url("assets/lib/ckeditor/ckeditor.js") ?>"></script> -->

<script type='text/javascript'>
    var csrfName = '<?= csrf_token() ?>';
    var csrfHash = "<?= csrf_hash() ?>";
    $(document).ready(function() {
        var tin = <?= json_encode($tin) ?>;
        fillForm($("#form-dang-tin"), tin);
        if (tin.files) {
            for (let item of tin.files) {
                add_file(item);
            }
        }
        $(".chosen").chosen({
            width: "100%"
        });
        $.validator.setDefaults({
            debug: true,
            success: "valid"
        });
        $(".button_file").click(function() {
            $(".file_document").trigger("click");
        })
        $(".file_document").change(function() {
            // Get the selected file
            var files = $('.file_document')[0].files;
            if (files.length > 0) {
                var fd = new FormData();
                for (var count = 0; count < files.length; count++) {
                    // Append data 
                    fd.append('files[]', files[count]);
                }
                fd.append([csrfName], csrfHash);

                // AJAX request 
                $.ajax({
                    url: "<?= site_url('admin/document/fileupload') ?>",
                    method: 'post',
                    data: fd,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(response) {
                        // Update CSRF hash
                        csrfHash = response.token;
                        $("[name=" + csrfName + "]").val(csrfHash);
                        if (response.success == 1) { // Uploaded successfully
                            let items = response.items;
                            for (let item of items) {
                                add_file(item);
                            }
                        } else {
                            // Display Error
                            alert(response.message);
                        }
                    },
                    error: function(response) {
                        console.log("error : " + JSON.stringify(response));
                    }
                });
            } else {
                alert("Please select a file.");
            }

        })
        $(document).on("click", ".button_receive", function() {
            let id = $(this).data("id");
            $("#id").val(id);
        });
        $(document).on("click", ".remove_file", function() {
            $(this).parents(".file_box").remove();
        });

        let data = {};
        data['<?= csrf_token() ?>'] = "<?= csrf_hash() ?>";
        $('#quanlytin').DataTable({
            "stateSave": true,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": path + "admin/<?= $controller ?>/tableloan/" + tin.id,
                "dataType": "json",
                "type": "POST",
                "data": data
            },
            "columns": [{
                    "data": "status_loan"
                }, {
                    "data": "user"
                },
                {
                    "data": "date_loan"
                },
                {
                    "data": "note_loan"
                }, {
                    "data": "user_receive"
                }, {
                    "data": "status_return"
                }, {
                    "data": "date_return"
                }, {
                    "data": "note_return"
                },
            ]

        });
    });

    function add_file(item) {
        var template = $(".file_template").html();
        var rendered = Mustache.render(template, item);
        $(".no_item").remove();
        $(".list_file").append(rendered);
    }
</script>
<?= $this->endSection() ?>