/*
window.esriGeowConfig = {
	groupId: 'ce7a94a2f30441d3b5f4b23741e5f291',
	baseUrl: 'http://geoss.maps.arcgis.com/home/',
	restBaseUrl: 'http://geoss.maps.arcgis.com/sharing/',
	pluginPath: 'wp-content/plugins/ArcGISMapGalleryWP/'
};
*/

// BEGIN maps and apps custom scroll
var homePageFeaturedContentCount = 4;

function stubInitFeaturedMapsAndApps() {

	dojo.query("#scrollPaneLeft").forEach(function (e) {
		e.style['display'] = "block";
		dojo.addClass(e, "scrollDisabled");
	});
	dojo.query("#scrollPaneRight").forEach(function (e) {
		e.style['display'] = "block";
		dojo.addClass(e, "scrollDisabled");
	});

}

function initFeaturedMapsAndApps() {
	var groupID = window.esriGeowConfig.groupId;
	
	esri.request({
		url: window.esriGeowConfig.restBaseUrl + "search",
		content: {
			f: 'json',
			q: 'group:' + groupID,
			sortField: 'uploaded',
			sortOrder: 'desc'
		},
		callbackParamName: 'callback',
		load: function (itemsResponse) {
			dojo.query("#featuredMaps").style("display", "block");

			if (!itemsResponse) {
				itemsResponse = stubResults;
			}
			addToPane(itemsResponse.results, 'mapsAndApps');
		},
		error: function (error) {
			console.warn(error.message);
		}
	});
}


function addToPane(results, paneNodeId) {
// window.esriGeowConfig.pluginPath + 'images/defaultThumb.png';

	// LOAD ITEMS INTO SCROLLING PANE
	dojo.forEach(results, function (resultItem) {
		//console.log(resultItem);

		// TITLE
		var title = (resultItem.title) ? resultItem.title : resultItem.item;

		// THUMBNAIL
		var thumbUrl = (resultItem.thumbnail) ? window.esriGeowConfig.restBaseUrl + dojo.replace("content/items/{id}/info/{thumbnail}", resultItem) : window.esriGeowConfig.default_thumbnail; 
		
		// ITEM URL
		var itemUrl = window.esriGeowConfig.baseUrl + dojo.replace("item.html?id={id}", resultItem);

		// ITEM CELL
		var itemCell = dojo.create('td', {
			'id': 'previewNode_' + paneNodeId + "_" + resultItem.id,
			'class': 'previewNode'
		}, paneNodeId);

		// DETAILS LINK
		var detailsLink = dojo.create('a', {
			'id': 'linkNode_' + paneNodeId + "_" + resultItem.id,
			'href': itemUrl,
			'target': '_blank'
		}, itemCell.id);

		// PREVIEW IMAGE
		var previwImg = dojo.create('img', {
			'id': 'previewImg_' + paneNodeId + "_" + resultItem.id,
			'title': 'View Item Details',
			'src': thumbUrl,
			'align': 'absmiddle',
			'height': '133px',
			'style': 'box-shadow: 3px 3px 4px #000'
		}, detailsLink.id);

		// LABEL
		var titleDiv = dojo.create('div', {
			'id': 'labelNode_' + paneNodeId + "_" + resultItem.id,
			'innerHTML': title,
			'class': 'itemLabel ms-rtestate-field'
		}, detailsLink.id);
	});

	dojo.query("#scrollPaneLeft").forEach(function (e) {
		e.style['display'] = "block";
		dojo.addClass(e, "scrollDisabled");
	});
	dojo.query("#scrollPaneRight").forEach(function (e) {
		e.style['display'] = "block";
		if (results.length <= homePageFeaturedContentCount) {
			dojo.addClass(e, "scrollDisabled");
		}
	});
}

var shiftStep = 245; //120;
var shiftElements = 1;

function getShift(node) {
	var shift = node.style['left'];
	if (shift == "") {
		shift = 0;
	} else {
		shift = eval(shift.substr(0, shift.length - 2));
	}
	return shift;
}

function setShift(node, oldShift, newShift) {
	//node.style['left'] = newShift + "px";
	dojo.animateProperty({
		node: node,
		properties: { left: { end: newShift, start: oldShift, units: "px"} }
	}).play();
}

dojo.addOnLoad(function () {
	dojo.query(".scrollPrev").forEach(function (spNode) {
		dojo.connect(spNode, "onclick", function (evt) {
			dojo.query(".scrollContent", spNode.parentNode).forEach(function (scNode) {
				var shift = getShift(scNode);
				var newShift = shift + (shiftElements * shiftStep);
				if (newShift >= 0) {
					newShift = 0;
					dojo.addClass(spNode, "scrollDisabled");
				}
				setShift(scNode, shift, newShift);
				dojo.query(".scrollNext", spNode.parentNode).forEach(function (s) {
					dojo.removeClass(s, "scrollDisabled");
				});
			});
		});
	});

	dojo.query(".scrollNext").forEach(function (spNode) {
		dojo.connect(spNode, "onclick", function (evt) {
			dojo.query(".scrollContent", spNode.parentNode).forEach(function (c) {
				var shift = getShift(c);
				var newShift = shift - (shiftElements * shiftStep);
				var tdCount = dojo.query("td", c).length;
				var shMax = (tdCount - homePageFeaturedContentCount) * shiftStep;
				if ((-newShift) >= shMax) {
					newShift = -shMax;
					dojo.addClass(spNode, "scrollDisabled");
				}
				setShift(c, shift, newShift);
				dojo.query(".scrollPrev", spNode.parentNode).forEach(function (s) {
					dojo.removeClass(s, "scrollDisabled");
				});
			});
		});
	});
	dojo.query("body").addClass("soria esri");
	//dojo.query("body").style("background-color", "FFF");
});
