<?php

// kodedaftar_mahasiswa
// nim_mahasiswa
// nama_mahasiswa
// kelas_mahasiswa
// semester_mahasiswa
// tgl_daftar_mahasiswa
// jam_daftar_mahasiswa
// total_biaya
// foto
// alamat
// tlp
// tempat
// tgl
// qrcode
// code

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
<?php if ($pendaftaran->tgl_daftar_mahasiswa->Visible) { // tgl_daftar_mahasiswa ?>
		<tr id="r_tgl_daftar_mahasiswa">
			<td><?php echo $pendaftaran->tgl_daftar_mahasiswa->FldCaption() ?></td>
			<td<?php echo $pendaftaran->tgl_daftar_mahasiswa->CellAttributes() ?>>
<span id="el_pendaftaran_tgl_daftar_mahasiswa">
<span<?php echo $pendaftaran->tgl_daftar_mahasiswa->ViewAttributes() ?>>
<?php echo $pendaftaran->tgl_daftar_mahasiswa->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pendaftaran->jam_daftar_mahasiswa->Visible) { // jam_daftar_mahasiswa ?>
		<tr id="r_jam_daftar_mahasiswa">
			<td><?php echo $pendaftaran->jam_daftar_mahasiswa->FldCaption() ?></td>
			<td<?php echo $pendaftaran->jam_daftar_mahasiswa->CellAttributes() ?>>
<span id="el_pendaftaran_jam_daftar_mahasiswa">
<span<?php echo $pendaftaran->jam_daftar_mahasiswa->ViewAttributes() ?>>
<?php echo $pendaftaran->jam_daftar_mahasiswa->ListViewValue() ?></span>
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
<span<?php echo $pendaftaran->foto->ViewAttributes() ?>>
<?php echo $pendaftaran->foto->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pendaftaran->alamat->Visible) { // alamat ?>
		<tr id="r_alamat">
			<td><?php echo $pendaftaran->alamat->FldCaption() ?></td>
			<td<?php echo $pendaftaran->alamat->CellAttributes() ?>>
<span id="el_pendaftaran_alamat">
<span<?php echo $pendaftaran->alamat->ViewAttributes() ?>>
<?php echo $pendaftaran->alamat->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pendaftaran->tlp->Visible) { // tlp ?>
		<tr id="r_tlp">
			<td><?php echo $pendaftaran->tlp->FldCaption() ?></td>
			<td<?php echo $pendaftaran->tlp->CellAttributes() ?>>
<span id="el_pendaftaran_tlp">
<span<?php echo $pendaftaran->tlp->ViewAttributes() ?>>
<?php echo $pendaftaran->tlp->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pendaftaran->tempat->Visible) { // tempat ?>
		<tr id="r_tempat">
			<td><?php echo $pendaftaran->tempat->FldCaption() ?></td>
			<td<?php echo $pendaftaran->tempat->CellAttributes() ?>>
<span id="el_pendaftaran_tempat">
<span<?php echo $pendaftaran->tempat->ViewAttributes() ?>>
<?php echo $pendaftaran->tempat->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pendaftaran->tgl->Visible) { // tgl ?>
		<tr id="r_tgl">
			<td><?php echo $pendaftaran->tgl->FldCaption() ?></td>
			<td<?php echo $pendaftaran->tgl->CellAttributes() ?>>
<span id="el_pendaftaran_tgl">
<span<?php echo $pendaftaran->tgl->ViewAttributes() ?>>
<?php echo $pendaftaran->tgl->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pendaftaran->qrcode->Visible) { // qrcode ?>
		<tr id="r_qrcode">
			<td><?php echo $pendaftaran->qrcode->FldCaption() ?></td>
			<td<?php echo $pendaftaran->qrcode->CellAttributes() ?>>
<span id="el_pendaftaran_qrcode">
<span<?php echo $pendaftaran->qrcode->ViewAttributes() ?>>
<?php echo $pendaftaran->qrcode->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pendaftaran->code->Visible) { // code ?>
		<tr id="r_code">
			<td><?php echo $pendaftaran->code->FldCaption() ?></td>
			<td<?php echo $pendaftaran->code->CellAttributes() ?>>
<span id="el_pendaftaran_code">
<span<?php echo $pendaftaran->code->ViewAttributes() ?>>
<?php echo $pendaftaran->code->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
