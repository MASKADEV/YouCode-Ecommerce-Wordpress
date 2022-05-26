export default {
	name: 'tooltip',
	template: `<span :class="[bottom ? 'wplegal-form-bottom-tooltip' : 'wplegal-form-tooltip']">
					<img class="wplegal-tooltip-image" :src="tooltip.default">
					<span :class="[bottom ? 'wplegal-form-bottom-tooltiptext' : 'wplegal-form-tooltiptext']">{{text}}</span>
				</span>`,
	props: {
		text: {
			type: String,
			default: '',
		},
		bottom: {
			type: Boolean,
			default: false,
		}
	},
	data() {
		return {
			tooltip: require('../../admin/images/tooltip-icon.png')
		}
	}

}