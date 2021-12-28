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

                            <div class="tab-content">
                                <div id="menu0" class="tab-pane active">
                                    <div class="form-group row">
                                        <b class="col-12 col-lg-2 col-form-label">Tên:<i class="text-danger">*</i></b>
                                        <div class="col-12 col-lg-4 pt-1">
                                            <input class="form-control form-control-sm" type='text' name="name" required="" placeholder="Tên" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <b class="col-12 col-lg-2 col-form-label">Mô tả:</b>
                                        <div class="col-12">
                                            <textarea class="form-control" name="description"></textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group row">
                                <div class="col-12 pt-2">
                                    <div class="card no-shadow border">
                                        <div class="card-header">
                                            File
                                            <div style="margin-left:auto;">
                                                <a class="btn btn-sm btn-success text-white float-right button_file">Thêm</a>
                                                <input type="file" class="d-none file_document" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
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
            </section>
        </form>
    </div>
</div>


<?= $this->endSection() ?>


<!-- Script --->
<?= $this->section('script') ?>

<template class="file_template">
    <div class="mb-2 file_box">
        <div class="file-icon" data-type="{{ext}}"></div>
        <a href="{{url}}" download="{{name}}">{{name}}</a>
        <input type="hidden" value="{{id}}" name="file_id" />
        <a class="text-danger ml-2 remove_file" href="javascript:void(0)"><i class="fas fa-trash-alt"></i></a>
    </div>
</template>
<script src="<?= base_url("assets/lib/mustache/mustache.min.js") ?>"></script>
<script type='text/javascript'>
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

    function add_file(item) {
        var template = $(".file_template").html();
        var rendered = Mustache.render(template, item);
        $(".no_item").remove();
        $(".list_file").html(rendered);
    }
</script>
<?= $this->endSection() ?>