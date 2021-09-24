<?= $this->extend('backend/layouts/main') ?>


<?= $this->section('content') ?>
<div class="row clearfix">
    <div class="col-12">
        <form method="POST" action="" id="form-dang-tin">
            <?= csrf_field() ?>

            <input type="hidden" name="parent_id" value="0" />
            <section class="card card-fluid">
                <h5 class="card-header">
                    <div class="d-inline-block w-100">
                        <button type="submit" name="dangtin" class="btn btn-sm btn-primary float-right">Save</button>
                    </div>
                </h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="form-group row">
                                <b class="col-12 col-lg-2 col-form-label">Username:<i class="text-danger">*</i></b>
                                <div class="col-12 col-lg-4 pt-1">
                                    <input class="form-control form-control-sm" type="text" name="username" readonly="" disabled placeholder="Username" aria-required="true">
                                </div>

                                <b class="col-12 col-lg-2 col-form-label">Tên:<i class="text-danger">*</i></b>
                                <div class="col-12 col-lg-4 pt-1">
                                    <input class="form-control form-control-sm price" type="text" name="name" required="" placeholder="Tên" im-insert="true" aria-required="true">
                                </div>
                            </div>

                            <div class="form-group row">

                                <b class="col-12 col-lg-2 col-form-label">Số điện thoại:</b>
                                <div class="col-12 col-lg-4 pt-1">
                                    <input class="form-control form-control-sm price" type="text" name="phone" placeholder="Số điện thoại" im-insert="true" aria-required="true">
                                </div>
                                <b class="col-12 col-lg-2 col-form-label">Địa chỉ:</b>
                                <div class="col-12 col-lg-4 pt-1">
                                    <input class="form-control form-control-sm price" type="text" name="address" placeholder="Địa chỉ" im-insert="true" aria-required="true">
                                </div>
                            </div>
                            <div class="form-group row">
                                <b class="col-12 col-lg-12 col-form-label">Mô tả:</b>
                                <div class="col-12 col-lg-12 pt-1">
                                    <textarea class="form-control form-control-sm" type="text" name="description" placeholder="Mô tả" aria-required="true"></textarea>
                                </div>

                            </div>

                            <div class="form-group row">
                                <b class="col-12 col-lg-2 col-form-label">Nhóm:</b>
                                <div class="col-lg-4 pt-1">
                                    <select name="groups[]" style="width: 200px;" multiple="">
                                        <?php foreach ($groups as $row) : ?>
                                            <option value="<?= $row['id'] ?>"><?= $row['description'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <b class="col-12 col-lg-2 col-form-label text-sm-right">Active:</b>
                                <div class="col-12 col-lg-4 pt-1">
                                    <div class="switch-button switch-button-success">
                                        <input type="hidden" name="active" value="0" class="input-tmp">
                                        <input type="checkbox" checked="" name="active" id="switch19" value="1">
                                        <span>
                                            <label for="switch19"></label>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group row">
                                <div class="col-12 pt-2 text-center">
                                    <div class="card no-shadow border">
                                        <div class="card-header">
                                            Image
                                        </div>
                                        <div class="card-body">
                                            <div id="image_url" class="image_ft"></div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </form>
    </div>
</div>

<?= $this->endSection() ?>


<!-- Script --->
<?= $this->section('style') ?>

<link rel="stylesheet" href="<?= base_url("assets/lib/chosen/chosen.min.css") ?> " ?>
<?= $this->endSection() ?>
<!-- Script --->
<?= $this->section('script') ?>

<script src="<?= base_url("assets/lib/mustache/mustache.min.js") ?>"></script>
<script src="<?= base_url("assets/lib/ckfinder/ckfinder.js") ?>"></script>
<script src="<?= base_url("assets/lib/image_feature/jquery.image_v2.js") ?>"></script>

<script src="<?= base_url("assets/lib/chosen/chosen.jquery.js") ?>"></script>
<script type='text/javascript'>
    var tin = <?= json_encode($tin) ?>;
    fillForm($("#form-dang-tin"), tin);
    $(document).ready(function() {
        $(".image_ft").imageFeature();

        $("select[name='groups[]']").chosen();
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

        if (tin.image_url) {
            $(".image_ft").imageFeature("set_image", tin.image_url);
        }
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
    });
</script>

<?= $this->endSection() ?>