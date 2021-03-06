<script type="text/javascript" charset="utf-8">
    var table;
	$(document).ready(function(){
		setTimeout(function(){ $(".alert-messages").remove(); }, 3000);
		<?php if (isset($dataTables)): ?>
      	table = $('.datatable').DataTable({
            dom: 'Bfrtip',
            lengthMenu: [
                [ 10, 25, 50, 100, -1 ],
                [ '10', '25', '50', '100', 'All' ]
            ],
            buttons: [
                'pageLength',
                {
                    extend: 'print',
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    },
                },
                {
                    extend: 'csv',
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    },
                },
                'colvis'
            ],
            columnDefs: [ {
                targets: -1,
                visible: false
            } ],
            "processing": true,
            "rowReorder": '<?= $name ?>' == 'category' ? true : false,
            "serverSide": true,
            'language': {
                'loadingRecords': '&nbsp;',
                'processing': 'Processing',
                'paginate': {
                    'first': '|',
                    'next': '<i class="fa fa-arrow-circle-right"></i>',
                    'previous': '<i class="fa fa-arrow-circle-left"></i>',
                    'last': '|'
                }
            },
            "order": [],
            "ajax": {
                url: "<?= base_url($url) ?>",
                type: "POST",
                data: function(data) {
                    data.star_line_token = $('#'+"<?= strtolower(str_replace(" ", '_', APP_NAME)).'_token' ?>").val();
                    data.role = $('#role').val();
                },
                complete: function(response) {
                    var data = JSON.parse(response.responseText).star_line_token;
                    $('#'+"<?= strtolower(str_replace(" ", '_', APP_NAME)).'_token' ?>").val(data);
                    $("input[data-bootstrap-switch]").each(function(){
                        $(this).bootstrapSwitch();
                    });
                },
            },
            "columnDefs": [{
                "targets": 'target',
                "orderable": false,
            },],
        });

        if (table != undefined) {
            table.on('row-reorder', function(e, diff, edit) {

                var result = [];
                
                for (var i = 0, ien = diff.length; i < ien; i++)
                    result.push({ id: $(table.row(diff[i].node).data()[1]).data('id'), position: diff[i].newData });
                
                if (result.length > 0) {
                    $.ajax({
                        url: '<?= base_url($url) ?>/sort',
                        type: 'POST',
                        data: { sort: result },
                        dataType: "JSON",
                        success: function(result) {
                            Swal.fire(
                                result.success === false ? "Sorry!" : "Success!",
                                result.message,
                                result.success === false ? "error" : "success",
                            );
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            Swal.fire("Sorry!", "Something is not going good. Please try later.", "error");
                        }
                    });
                }
            });
        }

        <?php endif ?>
	});
    <?php if (isset($dataTables)): ?>
        function change_user_type(id, user_type) {
            $.ajax({
                type:'POST',
                url: "<?= base_url($url).'/change-user-type/' ?>",
                cache:false,
                data: {id: id, user_type: user_type},
                dataType: 'json',
                success:function(data){
                    Swal.fire(
                        data.error ? "Sorry!" : "Success!",
                        data.message,
                        data.error ? "error" : "success",
                    );
                    table.ajax.reload();
                },
                error: function(data){
                    Swal.fire("Sorry!", "Something is not going good. Please try later.", "error");
                }
            });
        }
    <?php endif ?>
    
    function remove(id) {
      Swal.fire({
        title: 'Are you sure?',
        text: "This will be deleted from your data!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
      }).then((result) => {
        if (result.value) $('#'+id).submit();
      })
    }

    <?php if (isset($showImages)): ?>

    function uploadImage() {
      $("#imageUploadForm").submit();
    }

    function showImages(id) {
        $.ajax({
            type:'get',
            url: "<?= base_url($url).'/showImages/' ?>"+id,
            cache:false,
            contentType: false,
            dataType: 'json',
            processData: false,
            success:function(data){
                var images = '';
                $.each( data.images, function( key, img ) {
                  images += '<div class="col-md-3" id="'+img.image+'"> <img src="'+img.url+img.image+'" alt="" height = "200", width = "200" onclick="removeImage(\''+id+'\', \''+img.image+'\')"> </div>';
                });
                $("#all-images").html(images);
            },
            error: function(data){
                Swal.fire("Sorry!", "Something is not going good. Please try later.", "error");
            }
        });
    }
    
    showImages("<?= $id ?>");

    function removeImage(id, img) {

        Swal.fire({
            title: 'Are you sure?',
            text: "This will be deleted from your data!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.value) 
                $.ajax({
                type:'POST',
                url: "<?= base_url($url).'/removeImage/' ?>",
                data:{id: id, img: img},
                cache:false,
                dataType: 'json',
                success:function(data){
                    showImages(id);
                    Swal.fire("Done!", data.success, "success");
                },
                error: function(data){
                    Swal.fire("Sorry!", "Something is not going good. Please try later.", "error");
                }
            });
        });
    }

    $('#imageUploadForm').on('submit',(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type:'POST',
            url: $(this).attr('action'),
            data:formData,
            cache:false,
            contentType: false,
            dataType: 'json',
            processData: false,
            success:function(data){
                if (data.upload === false)  alert(data.error) 
                else {
                    showImages("<?= $id ?>");
                    Swal.fire("Done!", data.success, "success");
                }
            },
            error: function(data){
                Swal.fire("Sorry!", "Something is not going good. Please try later.", "error");
            }
        });
    }));
    <?php endif ?>


    function viewHistory(id) {
      
    }
</script>