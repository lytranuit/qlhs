<?php

namespace App\Controllers\Admin;


class Home extends BaseController
{
    public function index()
    {
        $document_model = model("DocumentModel");
        $option_model = model("OptionModel");
        $DocumentStatus_model = model("DocumentStatusModel");
        $this->data['num_doc'] = $document_model->countAllResults();
        $this->data['num_doc_in_inventory'] = $document_model->where("status_id", 2)->countAllResults();
        $this->data['num_doc_in_loan'] = $document_model->where("status_id", 4)->countAllResults();

        $mail_expire = $option_model->get_options_group("mail_expire");
        $before_send_expire = $mail_expire['before_send'];
        $this->data['num_doc_expire'] = $document_model->where("date_expire <", date("Y-m-d", strtotime("+$before_send_expire day")))->countAllResults();


        $mail_review = $option_model->get_options_group("mail_review");
        $before_send_review = $mail_review['before_send'];
        $this->data['num_doc_review'] = $document_model->where("date_review <", date("Y-m-d", strtotime("+$before_send_review day")))->countAllResults();

        $this->data['num_doc_out_review'] = $document_model->where("date_review <", date("Y-m-d"))->countAllResults();


        $this->data['status'] = $DocumentStatus_model->asObject()->findAll();

        return view($this->data['content'], $this->data);
    }
    public function listqrcode()
    {

        $Document_model = model("DocumentModel");
        $documents = $Document_model->findAll();

        // Creating the new document...
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        /* Note: any element you append to a document must reside inside of a Section. */

        // Adding an empty Section to the document...
        $section = $phpWord->addSection();

        $styleCell =
            [
                'borderColor' => 'ffffff',
                'borderSize' => 6,
            ];
        $table = $section->addTable(array('borderSize' => 0, 'cellMargin'  => 80, 'width' => 100 * 50, 'unit' => \PhpOffice\PhpWord\Style\Table::WIDTH_PERCENT, 'valign' => 'center'));

        $count = 0;
        foreach ($documents as $row) {
            $count++;
            if ($count > 6)
                $count = 1;
            if ($count == 1)
                $table->addRow(null, []);
            $cell = $table->addCell(null, $styleCell);
            $cell->addImage(
                APPPATH . '..' . $row->image_url,
                array(
                    'align' => 'center',
                    'width'         => 70,
                    'height'        => 70,
                    'marginTop'     => -1,
                    'marginLeft'    => -1,
                    'wrappingStyle' => 'behind'
                )
            );
            $name = basename($row->image_url);
            $cell->addText($name, array('size' => 8), array('align' => 'center'));
        }

        // Saving the document as OOXML file...
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save(time() . '.docx');
    }
}
