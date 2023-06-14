<?php

namespace Huashan\FileTypePlugin;

use ExternalModules\AbstractExternalModule;
use ExternalModules\ExternalModules;
use Form;
Use Stanford\Utility\ActionTagHelper;

class FileTypePlugin extends AbstractExternalModule {

    public $tag = "@FILETYPE";

    function redcap_every_page_top($project_id) {
        if (in_array(PAGE,array('ProjectSetup/index.php','Design/online_designer.php')) && $project_id) {
            echo "<script type='text/javascript' src='".$this->getUrl('js/helper.js')."'></script>";;
             
        }    
        if (!in_array(PAGE, array('DataEntry/index.php', 'surveys/index.php', 'Surveys/theme_view.php'))) {
            return;
        }

        if (empty($_GET['id'])) {
            return;
        }
        // Checking additional conditions for survey pages.
        if (PAGE == 'surveys/index.php' && !(isset($_GET['s']) && defined('NOAUTH'))) {
            return;
        }

        global $Proj;
       // var_dump($Proj);
        $settings = array();
        // Loop through action tags
        $instrument = $_GET['page'];    // This is a bit of a hack, but in surveys this is set before the every_page_top hook is called
        $haveFile=0;
        foreach (array_keys($Proj->forms[$instrument]['fields']) as $field_name) {
            $field_info = $Proj->metadata[$field_name];
             //Form::hasActionTag($this->tag,$field_info['misc']);
            if ($v = Form::getValueInActionTag($field_info['misc'], $this->tag)) {
                $fileType[$field_name]=$v;
                $haveFile++;
            }else{
               continue;
            }
        }
        if($haveFile){echo "<script type='text/javascript'>
            var file_type_plugin={fileTypes:".json_encode($fileType)."};
            $(document).ready(function(){
                $('body').on('dialogopen', function(event, ui) {
                    file_type_plugin.fieldName_linknew = $(event.target).find('#field_name');
                    $(event.target).find('input[name=\"myfile\"]').attr('accept',file_type_plugin.fileTypes[file_type_plugin.fieldName_linknew.val().slice(0,-8)]);
                });
            });
        </script>"; }
    }
}
