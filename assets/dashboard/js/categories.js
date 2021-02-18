const AJAX_URL = {
	get_cats: get_base_url("Admin_ajax/get_category/"),
	create_cat: get_base_url("Admin_ajax/create_category/"),
	update_cat: get_base_url("Admin_ajax/update_category/"),
	delete_cat: get_base_url("Admin_ajax/delete_category/"),
};
const DEFAULT_CATEGORY_TEXT = "Category name";
async function category_create() {
	let ref = $(".list-category").jstree(true);
	
	let sel = ref.get_selected();
	let parent = !sel.length ? "#" : sel[0];
	
	let cat_obj = {
		text: DEFAULT_CATEGORY_TEXT,
		type: "root",
	};

	let cat_id = await add_cat(DEFAULT_CATEGORY_TEXT, parent);
	if (cat_id > 0) {
		
		cat_obj["id"] = cat_id;
		
		if (parent != "#") {

			cat_obj["parent"] = parent;
			cat_obj["type"] = "file";
			
		}
		$(".list-category").jstree("create_node", parent, cat_obj, "last", function (node) {
			this.edit(node);
		});
		

	}
}

async function add_cat(text, parent) {
    let data = {
        name: text,
        parent_id: parent === "#" ? 0 : parent
    }
    const hashValue = jQuery("#csrf3").val();
    const tokenName = jQuery("#csrf3").attr("name");
	data[tokenName] = hashValue;
    try {
		let res = await post(AJAX_URL.create_cat, data);
		setTokenData(res);
		return res.cat_id || 0;
	} catch (error) {
		console.log(error);
		return 0;
	}
}

function post(url, data) {
	return $.ajax({
		type: "POST",
		url: url,
		data: data,
		dataType: "json"
	});
}


function update_cat(cat) {
	let data = {
        name: cat.text,
        parent_id: cat.parent === "#" ? 0 : cat.parent,
		id: cat.id
	};
	const hashValue = jQuery("#csrf3").val();
	const tokenName = jQuery("#csrf3").attr("name");
	data[tokenName] = hashValue;
	$.ajax({
		type: "POST",
		url: AJAX_URL.update_cat,
		data: data,
		dataType: "json",
		success: function (data) {
            setTokenData(data);
		},
	});
}

function delete_cat(cat) {
	let data = {
		id: cat.id
	};
	const hashValue = jQuery("#csrf3").val();
	const tokenName = jQuery("#csrf3").attr("name");
	data[tokenName] = hashValue;
	$.ajax({
		type: "POST",
		url: AJAX_URL.delete_cat,
		data: data,
		dataType: "json",
		success: function (data) {
			setTokenData(data);
		},
	});
}

function setTokenData(data) {
    const csrfName = data.csrfName;
    const csrfHash = data.csrfHash;
    jQuery("#csrf3").attr("name", csrfName);
    jQuery("#csrf3").val(csrfHash);
}

function category_rename() {
	let ref = $(".list-category").jstree(true),
		sel = ref.get_selected();
	if (!sel.length) {
		return false;
	}
	sel = sel[0];
	ref.edit(sel);
}

function category_delete() {
	let ref = $(".list-category").jstree(true),
		sel = ref.get_selected();
	if (!sel.length) {
		return false;
	}
	ref.delete_node(sel[0]);
}
$(function () {
	let to = false;
	$("#category_q").keyup(function () {
		if (to) {
			clearTimeout(to);
		}
		to = setTimeout(function () {
			let v = $("#category_q").val();
			$(".list-category").jstree(true).search(v);
		}, 250);
	});

	$(".list-category")
	// listen for event
	.on("rename_node.jstree", (e, data) => {
		update_cat(data.node);
	})
	.on("delete_node.jstree", (e, data) => {
		delete_cat(data.node);
	})
	.on("move_node.jstree", (e, data) => {
		let new_node = data.node;
		new_node['parent'] = data.parent;
		update_cat(new_node);
	})
	.jstree({
		core: {
			themes: {
				stripes: true,
			},
			data: {
				url: function (node) {
					const parent_id = node.id === "#" ? 0 : parseInt(node.id);
					return AJAX_URL.get_cats + parent_id;
				},
				type: "GET",
				dataType: "JSON",
				contentType: "application/json",
				data: function (node) {
					return {
						id: node.id,
					};
				},
			},
			check_callback: true
		},
		types: {
			"root": {
			    "icon": "fa fa-home",
			},
			"file": {
			    "icon": "fa fa-file",
			}
		},
		plugins: ["contextmenu", "dnd", "search", "state", "types", "wholerow"],
    });
});
