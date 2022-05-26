<?php
/**
 * Blocks CSS Output
 * All plugin blocks css are generated here
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'ATBLOCKS_Css' ) ) {

    class ATBLOCKS_Css_Output {

        /**
         * Container Block CSS
         * 
         */
        public static function get_container_block_css( $attributes, $id ) {
            global $content_width;
            $block_content_full_width = isset( $content_width ) ? $content_width : 1140;

            $defaults = array(
                'contentWidthSize' => $block_content_full_width,
                'contentWidthSizeUnit' => 'px',
                'contentWidthSizeTablet' => $block_content_full_width,
                'contentWidthSizeTabletUnit' => 'px',
                'contentWidthSizeMobile' => $block_content_full_width,
                'contentWidthSizeMobileUnit' => 'px',

                'wrapperZindex' => 0,

                'wrapperContentAlignment' => 'center',
                'wrapperContentAlignmentTablet' => 'center',
                'wrapperContentAlignmentMobile' => 'center',
                
                'wrapperBorderRadius' => 0,

                'wrapperBackgroundType' => 'color',
                'wrapperBackgroundColor' => '#f4f4f4',
                'wrapperBackgroundGradientColor1' => '#f7f7f7',
                'wrapperBackgroundGradientColor1Position' => 0,
                'wrapperBackgroundGradientColor2' => '#bbbbbb',
                'wrapperBackgroundGradientColor2Position' => 100,
                'wrapperBackgroundGradientDegree' => 45,
                'wrapperBackgroundImage' => null,
                'wrapperBackgroundImageType' => 'cover',
                'wrapperBackgroundPosition' => 'center',
                'wrapperBackgroundEffect' => 'scroll',

                'wrapperPaddingToggle' => true,
                'wrapperPaddingToggleTablet' => true,
                'wrapperPaddingToggleMobile' => true,
                'wrapperPadding' => 30,
                'wrapperPaddingTablet' => 30,
                'wrapperPaddingMobile' => 30,
                'wrapperPaddingTop' => 75,
                'wrapperPaddingTopUnit' => 'px',
                'wrapperPaddingRight' => 0,
                'wrapperPaddingRightUnit' => 'px',
                'wrapperPaddingBottom' => 75,
                'wrapperPaddingBottomUnit' => 'px',
                'wrapperPaddingLeft' => 0,
                'wrapperPaddingLeftUnit' => 'px',
                'wrapperPaddingTopTablet' => 75,
                'wrapperPaddingTopTabletUnit' => 'px',
                'wrapperPaddingRightTablet' => 0,
                'wrapperPaddingRightTabletUnit' => 'px',
                'wrapperPaddingBottomTablet' => 75,
                'wrapperPaddingBottomTabletUnit' => 'px',
                'wrapperPaddingLeftTablet' => 0,
                'wrapperPaddingLeftTabletUnit' => 'px',
                'wrapperPaddingTopMobile' => 75,
                'wrapperPaddingTopMobileUnit' => 'px',
                'wrapperPaddingRightMobile' => 0,
                'wrapperPaddingRightMobileUnit' => 'px',
                'wrapperPaddingBottomMobile' => 75,
                'wrapperPaddingBottomMobileUnit' => 'px',
                'wrapperPaddingLeftMobile' => 0,
                'wrapperPaddingLeftMobileUnit' => 'px',

                'wrapperMarginToggle' => '',
                'wrapperMarginToggleTablet' => '',
                'wrapperMarginToggleMobile' => '',
                'wrapperMarginTopBottom' => 0,
                'wrapperMarginTopBottomTablet' => 0,
                'wrapperMarginTopBottomMobile' => 0,
                'wrapperMarginTop' => 0,
                'wrapperMarginTopUnit' => 'px',
                'wrapperMarginBottom' => 0,
                'wrapperMarginBottomUnit' => 'px',
                'wrapperMarginTopTablet' => 0,
                'wrapperMarginTopTabletUnit' => 'px',
                'wrapperMarginBottomTablet' => 0,
                'wrapperMarginBottomTabletUnit' => 'px',
                'wrapperMarginTopMobile' => 0,
                'wrapperMarginTopMobileUnit' => 'px',
                'wrapperMarginBottomMobile' => 0,
                'wrapperMarginBottomMobileUnit' => 'px'
            );

            $atts = array_merge( $defaults, $attributes );

            $css = '';

            $desktopSelectors = array();
            $tabletSelectors  = array();
            $mobileSelectors  = array();

            // Container Wrapper - Start
            // Content Width Size
            $desktopSelectors['.athemes-blocks-block-container-wrapper > .athemes-blocks-block-container-wrapper-content'] = array(
                'max-width' => $atts['contentWidthSize'] . $atts['contentWidthSizeUnit']
            );
            $tabletSelectors['.athemes-blocks-block-container-wrapper > .athemes-blocks-block-container-wrapper-content'] = array(
                'max-width' => ATBLOCKS_Helpers::get_no_repeated_css_value(
                    $desktopSelectors['.athemes-blocks-block-container-wrapper > .athemes-blocks-block-container-wrapper-content']['max-width'],
                    $atts['contentWidthSizeTablet'] . $atts['contentWidthSizeTabletUnit']
                )
            );
            $mobileSelectors['.athemes-blocks-block-container-wrapper > .athemes-blocks-block-container-wrapper-content'] = array(
                'max-width' => ATBLOCKS_Helpers::get_no_repeated_css_value(
                    $desktopSelectors['.athemes-blocks-block-container-wrapper > .athemes-blocks-block-container-wrapper-content']['max-width'],
                    $tabletSelectors['.athemes-blocks-block-container-wrapper > .athemes-blocks-block-container-wrapper-content']['max-width'],
                    $atts['contentWidthSizeMobile'] . $atts['contentWidthSizeMobileUnit']
                )
            );

            // Content Alignment
            $desktopSelectors['.athemes-blocks-block-container-wrapper'] = array(
                'justify-content' => $atts['wrapperContentAlignment']
            );
            $tabletSelectors['.athemes-blocks-block-container-wrapper'] = array(
                'justify-content' => ATBLOCKS_Helpers::get_no_repeated_css_value( 
                    $desktopSelectors['.athemes-blocks-block-container-wrapper']['justify-content'], 
                    $atts['wrapperContentAlignmentTablet'] 
                )
            );
            $mobileSelectors['.athemes-blocks-block-container-wrapper'] = array(
                'justify-content' => ATBLOCKS_Helpers::get_no_repeated_css_value( 
                    $desktopSelectors['.athemes-blocks-block-container-wrapper']['justify-content'],
                    $tabletSelectors['.athemes-blocks-block-container-wrapper']['justify-content'], 
                    $atts['wrapperContentAlignmentMobile'] 
                )
            );

            // Border Radius
            $desktopSelectors['.athemes-blocks-block-container-wrapper'] = array_merge(
                $desktopSelectors['.athemes-blocks-block-container-wrapper'],
                array(
                    'border-radius' => $atts['wrapperBorderRadius'] . 'px'
                )
            );

            // Z-index
            $desktopSelectors['.athemes-blocks-block-container-wrapper'] = array_merge(
                $desktopSelectors['.athemes-blocks-block-container-wrapper'],
                array(
                    'z-index' => $atts['wrapperZindex']
                )
            );

            // Background
            if( $atts['wrapperBackgroundType'] == 'color' ) {
                $desktopSelectors['.athemes-blocks-block-container-wrapper'] = array_merge(
                    $desktopSelectors['.athemes-blocks-block-container-wrapper'],
                    array(
                        'background-color' => $atts['wrapperBackgroundColor']
                    )
                );
            }
            
            if( $atts['wrapperBackgroundType'] == 'gradient' ) {
                $desktopSelectors['.athemes-blocks-block-container-wrapper'] = array_merge(
                    $desktopSelectors['.athemes-blocks-block-container-wrapper'],
                    array(
                        'background' => 'linear-gradient( '. $atts['wrapperBackgroundGradientDegree'] .'deg, '. $atts['wrapperBackgroundGradientColor1'] .' '. $atts['wrapperBackgroundGradientColor1Position'] .'%, '. $atts['wrapperBackgroundGradientColor2'] .' '. $atts['wrapperBackgroundGradientColor2Position'] .'% );'
                    )
                );                 
            }
            
            if( $atts['wrapperBackgroundType'] == 'image' && $atts['wrapperBackgroundImage'] != null ) {
                $desktopSelectors['.athemes-blocks-block-container-wrapper > .athemes-blocks-background-image'] = array(
                    'object-fit' => $atts['wrapperBackgroundImageType'],
                    'object-position' => $atts['wrapperBackgroundPosition'],
                    'font-family' => "'object-fit: cover; object-position: bottom;';" // IE support
                );
            }

            // Margins
            $desktopSelectors['.athemes-blocks-block-container-wrapper'] = array_merge(
                $desktopSelectors['.athemes-blocks-block-container-wrapper'],
                array(
                    'margin-top' => ATBLOCKS_Helpers::get_spacement_value( $atts, 'wrapperMarginTop', '' ) . $atts['wrapperMarginTopUnit'],
                    'margin-bottom' => ATBLOCKS_Helpers::get_spacement_value( $atts, 'wrapperMarginBottom', '' ) . $atts['wrapperMarginBottomUnit']
                )
            );
            $tabletSelectors['.athemes-blocks-block-container-wrapper'] = array_merge(
                $tabletSelectors['.athemes-blocks-block-container-wrapper'],
                array(
                    'margin-top' => ATBLOCKS_Helpers::get_no_repeated_css_value( 
                        $desktopSelectors['.athemes-blocks-block-container-wrapper']['margin-top'], 
                        ATBLOCKS_Helpers::get_spacement_value( $atts, 'wrapperMarginTop', 'Tablet' ) . $atts['wrapperMarginTopTabletUnit']
                    ),
                    'margin-bottom' => ATBLOCKS_Helpers::get_no_repeated_css_value( 
                        $desktopSelectors['.athemes-blocks-block-container-wrapper']['margin-bottom'], 
                        ATBLOCKS_Helpers::get_spacement_value( $atts, 'wrapperMarginBottom', 'Tablet' ) . $atts['wrapperMarginBottomTabletUnit']
                    )
                )
            );
            $mobileSelectors['.athemes-blocks-block-container-wrapper'] = array_merge(
                $mobileSelectors['.athemes-blocks-block-container-wrapper'],
                array(
                    'margin-top' => ATBLOCKS_Helpers::get_no_repeated_css_value( 
                        $desktopSelectors['.athemes-blocks-block-container-wrapper']['margin-top'], 
                        $tabletSelectors['.athemes-blocks-block-container-wrapper']['margin-top'],
                        ATBLOCKS_Helpers::get_spacement_value( $atts, 'wrapperMarginTop', 'Mobile' ) . $atts['wrapperMarginTopMobileUnit']
                    ),
                    'margin-bottom' => ATBLOCKS_Helpers::get_no_repeated_css_value( 
                        $desktopSelectors['.athemes-blocks-block-container-wrapper']['margin-bottom'], 
                        $tabletSelectors['.athemes-blocks-block-container-wrapper']['margin-bottom'],
                        ATBLOCKS_Helpers::get_spacement_value( $atts, 'wrapperMarginBottom', 'Mobile' ) . $atts['wrapperMarginBottomMobileUnit']
                    )
                )
            );

            // Paddings
            $desktopSelectors['.athemes-blocks-block-container-wrapper'] = array_merge(
                $desktopSelectors['.athemes-blocks-block-container-wrapper'],
                array(
                    'padding-top' => ATBLOCKS_Helpers::get_spacement_value( $atts, 'wrapperPaddingTop', '' ) . $atts['wrapperPaddingTopUnit'],
                    'padding-right' => ATBLOCKS_Helpers::get_spacement_value( $atts, 'wrapperPaddingRight', '' ) . $atts['wrapperPaddingRightUnit'],
                    'padding-bottom' => ATBLOCKS_Helpers::get_spacement_value( $atts, 'wrapperPaddingBottom', '' ) . $atts['wrapperPaddingBottomUnit'],
                    'padding-left' => ATBLOCKS_Helpers::get_spacement_value( $atts, 'wrapperPaddingLeft', '' ) . $atts['wrapperPaddingLeftUnit']
                )
            );
            $tabletSelectors['.athemes-blocks-block-container-wrapper'] = array_merge(
                $tabletSelectors['.athemes-blocks-block-container-wrapper'],
                array(
                    'padding-top' => ATBLOCKS_Helpers::get_no_repeated_css_value( 
                        $desktopSelectors['.athemes-blocks-block-container-wrapper']['padding-top'], 
                        ATBLOCKS_Helpers::get_spacement_value( $atts, 'wrapperPaddingTop', 'Tablet' ) . $atts['wrapperPaddingTopTabletUnit']
                    ),
                    'padding-right' => ATBLOCKS_Helpers::get_no_repeated_css_value( 
                        $desktopSelectors['.athemes-blocks-block-container-wrapper']['padding-right'], 
                        ATBLOCKS_Helpers::get_spacement_value( $atts, 'wrapperPaddingRight', 'Tablet' ) . $atts['wrapperPaddingRightTabletUnit']
                    ),
                    'padding-bottom' => ATBLOCKS_Helpers::get_no_repeated_css_value( 
                        $desktopSelectors['.athemes-blocks-block-container-wrapper']['padding-bottom'], 
                        ATBLOCKS_Helpers::get_spacement_value( $atts, 'wrapperPaddingBottom', 'Tablet' ) . $atts['wrapperPaddingBottomTabletUnit']
                    ),
                    'padding-left' => ATBLOCKS_Helpers::get_no_repeated_css_value( 
                        $desktopSelectors['.athemes-blocks-block-container-wrapper']['padding-left'], 
                        ATBLOCKS_Helpers::get_spacement_value( $atts, 'wrapperPaddingLeft', 'Tablet' ) . $atts['wrapperPaddingLeftTabletUnit']
                    )
                )
            );
            $mobileSelectors['.athemes-blocks-block-container-wrapper'] = array_merge(
                $mobileSelectors['.athemes-blocks-block-container-wrapper'],
                array(
                    'padding-top' => ATBLOCKS_Helpers::get_no_repeated_css_value( 
                        $desktopSelectors['.athemes-blocks-block-container-wrapper']['padding-top'], 
                        $tabletSelectors['.athemes-blocks-block-container-wrapper']['padding-top'],
                        ATBLOCKS_Helpers::get_spacement_value( $atts, 'wrapperPaddingTop', 'Mobile' ) . $atts['wrapperPaddingTopMobileUnit']
                    ),
                    'padding-right' => ATBLOCKS_Helpers::get_no_repeated_css_value( 
                        $desktopSelectors['.athemes-blocks-block-container-wrapper']['padding-right'], 
                        $tabletSelectors['.athemes-blocks-block-container-wrapper']['padding-right'],
                        ATBLOCKS_Helpers::get_spacement_value( $atts, 'wrapperPaddingRight', 'Mobile' ) . $atts['wrapperPaddingRightMobileUnit']
                    ),
                    'padding-bottom' => ATBLOCKS_Helpers::get_no_repeated_css_value( 
                        $desktopSelectors['.athemes-blocks-block-container-wrapper']['padding-bottom'], 
                        $tabletSelectors['.athemes-blocks-block-container-wrapper']['padding-bottom'],
                        ATBLOCKS_Helpers::get_spacement_value( $atts, 'wrapperPaddingBottom', 'Mobile' ) . $atts['wrapperPaddingBottomMobileUnit']
                    ),
                    'padding-left' => ATBLOCKS_Helpers::get_no_repeated_css_value( 
                        $desktopSelectors['.athemes-blocks-block-container-wrapper']['padding-left'], 
                        $tabletSelectors['.athemes-blocks-block-container-wrapper']['padding-left'],
                        ATBLOCKS_Helpers::get_spacement_value( $atts, 'wrapperPaddingLeft', 'Mobile' ) . $atts['wrapperPaddingLeftMobileUnit']
                    )
                )
            );
            // Container Wrapper - End

            // Mount CSS to render
            $css .= ATBLOCKS_Helpers::mount_css( $desktopSelectors, $id, false );
            $css .= ATBLOCKS_Helpers::mount_css( $tabletSelectors, $id, apply_filters( 'athemes_blocks_css_tablet_breakpoint', 991 ) );
            $css .= ATBLOCKS_Helpers::mount_css( $mobileSelectors, $id, apply_filters( 'athemes_blocks_css_mobile_breakpoint', 719 ) );

            return $css;
        }
    }

}