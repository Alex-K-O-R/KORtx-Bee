<?
namespace app\display\nodes;
use app\WebUIApplication;
use app\Pages;

class TextEditor {
    private $TextEditorBlockId;
    private $TextInputName;

    public function __construct($TextEditorBlockId, $TextInputName, $defaultText = null){
        //if($defaultText === null) $defaultText = 'Текстовое описание (не менее 300 символов)';
        self::Draw($TextEditorBlockId, $TextInputName, $defaultText);
    }

    public static function Draw($TextEditorBlockId, $TextInputName, $defaultText){
        $defaultText = mb_ereg_replace('\r\n', '<br>', $defaultText);
        ?>
        <div class="pell ab_editblock">
            <div id="<?=$TextEditorBlockId?>" class="ab_textedit">
            </div>
            <textarea name="<?=$TextInputName?>" id="editor-output_<?=$TextInputName?>" class="no_count" hidden="hidden" echo_length=".words_count span"><?=$defaultText?></textarea>
            <script type="text/javascript">
                $(function(){
                    var editor = window.pell.init({
                        element: document.getElementById('<?=$TextEditorBlockId?>'),
                        actions: [
                            'bold', 'italic', 'underline',
                            {name: 'olist', icon: '<i class="fas fa-list-ol"></i>'},
                            {name: 'ulist', icon: '<i class="fas fa-list-ul"></i>'},
                            {name: 'undo', icon: '<i class="fas fa-undo"></i>'},
                            {name: 'redo', icon: '<i class="fas fa-redo"></i>'},
                            {name: 'link', icon: '<i class="fas fa-link"></i>'},
                            {name: 'justifyLeft', icon: '<i class="fas fa-align-left"></i>'},
                            {name: 'justifyRight', icon: '<i class="fas fa-align-right"></i>'},
                            {name: 'justifyCenter', icon: '<i class="fas fa-align-center"></i>'},
                            {name: 'justifyFull', icon: '<i class="fas fa-align-justify"></i>'},
                            {name: 'line', icon: '<i class="fas fa-minus"></i>'},
                            {name: 'removeFormat', icon: '<i class="fas fa-broom"></i>'},
                            {name: 'superscript', icon: '<i class="fas fa-superscript"></i>'},
                            {name: 'subscript', icon: '<i class="fas fa-subscript"></i>'}
                        ],
                        defaultParagraphSeparator: 'p',
                        onChange: function (html) {
                            document.getElementById('editor-output_<?=$TextInputName?>').value = html;
                            $('#editor-output_<?=$TextInputName?>').trigger('keyup');//   = html;
                        },
                        onBlur: function (html) {
                            document.getElementById('editor-output_<?=$TextInputName?>').value = html;
                        }
                    });
                    editor.content.innerHTML = <?=json_encode($defaultText)?>;
                    $('#editor-output_<?=$TextInputName?>').on('change', function(){
                        var prev_editor_val = document.getElementById('editor-output_<?=$TextInputName?>').value;
                        if(typeof prev_editor_val == 'undefined' || typeof prev_editor_val != 'undefined' && prev_editor_val == ''){
                        } else {
                            editor.content.innerHTML = prev_editor_val;
                        }
                    });
                    editor.addEventListener("paste", function(e) {
                        // cancel paste
                        e.preventDefault();
                        /*// get text representation of clipboard
                         var text = (e.originalEvent || e).clipboardData.getData('text/plain');
                         // insert text manually
                         document.execCommand("insertHTML", false, text);*/
                        var text = '';
                        if (e.clipboardData || e.originalEvent.clipboardData) {
                            text = (e.originalEvent || e).clipboardData.getData('text/plain');
                        } else if (window.clipboardData) {
                            text = window.clipboardData.getData('Text');
                        }
                        if (document.queryCommandSupported('insertText')) {
                            document.execCommand('insertText', false, text);
                        } else {
                            document.execCommand('paste', false, text);
                        }
                        return false;
                    });
                });
            </script>
        </div>
<?
    }
}

?>