<?php

// kodedaftar_mahasiswa
// nim_mahasiswa
// nama_mahasiswa
// kelas_mahasiswa
// semester_mahasiswa
// total_biaya
// foto

?>
<?php if ($pendaftaran->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $pendaftaran->TableCaption() ?></h4> -->
<table id="tbl_pendaftaranmaster" class="table table-bordered table-striped ewViewTable">
<?php echo $pendaftaran->TableCustomInnerHtml ?>
	<tbody>
<?php if ($pendaftaran->kodedaftar_mahasiswa->Visible) { // kodedaftar_mahasiswa ?>
		<tr id="r_kodedaftar_mahasiswa">
			<td><?php echo $pendaftaran->kodedaftar_mahasiswa->FldCaption() ?></td>
			<td<?php echo $pendaftaran->kodedaftar_mahasiswa->CellAttributes() ?>>
<span id="el_pendaftaran_kodedaftar_mahasiswa">
<span<?php echo $pendaftaran->kodedaftar_mahasiswa->ViewAttributes() ?>>
<?php echo $pendaftaran->kodedaftar_mahasiswa->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pendaftaran->nim_mahasiswa->Visible) { // nim_mahasiswa ?>
		<tr id="r_nim_mahasiswa">
			<td><?php echo $pendaftaran->nim_mahasiswa->FldCaption() ?></td>
			<td<?php echo $pendaftaran->nim_mahasiswa->CellAttributes() ?>>
<span id="el_pendaftaran_nim_mahasiswa">
<span<?php echo $pendaftaran->nim_mahasiswa->ViewAttributes() ?>>
<?php echo $pendaftaran->nim_mahasiswa->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pendaftaran->nama_mahasiswa->Visible) { // nama_mahasiswa ?>
		<tr id="r_nama_mahasiswa">
			<td><?php echo $pendaftaran->nama_mahasiswa->FldCaption() ?></td>
			<td<?php echo $pendaftaran->nama_mahasiswa->CellAttributes() ?>>
<span id="el_pendaftaran_nama_mahasiswa">
<span<?php echo $pendaftaran->nama_mahasiswa->ViewAttributes() ?>>
<?php echo $pendaftaran->nama_mahasiswa->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pendaftaran->kelas_mahasiswa->Visible) { // kelas_mahasiswa ?>
		<tr id="r_kelas_mahasiswa">
			<td><?php echo $pendaftaran->kelas_mahasiswa->FldCaption() ?></td>
			<td<?php echo $pendaftaran->kelas_mahasiswa->CellAttributes() ?>>
<span id="el_pendaftaran_kelas_mahasiswa">
<span<?php echo $pendaftaran->kelas_mahasiswa->ViewAttributes() ?>>
<?php echo $pendaftaran->kelas_mahasiswa->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pendaftaran->semester_mahasiswa->Visible) { // semester_mahasiswa ?>
		<tr id="r_semester_mahasiswa">
			<td><?php echo $pendaftaran->semester_mahasiswa->FldCaption() ?></td>
			<td<?php echo $pendaftaran->semester_mahasiswa->CellAttributes() ?>>
<span id="el_pendaftaran_semester_mahasiswa">
<span<?php echo $pendaftaran->semester_mahasiswa->ViewAttributes() ?>>
<?php echo $pendaftaran->semester_mahasiswa->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pendaftaran->total_biaya->Visible) { // total_biaya ?>
		<tr id="r_total_biaya">
			<td><?php echo $pendaftaran->total_biaya->FldCaption() ?></td>
			<td<?php echo $pendaftaran->total_biaya->CellAttributes() ?>>
<span id="el_pendaftaran_total_biaya">
<span<?php echo $pendaftaran->total_biaya->ViewAttributes() ?>>
<?php echo $pendaftaran->total_biaya->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pendaftaran->foto->Visible) { // foto ?>
		<tr id="r_foto">
			<td><?php echo $pendaftaran->foto->FldCaption() ?></td>
			<td<?php echo $pendaftaran->foto->CellAttributes() ?>>
<span id="el_pendaftaran_foto">
<span>
<?php echo ew_GetFileViewTag($pendaftaran->foto, $pendaftaran->foto->ListViewValue()) ?>
</span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
