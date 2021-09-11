/**
 * @license Copyright (c) 2003-2020, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function (config) {
    config.filebrowserBrowseUrl = '/assets/lib/ckfinder/ckfinder.html',
        config.filebrowserUploadUrl = '/assets/lib/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
        config.filebrowserWindowWidth = '1000',
        config.filebrowserWindowHeight = '700'
    config.toolbarGroups = [
        { name: 'document', groups: ['mode', 'document', 'doctools'] },
        { name: 'clipboard', groups: ['clipboard', 'undo'] },
        { name: 'editing', groups: ['find', 'selection', 'spellchecker', 'editing'] },
        { name: 'forms', groups: ['forms'] },
        { name: 'basicstyles', groups: ['basicstyles', 'cleanup'] },
        { name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph'] },
        { name: 'links', groups: ['links'] },
        { name: 'insert', groups: ['insert'] },
        { name: 'styles', groups: ['styles'] },
        { name: 'colors', groups: ['colors'] },
        { name: 'tools', groups: ['tools'] },
        { name: 'others', groups: ['others'] },
        { name: 'about', groups: ['about'] }
    ];

    config.removeButtons = 'Source,Save,Templates,Cut,Undo,Copy,Paste,NewPage,ExportPdf,Preview,Print,Redo,PasteText,PasteFromWord,Replace,Find,Scayt,SelectAll,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Flash,HorizontalRule,PageBreak,About,RemoveFormat,CopyFormatting,CreateDiv';

}