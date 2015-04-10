kkjs.event.onDOMReady(function(){
	kkjs.css.$("table").forEach(function(table){
		kkjs.table.hideable(
			table,
			kkjs.node.create({tag: "div", className: "noPrint", nextSibling: table})
		);
		kkjs.table.selectable(table);
		kkjs.table.filterable(table);
		kkjs.table.sortable(table);
	});
});