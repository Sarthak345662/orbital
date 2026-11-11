(function() {
	tinymce.PluginManager.add('orbital_tc_button', function( editor, url ) {
		editor.addButton( 'orbital_tc_button', {
			text: "Buttons ",
			icon: false,
			onclick: function() {
				editor.windowManager.open( {
					title: 'Insert Button',
					body: [{
						type: 'textbox',
						name: 'anchor',
						label: 'Anchor'
					},
					{
						type: 'textbox',
						name: 'url',
						label: 'URL'
					},
					{
						type: 'listbox',
						name: 'class',
						label: 'Style',
						'values': [
							{text: 'Normal', value: ''},
							{text: 'Primary', value: 'btn-primary'},
							{text: 'Outline Primary', value: 'btn-outline-primary'},
						]
					},
					{
						type: 'listbox',
						name: 'size',
						label: 'Size',
						'values': [
							{text: 'Normal', value: ''},
							{text: 'Large', value: 'btn-lg'},
							{text: 'Medium', value: 'btn-md'},
							{text: 'Small', value: 'btn-sm'},
						]
					}
				],
				onsubmit: function( e ) {
					editor.insertContent('<a class="btn ' + e.data.class + ' ' + e.data.size + '" href="' + e.data.url + '">' + e.data.anchor + '</a>');
				}
			});
		}
	});
});
})();