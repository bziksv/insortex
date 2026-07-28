<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

if(\Bitrix\Main\Application::getInstance()->getContext()->getRequest()->isAjaxRequest()) {
    $stylePath = $this->__template->__folder . '/style.css';
    if (file_exists($_SERVER['DOCUMENT_ROOT'].$stylePath)) {
        echo '<script>BX.loadCSS([\''.$stylePath.'\']);</script>';
    }
}
