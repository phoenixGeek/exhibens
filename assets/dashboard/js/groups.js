var dataTableObject;
jQuery(document).ready(function() {

    dataTableObject = jQuery("#groupTable").DataTable({
        "createdRow": function(row, data, dataIndex) {
            $(row).attr('data-id', data.gid);
        }
    });

    jQuery(".btn-create-group-modal").click(function(e) {

        e.preventDefault();

        var groupname = jQuery("#groupname").val();
        var groupdescription = jQuery("#groupdescription").val();
        var hashValue = jQuery('#csrf').val();
        var token_name = jQuery('#csrf').attr("name");
        var data = {
            groupname: groupname,
            groupdescription: groupdescription
        }
        data[token_name] = hashValue;

        jQuery.ajax({
            url: get_base_url("Admin_ajax/create_group/"),
            type: 'POST',
            dataType: "json",
            data: data,
            success: function(data) {

                csrfName = data.csrfName;
                csrfHash = data.csrfHash;
                jQuery(".csrftoken").attr("name", csrfName);
                jQuery(".csrftoken").val(csrfHash);

                if (data.error) {
                    jQuery('#new-group-modal .alert-danger').html(data.error);
                    jQuery('#new-group-modal .alert-danger').show();
                }

                if (data.success) {

                    jQuery('#new-user-modal').modal('hide');
                    detail_url = get_base_url("dashboard/group_detail/" + data.gid);
                    var newRow = dataTableObject.row.add([
                        data.groupname,
                        data.description,
                        0,
                        '<a href="' + detail_url + '">Detail</a>',
                        '<button data-toggle="modal" data-target="#edit-group-modal"' +
                        ' data-gid="' + data.gid +
                        '" data-groupname="' + data.groupname +
                        '" data-description="' + data.groupname +
                        '" class="btn btn-light btn-edit-group">Edit</button>',
                        "<button data-toggle='modal' data-target='#delete-group-modal' class='btn btn-light btn-delete-group' data-gid='" + data.gid + "' data-groupname='" + data.groupname + "'>Delete</button>"
                    ]).draw();

                    jQuery("#groupname").val("");
                    jQuery("#groupdescription").val("");
                    jQuery('#new-group-modal .alert-danger').hide();

                    jQuery('#new-group-modal').modal('hide');

                    jQuery('#created-group-alert').show();
                    setTimeout(function() {
                        jQuery('#created-group-alert').fadeOut(1000);
                    }, 3000);

                }
            }
        });
    });


    jQuery(document).on("click", ".btn-delete-group", function() {

        var delete_group_name = jQuery(this).attr("data-groupname");
        var delete_group_gid = jQuery(this).attr("data-gid");
        jQuery("#input-delete-gid").val(delete_group_gid);
        jQuery("#delete-group-name").html(delete_group_name);

    });

    jQuery(document).on("click", ".btn-delete-group-comfirm", function() {
        var gid = jQuery("#input-delete-gid").val();
        var hashValue = jQuery('#csrf3').val();
        var token_name = jQuery('#csrf3').attr("name");
        var data = {
            gid: gid
        }
        data[token_name] = hashValue;

        jQuery.ajax({
            url: get_base_url("Admin_ajax/delete_group/" + gid),
            type: 'POST',
            dataType: "json",
            data: data,
            success: function(data) {
                csrfName = data.csrfName;
                csrfHash = data.csrfHash;

                jQuery(".csrftoken").attr("name", csrfName);
                jQuery(".csrftoken").val(csrfHash);
                if (data.success) {

                    jQuery('#delete-group-modal').modal('hide');

                    jQuery("button[data-gid='" + data.success + "']").closest("tr").fadeOut(1000, function() {
                        jQuery("button[data-gid='" + data.success + "']").closest("tr").remove();
                    });

                    jQuery("#deleted-group-alert").show();

                    setTimeout(function() {
                        jQuery('#deleted-group-alert').fadeOut(1000);
                    }, 3000);

                }
            }
        });
    });

    jQuery(document).on("click", ".btn-edit-group", function() {

        var edit_group_gid = jQuery(this).attr("data-gid");
        jQuery("#input-edit-gid").val(edit_group_gid);

        jQuery("#edit-groupname").val(jQuery(this).attr("data-groupname"));
        jQuery("#edit-description").val(jQuery(this).attr("data-description"));

    });


    jQuery(document).on("click", ".btn-edit-group-modal", function(e) {
        e.preventDefault();

        var groupname = jQuery("input[name='edit_groupname']").val();
        var description = jQuery("input[name='edit_description']").val();

        var gid = jQuery("#input-edit-gid").val();

        var hashValue = jQuery('#csrf2').val();
        var token_name = jQuery('#csrf2').attr("name");


        var data = {
            groupname: groupname,
            groupdescription: description
        }
        data[token_name] = hashValue;



        jQuery.ajax({
            url: get_base_url("Admin_ajax/edit_group/" + gid),
            type: 'POST',
            dataType: "json",
            data: data,
            success: function(data) {

                csrfName = data.csrfName;
                csrfHash = data.csrfHash;

                jQuery(".csrftoken").attr("name", csrfName);
                jQuery(".csrftoken").val(csrfHash);

                if (data.error) {
                    jQuery('#edit-group-modal .alert-danger').html(data.error);
                    jQuery('#edit-group-modal .alert-danger').show();
                }
                if (data.success) {

                    jQuery("button[data-gid='" + gid + "']").closest("tr").html(
                        "<td>" + groupname + "</td>" +
                        "<td>" + description + "</td>" +
                        "<td>" + data.num_of_users + "</td>" +
                        "<td><a href='" + get_base_url("dashboard/group_detail/" + gid) + "'>Detail</a></td>" +
                        "<td><button data-toggle='modal' data-target='#edit-group-modal'" +
                        "data-gid='" + gid + "' " +
                        "data-groupname='" + groupname + "' " +
                        "data-description='" + description + "' " +
                        "class='btn btn-light btn-edit-group'>Edit</button></td>" +
                        "<td><button data-toggle='modal' data-target='#delete-group-modal' data-gid='" + gid + "' class='btn btn-light btn-delete-group' data-groupname='" + groupname + "'>Delete</button></td>"
                    );

                    jQuery('#edit-group-modal').modal('hide');

                    jQuery('#edited-group-alert').show();
                    setTimeout(function() {
                        jQuery('#edited-group-alert').fadeOut(1000);
                    }, 3000);

                }
            }
        });
    })

});