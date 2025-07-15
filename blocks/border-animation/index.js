/**
 * Border Animation Block - Editor Script
 * 
 * Provides the block editor interface for creating animated borders
 */

import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import { 
    InspectorControls, 
    RichText, 
    useBlockProps,
    PanelColorSettings
} from '@wordpress/block-editor';
import { 
    PanelBody, 
    SelectControl, 
    RangeControl,
    TextControl,
    ToggleControl
} from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';

// Import block metadata
import metadata from './block.json';

/**
 * Border Animation Block Edit Component
 */
function Edit({ attributes, setAttributes }) {
    const {
        animationType,
        primaryColor,
        secondaryColor,
        borderWidth,
        borderRadius,
        animationSpeed,
        animationDirection,
        content,
        padding,
        textAlign
    } = attributes;

    const [previewCSS, setPreviewCSS] = useState('');

    // Animation type options
    const animationTypes = [
        { label: __('Conic Gradient', 'border-animations-enhanced'), value: 'conic' },
        { label: __('Sparkler Effect', 'border-animations-enhanced'), value: 'sparkler' },
        { label: __('Neon Glow', 'border-animations-enhanced'), value: 'neon' },
        { label: __('Gradient Flow', 'border-animations-enhanced'), value: 'gradient' },
        { label: __('Dashed Rotation', 'border-animations-enhanced'), value: 'dashed' },
        { label: __('Wave Effect', 'border-animations-enhanced'), value: 'wave' },
        { label: __('⚡ Charged Particle', 'border-animations-enhanced'), value: 'particle-charged' },
        { label: __('☄️ Comet Trail', 'border-animations-enhanced'), value: 'particle-comet' },
        { label: __('🔄 Dual Particles', 'border-animations-enhanced'), value: 'particle-dual' },
        { label: __('⚡ Purple Electric Sparks', 'border-animations-enhanced'), value: 'particle-sparks' },
        { label: __('🔬 Quantum Dots', 'border-animations-enhanced'), value: 'particle-quantum' },
        { label: __('🪐 Planetary Orbit', 'border-animations-enhanced'), value: 'particle-planetary' },
        { label: __('🔥 Burning Fuse', 'border-animations-enhanced'), value: 'particle-fuse' },
        { label: __('🚂 Train Loop', 'border-animations-enhanced'), value: 'particle-train' },
        { label: __('🎡 Ferris Wheel', 'border-animations-enhanced'), value: 'particle-ferris' },
        { label: __('🎠 Merry-Go-Round', 'border-animations-enhanced'), value: 'particle-carousel' },
        { label: __('🏎️ Racing Circuit', 'border-animations-enhanced'), value: 'particle-racing' }
    ];

    // Speed options
    const speedOptions = [
        { label: __('Slow', 'border-animations-enhanced'), value: 'slow' },
        { label: __('Normal', 'border-animations-enhanced'), value: 'normal' },
        { label: __('Fast', 'border-animations-enhanced'), value: 'fast' },
        { label: __('Very Fast', 'border-animations-enhanced'), value: 'very-fast' }
    ];

    // Direction options
    const directionOptions = [
        { label: __('Clockwise', 'border-animations-enhanced'), value: 'clockwise' },
        { label: __('Counter-clockwise', 'border-animations-enhanced'), value: 'counterclockwise' }
    ];

    // Generate CSS based on attributes
    useEffect(() => {
        const css = generateAnimationCSS({
            type: animationType,
            primaryColor,
            secondaryColor,
            borderWidth,
            borderRadius,
            animationSpeed,
            animationDirection,
            padding
        });
        setPreviewCSS(css);
    }, [animationType, primaryColor, secondaryColor, borderWidth, borderRadius, animationSpeed, animationDirection, padding]);

    // Block props
    const blockProps = useBlockProps({
        className: `ba-${animationType}`,
        style: {
            textAlign,
            padding
        }
    });

    return (
        <>
            <InspectorControls>
                <PanelBody title={__('Animation Settings', 'border-animations-enhanced')} initialOpen={true}>
                    <SelectControl
                        label={__('Animation Type', 'border-animations-enhanced')}
                        value={animationType}
                        options={animationTypes}
                        onChange={(value) => setAttributes({ animationType: value })}
                    />
                    
                    <SelectControl
                        label={__('Speed', 'border-animations-enhanced')}
                        value={animationSpeed}
                        options={speedOptions}
                        onChange={(value) => setAttributes({ animationSpeed: value })}
                    />
                    
                    <SelectControl
                        label={__('Direction', 'border-animations-enhanced')}
                        value={animationDirection}
                        options={directionOptions}
                        onChange={(value) => setAttributes({ animationDirection: value })}
                    />
                </PanelBody>

                <PanelColorSettings
                    title={__('Colors', 'border-animations-enhanced')}
                    colorSettings={[
                        {
                            value: primaryColor,
                            onChange: (value) => setAttributes({ primaryColor: value }),
                            label: __('Primary Color', 'border-animations-enhanced')
                        },
                        {
                            value: secondaryColor,
                            onChange: (value) => setAttributes({ secondaryColor: value }),
                            label: __('Secondary Color', 'border-animations-enhanced')
                        }
                    ]}
                />

                <PanelBody title={__('Border Settings', 'border-animations-enhanced')} initialOpen={false}>
                    <TextControl
                        label={__('Border Width', 'border-animations-enhanced')}
                        value={borderWidth}
                        onChange={(value) => setAttributes({ borderWidth: value })}
                        help={__('e.g., 4px, 0.5em', 'border-animations-enhanced')}
                    />
                    
                    <TextControl
                        label={__('Border Radius', 'border-animations-enhanced')}
                        value={borderRadius}
                        onChange={(value) => setAttributes({ borderRadius: value })}
                        help={__('e.g., 1em, 10px, 50%', 'border-animations-enhanced')}
                    />
                </PanelBody>

                <PanelBody title={__('Layout', 'border-animations-enhanced')} initialOpen={false}>
                    <TextControl
                        label={__('Padding', 'border-animations-enhanced')}
                        value={padding}
                        onChange={(value) => setAttributes({ padding: value })}
                        help={__('e.g., 20px, 1em', 'border-animations-enhanced')}
                    />
                    
                    <SelectControl
                        label={__('Text Alignment', 'border-animations-enhanced')}
                        value={textAlign}
                        options={[
                            { label: __('Left', 'border-animations-enhanced'), value: 'left' },
                            { label: __('Center', 'border-animations-enhanced'), value: 'center' },
                            { label: __('Right', 'border-animations-enhanced'), value: 'right' }
                        ]}
                        onChange={(value) => setAttributes({ textAlign: value })}
                    />
                </PanelBody>
            </InspectorControls>

            <div {...blockProps}>
                <style dangerouslySetInnerHTML={{ __html: previewCSS }} />
                <RichText
                    tagName="div"
                    value={content}
                    onChange={(value) => setAttributes({ content: value })}
                    placeholder={__('Enter your content here...', 'border-animations-enhanced')}
                    allowedFormats={['core/bold', 'core/italic', 'core/link']}
                />
            </div>
        </>
    );
}

/**
 * Save component - outputs the final HTML
 */
function Save({ attributes }) {
    const {
        animationType,
        primaryColor,
        secondaryColor,
        borderWidth,
        borderRadius,
        animationSpeed,
        animationDirection,
        content,
        padding,
        textAlign
    } = attributes;

    const blockProps = useBlockProps.save({
        className: `ba-${animationType}`,
        style: {
            textAlign,
            padding
        }
    });

    const css = generateAnimationCSS({
        type: animationType,
        primaryColor,
        secondaryColor,
        borderWidth,
        borderRadius,
        animationSpeed,
        animationDirection,
        padding
    });

    return (
        <div {...blockProps}>
            <style dangerouslySetInnerHTML={{ __html: css }} />
            <RichText.Content tagName="div" value={content} />
        </div>
    );
}

/**
 * Generate CSS for the animation
 */
function generateAnimationCSS(options) {
    const {
        type,
        primaryColor,
        secondaryColor,
        borderWidth,
        borderRadius,
        animationSpeed,
        animationDirection,
        padding
    } = options;

    const speedMap = {
        'slow': '3s',
        'normal': '2s',
        'fast': '1s',
        'very-fast': '0.5s'
    };

    const speed = speedMap[animationSpeed] || '2s';
    const direction = animationDirection === 'counterclockwise' ? 'reverse' : '';
    const className = `.ba-${type}`;

    let css = '';

    switch (type) {
        case 'conic':
            css = `
                ${className} {
                    position: relative;
                    border-radius: ${borderRadius};
                    padding: ${padding};
                }
                ${className}::before {
                    content: '';
                    position: absolute;
                    inset: -${borderWidth};
                    border-radius: ${borderRadius};
                    background: conic-gradient(
                        from 0deg,
                        ${primaryColor},
                        ${secondaryColor},
                        ${primaryColor}
                    );
                    animation: ba-spin ${speed} linear infinite ${direction};
                    z-index: -1;
                }
                @keyframes ba-spin {
                    to { transform: rotate(360deg); }
                }
            `;
            break;

        case 'sparkler':
            css = `
                ${className} {
                    position: relative;
                    border-radius: ${borderRadius};
                    padding: ${padding};
                    overflow: hidden;
                }
                ${className}::before {
                    content: '';
                    position: absolute;
                    inset: -${borderWidth};
                    border-radius: ${borderRadius};
                    background: linear-gradient(
                        45deg,
                        transparent 30%,
                        ${primaryColor} 50%,
                        transparent 70%
                    );
                    animation: ba-sparkler ${speed} ease-in-out infinite;
                    z-index: -1;
                }
                @keyframes ba-sparkler {
                    0%, 100% { transform: translateX(-100%); }
                    50% { transform: translateX(100%); }
                }
            `;
            break;

        case 'neon':
            css = `
                ${className} {
                    position: relative;
                    border-radius: ${borderRadius};
                    padding: ${padding};
                    border: ${borderWidth} solid ${primaryColor};
                    box-shadow: 
                        0 0 10px ${primaryColor},
                        0 0 20px ${primaryColor},
                        0 0 40px ${primaryColor};
                    animation: ba-neon-pulse ${speed} ease-in-out infinite alternate;
                }
                @keyframes ba-neon-pulse {
                    0% { box-shadow: 0 0 10px ${primaryColor}, 0 0 20px ${primaryColor}, 0 0 40px ${primaryColor}; }
                    100% { box-shadow: 0 0 20px ${primaryColor}, 0 0 30px ${primaryColor}, 0 0 60px ${primaryColor}; }
                }
            `;
            break;

        case 'gradient':
            css = `
                ${className} {
                    position: relative;
                    border-radius: ${borderRadius};
                    padding: ${padding};
                    background: linear-gradient(45deg, ${primaryColor}, ${secondaryColor});
                    background-size: 200% 200%;
                    animation: ba-gradient-shift ${speed} ease infinite;
                }
                @keyframes ba-gradient-shift {
                    0% { background-position: 0% 50%; }
                    50% { background-position: 100% 50%; }
                    100% { background-position: 0% 50%; }
                }
            `;
            break;

        case 'dashed':
            css = `
                ${className} {
                    position: relative;
                    border-radius: ${borderRadius};
                    padding: ${padding};
                    border: ${borderWidth} dashed ${primaryColor};
                    animation: ba-dash-rotate ${speed} linear infinite;
                }
                @keyframes ba-dash-rotate {
                    to { transform: rotate(360deg); }
                }
            `;
            break;

        case 'wave':
            css = `
                ${className} {
                    position: relative;
                    border-radius: ${borderRadius};
                    padding: ${padding};
                    border: ${borderWidth} solid transparent;
                    background: 
                        linear-gradient(white, white) padding-box,
                        linear-gradient(45deg, ${primaryColor}, ${secondaryColor}) border-box;
                    animation: ba-wave ${speed} ease-in-out infinite;
                }
                @keyframes ba-wave {
                    0%, 100% { transform: scale(1); }
                    50% { transform: scale(1.05); }
                }
            `;
            break;

        case 'particle-dual':
            css = `
                ${className} {
                    position: relative;
                    border: 2px solid rgba(72, 187, 120, 0.3);
                    border-radius: ${borderRadius};
                    padding: ${padding};
                }
                ${className}::before,
                ${className}::after {
                    content: '';
                    position: absolute;
                    width: 6px;
                    height: 6px;
                    border-radius: 50%;
                    box-shadow: 0 0 10px currentColor;
                    z-index: 1;
                }
                ${className}::before {
                    background: #48bb78;
                    animation: ba-particle-clockwise calc(${speed} * 1.2) linear infinite;
                }
                ${className}::after {
                    background: #68d391;
                    animation: ba-particle-counter-clockwise calc(${speed} * 1.2) linear infinite;
                }
                @keyframes ba-particle-clockwise {
                    0% { top: -3px; left: 10%; }
                    25% { top: 10%; left: calc(100% + 3px); }
                    50% { top: calc(100% + 3px); left: 90%; }
                    75% { top: 90%; left: -3px; }
                    100% { top: -3px; left: 10%; }
                }
                @keyframes ba-particle-counter-clockwise {
                    0% { bottom: -3px; right: 10%; }
                    25% { right: -3px; bottom: 10%; }
                    50% { top: -3px; right: 90%; }
                    75% { top: 10%; right: calc(100% + 3px); }
                    100% { bottom: -3px; right: 10%; }
                }
            `;
            break;

        case 'particle-fuse':
            css = `
                ${className} {
                    position: relative;
                    border: 2px solid rgba(220, 38, 38, 0.3);
                    border-radius: ${borderRadius};
                    padding: ${padding};
                }
                ${className}::before {
                    content: '';
                    position: absolute;
                    width: 8px;
                    height: 8px;
                    background: radial-gradient(circle, #dc2626, #f97316, #fbbf24);
                    border-radius: 50%;
                    box-shadow: 0 0 15px #dc2626, 0 0 25px rgba(220, 38, 38, 0.6);
                    animation: ba-fuse-burn ${speed} linear infinite;
                    z-index: 1;
                }
                @keyframes ba-fuse-burn {
                    0% { top: -4px; left: 0%; }
                    25% { top: -4px; left: 100%; }
                    50% { top: 100%; left: calc(100% + 4px); }
                    75% { top: calc(100% + 4px); left: 0%; }
                    100% { top: -4px; left: 0%; }
                }
            `;
            break;

        case 'particle-ferris':
            css = `
                ${className} {
                    position: relative;
                    border: 2px solid rgba(225, 29, 72, 0.3);
                    border-radius: 50% !important;
                    padding: ${padding};
                }
                ${className}::before,
                ${className}::after {
                    content: '';
                    position: absolute;
                    width: 8px;
                    height: 6px;
                    border-radius: 2px;
                    animation: ba-ferris-rotation calc(${speed} * 4) linear infinite;
                    z-index: 1;
                }
                ${className}::before {
                    background: linear-gradient(135deg, #e11d48, #f43f5e);
                    box-shadow: 0 0 10px rgba(225, 29, 72, 0.6);
                }
                ${className}::after {
                    background: linear-gradient(135deg, #3b82f6, #60a5fa);
                    box-shadow: 0 0 10px rgba(59, 130, 246, 0.6);
                    animation-delay: calc(${speed} * 2);
                }
                @keyframes ba-ferris-rotation {
                    0% { top: 10%; left: 50%; transform: translateX(-50%); }
                    25% { top: 50%; left: 90%; transform: translateX(-50%); }
                    50% { top: 90%; left: 50%; transform: translateX(-50%); }
                    75% { top: 50%; left: 10%; transform: translateX(-50%); }
                    100% { top: 10%; left: 50%; transform: translateX(-50%); }
                }
            `;
            break;

        case 'particle-carousel':
            css = `
                ${className} {
                    position: relative;
                    border: 2px solid rgba(236, 72, 153, 0.3);
                    border-radius: 50% !important;
                    padding: ${padding};
                }
                ${className}::before {
                    content: '🎠';
                    position: absolute;
                    font-size: 12px;
                    line-height: 1;
                    animation: ba-carousel-ride calc(${speed} * 3.5) ease-in-out infinite;
                    z-index: 1;
                }
                @keyframes ba-carousel-ride {
                    0% { top: 15%; left: 50%; transform: translateX(-50%) scale(1) rotate(0deg); }
                    25% { top: 50%; left: 85%; transform: translateX(-50%) scale(1.1) rotate(90deg); }
                    50% { top: 85%; left: 50%; transform: translateX(-50%) scale(1) rotate(180deg); }
                    75% { top: 50%; left: 15%; transform: translateX(-50%) scale(0.8) rotate(270deg); }
                    100% { top: 15%; left: 50%; transform: translateX(-50%) scale(1) rotate(360deg); }
                }
            `;
            break;

        // Particle Effects
        case 'particle-charged':
            css = `
                ${className} {
                    position: relative;
                    border: 2px solid rgba(66, 153, 225, 0.3);
                    border-radius: ${borderRadius};
                    padding: ${padding};
                }
                ${className}::before {
                    content: '';
                    position: absolute;
                    width: 8px;
                    height: 8px;
                    background: radial-gradient(circle, #4299e1, #63b3ed, transparent);
                    border-radius: 50%;
                    box-shadow: 0 0 15px #4299e1;
                    animation: ba-particle-orbit ${speed} linear infinite;
                    z-index: 1;
                }
                @keyframes ba-particle-orbit {
                    0% { top: -4px; left: 0%; }
                    25% { top: -4px; left: 100%; }
                    50% { top: 100%; left: calc(100% + 4px); }
                    75% { top: calc(100% + 4px); left: 0%; }
                    100% { top: -4px; left: 0%; }
                }
            `;
            break;

        case 'particle-comet':
            css = `
                ${className} {
                    position: relative;
                    border: 2px solid rgba(237, 137, 54, 0.3);
                    border-radius: ${borderRadius};
                    padding: ${padding};
                }
                ${className}::before {
                    content: '';
                    position: absolute;
                    width: 12px;
                    height: 6px;
                    background: linear-gradient(90deg, #ed8936, #f6ad55, transparent);
                    border-radius: 50% 0 50% 0;
                    box-shadow: -8px 0 8px rgba(237, 137, 54, 0.6);
                    animation: ba-comet-orbit ${speed} ease-in-out infinite;
                    z-index: 1;
                }
                @keyframes ba-comet-orbit {
                    0% { top: -3px; left: 0%; transform: rotate(0deg); }
                    25% { top: -3px; left: 100%; transform: rotate(90deg); }
                    50% { top: 100%; left: calc(100% + 3px); transform: rotate(180deg); }
                    75% { top: calc(100% + 3px); left: 0%; transform: rotate(270deg); }
                    100% { top: -3px; left: 0%; transform: rotate(360deg); }
                }
            `;
            break;

        case 'particle-sparks':
            css = `
                ${className} {
                    position: relative;
                    border: 2px solid rgba(138, 43, 226, 0.4);
                    border-radius: ${borderRadius};
                    padding: ${padding};
                }
                ${className}::before {
                    content: '';
                    position: absolute;
                    width: 6px;
                    height: 6px;
                    background: radial-gradient(circle, #8a2be2, #9932cc, transparent);
                    border-radius: 50%;
                    box-shadow: 0 0 15px #8a2be2, 0 0 25px rgba(138, 43, 226, 0.6);
                    animation: ba-spark-orbit calc(${speed} * 0.8) linear infinite;
                    z-index: 1;
                }
                @keyframes ba-spark-orbit {
                    0% { top: -3px; left: 0%; }
                    25% { top: -3px; left: 100%; }
                    50% { top: 100%; left: calc(100% + 3px); }
                    75% { top: calc(100% + 3px); left: 0%; }
                    100% { top: -3px; left: 0%; }
                }
            `;
            break;

        case 'particle-planetary':
            css = `
                ${className} {
                    position: relative;
                    border: 2px solid rgba(30, 64, 175, 0.3);
                    border-radius: ${borderRadius};
                    padding: ${padding};
                }
                ${className}::before {
                    content: '';
                    position: absolute;
                    width: 10px;
                    height: 10px;
                    background: radial-gradient(circle, #1e40af, #3b82f6, #60a5fa);
                    border-radius: 50%;
                    box-shadow: 0 0 20px #3b82f6, inset 0 0 6px #ffffff;
                    animation: ba-planet-orbit calc(${speed} * 3) ease-in-out infinite;
                    z-index: 1;
                }
                @keyframes ba-planet-orbit {
                    0% { top: -5px; left: 20%; transform: scale(1); }
                    25% { top: -5px; left: 100%; transform: scale(1.2); }
                    50% { top: 60%; left: calc(100% + 5px); transform: scale(0.8); }
                    75% { top: calc(100% + 5px); left: 20%; transform: scale(1); }
                    100% { top: -5px; left: 20%; transform: scale(1); }
                }
            `;
            break;

        case 'particle-racing':
            css = `
                ${className} {
                    position: relative;
                    border: 2px solid rgba(220, 38, 38, 0.3);
                    border-radius: ${borderRadius};
                    padding: ${padding};
                }
                ${className}::before {
                    content: '';
                    position: absolute;
                    width: 10px;
                    height: 5px;
                    background: linear-gradient(90deg, #dc2626, #ef4444, #dc2626);
                    border-radius: 3px;
                    box-shadow: -8px 0 6px rgba(220, 38, 38, 0.4);
                    animation: ba-race-speed calc(${speed} * 0.6) ease-in-out infinite;
                    z-index: 1;
                }
                @keyframes ba-race-speed {
                    0% { top: -2.5px; left: 0%; transform: scale(1); }
                    25% { top: -2.5px; left: 100%; transform: scale(1.3); }
                    50% { top: 50%; left: calc(100% + 2.5px); transform: scale(1.1); }
                    75% { top: calc(100% + 2.5px); left: 0%; transform: scale(1.2); }
                    100% { top: -2.5px; left: 0%; transform: scale(1); }
                }
            `;
            break;

        default:
            css = `
                ${className} {
                    border: ${borderWidth} solid ${primaryColor};
                    border-radius: ${borderRadius};
                    padding: ${padding};
                }
            `;
    }

    return css;
}

// Register the block
registerBlockType(metadata.name, {
    edit: Edit,
    save: Save
});
