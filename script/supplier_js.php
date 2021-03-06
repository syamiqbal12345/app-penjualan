<script>
   var table, method;

   $(document).ready(function() {

      // konfigurasi DataTable
      table = $('.dataTable').DataTable({
         "processing": true,
         "serverside": true,
         "ordering": false,
         "ajax": "../lib/supplier/supplier_data.php",
         "language": {
            sEmptyTable: "Tidak ada data yang tersedia pada tabel ini",
            sProcessing: "Sedang memproses...",
            sLengthMenu: "Tampilkan _MENU_ data",
            sZeroRecords: "Tidak ditemukan data yang sesuai",
            sInfo: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            sInfoEmpty: "Tampil 0 sampai 0 dari 0 entri",
            sInfoFiltered: "(disaring dari _MAX_ entri keseluruhan)",
            sInfoPostFix: "",
            sSearch: "Pencarian:",
            sUrl: "",
            oPaginate: {
               sFirst: "Awal",
               sPrevious: "Sebelumnya",
               sNext: "Selanjutnya",
               sLast: "Akhir"
            }
         }
      });

      // menyimpan data untuk proses tambah dan edit dengan ajax jquery
      $('#modal-form form').submit(function(e) {
         e.preventDefault();
         var id = $('#id').val();

         if (method == "tambah") {
            url = "../lib/supplier/supplier_tambah.php";
         } else {
            url = "../lib/supplier/supplier_ubah.php/?id=" + id;
         }

         $.ajax({
            type: "POST",
            url: url,
            data: {
               'id': $('#id').val(),
               'nama': $('#nama').val(),
               'kontak': $('#kontak').val(),
               'alamat': $('#alamat').val()
            },
            success: function(response) {
               $('#modal-form').modal('hide');
               table.ajax.reload();

               if (method == "tambah") {
                  $('.alert-primary').show();
               } else {
                  $('.alert-warning').show();
               }
            },
            error: function() {
               alert("request ajax jquery supplier error");
            }
         });

         return false;
      });
   });

   // menampilkan modal form tambah data
   function tambahForm() {
      method = "tambah";
      $('#modal-form').modal('show');
      $('.modal-title').text('Tambah Data Supplier');
      $('#modal-form form')[0].reset();
   }

   // menampilkan modal form tambah data
   function ubahForm(id) {
      method = "ubah";
      $.ajax({
         type: "GET",
         url: "../lib/supplier/supplier_edit.php/?id=" + id,
         dataType: "JSON",
         success: function(response) {
            $('#modal-form').modal('show');
            $('.modal-title').text('Ubah Data Supplier');

            $('#id').val(response.id_supp);
            $('#nama').val(response.nama_supp);
            $('#kontak').val(response.kntk_supp);
            $('#alamat').val(response.almt_supp);
         },
         error: function() {
            alert("function ubahForm error");
         }
      });
   }

   // menghapus data berdasarkan ID
   function hapusData(id) {
      Swal.fire({
         text: "Apakah yakin ingin menghapus data ini ?",
         icon: 'warning',
         showCancelButton: true,
         confirmButtonColor: '#dc3545',
         cancelButtonColor: '#343a40',
         confirmButtonText: 'Ya, hapus',
         cancelButtonText: 'Tidak, batal',
      }).then((result) => {
         if (result.value) {
            $.ajax({
               type: "GET",
               url: "../lib/supplier/supplier_hapus.php/?id=" + id,
               success: function(response) {
                  table.ajax.reload();
                  $('.alert-danger').show();
               },
               error: function() {
                  alert("function hapusData error");
               }
            });
         }
      });
   }
</script>