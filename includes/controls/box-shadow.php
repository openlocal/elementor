<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Control_Box_Shadow extends Control_Base_Multiple {

	public function get_type() {
		return 'box_shadow';
	}

	public function enqueue() {
		wp_register_script( 'iris', admin_url( '/js/iris.min.js' ), [ 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ], '1.0.7', 1 );
		wp_register_script( 'wp-color-picker', admin_url( '/js/color-picker.min.js' ), [ 'iris' ], false, true );

		wp_localize_script(
			'wp-color-picker',
			'wpColorPickerL10n',
			[
				'clear' => __( 'Clear', 'elementor' ),
				'defaultString' => __( 'Default', 'elementor' ),
				'pick' => __( 'Select Color', 'elementor' ),
				'current' => __( 'Current Color', 'elementor' ),
			]
		);

		wp_register_script(
			'wp-color-picker-alpha',
			ELEMENTOR_ASSETS_URL . 'admin/js/lib/wp-color-picker-alpha.js',
			[
				'wp-color-picker',
			],
			'1.1',
			true
		);

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker-alpha' );
	}

	public function get_default_value() {
		return [
			'horizontal' => 0,
			'vertical' => 0,
			'blur' => 0,
			'spread' => 0,
		];
	}

	protected function get_default_settings() {
		return array_merge( parent::get_default_settings(), [
			'label_block' => true,
		] );
	}

	public function get_sliders() {
		return [
			[ 'label' => 'Horizontal Length', 'type' => 'horizontal' ],
			[ 'label' => 'Vertical Length', 'type' => 'vertical' ],
			[ 'label' => 'Blur Radius', 'type' => 'blur' ],
			[ 'label' => 'Spread Radius', 'type' => 'spread' ],
			[ 'label' => 'Opacity', 'type' => 'opacity' ],
		];
	}

	public function get_colors() {
		return [
			[ 'label' => 'Shadow Color', 'type' => 'shadow' ],
		];
	}

	public function content_template() {
		?>
		<div class="elementor-control-field">
			<label class="elementor-control-title"><?php _e( 'Inset Or Outset', 'elementor' ); ?></label>
			<div class="elementor-control-input-wrapper">
				<select class="elementor-control-box-shadow-inset" data-setting="inset">
					<option value="no"><?php _e( 'No', 'elementor' ); ?></option>
					<option value="inset"><?php _e( 'Yes', 'elementor' ); ?></option>
				</select>
			</div>
		</div>

		<?php
		foreach ( $this->get_sliders() as $slider ) : ?>
			<div class="elementor-control-field">
				<label class="elementor-control-title">
					<?php echo $slider['label']; ?>
				</label>
				<div class="elementor-control-input-wrapper">
					<div class="elementor-control-slider"></div>
					<div class="elementor-control-slider-input">
						<input type="number" min="<%- data.min %>" max="<%- data.max %>" step="<%- data.step %>"
						       data-setting="<?php echo $slider['type']; ?>"/>
					</div>
				</div>
			</div>
		<?php endforeach; ?>

		<?php
		foreach ( $this->get_colors() as $color ) : ?>
			<%
			var defaultValue = '', dataAlpha = '';
			if ( data.default ) {
				if ( '#' !== data.default.substring( 0, 1 ) ) {
					defaultValue = '#' + data.default;
				} else {
					defaultValue = data.default;
				}
				defaultValue = ' data-default-color=' + defaultValue; // Quotes added automatically.
			}

			dataAlpha = ' data-alpha="true"';
			%>
			<div class="elementor-control-field">
				<label class="elementor-control-title">
					<?php echo $color['label']; ?>
				</label>
				<div class="elementor-control-input-wrapper">
					<input data-setting="<?php echo $color['type']; ?>" class="color-picker-hex" type="text" maxlength="7"
					       placeholder="<?php esc_attr_e( 'Hex Value', 'elementor' ); ?>" <%= defaultValue %><%=
					dataAlpha %> />
				</div>
			</div>
			<?php
		endforeach;
	}
}
