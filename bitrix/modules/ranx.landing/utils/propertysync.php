<?require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');
if (!$GLOBALS['USER']->IsAdmin()) die();

if (!\Bitrix\Main\Loader::includeModule('iblock')) {
    echo 'Не удалось подключить инфоблоки';
    die();
}
if (!\Bitrix\Main\Loader::includeModule('ranx.landing')) {
    echo 'Не удалось подключить модуль';
    die();
}

use Ranx\Landing\Utils\PropertySync as RxPropertySync;
use Ranx\Landing\Utils\ReportMaker as RxReportMaker;
use Ranx\Landing\Utils\Iblock as RxIblock;

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
if ($request->isPost() && $request->getPost('run')) {
    $selectedIblockCode = $request->getPost('iblock_code');
    if (!empty($selectedIblockCode)) {
        $syncer = new RxPropertySync($selectedIblockCode);

        if (!empty($request->getPost('duplicate'))) {
            $syncer->removeDuplicates();
        }
        if (!empty($request->getPost('irrelevant'))) {
            $syncer->removeIrrelevant();
        }
        if (!empty($request->getPost('new'))) {
            $syncer->addNew();
        }
        if (!empty($request->getPost('sync'))) {
            $syncer->syncDifferences();
        }
    }
}

if ($request->isPost() && ($request->getPost('verify') || $request->getPost('run'))) {
    $selectedIblockCode = $request->getPost('iblock_code');
    if (!empty($selectedIblockCode)) {
        $reportMaker = new RxReportMaker($selectedIblockCode);
        $report = $reportMaker->makeReport();
    }
}
?>

<style>
    .setting {
        display: flex;
        flex-direction: column;
    }
    .buttons {
        margin-top: 5px;
    }
    table {
        border-collapse: collapse;
        max-width: 100%
    }
    td, th {
        padding: 5px 10px;
        vertical-align: top;
    }
    table, td, th {
        border: 1px solid black;
    }
</style>

<form action="<?=$APPLICATION->GetCurPageParam()?>" method="post">
    <div class="setting">
        <label>
            Инфоблок:
            <?$iblocks = RxIblock::getIblocksInfo();?>
            <select name="iblock_code">
                <?foreach ($iblocks as $iblock):?>
                    <?$isSelected = $request->getPost('iblock_code') == $iblock['CODE'];?>
                    <option value="<?=$iblock['CODE']?>" <?if($isSelected):?>selected<?endif;?>>
                        <?=$iblock['NAME']?>
                    </option>
                <?endforeach?>
            </select>
        </label>
        <label>
            <input type="checkbox" name="duplicate" <?if(!empty($request->getPost('duplicate'))):?>checked<?endif;?>>
            Удалить дубликаты
        </label>
        <label>
            <input type="checkbox" name="irrelevant" <?if(!empty($request->getPost('irrelevant'))):?>checked<?endif;?>>
            Удалить неактуальные свойства
        </label>
        <label>
            <input type="checkbox" name="new" <?if(!empty($request->getPost('new'))):?>checked<?endif;?>>
            Добавить недостающие свойства
        </label>
        <label>
            <input type="checkbox" name="sync" <?if(!empty($request->getPost('sync'))):?>checked<?endif;?>>
            Убрать расхождения в полях свойств
        </label>
    </div>
    <div class="buttons">
        <input type="submit" name="verify" value="Проверить">
        <input type="submit" name="run" value="Выполнить">
    </div>
</form>

<?if(!empty($report)):?>
<table>
    <tr>
        <th>Тип</th>
        <th>Код</th>
        <th>Различие в полях</th>
        <th>Значения в инфоблоке</th>
        <th>Значения в файле</th>
    </tr>
    <?foreach ($report as $item):?>
    <tr>
        <td><?=$item['TYPE']?></td>
        <td><?=$item['CODE']?></td>
        <td><?=implode('<br>', $item['DIFF_FIELDS'] ?? [])?></td>
        <td><?=$item['IBLOCK_VALUES']?></td>
        <td><?=$item['FILE_VALUES']?></td>
    </tr>
    <?endforeach?>
</table>
<?endif?>

<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_after.php');?>
