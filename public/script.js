let api_path = "/projects/tree/public/api/tree/";
let deleteModal = null;
let renameModal = null;
let countdown = null;
let autocloseTime = 20;
let currentTime = 0;
let timerId = 0;

$( document ).ready(function() {
	deleteModal = new bootstrap.Modal(document.getElementById("deleteModal"), {});
	renameModal = new bootstrap.Modal(document.getElementById("renameModal"), {});
	$("#deleteModal").on('hide.bs.modal', function() { clearInterval(timerId); });
	countdown = $(document.getElementById("deleteModal")).find("#countdown").first();
	$.ajax({
		url: api_path,
		type: 'GET',
		success: function(data) {
			if(data.length)
				buildTree(data);
			else
				$("#create_root_btn").removeClass("d-none");
		}
	});
});

function buildTree(data) {
	data.forEach(el => {
		var node = createNode(el.id, el.title, el.parent_id == 0);
		addNodeToTree(node, el.parent_id);
	});
	initCollapseVisibility();
}
function initCollapseVisibility() {
	let elements = $("#tree_ul_element_0 > li");
	elements.each(function(index) {
		collapseChildrenVisibility($(this).data("node-id"));
	});
}
function collapseChildrenVisibility(el_id) {
	let elements = $("#tree_ul_element_" + el_id + " > li");
	if(elements.length > 0) {
		$('#collapse_' + el_id).removeClass("invisible");
	} else {
		$('#collapse_' + el_id).addClass("invisible");
	}
	elements.each(function(index) {
		collapseChildrenVisibility($(this).data("node-id"));
	});
}

function addNodeToTree(node, parent_id) {
	$("#tree_ul_element_" + parent_id).append(node);
}

function createRootApi() {
	$.ajax({
		url: api_path,
		type: 'POST',
		data: JSON.stringify({parent_id: 0, title: "Root"}),
		dataType: 'json',
		success: function(data) {
			var node = createNode(data.id, "Root", true);
			addNodeToTree(node, 0);
			$("#create_root_btn").addClass("d-none");
			initCollapseVisibility();
		}
	});
}

function createNodeApi(parent_id, title) {
	$.ajax({
		url: api_path,
		type: 'POST',
		data: JSON.stringify({parent_id: parent_id, title: title}),
		dataType: 'json',
		success: function(data) {
			var node = createNode(data.id, title);
			addNodeToTree(node, parent_id);
			initCollapseVisibility();
		}
	});
}

function saveNodeApi(new_name, node_id) {
	$.ajax({
		url: api_path + node_id + '/',
		type: 'PUT',
		data: JSON.stringify({id: node_id, title: new_name}),
		dataType: 'text',
		error: function(data, d1, d2) {
			console.log(data);
			console.log(d1);
			console.log(d2);
		},
		success: function(data) {
			$("#title_element_" + node_id).text(new_name);
		}
	});
}

function deleteNodeApi(id) {
	$.ajax({
		url: api_path + id + '/',
		type: 'DELETE',
		data: JSON.stringify({id: id}),
		dataType: 'text',
		error: function(data, d1, d2) {
			console.log(data);
			console.log(d1);
			console.log(d2);
		},
		success: function(data) {
			if($("#tree_li_element_" + id).data("root"))
				$("#create_root_btn").removeClass("d-none");
			$("#tree_li_element_" + id).remove();
			initCollapseVisibility();
		}
	});
}

function createNode(id, title, is_root = false) {
	let colapse_el = document.createElement('i');
	$(colapse_el).addClass("bi bi-arrow-down-circle pointer invisible").prop("id", "collapse_" + id).attr("data-id", id).click(collapseClicked);

	let add_el = document.createElement('i');
	$(add_el).addClass("bi bi-plus-circle pointer").data("id", id).click(addNodeClicked);

	let del_el = document.createElement('i');
	$(del_el).addClass("bi bi-dash-circle pointer").data("id", id);

	if(is_root)
		$(del_el).click(delRootClicked);
	else
		$(del_el).click(delNodeClicked);

	let ul_el = document.createElement('ul');
	$(ul_el).prop("id", "tree_ul_element_" + id);

	let title_el = document.createElement('span');
	$(title_el).prop("id", "title_element_" + id).text(title).attr("data-id", id).addClass("pointer").on("click", renameNodeClicked);
	let li_el = document.createElement('li');
	$(li_el).prop("id", "tree_li_element_" + id).append(colapse_el).append(" ").append(title_el).append(" ").append(add_el).append(" ").append(del_el).append(ul_el).attr("data-root", is_root ? "1" : "0").attr("data-node-id", id);
	return li_el;
}

function renameNodeClicked() {
	$("#new_item_name").val($(this).text());
	$("#rename_item_id").val($(this).data("id"));
	renameModal.show();
}
function saveNodeClicked() {
	let new_name = $("#new_item_name").val();
	let node_id = $("#rename_item_id").val();
	saveNodeApi(new_name, node_id);
}

function collapseClicked() {
	let id = $(this).data("id");
	$("#tree_ul_element_" + id).toggle();
	$(this).toggleClass("bi-arrow-down-circle").toggleClass("bi-arrow-right-circle");
}

function addNodeClicked() {
	let id = $(this).data("id");
	createNodeApi(id, "Sub node for " + id);
}

function delNodeClicked() {
	deleteNodeApi($(this).data("id"));
}

function delRootClicked() {
	countdown.html(autocloseTime);
	currentTime = autocloseTime;
	timerId = setInterval(autocloseTimer, 1000);
	deleteModal.show();
}

function autocloseTimer() {
	if(currentTime == 0) {
		deleteModal.hide();
		clearInterval(timerId);
	} else {
		currentTime--;
		countdown.html(currentTime);
	}
}

function deleteRoot() {
	let root = $('*[data-root=1]');
	deleteNodeApi(root.data("node-id"));
}