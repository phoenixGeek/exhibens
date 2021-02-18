jQuery(document).ready(function() {

    dataTableObject = jQuery("#pageTable").DataTable({
        "createdRow": function(row, data, dataIndex) {
            $(row).attr('data-id', data.uid);
        }
    });

    $(".search-category").SumoSelect({ search: true, searchText: "Search category" });


    $("#new-page-modal").on("show.bs.modal", function (event) {
        $.each($("#search-category-create option"), function (
            indexInArray,
            valueOfElement
        ) {

            $("#search-category-create")[0].sumo.unSelectItem(
                indexInArray
            );
        });
    });


    $("#edit-page-modal").on("show.bs.modal", function (event) {
        $.each($("#search-category-edit option"), function (
            indexInArray,
            valueOfElement
        ) {

            $("#search-category-edit")[0].sumo.unSelectItem(
                indexInArray
            );
        });
        
        let button      = $(event.relatedTarget);
        let title       = button.data("title");
        let id          = button.data("id");
        let description = button.data("description");
        let categories  = button.data("categories");
        let status      = button.data("status");
        let slug        = button.data("slug");

        
        let modal = $(this);

        modal.find(".modal-title").html("Edit page: <strong>" + title + "</strong>");
        modal.find(".modal-body #edit-page_title").val(title);
        modal.find(".modal-body #edit-page_description").val(description);
        modal.find(".modal-body #edit-page_id").val(id);
        modal.find(".modal-body #edit-status").val(status);
        modal.find(".modal-body #edit-slug").val(slug);
        
        if (categories.length) {

            categories = categories.split(",");
            categories.forEach(cat_id => {
                $("#search-category-edit")[0].sumo.selectItem(cat_id);
            });
                
        }
    });

    $(document).on("click", ".btn-edit-page-modal", e => {
        e.preventDefault();

        var title       = jQuery("input[name='edit-page_title']").val();
        var description = jQuery("input[name='edit-page_description']").val();
        let status      = jQuery("[name='edit-status']").val();
        let slug        = jQuery("input[name='edit-slug']").val();
        let categories  = jQuery("#search-category-edit").val();

        var id = jQuery("#edit-page_id").val();

        var hashValue = jQuery('.csrftoken').val();
        var token_name = jQuery('.csrftoken').attr("name");


        var data = {
            id: id,
            page_title: title,
            page_description: description,
            categories: categories,
            status: status,
            slug: slug
        }
        data[token_name] = hashValue;

        jQuery.ajax({
            url: get_base_url("Admin_ajax/quick_edit_page/"),
            type: 'POST',
            dataType: "json",
            data: data,
            success: function(data) {

                setTokenData(data);

                jQuery("button[data-id='" + id + "']").closest("tr").html(
                    "<td>" + title + "</td>" +
                    "<td>" + description + "</td>" +
                    "<td>" + render_categories_span( categories, "#search-category-edit" ) + "</td>" +
                    "<td>" + `<span class="badge badge-pill status-${status}">${status}</span>` + "</td>" +
                    "<td><button data-toggle='modal' data-target='#edit-page-modal'" +
                    "data-id='" + id + "' " +
                    "data-title='" + title + "' " +
                    "data-description='" + description + "' " +
                    "data-status='" + status + "' " +
                    "data-slug='" + slug + "' " +
                    "data-categories='" + categories.join(",") + "' " +
                    "class='btn btn-light btn-edit-page'>Quick edit</button>" +
                    '<a href="'+ window.location.origin +'/dashboard/pages/edit/' + id + '" class="btn btn-light">Edit</a>' +
                    "<button data-toggle='modal' data-target='#delete-page-modal' data-id='" + id + "' class='btn btn-danger btn-delete-page' data-title='" + title + "'>Delete</button>" +
                    '<a class="btn btn-light" href="' +
                    window.location.origin +
                    "/preview/" +
                    data.id +
                    '">Preview</a></td>'
                );

                jQuery('#edit-page-modal').modal('hide');

                jQuery('#edited-page-alert').show();
                setTimeout(function() {
                    jQuery('#edited-page-alert').fadeOut(1000);
                }, 3000);

            },
            error: (error) => {

                error = error.responseJSON;
                jQuery("#edit-page-modal .alert-danger").html(error.error);
                jQuery("#edit-page-modal .alert-danger").show();
                setTokenData(error);
            }
        });

        $("#search-category-edit")[0].sumo.unSelectAll();
    });

    // Remove page
    jQuery(document).on("click", ".btn-delete-page", function () {
        let delete_page_title = jQuery(this).attr("data-title");
        let delete_page_id = jQuery(this).attr("data-id");
        jQuery("#input-delete-id").val(delete_page_id);
        jQuery("#delete-page-title").html(delete_page_title);
    });

    jQuery(document).on("change", "input[name$='page_title']", function () {
        const name = jQuery(this).val();
        let that = this;
        let action = 'create';
        let object_id = 0;
        const hashValue = jQuery('.csrftoken').val();
        const token_name = jQuery('.csrftoken').attr("name");
        const form_data = {
            title: name,
            type: "page",
            action: action,
            object_id: object_id,
        };
        form_data[token_name] = hashValue

        if (jQuery(that).parents(".modal-content").find("#edit-page_id").length) {
            action = 'edit';
            object_id = parseInt(jQuery(that).parents(".modal-content").find("#edit-page_id").val());
        }
        $.ajax({
            type: "post",
            url: "/admin-ajax/sanitize-title",
            data: form_data,
            dataType: "json",
            success: function (res) {
                jQuery(that).parents(".modal-content").find(".slug").val(res.slug)
                setTokenData(res);
            },
            error: (error) => {
                error = error.responseJSON;
                setTokenData(error);
            }
        });
    });

    function setTokenData(data) {
        const csrfName = data.csrfName;
        const csrfHash = data.csrfHash;
        jQuery(".csrftoken").attr("name", csrfName);
        jQuery(".csrftoken").val(csrfHash);
    }

    jQuery(document).on("click", ".btn-delete-page-comfirm", function () {
        let id = jQuery("#input-delete-id").val();
        let hashValue = jQuery('.csrftoken').val();
        let token_name = jQuery('.csrftoken').attr("name");
        let data = {
            id: id
        }
        
        data[token_name] = hashValue;
        
        jQuery.ajax({
            url: get_base_url("Admin_ajax/delete_page/"),
            type: 'POST',
            dataType: "json",
            data: data,
            success: function(data) {
                setTokenData(data);

                jQuery('#delete-page-modal').modal('hide');

                jQuery("button[data-id='" + id + "']").closest("tr").fadeOut(1000, function() {
                    jQuery("button[data-id='" + id + "']").closest("tr").remove();
                });

                jQuery("#deleted-page-alert").show();

                setTimeout(function() {
                    jQuery('#deleted-page-alert').fadeOut(1000);
                }, 3000);

            },
            error: (error) => {
                error = error.responseJSON;
                setTokenData(error);
                jQuery('#edit-page-modal .alert-danger').html(error.error);
                jQuery('#edit-page-modal .alert-danger').show();
            }
        });
    });
    
    jQuery(".btn-create-page-modal").click(function(e) {
    
        e.preventDefault();
    
        let page_title       = jQuery("input[name='page_title']").val();
        let page_description = jQuery("[name='page_description']").val();
        let status           = jQuery("[name='status']").val();
        let slug             = jQuery("input[name='slug']").val();
        let categories       = jQuery("#search-category-create").val();
        let hashValue        = jQuery('.csrftoken').val();
        let token_name       = jQuery('.csrftoken').attr("name");
    
        let data = {
            page_title      : page_title,
            page_description: page_description,
            categories      : categories,
            status          : status,
            slug            : slug,
            type            : jQuery("#page-type").val() === 'pages' ? 1: 2
        }
    
        data[token_name] = hashValue;
    
        jQuery.ajax({
            url: get_base_url("admin-ajax/create-page"),
            type: 'POST',
            dataType: "json",
            data: data,
            success: function(data) {
                
                setTokenData(data);
    
                jQuery('#new-page-modal').modal('hide');
    
                dataTableObject.row
                .add([
                    page_title,
                    page_description,
                    render_categories_span(
                        categories,
                        "#search-category-create"
                    ),
                    `<span class="badge badge-pill status=${status}">${status}</span>`,
                    '<button data-toggle="modal" data-target="#edit-page-modal"' +
                        '" data-id="' + data.id +
                        '" data-title="' + page_title +
                        '" data-description="' + page_description +
                        '" data-categories="' + categories.join(",") +
                        '" class="btn btn-light btn-edit-page">Quick Edit</button> ' +
                        '<a class="btn btn-light" href="' +
                        window.location.origin +
                        "/dashboard/pages/edit/" +
                        data.id +
                        '">Edit</a> ' +
                        `<button 
                            data-toggle='modal' 
                            data-target='#delete-page-modal' 
                            data-id='${data.id}' 
                            data-status='${data.status}' 
                            data-slug='${data.slug}' 
                            class='btn btn-danger btn-delete-page' 
                            data-title='${page_title}'
                        >Delete</button>` +
                        '<a class="btn btn-light" href="' +
                        window.location.origin +
                        "/preview/" +
                        data.id +
                        '">Preview</a> ',
                ])
                .draw();
    
                jQuery("#page_title").val("");
                jQuery("#page_description").val("");
                
                jQuery('#created-page-alert').show();
                setTimeout(function() {
                    jQuery('#created-page-alert').fadeOut(1000);
                }, 3000);
            },
            error: error => {
                error = error.responseJSON;
                setTokenData(error);
                jQuery('#new-page-modal .alert-danger').html(error.error);
                jQuery('#new-page-modal .alert-danger').show();
            }
        });
        
    });

    /**
     * render list category pills
     * @param {*} list_ids list ids of categories
     * @param {*} selector selector that contains all categories
     */
    let render_categories_span = (list_ids, selector) => {
        let html = '';
        
        $.each($(selector).find('option'), function (index, option) { 
            if( list_ids.indexOf($(option).attr('value')) > -1) {
                html += `<span class="badge badge-pill badge-info">${$(option).text()}</span>`;
            }
        });
    
        return html;
    }
})