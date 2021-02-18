var dataTableObject;

jQuery(document).ready(function() {

    dataTableObject = jQuery("#userTable").DataTable({
        "createdRow": function(row, data, dataIndex) {
            $(row).attr('data-id', data.uid);
        }
    });


    jQuery("#add-user-to-group").typeahead({
        hint: false
    }, {
        source: function(query, process) {
            console.log(query);
            users = [];
            map = {};

            var data = user_not_in_group;

            $.each(data, function(i, user) {
                map[user.fullname] = user.id;
                var mstr = user.email + " - " + user.fullname + " - " + user.id;
                if (mstr.toLowerCase().indexOf(query.trim().toLowerCase()) != -1) {
                    users.push(mstr);
                }
            });

            process(users);
        }
    })


});

jQuery(document).on("click", ".btn-remove-user", function() {

    var delete_account_name = jQuery(this).attr("data-username");
    var delete_account_uid = jQuery(this).attr("data-uid");
    jQuery("#input-delete-uid").val(delete_account_uid);
    jQuery("#delete-account-name").html(delete_account_name);

});

jQuery(document).on("click", ".btn-remove-user-comfirm", function() {

    var uid = jQuery("#input-delete-uid").val();

    var hashValue = jQuery('#csrf3').val();
    var token_name = jQuery('#csrf3').attr("name");
    var data = {
        uid: uid,
        gid: gid
    }
    data[token_name] = hashValue;

    jQuery.ajax({
        url: get_base_url("Admin_ajax/remove_user_from_group/"),
        type: 'POST',
        dataType: "json",
        data: data,
        success: function(data) {

            csrfName = data.csrfName;
            csrfHash = data.csrfHash;
            jQuery(".csrftoken").attr("name", csrfName);
            jQuery(".csrftoken").val(csrfHash);

            if (data.success) {

                jQuery(".csrftoken").attr("name", csrfName);
                jQuery(".csrftoken").val(csrfHash);

                jQuery('#remove-user-modal').modal('hide');

                jQuery("button[data-uid='" + uid + "']").closest("tr").fadeOut(1000, function() {
                    jQuery("button[data-uid='" + uid + "']").closest("tr").remove();
                });

                jQuery("#num_of_users").html(data.num_of_users);
                jQuery("#remove-user-alert").show();

                setTimeout(function() {
                    jQuery('#remove-user-alert').fadeOut(1000);
                }, 3000);
            }
        },
        fail: function(data) {

            alert("This user is already added to the Group OR not existed!");
            location.reload();
        }
    });
});



jQuery("#add-user-to-group-btn").click(function(e) {

    selectedVal = jQuery("#add-user-to-group").val();
    selectedId = selectedVal.split(" - ")[2];

    var hashValue = jQuery('#csrf').val();
    var token_name = jQuery('#csrf').attr("name");

    data = {
        gid: gid,
        uid: selectedId
    }

    data[token_name] = hashValue;

    jQuery.ajax({
        url: get_base_url("Admin_ajax/add_user_to_group/"),
        type: 'POST',
        dataType: "json",
        data: data,
        success: function(data) {

            csrfName = data.csrfName;
            csrfHash = data.csrfHash;

            jQuery(".csrftoken").attr("name", csrfName);
            jQuery(".csrftoken").val(csrfHash);

            if (data.success) {



                var newRow = dataTableObject.row.add([
                    data.user.username,
                    data.user.email,
                    data.user.first_name,
                    data.user.last_name,
                    data.user.active,
                    "<button data-toggle='modal' data-target='#remove-user-modal' class='btn btn-light btn-remove-user' data-uid='" + data.user.id + "' data-username='" + data.user.username + "'>Remove</button>"
                ]).draw();

                for (i = 0; i < user_not_in_group.length; i++) {
                    if ((user_not_in_group[i].id + "") == selectedId) {
                        user_not_in_group.splice(i, 1);
                    }
                }
                jQuery("#add-user-to-group").val("");
                jQuery("#num_of_users").html(data.num_of_users);
                jQuery("#added-user-alert").show();

                setTimeout(function() {
                    jQuery('#added-user-alert').fadeOut(1000);
                }, 3000);
            }
            if (data.error) {
                jQuery("#add-user-to-group").val("");
                alert(data.error);
            }
        }
    });

    e.preventDefault();
});