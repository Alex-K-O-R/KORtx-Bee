<?php
namespace app\utilities\inner\forms;

abstract class Field {
    public $field;
    public $html;
    public $alias;

    /**
     * Field constructor.
     * @param $text
     * @param $backEndField
     */
    public function __construct($text, $backEndField = null)
    {
        $this->alias = $text;
        $this->field = $backEndField;
    }

    public function render($odd = false){
        if (!$this->field) {
            ?>
            <tr>
                <td colspan="2"><?=$this->alias?></td>
            </tr>
            <?php
        } else {
            ?>
            </tr>
            <td><?=$this->alias?></td>
            <td><?=$this->html?></td>
            </tr>
            <?php
        }
    }

    public function input(string $value = '', bool $disabled = false){
        $this->html = '<input name="'.$this->field.'" type="text"
                       value="'.$value.'" '.($disabled ? 'disabled="disabled" ' : '').'/>';
    }

    public function select(array $values, $selectedInd = null, bool $disabled = false){
        $isMultiple = is_array($selectedInd);
        $this->html = '<select name="'.$this->field.'" class="" '.($isMultiple ? 'multiple="multiple" ': '').'>';

        foreach ($values as $id => $value){
            $isSelected = ($isMultiple ? (in_array($id, $selectedInd) ? true : false) : (($id == $selectedInd) ? true : false) );
            $this->html .= '<option value="'.$id.'" '. ($isSelected ? 'selected ' : '').'>'.$value.'</option>';
        }
        $this->html .= '</select>';
    }
}