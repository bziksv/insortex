<?

namespace Ranx\Landing\Helpers;

use Ranx\Landing\Config;
use Bitrix\Main\Application;


class FormHelper
{
    public static function isB24Form($code)
    {
        return strpos($code, 'ranx_landing_form_b24') === 0;
    }

    public static function showHtmlEditorField($name, $id, $value = '', $extraOptions = [])
    {
        $request = Application::getInstance()->getContext()->getRequest();

        // load editor JS manually for AJAX response
        if($request->isAjaxRequest())
        {
            \CJSCore::RegisterExt('html_editor_field', array(
                'js' => array(
                    '/bitrix/js/fileman/html_editor/range.js',
                    '/bitrix/js/fileman/html_editor/html-actions.js',
                    '/bitrix/js/fileman/html_editor/html-views.js',
                    '/bitrix/js/fileman/html_editor/html-parser.js',
                    '/bitrix/js/fileman/html_editor/html-base-controls.js',
                    '/bitrix/js/fileman/html_editor/html-controls.js',
                    '/bitrix/js/fileman/html_editor/html-components.js',
                    '/bitrix/js/fileman/html_editor/html-snippets.js',
                    '/bitrix/js/fileman/html_editor/html-editor.js',
                    '/bitrix/js/main/dd.js',
                ),
                'css' => array(
                    '/bitrix/js/fileman/html_editor/html-editor.css',
                    '/bitrix/js/fileman/comp_params_manager/component_params_manager.css',
                ),
                'rel' => array('date', 'timer')
            ));
            echo \CUtil::InitJSCore(array('fx', 'html_editor_field'), true);
        }

        $options = [
            'name' => $name,
            'id' => $id,
            'inputName' => $name,
            'content' => $value,
            'width' => '100%',
            'minBodyWidth' => 490,
            'normalBodyWidth' => 600,
            'height' => '200',
            'bAllowPhp' => false,
            'limitPhpAccess' => false,
            'autoResize' => true,
            'autoResizeOffset' => 40,
            'useFileDialogs' => true,
            'showTaskbars' => false,
            'showNodeNavi' => false,
            'askBeforeUnloadPage' => false,
            'uploadImagesFromClipboard' => false,
            'bbCode' => false,
            'siteId' => SITE_ID,
            'controlsMap' => [
                ['id' => 'ChangeView', 'compact' => true, 'sort' => 70],
                ['id' => 'Bold', 'compact' => true, 'sort' => 80],
                ['id' => 'Italic', 'compact' => true, 'sort' => 90],
                ['id' => 'Underline', 'compact' => true, 'sort' => 100],
                ['id' => 'Strikeout', 'compact' => true, 'sort' => 110],
                ['id' => 'RemoveFormat', 'compact' => true, 'sort' => 120],
                ['id' => 'Color', 'compact' => true, 'sort' => 130],
                ['id' => 'StyleSelector', 'compact' => false, 'sort' => 135],
                ['separator' => true, 'compact' => true, 'sort' => 145],
                ['id' => 'OrderedList', 'compact' => true, 'sort' => 150],
                ['id' => 'UnorderedList', 'compact' => true, 'sort' => 160],
                ['id' => 'AlignList', 'compact' => true, 'sort' => 190],
                ['separator' => true, 'compact' => true, 'sort' => 200],
                ['id' => 'InsertLink', 'compact' => true, 'sort' => 210],
                ['id' => 'InsertImage', 'compact' => true, 'sort' => 220],
                ['id' => 'InsertVideo', 'compact' => true, 'sort' => 230],
                ['id' => 'InsertTable', 'compact' => false, 'sort' => 250],
                ['separator' => true, 'compact' => true, 'sort' => 290],
                ['id' => 'Fullscreen', 'compact' => true, 'sort' => 310],
                ['id' => 'More', 'compact' => true, 'sort' => 400]
            ],
        ];

        $options = array_merge($options, $extraOptions);

        $editor = new \CHTMLEditor;
        $editor->Show($options);
    }


    public static function showTextField(
        $name, $id, $value = '', $showEditor = true, $extraEditorOptions = [])
    {
        if($showEditor && Config::useVisualEditor())
        {
            self::showHtmlEditorField($name, $id, $value, $extraEditorOptions);
        }
        else
        {
            $html  = '<textarea name="'.$name.'" class="form-control">';
            $html .= $value;
            $html .= '</textarea>';

            echo $html;
        }
    }
}
?>
