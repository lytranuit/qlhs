<?php

namespace App\Controllers\Admin;

use App\Libraries\Ciqrcode;

class Qrcode extends BaseController
{
    public function index()
    {
        return view($this->data['content'], $this->data);
    }
    public function createqr()
    {
        $data_qr =  $this->request->getPost("data");
        $dir = FCPATH . "assets/tmp/";
        $save_name =   time()  . '.png';

        /* QR Code File Directory Initialize */
        if (!file_exists($dir)) {
            mkdir($dir, 0775, true);
        }

        /* QR Configuration  */
        $ciqrcode = new Ciqrcode();
        /* QR Data  */
        $params['data']     = $data_qr;
        $params['level']    = 'L';
        $params['size']     = 10;
        $params['savename'] = $dir . $save_name;

        $ciqrcode->generate($params);

        echo base_url("assets/tmp/$save_name");
    }
}
