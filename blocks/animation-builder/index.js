/**
 * Border Animation Builder Block - Editor Script
 * 
 * Provides the interactive builder interface in the block editor
 */

import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import { 
    InspectorControls, 
    useBlockProps
} from '@wordpress/block-editor';
import { 
    PanelBody, 
    ToggleControl,
    SelectControl
} from '@wordpress/components';
import { useEffect, useRef } from '@wordpress/element';

// Import block metadata
import metadata from './block.json';

/**
 * Animation Builder Block Edit Component
 */
function Edit({ attributes, setAttributes }) {
    const {
        showPreview,
        showCode,
        showPresets,
        showAdvanced,
        theme
    } = attributes;

    const builderRef = useRef(null);

    // Theme options
    const themeOptions = [
        { label: __('Default', 'border-animations-enhanced'), value: 'default' },
        { label: __('Dark', 'border-animations-enhanced'), value: 'dark' },
        { label: __('Light', 'border-animations-enhanced'), value: 'light' }
    ];

    // Initialize the builder when component mounts
    useEffect(() => {
        if (builderRef.current && window.BorderAnimationBuilder) {
            new window.BorderAnimationBuilder(builderRef.current);
        }
    }, []);

    const blockProps = useBlockProps({
        className: `border-animation-builder-block theme-${theme}`
    });

    return (
        <>
            <InspectorControls>
                <PanelBody title={__('Builder Settings', 'border-animations-enhanced')} initialOpen={true}>
                    <SelectControl
                        label={__('Theme', 'border-animations-enhanced')}
                        value={theme}
                        options={themeOptions}
                        onChange={(value) => setAttributes({ theme: value })}
                    />
                    
                    <ToggleControl
                        label={__('Show Preview', 'border-animations-enhanced')}
                        checked={showPreview}
                        onChange={(value) => setAttributes({ showPreview: value })}
                        help={__('Display live animation preview', 'border-animations-enhanced')}
                    />
                    
                    <ToggleControl
                        label={__('Show Code Output', 'border-animations-enhanced')}
                        checked={showCode}
                        onChange={(value) => setAttributes({ showCode: value })}
                        help={__('Display generated CSS code', 'border-animations-enhanced')}
                    />
                    
                    <ToggleControl
                        label={__('Show Presets', 'border-animations-enhanced')}
                        checked={showPresets}
                        onChange={(value) => setAttributes({ showPresets: value })}
                        help={__('Show preset animation buttons', 'border-animations-enhanced')}
                    />
                    
                    <ToggleControl
                        label={__('Show Advanced Options', 'border-animations-enhanced')}
                        checked={showAdvanced}
                        onChange={(value) => setAttributes({ showAdvanced: value })}
                        help={__('Display advanced customization options', 'border-animations-enhanced')}
                    />
                </PanelBody>
            </InspectorControls>

            <div {...blockProps}>
                <div className="border-animation-builder-header">
                    <h3>{__('Border Animation Builder', 'border-animations-enhanced')}</h3>
                    <p>{__('Create beautiful CSS border animations with live preview', 'border-animations-enhanced')}</p>
                </div>
                
                <div 
                    ref={builderRef}
                    className="border-animation-builder"
                    data-show-preview={showPreview}
                    data-show-code={showCode}
                    data-show-presets={showPresets}
                    data-show-advanced={showAdvanced}
                    data-theme={theme}
                >
                    {/* Builder content will be populated by JavaScript */}
                    <div className="builder-placeholder">
                        <div className="placeholder-icon">🎨</div>
                        <p>{__('Interactive builder will load here...', 'border-animations-enhanced')}</p>
                    </div>
                </div>
            </div>
        </>
    );
}

/**
 * Save component - outputs the final HTML
 */
function Save({ attributes }) {
    const {
        showPreview,
        showCode,
        showPresets,
        showAdvanced,
        theme
    } = attributes;

    const blockProps = useBlockProps.save({
        className: `border-animation-builder-block theme-${theme}`
    });

    return (
        <div {...blockProps}>
            <div 
                className="border-animation-builder"
                data-show-preview={showPreview}
                data-show-code={showCode}
                data-show-presets={showPresets}
                data-show-advanced={showAdvanced}
                data-theme={theme}
            >
                {/* Builder will be populated by frontend JavaScript */}
            </div>
        </div>
    );
}

// Register the block
registerBlockType(metadata.name, {
    edit: Edit,
    save: Save
});
