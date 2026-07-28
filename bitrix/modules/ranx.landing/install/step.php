<?if(!check_bitrix_sessid()) return;?>
<style type="text/css">.adm-info-message-wrap + .adm-info-message-wrap .adm-info-message{margin-top: 0 !important;}</style>
<?=CAdminMessage::ShowNote(GetMessage('RXLANDING_MOD_INST_OK'));?>
<?=BeginNote('align="left"');?>
<?=GetMessage('RXLANDING_MOD_INST_NOTE')?>
<?=EndNote();?>
<form action="/bitrix/admin/wizard_list.php?lang=ru">
	<input type="submit" name="" value="<?=GetMessage('RXLANDING_OPEN_WIZARDS_LIST')?>">
    <input type="button" value="<?=GetMessage('RXLANDING_INSTALL_SITE')?>" style="margin-right: 30px;"
           onclick="document.location.href='/bitrix/admin/wizard_install.php?lang=ru&wizardName=ranx:landing&<?=bitrix_sessid_get()?>';">
<form>
