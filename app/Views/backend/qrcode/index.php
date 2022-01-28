<?= $this->extend('backend/layouts/main') ?>


<?= $this->section('content') ?>
<div class="row clearfix">
    <div class="col-12">
        <section class="card card-fluid">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group row">
                            <b class="col-12 col-lg-2 col-form-label">Data:<i class="text-danger">*</i></b>
                            <div class="col-12 col-lg-4 pt-1">
                                <input class="form-control form-control-sm data" type='text' name="data" required="" placeholder="Data" />
                            </div>
                            <div class="col-12 col-lg-2 pt-1">
                                <button class="btn btn-sm btn-primary createqr">Tạo</button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12 pt-1">
                                <a href="#" class="qrcode"><img class="img-fluid w-50 "></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>


<?= $this->endSection() ?>


<!-- Script --->
<?= $this->section('script') ?>

<script type='text/javascript'>
    var csrfName = '<?= csrf_token() ?>';
    var csrfHash = "<?= csrf_hash() ?>";
    $(document).ready(function() {
        $(".createqr").click(async function() {
            $(".page-loader-wrapper").show();
            let data = $(".data").val();
            let url = await $.ajax({
                "url": path + "admin/qrcode/createqr",
                "data": {
                    data: data,
                    '<?= csrf_token() ?>': "<?= csrf_hash() ?>"
                },
                "type": "POST"
            })
            $(".page-loader-wrapper").hide();
            $(".qrcode").attr("href", url);
            $(".qrcode img").attr("src", url);
        });
    });
</script>
<?= $this->endSection() ?>