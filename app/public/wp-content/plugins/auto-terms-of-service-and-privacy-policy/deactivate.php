<?php

namespace wpautoterms;

function deactivate() {
	update_option( WPAUTOTERMS_OPTION_PREFIX . WPAUTOTERMS_OPTION_ACTIVATED, false );
	flush_rewrite_rules();
}
