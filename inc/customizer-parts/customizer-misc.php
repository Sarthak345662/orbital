<?php

function orbital_customizer_styles()
{
	?>
	<style>
		.customize-control-title-misc {
			margin: 0;
			font-size: 24px;
			padding-top: 25px;
		}

		.customize-separator {
			border-top: 2px solid #000;
		}

		.customize-multiselect{
			padding: 0.8rem 1rem;
			border: 1px solid #c9bdbd;
			background: #e7e7e7;
		}

		.in-sub-panel #customize-theme-controls .customize-pane-child.current-panel-parent,
		#customize-theme-controls .customize-pane-child.current-section-parent {
			-webkit-transform: translateX(-100%);
			-ms-transform: translateX(-100%);
			transform: translateX(-100%);
		}


	</style>
	<?php
}
add_action('customize_controls_print_styles', 'orbital_customizer_styles', 999);

if (class_exists('WP_Customize_Control') && ! class_exists('orbital_Customize_Misc_Control')) {
	class orbital_Customize_Misc_Control extends WP_Customize_Control
	{
		public $settings = 'blogname';
		public $description = '';
		public $label = '';
		public function render_content()
		{
			switch ($this->type) {
				default:
				case 'text':
				echo '<p class="description">' . $this->description . '</p>';
				break;

				case 'multiselect':
				if (empty($this->choices)) {
					return;
				}

				echo '<script src="' . get_template_directory_uri() . '/assets/js/theme-customizer.js"></script>';
				echo '<div class="customize-multiselect">';
				echo '<span class="customize-control-title">' . esc_html($this->label) . '</span>';
				foreach ($this->choices as $choice => $choiceName) {
					echo '<label><input type="checkbox" name="category-' . $choice . '" id="category-' . $choice. '" class="cstmzr-category-checkbox"> ' . $choiceName . '</label><br>';
				}

				?><input type="hidden" id="<?php echo $this->id; ?>" class="cstmzr-hidden-categories" <?php $this->link(); ?> value="<?php echo sanitize_text_field($this->value()); ?>"><?php

				echo '</div>';
				break;
			}
		}
	}
}
