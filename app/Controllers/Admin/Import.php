<?php

namespace App\Controllers\Admin;

use App\Libraries\Ciqrcode;

class Import extends BaseController
{
    public function index()
    {
        return view($this->data['content'], $this->data);
    }
    public function sop()
    {

        $ciqrcode = new Ciqrcode();
        //Đường dẫn file
        $file = APPPATH . '../assets/up/SOP.xlsx';

        /** Load $inputFileName to a Spreadsheet Object  **/
        // $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
        // print_r($spreadsheet);
        // die();
        //Tiến hành xác thực file
        $objFile = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file);
        $objData = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($objFile);

        //Chỉ đọc dữ liệu
        // $objData->setReadDataOnly(true);
        // Load dữ liệu sang dạng đối tượng
        $objPHPExcel = $objData->load($file);
        //Lấy ra số trang sử dụng phương thức getSheetCount();
        // Lấy Ra tên trang sử dụng getSheetNames();
        //Chọn trang cần truy xuất
        // $sheet = $objPHPExcel->setActiveSheetIndex(0);

        // //Lấy ra số dòng cuối cùng
        // $Totalrow = $sheet->getHighestRow();
        // //Lấy ra tên cột cuối cùng
        // $LastColumn = $sheet->getHighestColumn();
        // //Chuyển đổi tên cột đó về vị trí thứ, VD: C là 3,D là 4
        // $TotalCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($LastColumn);

        //Tạo mảng chứa dữ liệu
        $data = [];

        $count_sheet = $objPHPExcel->getSheetCount();
        // print_r($count_sheet);
        // die();
        for ($k = 0; $k < $count_sheet; $k++) {

            $sheet = $objPHPExcel->setActiveSheetIndex($k);
            $sheet_name = $sheet->getTitle();
            if (strpos($sheet_name, "#") == false) continue;
            // print_r($sheet_name);die();
            //Lấy ra số dòng cuối cùng
            $Totalrow = $sheet->getHighestRow();
            //Lấy ra tên cột cuối cùng
            $LastColumn = $sheet->getHighestColumn();
            //Chuyển đổi tên cột đó về vị trí thứ, VD: C là 3,D là 4
            $TotalCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($LastColumn);

            //Tạo mảng chứa dữ liệu
            $data = [];

            //Tiến hành lặp qua từng ô dữ liệu
            //----Lặp dòng, Vì dòng đầu là tiêu đề cột nên chúng ta sẽ lặp giá trị từ dòng 2
            $row = 4;
            for ($i = $row; $i <= $Totalrow; $i++) {
                //----Lặp cột
                for ($j = 0; $j < $TotalCol; $j++) {
                    // Tiến hành lấy giá trị của từng ô đổ vào mảng
                    $cell = $sheet->getCellByColumnAndRow($j, $i);

                    $data[$i -  $row][$j] = $cell->getValue();
                    ///CHUYEN RICH TEXT
                    if ($data[$i -  $row][$j] instanceof \PhpOffice\PhpSpreadsheet\RichText\RichText) {
                        $data[$i -  $row][$j] = $data[$i -  $row][$j]->getPlainText();
                    }

                    // ////CHUYEN DATE 
                    // if (PHPExcel_Shared_Date::isDateTime($cell) && $data[$i - 1][$j] > 0) {

                    //     if (is_numeric($data[$i - 1][$j])) {
                    //         $data[$i - 1][$j] = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($data[$i - 1][$j]));
                    //     } else if ($data[$i - 1][$j] == '26/09/16') {
                    //         $data[$i - 1][$j] = '2016-09-26';
                    //     }
                    // }
                }
            }


            // echo "<pre>";
            // echo $sheet_name . "<br>";
            // print_r($data);
            // die();
            $document_model = model("DocumentModel");
            foreach ($data as $row) {
                $row[2] = trim($row[2]);
                $explode =  explode(".", $row[2]);
                if (count($explode) < 2) continue;

                $version = $explode[1];
                $code = $explode[0];
                $name = $row[3];
                $date_effect_row = explode(".", $row[4]);
                $date_review_row = explode(".", $row[5]);
                if (count($date_effect_row) < 3) {
                    $date_effect = null;
                } else {
                    $d = $date_effect_row[0];
                    $m = $date_effect_row[1];
                    $y = "20$date_effect_row[2]";
                    $date_effect = "$y-$m-$d";
                }
                if (count($date_review_row) < 3) {
                    $date_review = null;
                } else {
                    $d = $date_review_row[0];
                    $m = $date_review_row[1];
                    $y = "20$date_review_row[2]";
                    $date_review = "$y-$m-$d";
                }

                $array = array(
                    'code' => $code,
                    'version' => $version,
                    'date_effect' => $date_effect,
                    'date_review' => $date_review,
                    'name_vi' => $name,
                    'status_id' => 1,
                    'is_active' => 1,
                );
                $id = $document_model->insert($array);

                $data_qr = base_url("admin/document/edit/$id");
                $dir = FCPATH . "assets/qrcode/";
                $save_name  = $id . "_" . $row[2]  . '.png';

                /* QR Code File Directory Initialize */
                if (!file_exists($dir)) {
                    mkdir($dir, 0775, true);
                }

                /* QR Configuration  */

                /* QR Data  */
                $params['data']     = $data_qr;
                $params['level']    = 'L';
                $params['size']     = 10;
                $params['savename'] = $dir . $save_name;

                $ciqrcode->generate($params);
                $document_model->update($id, array("image_url" => "/assets/qrcode/$save_name"));
            }
        }
    }

    function updateqr()
    {

        $ciqrcode = new Ciqrcode();
        $document_model = model("DocumentModel");
        $documents = $document_model->asArray()->findAll();
        foreach ($documents as $row) {
            $code1 =  $row['code'] . "." . ($row['version'] < 10 ? "0" . $row['version'] : $row['version']);
            $id = $row['id'];
            $data_qr = base_url("admin/document/edit/$id");
            $dir = FCPATH . "assets/qrcode/";
            $save_name  = $id . "_" . $code1  . '.png';

            /* QR Code File Directory Initialize */
            if (!file_exists($dir)) {
                mkdir($dir, 0775, true);
            }

            /* QR Configuration  */

            /* QR Data  */
            $params['data']     = $data_qr;
            $params['level']    = 'L';
            $params['size']     = 10;
            $params['savename'] = $dir . $save_name;

            $ciqrcode->generate($params);
            // $document_model->update($id, array("image_url" => "/assets/qrcode/$save_name"));
        }
    }
}