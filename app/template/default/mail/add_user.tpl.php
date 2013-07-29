<?php

$appli = array();
$appli['where'][] = 'video.boxoffice > 3000000';
$appli['orderby'] = 'RAND()';
$appli['limit'] = '0, 10';
$appli['nocache'] = true;

$Video = new VideoModel;
$list = $Video->listes( $appli );

$i = 0;
$video[$i] = '';
foreach ( $list as $j => $item ) {
	if ( $j == 5 ) {
		$i++;
	}
$video[$i] .= '
<td valign="top" width="130" class="columnOneContent">
	<!-- // Begin Module: Top Image with Content \\ -->
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr mc:repeatable>
			<td valign="top">
				<a href="'.url."video/view/id:{$item->id}".'"><img src="http://'.HOST.img::get( 'video', $item->image, 110, 144 ).'" /></a>
				<div mc:edit="tiwc150_content00">
					<h4 class="h4"><a href="'.url."video/view/id:{$item->id}".'">'.$item->title.'</a></h4>
				</div>
			</td>
		</tr>
	</table>
	<!-- // End Module: Top Image with Content \\ -->
</td>';
}

return array(
'subject' => 'Confirmation de l\'inscription à Dailymatons.com',
'body' => '
									<h2 class="h2">Voici vos identifiants</h2>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Login: </strong>:login:<br />
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Mot de pass: </strong>:pass:<br />
								</div>
							</td>
						</tr>
					</table>
					<!-- // End Module: Standard Content \\ -->
				</td>
			</tr>
		</table>
		<!-- // End Template Body \\ -->
	</td>
</tr>
<tr>
	<td align="center" valign="top">
		<!-- // Begin Template Body \\ -->
		<table border="0" cellpadding="0" cellspacing="0" width="600" id="templateBody">
			<tr>
				<td><h3 class="h3">10 films qui peuvent vous intéresser</h3></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td valign="top" width="130" class="columnOneContent">
					<!-- // Begin Module: Top Image with Content \\ -->
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr mc:repeatable>
							'.$video[0].'
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td valign="top" width="130" class="columnOneContent">
					<!-- // Begin Module: Top Image with Content \\ -->
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr mc:repeatable>
							'.$video[1].'
						</tr>
					</table>
				</td>
			</tr>
		</table
	</td>
</tr>'
);