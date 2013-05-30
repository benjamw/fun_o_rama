/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'en';
	// config.uiColor = '#AADC6E';

/*
	// ORIGINAL FULL TOOLBAR
	config.toolbar_Full =
	[
		['Source','-','Save','NewPage','Preview','-','Templates'],
		['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
		['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
		['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],
		['BidiLtr', 'BidiRtl'],
		'/',
		['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
		['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
		['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		['Link','Unlink','Anchor'],
		['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
		'/',
		['Styles','Format','Font','FontSize'],
		['TextColor','BGColor'],
		['Maximize', 'ShowBlocks','-','About']
	];


	// ORIGINAL BASIC TOOLBAR
	config.toolbar_Basic =
	[
		['Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink','-','About']
	];
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
