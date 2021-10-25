<?php

namespace App\Controllers\Admin;

use App\Libraries\Ciqrcode;

class Import extends BaseController
{
    public function __construct()
    {
        if (!in_groups(array('admin'))) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(lang('Auth.notEnoughPrivilege'));
        }
    }
    public function index()
    {
        return view($this->data['content'], $this->data);
    }
    public function sop()
    {
        die();
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


            echo "<pre>";
            echo $sheet_name . "<br>";
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
                $array = preg_split("/\r\n|\n|\r/", $name);
                $name_vi = isset($array[0]) ? $array[0] : "";
                $name_en = isset($array[1]) ? $array[1] : "";
                // print_r($array);
                // die();
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
                    'name_vi' => $name_vi,
                    'name_en' => $name_en,
                    'status_id' => 1,
                    'is_active' => 1,
                );
                $id = $document_model->insert($array);
            }
        }
    }
    public function maiqa()
    {
        die();
        //Đường dẫn file
        $file = APPPATH . '../assets/up/List - DANH MA SO CHO TDQTSX, TDQTVS.xlsx';

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
            // if (strpos($sheet_name, "#") == false) continue;
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
            $row = 6;
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
                $name = $row[4];
                $array = preg_split("/\r\n|\n|\r/", $name);
                $name_vi = isset($array[0]) ? $array[0] : "";
                $name_en = isset($array[1]) ? $array[1] : "";
                // print_r($array);
                // die();
                $date_effect_row = explode(".", $row[6]);
                // $date_review_row = explode(".", $row[5]);
                if (count($date_effect_row) < 3) {
                    $date_effect = null;
                } else {
                    $d = $date_effect_row[0];
                    $m = $date_effect_row[1];
                    $y = "20$date_effect_row[2]";
                    $date_effect = "$y-$m-$d";
                }
                // if (count($date_review_row) < 3) {
                //     $date_review = null;
                // } else {
                //     $d = $date_review_row[0];
                //     $m = $date_review_row[1];
                //     $y = "20$date_review_row[2]";
                //     $date_review = "$y-$m-$d";
                // }

                $array = array(
                    'code' => $code,
                    'version' => $version,
                    'date_effect' => $date_effect,
                    'name_vi' => $name_vi,
                    'name_en' => $name_en,
                    'status_id' => 1,
                    'type_id' => 5,
                    'is_active' => 1,
                    'from_file' => "Sheet_" . $sheet_name . "_List - DANH MA SO CHO TDQTSX, TDQTVS.xlsx"
                );
                $id = $document_model->insert($array);
            }
        }
    }
    public function hiepqa()
    {
        die();
        //Đường dẫn file
        $file = APPPATH . '../assets/up/Danh sách & tình trạng hồ sơ cập nhật hồ sơ đao tạo_21.10.21.xlsx';

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
            // if (strpos($sheet_name, "#") == false) continue;
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
            $row = 2;
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


            echo "<pre>";
            // echo $sheet_name . "<br>";
            // print_r($data);
            // die();
            $document_model = model("DocumentModel");
            foreach ($data as $row) {

                $name = $row[3];
                if ($name == "") continue;
                // $row[1] = trim($row[1]);
                // $explode =  explode(".", $row[1]);
                // if (count($explode) < 2) continue;

                $version = $row[2];
                $code = $row[1];
                $status = $row[4];
                $type = $row[5];
                $is_active = $row[9];
                $description = $row[10];

                $other = strlen($version);
                $explode = array();
                if ($code != "" && $code != "NA") {

                    $explode =  explode(".", $code);
                    if (count($explode) >= 2) {
                        goto end;
                    }
                    $explode =  explode("/", $code);
                    if (count($explode) >= 2) {
                        goto end;
                    }
                    end:
                    if ($explode[count($explode) - 1] == $version) {
                        $code = substr($code, 0, 0 - strlen($version) - 1);
                    }
                }


                $array = preg_split("/\r\n|\n|\r/", $name);
                $name_vi = isset($array[0]) ? $array[0] : "";
                $name_en = isset($array[1]) ? $array[1] : "";
                // print_r($array);
                // die();
                $date_effect_row = explode(".", $row[6]);
                // $date_review_row = explode(".", $row[5]);
                if (count($date_effect_row) < 3) {
                    $date_effect = null;
                } else {
                    $d = $date_effect_row[0];
                    $m = $date_effect_row[1];
                    $y = "20$date_effect_row[2]";
                    $date_effect = "$y-$m-$d";
                }
                // if (count($date_review_row) < 3) {
                //     $date_review = null;
                // } else {
                //     $d = $date_review_row[0];
                //     $m = $date_review_row[1];
                //     $y = "20$date_review_row[2]";
                //     $date_review = "$y-$m-$d";
                // }
                switch ($status) {
                    case "Đang lưu trữ":
                        $status_id = 2;
                        break;
                    default:
                        $status_id = 1;
                        break;
                }
                switch ($type) {
                    case "Hồ sơ đào tạo":
                        $type_id = 8;
                        break;
                    case "Hồ sơ GMP":
                        $type_id = 9;
                        break;
                    default:
                        $type_id = 7;
                }
                if ($is_active == "Yes") {
                    $is_active = 1;
                } else {
                    $is_active = 0;
                }
                $array = array(
                    // 'other' => $explode,
                    'code' => $code,
                    'version' => $version,
                    'date_effect' => $date_effect,
                    'name_vi' => $name_vi,
                    'name_en' => $name_en,
                    'status_id' => $status_id,
                    'type_id' => $type_id,
                    'is_active' => $is_active,
                    'description_vi' => $description,
                    'from_file' => "Sheet_" . $sheet_name . "_Danh sách & tình trạng hồ sơ cập nhật hồ sơ đao tạo_21.10.21.xlsx"
                );
                // print_r($array);
                $id = $document_model->insert($array);
            }
        }
    }

    public function luuqa()
    {
        die();
        //Đường dẫn file
        $file = APPPATH . '../assets/up/Danh sách & tình trạng hồ sơ cập nhật đến_ver 150921_CKBT.xlsx';

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
            // if (strpos($sheet_name, "#") == false) continue;
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


            echo "<pre>";
            // echo $sheet_name . "<br>";
            // print_r($data);
            // die();
            $document_model = model("DocumentModel");
            foreach ($data as $row) {

                $name = $row[3];
                if ($name == "") continue;
                // $row[1] = trim($row[1]);
                // $explode =  explode(".", $row[1]);
                // if (count($explode) < 2) continue;

                $version = $row[2];
                $code = $row[1];
                $status = $row[4];
                $type = $row[5];
                $is_active = $row[9];
                $description = $row[10];

                $other = strlen($version);
                $explode = array();
                if ($code != "" && $code != "NA") {

                    $explode =  explode(".", $code);
                    if (count($explode) >= 2) {
                        goto end;
                    }
                    $explode =  explode("/", $code);
                    if (count($explode) >= 2) {
                        goto end;
                    }
                    end:
                    if ($explode[count($explode) - 1] == $version) {
                        $code = substr($code, 0, 0 - strlen($version) - 1);
                    }
                }


                $array = preg_split("/\r\n|\n|\r/", $name);
                $name_vi = isset($array[0]) ? $array[0] : "";
                $name_en = isset($array[1]) ? $array[1] : "";
                // print_r($array);
                // die();
                // $date_effect_row = explode(".", $row[6]);
                // // $date_review_row = explode(".", $row[5]);
                // if (count($date_effect_row) < 3) {
                //     $date_effect = null;
                // } else {
                //     $d = $date_effect_row[0];
                //     $m = $date_effect_row[1];
                //     $y = "20$date_effect_row[2]";
                //     $date_effect = "$y-$m-$d";
                // }
                // if (count($date_review_row) < 3) {
                //     $date_review = null;
                // } else {
                //     $d = $date_review_row[0];
                //     $m = $date_review_row[1];
                //     $y = "20$date_review_row[2]";
                //     $date_review = "$y-$m-$d";
                // }
                switch ($status) {
                    case "Đang lưu trữ":
                        $status_id = 2;
                        break;
                    default:
                        $status_id = 1;
                        break;
                }
                switch ($type) {
                    case "Thẩm định thiết bị":
                        $type_id = 4;
                        break;
                    case "Đánh giá khuôn mẫu":
                        $type_id = 3;
                        break;
                    case "Tái thẩm định thiết bị":
                        $type_id = 2;
                        break;
                    default:
                        $type_id = 7;
                }
                if ($is_active == "Yes") {
                    $is_active = 1;
                } else {
                    $is_active = 0;
                }
                $array = array(
                    // 'other' => $explode,
                    'code' => $code,
                    'version' => $version,
                    // 'date_effect' => $date_effect,
                    'name_vi' => $name_vi,
                    'name_en' => $name_en,
                    'status_id' => $status_id,
                    'type_id' => $type_id,
                    'is_active' => $is_active,
                    'description_vi' => $description,
                    'from_file' => "Sheet_" . $sheet_name . "_Danh sách & tình trạng hồ sơ cập nhật đến_ver 150921_CKBT.xlsx"
                );
                // print_r($array);
                $id = $document_model->insert($array);
            }
        }
    }
    public function truongqa()
    {
        die();
        //Đường dẫn file
        $file = APPPATH . '../assets/up/List & Database-SPC.xlsx';

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
            // if (strpos($sheet_name, "#") == false) continue;
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


            echo "<pre>";
            // echo $sheet_name . "<br>";
            // print_r($data);
            // die();
            $document_model = model("DocumentModel");
            foreach ($data as $row) {

                $name = $row[3];
                if ($name == "") continue;
                // $row[1] = trim($row[1]);
                // $explode =  explode(".", $row[1]);
                // if (count($explode) < 2) continue;

                // $version = $row[2];
                $code = $row[2];
                // $is_active = $row[9];
                $description = $row[13];

                // $other = strlen($version);
                $explode = array();
                if ($code != "" && $code != "NA") {
                    $explode =  explode("-", $code);
                    if (count($explode) >= 2) {
                        $version = $explode[count($explode) - 1];
                        $code = substr($code, 0, 0 - strlen($version) - 1);
                    }
                }


                $array = preg_split("/\r\n|\n|\r/", $name);
                $name_vi = isset($array[0]) ? $array[0] : "";
                $name_en = isset($array[1]) ? $array[1] : "";
                // print_r($array);
                // die();
                $date_effect_row = explode(".", $row[4]);
                // // $date_review_row = explode(".", $row[5]);
                if (count($date_effect_row) < 3) {
                    $date_effect = null;
                } else {
                    $d = $date_effect_row[0];
                    $m = $date_effect_row[1];
                    $y = "20$date_effect_row[2]";
                    $date_effect = "$y-$m-$d";
                }
                // if (count($date_review_row) < 3) {
                //     $date_review = null;
                // } else {
                //     $d = $date_review_row[0];
                //     $m = $date_review_row[1];
                //     $y = "20$date_review_row[2]";
                //     $date_review = "$y-$m-$d";
                // }
                $status_id = 2;

                $type_id = 10;
                $is_active = 1;

                $array = array(
                    // 'other' => $explode,
                    'code' => $code,
                    'version' => $version,
                    'date_effect' => $date_effect,
                    'name_vi' => $name_vi,
                    'name_en' => $name_en,
                    'status_id' => $status_id,
                    'type_id' => $type_id,
                    'is_active' => $is_active,
                    'description_vi' => $description,
                    'from_file' => "Sheet_" . $sheet_name . "_List & Database-SPC.xlsx"
                );
                // print_r($array);
                $id = $document_model->insert($array);
            }
        }
    }
    public function duyqa2019()
    {
        die();
        //Đường dẫn file
        $file = APPPATH . '../assets/up/LIST OF GCL 2019.xlsx';

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
            // if (strpos($sheet_name, "#") == false) continue;
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
            $row = 7;
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

                    ////CHUYEN DATE 
                    // if (\PhpOffice\PhpSpreadsheet\Shared\Date::isDateTime($cell) && $data[$i - 1][$j] > 0) {
                    //     if (is_numeric($data[$i - 1][$j])) {
                    //         $data[$i - 1][$j] =  $cell->getFormattedValue();
                    //     }
                    // }
                }
            }


            echo "<pre>";
            // echo $sheet_name . "<br>";
            // print_r($data);
            // die();
            $document_model = model("DocumentModel");
            foreach ($data as $row) {

                $name = $row[6];
                if ($name == "") continue;
                // $row[1] = trim($row[1]);
                // $explode =  explode(".", $row[1]);
                // if (count($explode) < 2) continue;

                // $version = $row[2];
                $code = $row[7];
                $version = "01";
                // $is_active = $row[9];
                $description = $row[8];

                // $other = strlen($version);
                $explode = array();
                // if ($code != "" && $code != "NA") {
                //     $explode =  explode("-", $code);
                //     if (count($explode) >= 2) {
                //         $version = $explode[count($explode) - 1];
                //         $code = substr($code, 0, 0 - strlen($version) - 1);
                //     }
                // }


                $array = preg_split("/\r\n|\n|\r/", $name);
                $name_vi = isset($array[0]) ? $array[0] : "";
                $name_en = isset($array[1]) ? $array[1] : "";
                // print_r($array);
                // die();
                // if()
                // if(is_numeric()){

                // }
                if (is_numeric($row[2])) {
                    $date_effect = date("Y-m-d", \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($row[2]));
                } else {
                    $date_effect_row = explode(".", $row[2]);
                    // // $date_review_row = explode(".", $row[5]);
                    if (count($date_effect_row) < 3) {
                        $date_effect = null;
                    } else {
                        $d = $date_effect_row[0];
                        $m = $date_effect_row[1];
                        $y = "20$date_effect_row[2]";
                        $date_effect = "$y-$m-$d";
                    }
                }

                // if (count($date_review_row) < 3) {
                //     $date_review = null;
                // } else {
                //     $d = $date_review_row[0];
                //     $m = $date_review_row[1];
                //     $y = "20$date_review_row[2]";
                //     $date_review = "$y-$m-$d";
                // }
                $status_id = 2;

                $type_id = 11;
                $is_active = 0;

                $array = array(
                    // 'other' => $explode,
                    'code' => $code,
                    'version' => $version,
                    'date_effect' => $date_effect,
                    'name_vi' => $name_vi,
                    'name_en' => $name_en,
                    'status_id' => $status_id,
                    'type_id' => $type_id,
                    'is_active' => $is_active,
                    'description_vi' => $description,
                    'from_file' => "Sheet_" . $sheet_name . "_LIST OF GCL 2019.xlsx"
                );
                // print_r($array);
                $id = $document_model->insert($array);
            }
        }
    }

    public function duyqa2020()
    {
        die();
        //Đường dẫn file
        $file = APPPATH . '../assets/up/LIST OF GCL 2020.xlsx';

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
            // if (strpos($sheet_name, "#") == false) continue;
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
            $row = 7;
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

                    ////CHUYEN DATE 
                    // if (\PhpOffice\PhpSpreadsheet\Shared\Date::isDateTime($cell) && $data[$i - 1][$j] > 0) {
                    //     if (is_numeric($data[$i - 1][$j])) {
                    //         $data[$i - 1][$j] =  $cell->getFormattedValue();
                    //     }
                    // }
                }
            }


            echo "<pre>";
            // echo $sheet_name . "<br>";
            // print_r($data);
            // die();
            $document_model = model("DocumentModel");
            foreach ($data as $row) {

                $name = $row[6];
                if ($name == "") continue;
                // $row[1] = trim($row[1]);
                // $explode =  explode(".", $row[1]);
                // if (count($explode) < 2) continue;

                // $version = $row[2];
                $code = $row[7];
                $version = "01";
                // $is_active = $row[9];
                $description = $row[8];

                // $other = strlen($version);
                $explode = array();
                // if ($code != "" && $code != "NA") {
                //     $explode =  explode("-", $code);
                //     if (count($explode) >= 2) {
                //         $version = $explode[count($explode) - 1];
                //         $code = substr($code, 0, 0 - strlen($version) - 1);
                //     }
                // }


                $array = preg_split("/\r\n|\n|\r/", $name);
                $name_vi = isset($array[0]) ? $array[0] : "";
                $name_en = isset($array[1]) ? $array[1] : "";
                // print_r($array);
                // die();
                // if()
                // if(is_numeric()){

                // }
                if (is_numeric($row[2])) {
                    $date_effect = date("Y-m-d", \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($row[2]));
                } else {
                    $date_effect_row = explode(".", $row[2]);
                    // // $date_review_row = explode(".", $row[5]);
                    if (count($date_effect_row) < 3) {
                        $date_effect = null;
                    } else {
                        $d = $date_effect_row[0];
                        $m = $date_effect_row[1];
                        $y = "20$date_effect_row[2]";
                        $date_effect = "$y-$m-$d";
                    }
                }

                // if (count($date_review_row) < 3) {
                //     $date_review = null;
                // } else {
                //     $d = $date_review_row[0];
                //     $m = $date_review_row[1];
                //     $y = "20$date_review_row[2]";
                //     $date_review = "$y-$m-$d";
                // }
                $status_id = 2;

                $type_id = 11;
                $is_active = 0;

                $array = array(
                    // 'other' => $explode,
                    'code' => $code,
                    'version' => $version,
                    'date_effect' => $date_effect,
                    'name_vi' => $name_vi,
                    'name_en' => $name_en,
                    'status_id' => $status_id,
                    'type_id' => $type_id,
                    'is_active' => $is_active,
                    'description_vi' => $description,
                    'from_file' => "Sheet_" . $sheet_name . "_LIST OF GCL 2020.xlsx"
                );
                // print_r($array);
                $id = $document_model->insert($array);
            }
        }
    }

    public function duyqa2021()
    {
        die();
        //Đường dẫn file
        $file = APPPATH . '../assets/up/LIST OF GCL 2021.xlsx';

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
            // if (strpos($sheet_name, "#") == false) continue;
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
            $row = 7;
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

                    ////CHUYEN DATE 
                    // if (\PhpOffice\PhpSpreadsheet\Shared\Date::isDateTime($cell) && $data[$i - 1][$j] > 0) {
                    //     if (is_numeric($data[$i - 1][$j])) {
                    //         $data[$i - 1][$j] =  $cell->getFormattedValue();
                    //     }
                    // }
                }
            }


            echo "<pre>";
            // echo $sheet_name . "<br>";
            // print_r($data);
            // die();
            $document_model = model("DocumentModel");
            foreach ($data as $row) {

                $name = $row[6];
                if ($name == "") continue;
                // $row[1] = trim($row[1]);
                // $explode =  explode(".", $row[1]);
                // if (count($explode) < 2) continue;

                // $version = $row[2];
                $code = $row[7];
                $version = "01";
                // $is_active = $row[9];
                $description = $row[8];

                // $other = strlen($version);
                $explode = array();
                // if ($code != "" && $code != "NA") {
                //     $explode =  explode("-", $code);
                //     if (count($explode) >= 2) {
                //         $version = $explode[count($explode) - 1];
                //         $code = substr($code, 0, 0 - strlen($version) - 1);
                //     }
                // }


                $array = preg_split("/\r\n|\n|\r/", $name);
                $name_vi = isset($array[0]) ? $array[0] : "";
                $name_en = isset($array[1]) ? $array[1] : "";
                // print_r($array);
                // die();
                // if()
                // if(is_numeric()){

                // }
                if (is_numeric($row[2])) {
                    $date_effect = date("Y-m-d", \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($row[2]));
                } else {
                    $date_effect_row = explode(".", $row[2]);
                    // // $date_review_row = explode(".", $row[5]);
                    if (count($date_effect_row) < 3) {
                        $date_effect = null;
                    } else {
                        $d = $date_effect_row[0];
                        $m = $date_effect_row[1];
                        $y = "20$date_effect_row[2]";
                        $date_effect = "$y-$m-$d";
                    }
                }

                // if (count($date_review_row) < 3) {
                //     $date_review = null;
                // } else {
                //     $d = $date_review_row[0];
                //     $m = $date_review_row[1];
                //     $y = "20$date_review_row[2]";
                //     $date_review = "$y-$m-$d";
                // }
                $status_id = 1;

                $type_id = 11;
                $is_active = 1;

                $array = array(
                    // 'other' => $explode,
                    'code' => $code,
                    'version' => $version,
                    'date_effect' => $date_effect,
                    'name_vi' => $name_vi,
                    'name_en' => $name_en,
                    'status_id' => $status_id,
                    'type_id' => $type_id,
                    'is_active' => $is_active,
                    'description_vi' => $description,
                    'from_file' => "Sheet_" . $sheet_name . "_LIST OF GCL 2021.xlsx"
                );
                // print_r($array);
                $id = $document_model->insert($array);
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

            $data_qr = base_url("qrcode/document") . "/" . urlencode($row['uuid']);
            // print_r($data_qr);
            // die();
            $dir = FCPATH . "assets/qrcode/";
            $save_name = $id . "_" . time()  . '.png';
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

    function updateen()
    {
        $document_model = model("DocumentModel");
        $documents = $document_model->asArray()->findAll();
        foreach ($documents as $row) {
            $id = $row['id'];
            $name = $row['name_vi'];
            $array = preg_split("/\r\n|\n|\r/", $name);
            // if($id == 1){
            //     print_r($array);
            //     die();
            // }
            $name_vi = isset($array[0]) ? $array[0] : null;
            $name_en = isset($array[1]) ? $array[1] : null;
            $document_model = model("DocumentModel");
            $document_model->where('id', $id)
                ->set(["name_vi" => $name_vi, 'name_en' => $name_en])->update();
        }
    }
}
