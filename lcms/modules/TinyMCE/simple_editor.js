$(document).ready(function() {
		var lang = $("#lang").val();

		// O2k7 skin
		tinyMCE.init({
			// General options
			mode : "textareas",
			theme: "advanced",
			skin : "o2k7",
			relative_urls : false,
			plugins : "safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras",

			// Theme options
			theme_advanced_buttons1 : ",bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,fontsizeselect,|,link,unlink,|,bullist,numlist,|,image,code,|,copy,paste,|,undo,redo",
			theme_advanced_buttons2 : "",
			theme_advanced_buttons3 : "",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,
			file_browser_callback : MadFileBrowser,
			language : lang
		});

		function MadFileBrowser(field_name, url, type, win) {
			  tinyMCE.activeEditor.windowManager.open({
			      file : "modules/TinyMCE/tiny_mce/plugins/mfm_013/mfm.php?field=" + field_name + "&url=" + url + "",
			      title : 'File Manager',
			      width : 640,
			      height : 450,
			      resizable : "no",
			      inline : "yes",
			      close_previous : "no"
			  }, {
			      window : win,
			      input : field_name
			  });
			  return false;
		}
    });