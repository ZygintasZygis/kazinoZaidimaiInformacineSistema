<ul id="reportInfo">
	<li class="title">Sudarytų sutarčių ataskaita</li>
	<li>Sudarymo data: <span><?php echo date("Y-m-d"); ?></span></li>
	<li>Sutarčių sudarymo laikotarpis:
		<span>
		<?php
			if(!empty($data['dataNuo'])) {
				if(!empty($data['dataIki'])) {
					echo "nuo {$data['dataNuo']} iki {$data['dataIki']}";
				} else {
					echo "nuo {$data['dataNuo']}";
				}
			} else {
				if(!empty($data['dataIki'])) {
					echo "iki {$data['dataIki']}";
				} else {
					echo "nenurodyta";
				}
			}
		?>
		</span>
	</li>
</ul>
<?php
	if(sizeof($contractData) > 0) { ?>
		<table class="table">
			<thead>	
				<tr>
					<th>Sutartis</th>
					<th>Data(nuo kada)</th>
					<th>Punkto Adresas</th>
					<th>Iš viso turi sutarčių:</th>
				</tr>
			</thead>
			<tbody>
				<?php
					//sukuriame kintamąjį, kuriame laikysime visų kontraktų skaičių
					// suformuojame lentelę
					for($i = 0; $i < sizeof($contractData); $i++) {
						
						// $_SESSION['DARB_ID'] = $contractData[$i]['DARBUOTOJAS'];
						
						if($i == 0 || $contractData[$i]['Asmens_kodas'] != $contractData[$i-1]['Asmens_kodas']) {
							echo
								"<tr class='table-primary'>"
									. "<td colspan='4'>{$contractData[$i]['vardas']} {$contractData[$i]['pavarde']}</td>"
								. "</tr>";
						}

						//gauname kiekvieno dabuotojo sutarčių skaičių
						$kontraktai = $contractsObj->darbuotojoSutarciuSkaicius(intval($contractData[$i]['DARBUOTOJAS']));


						if (empty($kontraktai) || $kontraktai == 0) {
							$contractData[$i]['kiekiskiekis'] = "Nėra";
						} else {
							$contractData[$i]['kiekiskiekis'] = $kontraktai;
						}

						
						echo
							"<tr>"
								. "<td>#{$contractData[$i]['DARBO_SUTARTIS']}</td>"
								. "<td>{$contractData[$i]['Galioja_nuo']}</td>"
								. "<td>{$contractData[$i]['Vieta']};</td>"
								. "<td>{$contractData[$i]['kiekiskiekis']}</td>"
							. "</tr>";
						if($i == (sizeof($contractData) - 1) || $contractData[$i]['Asmens_kodas'] != $contractData[$i+1]['Asmens_kodas']) {
							if(!isset($contractData[$i]['kiekiskiekis']) && empty($contractData[$i]['kiekiskiekis'])) {
								$contractData[$i]['kiekiskiekis'] = "nera";
							}
							
							echo 
								"<tr>"
									. "<td colspan='2'></td>"
								. "</tr>";
						}
					}
				?>
				
			</tbody>
		</table>
		<span>Viso kontraktų: <?php echo $contractsObj->visuDarbuotojuSutarciuSkaicius(); ?></span><br>
		<a href="index.php?module=report&action=contracts" title="Nauja ataskaita" style="margin-bottom: 15px" class="button large float-right">nauja ataskaita</a>
<?php   
	} else {
?>
		<div class="warningBox">
			Nurodytu laikotartpiu sutarčių sudaryta nebuvo.
		</div>
<?php
	}
?>