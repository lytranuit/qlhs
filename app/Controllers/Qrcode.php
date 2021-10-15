<?php

namespace App\Controllers;


class Qrcode extends BaseController
{
    function __construct()
    {
    }
    public function document($code)
    {
        $document_model = model("DocumentModel");
        $document = $document_model->where('uuid', $code)->first();
        if (!empty($document)) {
            return redirect()->to(base_url("admin/document/edit/$document->id"));
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(lang('Auth.notEnoughPrivilege'));
        }
    }
}
