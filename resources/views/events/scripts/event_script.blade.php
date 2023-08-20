<script>

    var user_ids = [];

    function onFetchFormModal(event, route, target_model, bind_model) {

        event.preventDefault();

        showAjaxLoader();

        $.get(route, function(data) {

            $(bind_model).html(data);

            $(target_model).modal('show');
            
            removeAjaxLoader();
        });
    }

    function formSubmitWithModal(event, obj, modal_id, form_id, table_id, option) {
        event.preventDefault();

        event.preventDefault();

        Swal.fire({
            title: 'Are you sure?',
            text: "You want to " + $(obj).attr("content"),
            icon: 'warning',
            showCancelButton: true,
            width: '400px',
            height: '300px',
            confirmButtonText: 'Yes, submit!',
            customClass: {
                confirmButton: 'btn btn-primary me-3',
                cancelButton: 'btn btn-label-secondary'
            },
            buttonsStyling: false
        }).then(function(result) {
            if (result.value) {
                showAjaxLoader();
                var form_obj = $(form_id);

                var form = document.querySelector(form_id); // Find the <form> element

                var formData = new FormData(form); // Wrap form contents

                var route = form_obj.attr("action");

                $.ajax({
                    url: route,

                    type: form_obj.attr("method"),

                    data: formData,

                    dataType: "json",

                    contentType: false,

                    cache: false,

                    processData: false,

                    success: function(result) {
                        removeAjaxLoader();
                        if (result.status) {
                            // messageToaster("success", result.message, "Success");

                            Swal.fire("Success!", result.message, "success");

                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: result.message,
                                showConfirmButton: false,
                                width: '300px',
                                height: '300px',
                                timer: 3500,
                                customClass: {
                                    confirmButton: 'btn btn-primary'
                                },
                                buttonsStyling: false
                            });

                            if ($(modal_id).length > 0) {
                                hideModal(modal_id);
                                if (option == "load_task") {
                                    loadTasks();
                                }
                            }

                            if ($(table_id).length > 0) {
                                $(table_id).DataTable().ajax.reload();
                            } else {
                                location.reload(true);
                            }
                            // $(form_id)[0].reset();

                        } else {
                            removeAjaxLoader();
                            Swal.fire("error!", result.message, "error");

                        }

                    },
                    error: function(result) {
                        removeAjaxLoader();
                        ajax_show_error(result);

                        $.each(result.responseJSON.errors, function(index, value) {
                            if (index == "module_list") {
                                $("#module_list_array").html("(" + value + ")");
                            } else {
                                $("#module_list_array").html(" ");
                            }
                        });

                        $.each(result.responseJSON.errors, function(index, value) {
                            if (index == "sub_module") {
                                $("#sub_module_array").html("(" + value + ")");
                            } else {
                                $("#sub_module_array").html(" ");
                            }
                        });

                        $.each(result.responseJSON.errors, function(index, value) {
                            if (index == "files") {
                                $("#files_array").html("(" + value + ")");
                                messageToaster("error", value, "Failed");
                            } else {
                                $("#files_array").html(" ");
                            }
                        });

                    },
                });
            }
        });


    }

    function showAjaxLoader(){

        $("body").addClass("loading");
    }

    function removeAjaxLoader(){
        $("body").removeClass("loading");
    }

    function ajax_base_field_unsuccess(element, message) {
        element
            .parentsUntil()
            .children("small.req")
            .html(" (" + message + ")");
        //element.after('<small class="error_message">'+message+'</small><br>').next().css('color','red');
        element.css("border-color", "red");
        $(".ajax_errors").html(" (" + message + ")");
    }

    function ajax_show_error(error_list, form_element, model_element) {
        var separate_data = "";
        $.each(error_list.responseJSON.errors, function (index, value) {
            separate_data = index.split(".");

            // ITS MEAN DATA AN ARRAY FORM
            if (typeof separate_data[1] !== "undefined") {
                // FOR ALL INPUT FROM ERROR LIST
                element = form_element.find(
                    "input[name^=" +
                        separate_data[0] +
                        "]:eq(" +
                        separate_data[1] +
                        ")"
                );

                value = value.toString();

                ajax_base_field_unsuccess(
                    element,
                    value.replace("." + separate_data[1], " ")
                );


                // FOR ALL TEXTAREA FROM ERROR LIST
                element = form_element.find(
                    "textarea[name^=" +
                        separate_data[0] +
                        "]:eq(" +
                        separate_data[1] +
                        ")"
                );

                value = value.toString();

                ajax_base_field_unsuccess(
                    element,
                    value.replace("." + separate_data[1], " ")
                );

                // FOR ALL DROPDOWN FROM ERROR LIST
                element = form_element.find(
                    "select[name^=" +
                        separate_data[0] +
                        "]:eq(" +
                        separate_data[1] +
                        ")"
                );

                value = value.toString();

                ajax_base_field_unsuccess(
                    element,
                    value.replace("." + separate_data[1], " ")
                );

                model_element.scrollTop(0);
            } else {
                // messageToaster('error', value, 'Error',10000);
                ajax_base_field_unsuccess($('input[name=' + index + ']'), value);
                ajax_base_field_unsuccess($('textarea[name=' + index + ']'), value);
                ajax_base_field_unsuccess($('select[name=' + index + ']'), value);


            }
        });

        // removeLoader();

        // swal("Your request has been cancelled!");
    }


    function getUserByEmail(event,obj,target_input){
        
        event.preventDefault();

        showAjaxLoader();
        
        var email = $(obj).val();

        var route = "{{ route('get_user_by_email') }}";

        $.get(route,{email:email},function(result){
            removeAjaxLoader();

            if(result.status)
            {
                $(target_input).val(result.data.name);
                
                user_ids.push(result.data.id);
                
                $('#user_ids').val(user_ids);

            }
            else{
                $(target_input).val(' ');
                showAlert('Warning!',result.message,'warning');                
            }
        });
    }

    function showAlert(title = 'Title',message = 'Message',icon = 'warning')
    {
        Swal.fire({
            title: title,
            text: message,
            icon: icon,
            confirmButtonText: 'Ok'
        });
    }

    // CHECK FIELD IS EMPTY OR NOT
    $(document.body).on("keyup", ".cls_required", function () {
        field_refersh($(this));
        field_isempty($(this), "This field is required.");
    });

    function field_refersh(element) {
        if (element.parentsUntil().children("small.req").attr("value") == "*") {
            // element.parentsUntil().children('small.req').html('*');
            element.parentsUntil().children("small.req").html("");
        } else {
            element.parentsUntil().children("small.req").html(" ");
        }

        element.css("border-color", "#ced4da");
    }

    function field_isempty(element, message) {
        if (element.val().trim().length == 0) {
            field_unsuccess(element, message);

            return false;
        } else {
            return true;
        }
    }

    function hideModal(modal_id) {
        $(".modal-backdrop").remove();
        $("body").removeClass("modal-open");
        $("body").css("padding-right", "");
        $(modal_id).hide();

        // $(modal_id).modal('hide');
    }

    var token = "{{ Session::token() }}" ;

    function loadPackageData()
    {
        var element = $('#events_table');
        // element.DataTable().clear();
        // element.DataTable().destroy();
        element.DataTable({
            "columnDefs": [
                  { "type": "numeric-comma", targets: 0 }
            ],
            cache:true,
          responsive: true,
          bProcessing: true,
          bServerSide: true,
          deferRender: true,
          select: true,
          stateSave: true,
          pageLength:50,
          colReorder : true,
            'ajax': {
                'url':'{{route("events")}}',
                'type': 'POST',
                'data': {_token:token}
            },
            'columns': [
                {
                    name : 'count',
                    data : 'count'
                },
                {
                    name : 'subject',
                    data : 'subject'
                },
                {
                    name : 'start_date',
                    data : 'start_date'
                },
                {
                    name : 'end_date',
                    data : 'end_date'
                },
                {
                    name : 'attendies',
                    data : 'attendies'
                },                
                {
                    name : 'action',
                    data : 'action'
                },
            ],
        });
    }
    $(document).ready(function() {

        loadPackageData();

    });

    function add(event){
        event.preventDefault();
        var new_chq_no = parseInt($('#total_chq').val())+1;
        if(new_chq_no == 3)
        {
            showAlert('Warning!','You can\'t add more then 2 attendees','warning');  
            
            return false;
        }
      var new_input = "<div class='row' id='new_"+new_chq_no+"'>";                           
       new_input += "<div class='col-md-6'>";                           
            new_input += "<div class='my-1'>";
            new_input += "<label for=''>Email <i class='text-danger'>*</i></label>";
            new_input += "<input type='email' name='email[]' class='form-control' onChange=\"getUserByEmail(event,this,'#name_"+new_chq_no+"')\" placeholder='Email'>";
            new_input += "</div>";
            new_input += "</div>";
            new_input += "<div class='col-md-4'>";                            
            new_input += "<div class='my-1'>";
            new_input += "<label for=''>Name <i class='text-danger'>*</i></label>";
            new_input += "<input type='name' name='name' disabled class='form-control' id='name_"+new_chq_no+"' placeholder='email'>";                  
            new_input += "</div>";
            new_input += "</div>  ";
            new_input += '<div class="col-md-2">';
            new_input += '<button type="button" class="btn btn-sm btn-danger mt-4" onclick="remove(event)">Remove</button>';
            new_input += "</div>  ";
            new_input += "</div>  ";
    //   var new_input="<input type='text' id='new_"+new_chq_no+"'>";
      $('#new_chq').append(new_input);
      $('#total_chq').val(new_chq_no)
    }
    function remove(event){
        event.preventDefault();
      var last_chq_no = $('#total_chq').val();
      if(last_chq_no>1){
        $('#new_'+last_chq_no).remove();
        $('#total_chq').val(last_chq_no-1);
      }
    }

    // BEFORE DELETE POP UP MESSAGE
    $(document.body).on('click','.cls_delete',function(event){

        event.preventDefault();

        var route    = $(this).attr('href');
        var content  = $(this).attr('content');
        var table  = $(this).attr('data-table');

        var formData = new FormData();
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

        Swal.fire({
            title: 'Are you sure?',
            text: "You want to " + content,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            customClass: {
            confirmButton: 'btn btn-primary me-3',
            cancelButton: 'btn btn-label-secondary'
            },
            buttonsStyling: false
        }).then(function (result) {
            if (result.value) {

                showAjaxLoader();
                
                $.ajax({
                    url: route,

                    type: 'post',				

                    data: formData,

                    dataType: "json",

                    contentType: false,

                    cache: false,

                    processData: false,

                    success: function (result) {
                        removeAjaxLoader();
                        if (result.status) {
                            showAlert("Success",result.message,"success");

                            if ($(table).length > 0) {
                                $(table).DataTable().ajax.reload();
                            } else {
                                location.reload(true);
                            }
                            // $(form_id)[0].reset();

                        } else {
                            showAlert("Error",result.message,"error");
                        }

                    },
                    error: function (result) {
                        
                        ajax_show_error(result); 

                    },
                });
            }
        });


    });
</script>