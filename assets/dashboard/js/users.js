var dataTableObject;
var mSumoSelect;
jQuery(document).ready(function() {

    dataTableObject = jQuery("#userTable").DataTable({
        "createdRow": function(row, data, dataIndex) {
            $(row).attr('data-id', data.uid);
        }
    });

    mSumoSelect = jQuery("#group-slt2").SumoSelect({ search: true, searchText: 'Find a group', okCancelInMulti: true });

    jQuery('#new-user-modal').on('shown.bs.modal', function(e) {
        jQuery('#new-user-modal .alert-danger').html("");
        jQuery('#new-user-modal .alert-danger').hide();
    })

    jQuery('#edit-user-modal').on('shown.bs.modal', function(e) {
        jQuery('#edit-user-modal .alert-danger').html("");
        jQuery('#edit-user-modal .alert-danger').hide();
    })

    jQuery(".btn-create-user-modal").click(function(e) {

        e.preventDefault();

        var username = jQuery("input[name='username']").val();
        var first_name = jQuery("input[name='first_name']").val();
        var last_name = jQuery("input[name='last_name']").val();
        var email = jQuery("input[name='email']").val();
        var password = jQuery("input[name='password']").val();
        var password_confirm = jQuery("input[name='password_confirm']").val();
        var user_gid = jQuery("#group-slt").val();

        var hashValue = jQuery('#csrf').val();
        var token_name = jQuery('#csrf').attr("name");
        var data = {
            username: username,
            first_name: first_name,
            last_name: last_name,
            email: email,
            password: password,
            password_confirm: password_confirm,
            user_gid: user_gid
        }
        data[token_name] = hashValue;

        jQuery.ajax({
            url: get_base_url("Admin_ajax/create_user"),
            type: 'POST',
            dataType: "json",
            data: data,
            success: function(data) {
                csrfName = data.csrfName;
                csrfHash = data.csrfHash;
                jQuery(".csrftoken").attr("name", csrfName);
                jQuery(".csrftoken").val(csrfHash);
                console.log(data);

                if (data.error) {
                    jQuery('#new-user-modal .alert-danger').html(data.error);
                    jQuery('#new-user-modal .alert-danger').show();
                }
                if (data.success) {

                    jQuery('#new-user-modal').modal('hide');

                    var newRow = dataTableObject.row.add([
                        data.username,
                        data.email,
                        data.first_name,
                        data.last_name,
                        data.active,
                        '<button data-toggle="modal" data-target="#edit-user-modal"' +
                        ' data-uid="' + data.uid +
                        '" data-username="' + data.username +
                        '" data-email="' + data.email +
                        '" data-first-name = "' + data.first_name +
                        '" data-last-name = "' + data.last_name +
                        '" data-active = "' + data.active +
                        '" class="btn btn-light btn-edit-user">Edit</button>',
                        "<button data-toggle='modal' data-target='#delete-user-modal' class='btn btn-light btn-delete-user' data-uid='" + data.uid + "' data-username='" + data.username + "'>Delete</button>"
                    ]).draw();

                    console.log(newRow);

                    jQuery("#email").val("");
                    jQuery("#username").val("");
                    jQuery("#first_name").val("");
                    jQuery("#last_name").val("");
                    jQuery("#password").val("");
                    jQuery("#password_confirm").val("");

                    $("#group-slt option:selected").prop("selected", false);

                    jQuery('#created-user-alert').show();
                    setTimeout(function() {
                        jQuery('#created-user-alert').fadeOut(1000);
                    }, 3000);
                }
            }
        });


    });

    jQuery(document).on("click", ".btn-edit-user-modal", function(e) {
        e.preventDefault();

        var username = jQuery("input[name='edit_username']").val();
        var first_name = jQuery("input[name='edit_first_name']").val();
        var last_name = jQuery("input[name='edit_last_name']").val();
        var email = jQuery("input[name='edit_email']").val();
        var uid = jQuery("#input-edit-uid").val();

        var active = 0;

        var hashValue = jQuery('#csrf2').val();
        var token_name = jQuery('#csrf2').attr("name");

        var password = jQuery("#edit-password").val();
        var password_confirm = jQuery("#edit-password-confirm").val();
        var user_gid = jQuery("#group-slt2").val();


        if ($('#active-check').is(":checked")) {
            active = 1;
        }

        var data = {
            edit_username: username,
            edit_first_name: first_name,
            edit_last_name: last_name,
            edit_email: email,
            edit_active: active,
            edit_password: password,
            edit_password_confirm: password_confirm,
            user_gid: user_gid
        }

        data[token_name] = hashValue;

        jQuery.ajax({
            url: get_base_url("Admin_ajax/edit_user/" + uid),
            type: 'POST',
            dataType: "json",
            data: data,
            success: function(data) {

                if (data.error) {
                    csrfName = data.csrfName;
                    csrfHash = data.csrfHash;
                    jQuery(".csrftoken").attr("name", csrfName);
                    jQuery(".csrftoken").val(csrfHash);

                    jQuery('#edit-user-modal .alert-danger').html(data.error);
                    jQuery('#edit-user-modal .alert-danger').show();
                }
                if (data.success) {

                    csrfName = data.csrfName;
                    csrfHash = data.csrfHash;
                    jQuery(".csrftoken").attr("name", csrfName);
                    jQuery(".csrftoken").val(csrfHash);

                    //update row info
                    jQuery("button[data-uid='" + uid + "']").closest("tr").html(
                        "<td>" + data.userdata.username + "</td>" +
                        "<td>" + data.userdata.email + "</td>" +
                        "<td>" + data.userdata.first_name + "</td>" +
                        "<td>" + data.userdata.last_name + "</td>" +
                        "<td>" + data.userdata.active + "</td>" +
                        "<td><button data-toggle='modal' data-target='#edit-user-modal'" +
                        "data-uid='" + uid + "' " +
                        "class='btn btn-light btn-edit-user'>Edit</button></td>" +
                        "<td><button data-toggle='modal' data-target='#delete-user-modal' data-uid='" + uid + "' class='btn btn-light btn-delete-user' data-username='" + username + "'>Delete</button></td>"
                    );

                    jQuery("#edit-password").val("");
                    jQuery("#edit-password-confirm").val("");
                    jQuery("#active-check").prop("checked", false);
                    mSumoSelect.sumo.unSelectAll();

                    jQuery('#edit-user-modal').modal('hide');

                    jQuery('#edited-user-alert').show();
                    setTimeout(function() {
                        jQuery('#edited-user-alert').fadeOut(1000);
                    }, 3000);

                }
            },
            fail: function(data) {
                csrfName = data.csrfName;
                csrfHash = data.csrfHash;
                jQuery(".csrftoken").attr("name", csrfName);
                jQuery(".csrftoken").val(csrfHash);
            }
        });
    })


    jQuery(document).on("click", ".btn-delete-user", function() {

        var delete_account_name = jQuery(this).attr("data-username");
        var delete_account_uid = jQuery(this).attr("data-uid");
        jQuery("#input-delete-uid").val(delete_account_uid);
        jQuery("#delete-account-name").html(delete_account_name);

    });


    jQuery(document).on("click", ".btn-edit-user", function() {

        var edit_account_uid = jQuery(this).attr("data-uid");

        jQuery.ajax({
            url: get_base_url("Admin_ajax/get_user_info/" + edit_account_uid),
            type: 'GET',
            dataType: "json",
            success: function(data) {
                console.log(data);

                jQuery("#input-edit-uid").val(data.id);
                jQuery("#edit-email").val(data.email);
                jQuery("#edit-username").val(data.username);
                jQuery("#edit-first-name").val(data.first_name);
                jQuery("#edit-last-name").val(data.last_name);

                if (data.active == 1) {
                    jQuery("#active-check").prop("checked", true);
                } else {
                    jQuery("#active-check").prop("checked", false);
                }
                mSumoSelect.sumo.unSelectAll();
                for (var i = 0; i < data.groups.length; i++) {
                    id = data.groups[i].id + "";
                    mSumoSelect.sumo.selectItem(id);
                }

            },
            fail: function(data) {

            }
        });
    });

    jQuery(document).on("click", ".btn-delete-user-comfirm", function() {

        var uid = jQuery("#input-delete-uid").val();
        var hashValue = jQuery('#csrf3').val();
        var token_name = jQuery('#csrf3').attr("name");
        var data = {
            uid: uid
        }
        data[token_name] = hashValue;

        jQuery.ajax({
            url: get_base_url("Admin_ajax/delete_user/" + uid),
            type: 'POST',
            dataType: "json",
            data: data,
            success: function(data) {
                if (data.success) {

                    csrfName = data.csrfName;
                    csrfHash = data.csrfHash;

                    jQuery(".csrftoken").attr("name", csrfName);
                    jQuery(".csrftoken").val(csrfHash);

                    jQuery('#delete-user-modal').modal('hide');

                    jQuery("button[data-uid='" + data.success + "']").closest("tr").fadeOut(1000, function() {
                        jQuery("button[data-uid='" + data.success + "']").closest("tr").remove();
                    });

                    jQuery("#deleted-user-alert").show();

                    setTimeout(function() {
                        jQuery('#deleted-user-alert').fadeOut(1000);
                    }, 3000);
                }
            }
        });
    });
});