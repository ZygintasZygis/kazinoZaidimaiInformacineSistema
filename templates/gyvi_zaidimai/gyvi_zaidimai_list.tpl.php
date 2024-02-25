<?php
	// suformuojame puslapių kelio (breadcrumb) elementų masyvą
	$breadcrumbItems = array(array('link' => 'index.php', 'title' => 'Pradžia'), array('title' => 'Gyvi žaidimai'));

	// puslapių kelio šabloną
	include 'templates/common/breadcrumb.tpl.php';
?>

<div class="d-flex flex-row-reverse gap-3">
    <a href='index.php?module=<?php echo $module; ?>&action=create'>Pridėti nauja žaidimą</a>
</div>

<table class="table">
    <tr>
        <th>Nr.</th>
        <th>Pradžios data</th>
        <th>Darbuotojas</th>
        <th>Žaidėjų skaičius</th>
        <th></th>
    </tr>
    <?php
		// suformuojame lentelę
		foreach($data as $key => $val) {
			echo
				"<tr>"
					. "<td>{$val['GYVI_ZAIDIMAIid']}</td>"
					. "<td>{$val['pradzios_data']}</td>"
					. "<td>{$val['vardas']} {$val['pavarde']}</td>"
					. "<td>{$val['zaideju_skaicius']}</td>"
					. "<td class='d-flex flex-row-reverse gap-2'>"
                        . "<a href='index.php?module={$module}&action=edit&id={$val['GYVI_ZAIDIMAIid']}'>redaguoti</a>"
                        . "<a href='#' onclick='showConfirmDialog(\"{$module}\", \"{$val['GYVI_ZAIDIMAIid']}\"); return false;'>šalinti</a>"
					. "</td>"
				. "</tr>";
		}
    ?>
</table>

<?php
	// įtraukiame puslapių šabloną
	include 'templates/common/paging.tpl.php';
?>