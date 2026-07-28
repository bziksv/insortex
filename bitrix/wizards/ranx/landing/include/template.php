<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();?>

<!DOCTYPE html>
<html>
	<head>
		<title><?=$wizardName?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=<?=LANG_CHARSET?>">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<link rel="stylesheet" href="/bitrix/images/install/installer_style.css">
        <link rel="stylesheet" href="/bitrix/wizards/<?=$rxPartnerName?>/<?=$rxModuleNameShort?>/css/simplebar.min.css" />
        <link rel="stylesheet" href="/bitrix/wizards/<?=$rxPartnerName?>/<?=$rxModuleNameShort?>/css/main.css" />

		<noscript>
			<style type="text/css">
                div {display: none;}
				#noscript {padding: 3em; font-size: 130%; background:white; display:block;}
			</style>
			<p id="noscript"><?=GetMessage("RX_JAVASCRIPT_DISABLED");?></p>
		</noscript>
		<script>
			function SubmitForm(button)
			{
                let buttons = {
                    "next" : "<?=$nextButtonID?>",
					"prev" : "<?=$prevButtonID?>",
					"cancel" : "<?=$cancelButtonID?>",
					"finish" : "<?=$finishButtonID?>"
				};

				let form = document.forms["<?=$formName?>"];
				if (form)
                {
                    hiddenField = document.createElement("INPUT");
                    hiddenField.type = "hidden";
                    hiddenField.name = buttons[button];
                    hiddenField.value = button;
                    form.appendChild(hiddenField);
                    form.submit();
                }

				return false;

			}
            <?=$jsCode?>
		</script>
    </head>

    <body id="bitrix_install_template">
        <script src="/bitrix/wizards/<?=$rxPartnerName?>/<?=$rxModuleNameShort?>/js/jquery.min.js"></script>
        <script src="/bitrix/wizards/<?=$rxPartnerName?>/<?=$rxModuleNameShort?>/js/simplebar.min.js"></script>

        <table class="installer-main-table" id="container">
            <tr>
                <td class="installer-main-table-cell">
                    <div class="installer-block-wrap">
                        <div class="installer-block">
                            {#FORM_START#}
                                <table class="installer-block-table">
                                    <tr>
                                        <td class="installer-block-cell-left">
                                            <table class="inst-left-side-img-table">
                                                <tr>
                                                    <td class="inst-left-side-img-cell"><?=$boxImage?></td>
                                                </tr>
                                            </table>
                                            <?=$strNavigation?>
                                        </td>
                                        <td class="installer-block-cell-right">
                                            <div class="inst-cont-title-wrap">
                                                <div class="inst-cont-title"><?=$stepTitle?></div>
                                            </div>
                                            <div id="step-content">
                                                <?=$strError?>
                                                {#CONTENT#}
                                           </div>
                                            <div class="instal-btn-wrap">
                                                {#BUTTONS#}
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="installer-block-cell-left installer-block-cell-bottom">
                                            <a href="https://ranx.ru" target="_blank"><?=$logoImage?></a>
                                        </td>
                                        <td class="installer-block-cell-right installer-block-cell-bottom"></td>
                                    </tr>
                                </table>
                            {#FORM_END#}
                        </div>
                        <div class="installer-footer"></div>
                    </div>
                </td>
            </tr>
        </table>

        <div class="instal-bg"><div class="instal-bg-inner"></div></div>
    </body>
</html>
