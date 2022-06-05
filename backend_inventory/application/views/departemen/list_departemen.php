<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h4>Di bawah ini Adalah Data Departemen</h4>
                <table id="tabel_departemen" class="table">

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

                $('#tabel_departemen').html(objData.konten);

            },
            // jika error
            error: function(jqXHR, textStatus, errorMsg) {

                alert('Error : ' + errorMsg);

            }

        })

    }

    loadKonten('http://localhost/php/backend_inventory/departemen/list');
</script>