<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">

				<a href="#" onclick="loadMenu('<?= base_url('barang/form_create') ?>')" class="btn btn-primary">Tambah Data Barang </a>

				<hr>

				<h4>Di bawah ini Adalah Data Barang</h4>
				<table id="tabel_barang" class="table">

				</table>

			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	function loadKonten(url) {

		$.ajax(url, {

			type: 'GET',

			// jika berhasil
			success: function(data, status, xhr) {

				var objData = JSON.parse(data);

				$('#tabel_barang').html(objData.konten);

				reload_event();

			},
			// jika error
			error: function(jqXHR, textStatus, errorMsg) {

				alert('Error : ' + errorMsg);

			}

		})

	}

	loadKonten('http://localhost/Web_Inventory/backend_inventory/barang/list_barang');

	function reload_event() {
		$('.linkEditBarang').on('click', function() {
			var hashClean = this.hash.replace('#', '');
			loadMenu('<?= base_url('barang/form_edit/') ?>' + hashClean);
		});
	}
</script>
