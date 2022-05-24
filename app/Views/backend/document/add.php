<?= $this->extend('backend/layouts/main') ?>


<?= $this->section('content') ?>
<div class="row clearfix">
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

                                <b class="col-12 col-lg-2 col-form-label">Ấn bản:</b>
                                <div class="col-12 col-lg-4 pt-1">
                                    <input class="form-control form-control-sm" type='number' name="version" placeholder="Ấn bản" />
                                </div>

                            </div>
                            <div class="form-group row">
                                <b class="col-12 col-lg-2 col-form-label">Tiêu đề:<span class="text-danger">*</span></b>
                                <div class="col-12 col-lg-10 pt-1">
                                    <input class="form-control form-control-sm" type='text' name="name_vi" required="" placeholder="Tiếng việt" />
                                </div>
                                <!-- <div class="col-12 col-lg-5 pt-1">
                                    <input class="form-control form-control-sm" type='text' name="name_en" placeholder="Tiếng anh" />
                                </div> -->
                            </div>
                            <div class="form-group row">
                                <b class="col-12 col-lg-2 col-form-label">Trạng thái:<span class="text-danger">*</span></b>
                                <div class="col-12 col-lg-4 pt-1">
                                    <select class="form-control form-control-sm" name="status_id" required="">
                                        <?php foreach ($status as $row) : ?>
                                            <option value="<?= $row->id ?>" <?= $row->no_delete ? "disabled" : "" ?>><?= $row->name ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <b class="col-12 col-lg-2 col-form-label">Loại hồ sơ:<span class="text-danger">*</span></b>
                                <div class="col-12 col-lg-4 pt-1">
                                    <select class="form-control form-control-sm" name="type_id" required="">
                                        <?php foreach ($types as $row) : ?>
                                            <option value="<?= $row->id ?>"><?= $row->name ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <b class="col-12 col-lg-2 col-form-label">Ngày hiệu lực:</b>
                                <div class="col-12 col-lg-4 pt-1">
                                    <input class="form-control form-control-sm" type='date' name="date_effect" />
                                </div>
                                <b class="col-12 col-lg-2 col-form-label">Ngày rà soát:</b>
                                <div class="col-12 col-lg-4 pt-1">
                                    <input class="form-control form-control-sm" type='date' name="date_review" />
                                </div>
                            </div>

                            <div class="form-group row">

                                <b class="col-12 col-lg-2 col-form-label">Ngày hết hạn:</b>
                                <div class="col-12 col-lg-4 pt-1">
                                    <input class="form-control form-control-sm" type='date' name="date_expire" />
                                </div>
                                <b class="col-12 col-lg-2 col-form-label">Hiện hành:</b>
                                <div class="col-12 col-lg-4 pt-1">
                                    <div class="switch-button switch-button-xs switch-button-success">
                                        <input type="hidden" class="input-tmp" name="is_active" value="0">
                                        <input type="checkbox" id="switch4" name="is_active" value="1" checked>
                                        <span>
                                            <label for="switch4"></label>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <b class="col-12 col-form-label">Ghi chú:</b>
                                <div class="col-12 pt-1">
                                    <textarea class="form-control" name="description_vi" rows="10"></textarea>
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

<?= $this->endSection() ?>


<!-- Script --->
<?= $this->section('script') ?>

<script src="<?= base_url("assets/lib/mustache/mustache.min.js") ?>"></script>
<!-- <script src="<?= base_url("assets/lib/image_feature/jquery.image_v2.js") ?>"></script> -->
<!-- <script src="<?= base_url("assets/lib/ckfinder/ckfinder.js") ?>"></script> -->
<!-- <script src="<?= base_url("assets/lib/ckeditor/ckeditor.js") ?>"></script> -->

<script type='text/javascript'>
    // var allEditors = document.querySelectorAll('.edit');
    // for (var i = 0; i < allEditors.length; ++i) {
    //     CKEDITOR.replace(allEditors[i], {
    //         height: '300px'
    //     });
    // }
    var csrfName = '<?= csrf_token() ?>';
    var csrfHash = "<?= csrf_hash() ?>";
    $(document).ready(function() {
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
        $(document).on("click", ".remove_file", function() {
            $(this).parents(".file_box").remove();
        })
        $.validator.setDefaults({
            debug: true,
            success: "valid"
        });
        $("#form-dang-tin").validate({
            highlight: function(input) {
                $(input).parents('.col-12').addClass('error');
            },
            unhighlight: function(input) {
                $(input).parents('.col-12').removeClass('error');
            },
            errorPlacement: function(error, element) {
                $(element).closest('.col-12').append(error);
            },
            success: function(error) {
                error.remove();
            },
            submitHandler: function(form) {
                form.submit();
                return false;
            }
        });
        $("[name='category_list[]']").change(function() {
            if ($(this).is(":checked")) {
                $(this).parents("li").find("> .custom-checkbox > [name='category_list[]']").prop("checked", true);
            } else {
                $(this).closest("li").find("[name='category_list[]']").prop("checked", false);
            }
        })
    });

    function add_file(item) {
        var template = $(".file_template").html();
        var rendered = Mustache.render(template, item);
        $(".no_item").remove();
        $(".list_file").append(rendered);
    }
</script>

<?= $this->endSection() ?>