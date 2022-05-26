<?php
/**
 * Helpers
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'ATBLOCKS_Helpers' ) ) {

    class ATBLOCKS_Helpers {
        
        /**
         * Generate css based on selectors
         * 
         */
        public static function mount_css( $selectors, $id, $media_querie_breakpoint = '' ) {
            $output = $css = '';
            $values = array();
            
            $id = '.' . $id;
            foreach( $selectors as $selector => $css_values ) {
                $css .= $id . ' > ' . $selector . '{';
                foreach( $css_values as $property => $value ) {
                    if( $value ) {
                        $css .= $property . ':' . $value . ';';
                        array_push( $values, $value );
                    }
                }
                    
                $css .= '}';
            }

            // Check if the provided selectors has values
            // If not, then retun empty to avoid unnecessary css
            if( count($values) < 1 ) {
                return '';
            }

            if( $media_querie_breakpoint ) {
                $output .= '@media only screen and (max-width:'. $media_querie_breakpoint .'px) {';
            }

            $output .= $css;

            if( $media_querie_breakpoint ) {
                $output .= '}';
            }

            // Remove empty selectors
            preg_match_all( "/\..+?\S\S+{}/i", $output, $matches );
            if( count($matches[0]) > 0 ) {
                foreach( $matches as $match ) {
                    $output = str_replace( $match, '', $output );
                }
            }

            return $output;
        }

        /**
         * Check values from two fields
         * Return empty if values are equal
         * 
         */
        public static function get_no_repeated_css_value( $desktopValue, $tabletValue, $mobileValue = '' ) {
            $units = array( 'px', '%', 'rem', 'em', 'vw', 'vh' );
            
            if( in_array( $desktopValue, $units ) || in_array( $tabletValue, $units ) || in_array( $mobileValue, $units ) ) {
                return '';
            }

            if( $mobileValue && $mobileValue != $desktopValue && $mobileValue != $tabletValue ) {
                return $mobileValue;
            }

            if( !$mobileValue && $desktopValue != $tabletValue ) {
                return $tabletValue;
            }

            if( $desktopValue == $mobileValue && $desktopValue != $tabletValue ) {
                if( $tabletValue != '' ) {
                    return $desktopValue;
                }
            }

            return '';
        }

        /**
         * Get spacement value (paddings/margins)
         * 
         */
        public static function get_spacement_value( $atts, $attName, $deviceType ) {
            $type = ( strpos( $attName, 'Margin' ) !== FALSE ) ? 'margin' : 'padding'; 
            $value = '';

            if( $type == 'margin' ) {
                if( $atts[ 'wrapperMarginToggle' . $deviceType ] ) {
                    $value = $atts['wrapperMarginTopBottom' . $deviceType];
                } else {
                    $value = $atts[$attName . $deviceType];
                }
            } else if( $type == 'padding' ) {
                if( $atts[ 'wrapperPaddingToggle' . $deviceType ] ) {
                    $value = $atts['wrapperPadding' . $deviceType];
                } else {
                    $value = $atts[$attName . $deviceType];
                }
            }
        
            return $value;
        }

        /**
         * Check if is Internet Explorer
         * 
         */
        public static function is_ie() {
            if( preg_match('~MSIE|Internet Explorer~i', $_SERVER['HTTP_USER_AGENT'] ) || preg_match( '~Trident/7.0(; Touch)?; rv:11.0~', $_SERVER['HTTP_USER_AGENT'] ) ) {
                return true;
            }

            return false;
        }

    }

}