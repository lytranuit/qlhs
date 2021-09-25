<table class="table table-bordered table-striped" style="width: 100%;
    margin-bottom: 1rem;
    background-color: transparent;  border: 1px solid #dee2e6;border-collapse: collapse;">
    <thead class="thead-dark">
        <tr>
            <th style=" color: #fff;
        background-color: #212529;
        border-color: #32383e;
        border-bottom-width: 2px;
        vertical-align: bottom;
        border-bottom: 2px solid #dee2e6;
        border: 1px solid #dee2e6;
        padding: .75rem;
        vertical-align: top;
        border-top: 1px solid #dee2e6;
        text-align: inherit;">Mã</th>
            <th style=" color: #fff;
        background-color: #212529;
        border-color: #32383e;
        border-bottom-width: 2px;
        vertical-align: bottom;
        border-bottom: 2px solid #dee2e6;
        border: 1px solid #dee2e6;
        padding: .75rem;
        vertical-align: top;
        border-top: 1px solid #dee2e6;
        text-align: inherit;">Tiêu đề</th>
            <th style=" color: #fff;
        background-color: #212529;
        border-color: #32383e;
        border-bottom-width: 2px;
        vertical-align: bottom;
        border-bottom: 2px solid #dee2e6;
        border: 1px solid #dee2e6;
        padding: .75rem;
        vertical-align: top;
        border-top: 1px solid #dee2e6;
        text-align: inherit;">Ngày Hết hạn</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($documents as $row) : ?>
            <tr style="    border: 1px solid #8e8e8e;">
                <td style="padding: .75rem; border: 1px solid #8e8e8e;"><?= $row->code . "." . ($row->version < 10 ? "0" . $row->version : $row->version) ?></td>
                <td style="padding: .75rem; border: 1px solid #8e8e8e;"><?= $row->name_vi ?></td>
                <td style="padding: .75rem; border: 1px solid #8e8e8e;"><?= $row->date_expire ?> (<?= date_diff(date_create(), date_create($row->date_expire))->format("%R%a ngày") ?>)</td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>