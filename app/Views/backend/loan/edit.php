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
                                <b class="col-12 col-lg-2 col-form-label">Người mượn:<span class="text-danger">*</span></b>
                                <div class="col-12 col-lg-4 pt-1">
                                    <input class="form-control form-control-sm" type='text' name="name" required="" placeholder="Người mượn" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <b class="col-12 col-lg-2 col-form-label">Tài liệu:</b>
                                <div class="col-lg-10 pt-1">
                                    <select name="documents[]" class="w-100 chosen" multiple="">
                                        <?php foreach ($documents as $row) : ?>
                                            <option value="<?= $row->id ?>"><?= $row->code . "." . $row->version . " - " . $row->name_vi ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <b class="col-12 col-form-label">Ghi chú:</b>
                                <div class="col-12 pt-1">
                                    <textarea class="form-control" name="note" rows="10"></textarea>
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
<?= $this->endSection() ?>


<!-- Style --->
<?= $this->section("style") ?>
<link rel="stylesheet" href="<?= base_url("assets/lib/chosen/chosen.min.css") ?> " ?>

<link rel="stylesheet" href="<?= base_url("assets/lib/datatables/datatables.min.css") ?> " ?>
<?= $this->endSection() ?>

<!-- Script --->
<?= $this->section('script') ?>
<script src="<?= base_url("assets/lib/chosen/chosen.jquery.js") ?>"></script>
<script type='text/javascript'>
    var csrfName = '<?= csrf_token() ?>';
    var csrfHash = "<?= csrf_hash() ?>";
    $(document).ready(function() {
        var tin = <?= json_encode($tin) ?>;
        fillForm($("#form-dang-tin"), tin);
        $(".chosen").chosen({
            width: "100%"
        });
        $.validator.setDefaults({
            debug: true,
            success: "valid"
        });
        
    });
</script>
<?= $this->endSection() ?>