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
                                            <input class="form-control form-control-sm" type='text' name="name" required="" placeholder="Tên" />
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
<?= $this->endSection() ?>


<!-- Style --->
<?= $this->section("style") ?>
<link rel="stylesheet" href="<?= base_url("assets/lib/datatables/datatables.min.css") ?> " ?>
<?= $this->endSection() ?>

<!-- Script --->
<?= $this->section('script') ?>

<script src="<?= base_url('assets/lib/datatables/datatables.min.js') ?>"></script>
<script src="<?= base_url('assets/lib/datatables/jquery.highlight.js') ?>"></script>
<script type='text/javascript'>
    var tin = <?= json_encode($tin) ?>;
    var controller = '<?= $controller ?>';
    fillForm($("#form-dang-tin"), tin);
    $(document).ready(function() {

        // $("select[multiple]").chosen();
        // $(".image_ft").imageFeature();
        $('#quanlytin').DataTable();
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
        // $(".add_document").click(function() {

        //     let document = $(".document_add").val();
        //     let category_id = tin['id'];
        //     $.ajax({
        //         type: "POST",
        //         data: {
        //             data: JSON.stringify(document),
        //             category_id: category_id,
        //         },
        //         url: path + "admin/" + controller + "/adddocumentcategory",
        //         success: function(msg) {
        //             // alert("Success!");
        //             location.reload();
        //         }
        //     })
        // });
    });
</script>

<?= $this->endSection() ?>