<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if(\Bitrix\Main\Application::getInstance()->getContext()->getRequest()->isAjaxRequest()) {
    $templatePath = $this->__template->__folder;

    $stylePath = $templatePath.'/style.css';
    if (file_exists($_SERVER['DOCUMENT_ROOT'].$stylePath)) {
        echo '<script>BX.loadCSS([\''.$stylePath.'\']);</script>';
    }

    $scriptPath = $templatePath.'/script.js';
    if (file_exists($_SERVER['DOCUMENT_ROOT'].$scriptPath)) {
        echo '<script>'.file_get_contents($_SERVER['DOCUMENT_ROOT'].$scriptPath).'</script>';
    }
}
echo $templateData['INIT_JS'];
