/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For the complete reference:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config

/*
	// The toolbar groups arrangement, optimized for two toolbar rows.
	config.toolbarGroups = [
		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
		{ name: 'links' },
		{ name: 'insert' },
		{ name: 'forms' },
		{ name: 'tools' },
		{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'others' },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
		{ name: 'styles' },
		{ name: 'colors' },
		{ name: 'about' }
	];

	// Remove some buttons, provided by the standard plugins, which we don't
	// need to have in the Standard(s) toolbar.
	config.removeButtons = 'Underline,Subscript,Superscript';

	// Se the most common block elements.
	config.format_tags = 'p;h1;h2;h3;pre';

	// Make dialogs simpler.
	config.removeDialogTabs = 'image:advanced;link:advanced';
*/

	// replace the full toolbar with our version of the full toolbar
	// with some of the more dangerous buttons removed

	config.toolbar = 'Full';

	config.toolbar_Full =
	[
		['Cut','Copy','Paste','PasteText','PasteFromWord','-','SpellChecker','Scayt'],
		['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
//		['Form','Checkbox','Radio','TextField','Textarea','Select','Button','ImageButton','HiddenField'],
		'/',
		['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
		['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
		['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		['Link','Unlink','Anchor'],
		['Image','Table','HorizontalRule','Smiley','SpecialChar'],
		'/',
		['Styles','Format','Font','FontSize'],
		['TextColor','BGColor'],
		['Maximize','ShowBlocks','-','About'],
		['Source']
	];

	config.toolbar_BImage =
	[
		['Bold','Italic'],
		['NumberedList','BulletedList'],
		['Link','Unlink'],
		['Image','About']
	];

	config.toolbar_Slim =
	[
		['Cut','Copy','Paste','PasteText','PasteFromWord'],
		['SpellChecker','Scayt'],
		['Undo','Redo'],
		['Find','Replace'],
		['SelectAll','RemoveFormat'],
		['About'],
		['Source'],
		'/',
		['Bold','Italic','Strike'],
		['Link','Unlink','Anchor'],
		['SpecialChar']
	];

	config.toolbar_SImage =
	[
		['Cut','Copy','Paste','PasteText','PasteFromWord'],
		['SpellChecker','Scayt'],
		['Undo','Redo'],
		['Find','Replace'],
		['SelectAll','RemoveFormat'],
		['About'],
		['Source'],
		'/',
		['Bold','Italic','Strike'],
		['Link','Unlink','Anchor'],
		['Image','SpecialChar']
	];


	// set up our file manangement links
//	config.filebrowserBrowseUrl = ROOT_URL+'files/browse/';
//	config.filebrowserUploadUrl = ROOT_URL+'files/add/';
	config.filebrowserImageBrowseUrl = ROOT_URL+'imgs/browse/';
	config.filebrowserImageUploadUrl = ROOT_URL+'imgs/add/';

};
