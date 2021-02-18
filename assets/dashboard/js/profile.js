$(function () {

    $(document).on("change", "#user-avatar", e => {
        readURL($("#user-avatar")[0]);
    });
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $("label[for='user-avatar'] img").attr("src", e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

	$("#edit-profile-form").on("submit", (e) => {
		e.preventDefault();
		e.stopPropagation();
		let formData = $("#edit-profile-form").serializeArray();
        let data = new FormData();
        formData.forEach(row => {
			data.append(row.name, row.value);
		});
		const hashValue = jQuery(".csrf-token").val();
		const tokenName = jQuery(".csrf-token").attr("name");
        data.append(tokenName, hashValue);
        
        if ($("#user-avatar")[0].files && $("#user-avatar")[0].files[0]) {

            data.append("avatar", $("#user-avatar")[0].files[0]);
        
        }

        $.ajax({
            type: "POST",
            url: $("#edit-profile-form").attr("action"),
            data: data,
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
				$(".alert.alert-success").html(data.msg).show();
				setTimeout(() => {
					$(".alert.alert-success").hide();
				}, 5000);
                setTokenData(data);
                if (data.redirect != undefined) {
                    setTimeout(() => {
                        window.location.href = window.location.origin + data.redirect;
                    }, 3000);
                }
            },
            error: (error) => {
                error = error.responseJSON;
                $(".alert.alert-warning").html(error.error).show();
                setTimeout(() => {
                    $(".alert.alert-warning").hide();
                }, 5000);
                setTokenData(error);
            }
        });

    });
    
    function setTokenData(data) {
        const csrfName = data.csrfName;
        const csrfHash = data.csrfHash;
        jQuery(".csrf-token").attr("name", csrfName);
        jQuery(".csrf-token").val(csrfHash);
    }
});
