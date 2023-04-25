<?
namespace app\modules\admin\components;

use Yii;
use yii\base\Component;
use yii\web\UploadedFile;
use yii\helpers\Json;
use linslin\yii2\curl;

class Functions  extends Component
{
    public function init(){
        parent::init();
    }

    public function recursiveCheckboxes( &$data, $parent = 0,  $field = 'name', $inputname, $checked = [] )
    {
        if (isset($data[$parent]))
        {
            $hasChecked = 0;
            foreach ($data[$parent] as $id => $node)
            {
                if (isset($checked[$node->id]))
                {
                    $hasChecked = 1;
                    break;
                }
            }

            echo '<ul class="recursive_checkboxes_container_'.$parent.'">';
            foreach ($data[$parent] as $id => $node)
            {
                echo '<li id="recursive_checkboxes_item_'.$node->id.'" class="recursive_checkboxes_item_'.$node->id.'"><div><label>
            <input class="recursive_checkboxes_'.str_replace(["[", "]"], "_", $inputname).'" id="recursive_checkboxes_item_input_'.$node->id.'" '.(isset($checked[$node->id]) ? 'checked' : '').' type="checkbox" name="'.$inputname.'['.$node->id.']" value="'.$node->id.'" /> '.$node->{$field}.'</label></div></li>';
                $this->recursiveCheckboxes($data, $node->id,  $field, $inputname, $checked);
            }
            echo '</ul>';
        }
        elseif(count($data) > 0){

        }
    }
}