/**
 * Border Animation Builder JavaScript
 * 
 * Interactive frontend for the Border Animation Builder
 * Provides real-time preview and code generation
 * 
 * @package Reia\BorderAnimations
 * @version 2.0.0
 * @author REIA Development Team
 * @license MIT
 */

(function() {
    'use strict';

    /**
     * Border Animation Builder Class
     */
    class BorderAnimationBuilder {
        constructor(container) {
            this.container = container;
            this.preview = container.querySelector('.preview-container');
            this.codeOutput = container.querySelector('.code-output');
            this.currentOptions = {};
            
            this.init();
        }

        /**
         * Initialize the builder
         */
        init() {
            this.bindEvents();
            this.updatePreview();
        }

        /**
         * Bind event handlers
         */
        bindEvents() {
            // Animation type selector
            const animationSelect = this.container.querySelector('#animation-type');
            if (animationSelect) {
                animationSelect.addEventListener('change', () => this.updatePreview());
            }

            // Color pickers
            const colorInputs = this.container.querySelectorAll('input[type="color"]');
            colorInputs.forEach(input => {
                input.addEventListener('input', () => this.updatePreview());
            });

            // Range sliders and text inputs
            const rangeInputs = this.container.querySelectorAll('input[type="range"], input[type="text"]');
            rangeInputs.forEach(input => {
                input.addEventListener('input', () => this.updatePreview());
            });

            // Select dropdowns
            const selects = this.container.querySelectorAll('select');
            selects.forEach(select => {
                select.addEventListener('change', () => this.updatePreview());
            });

            // Preset buttons
            const presetButtons = this.container.querySelectorAll('.preset-btn');
            presetButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.applyPreset(button.dataset.preset);
                });
            });

            // Copy code button
            const copyButton = this.container.querySelector('.copy-code-btn');
            if (copyButton) {
                copyButton.addEventListener('click', () => this.copyCode());
            }

            // Reset button
            const resetButton = this.container.querySelector('.reset-btn');
            if (resetButton) {
                resetButton.addEventListener('click', () => this.reset());
            }

            // Export button
            const exportButton = this.container.querySelector('.export-btn');
            if (exportButton) {
                exportButton.addEventListener('click', () => this.exportAnimation());
            }
        }

        /**
         * Update the preview
         */
        updatePreview() {
            const options = this.getCurrentOptions();
            
            // Generate CSS
            const css = this.generateCSS(options);
            
            // Update preview
            if (this.preview) {
                const previewElement = this.preview.querySelector('.preview-element');
                if (previewElement) {
                    // Clear existing styles
                    previewElement.style.cssText = '';
                    
                    // Apply new styles
                    const style = document.createElement('style');
                    style.textContent = css;
                    
                    // Remove old style
                    const oldStyle = this.preview.querySelector('style');
                    if (oldStyle) {
                        oldStyle.remove();
                    }
                    
                    this.preview.appendChild(style);
                    previewElement.className = `preview-element ba-${options.type}`;
                }
            }

            // Update code output
            if (this.codeOutput) {
                this.codeOutput.textContent = css;
                
                // Syntax highlighting if available
                if (typeof Prism !== 'undefined') {
                    Prism.highlightElement(this.codeOutput);
                }
            }
        }

        /**
         * Get current options from form
         */
        getCurrentOptions() {
            const form = this.container.querySelector('.border-animation-form');
            if (!form) return {};

            const formData = new FormData(form);
            const options = {};

            for (const [key, value] of formData.entries()) {
                options[key.replace(/-/g, '_')] = value;
            }

            this.currentOptions = options;
            return options;
        }

        /**
         * Generate CSS from options
         */
        generateCSS(options) {
            const type = options.type || 'conic';
            const color = options.color || '#0073e6';
            const secondaryColor = options.secondary_color || '#ac3033';
            const width = options.width || '4px';
            const radius = options.radius || '1em';
            const speed = this.getSpeedValue(options.speed || 'normal');
            const direction = options.direction || 'clockwise';

            let css = '';
            const className = `.ba-${type}`;

            switch (type) {
                case 'conic':
                    css = `${className} {
    position: relative;
    border-radius: ${radius};
    padding: 20px;
}

${className}::before {
    content: '';
    position: absolute;
    inset: -${width};
    border-radius: ${radius};
    background: conic-gradient(
        from 0deg,
        ${color},
        ${secondaryColor},
        ${color}
    );
    animation: spin ${speed}s linear infinite ${direction === 'counterclockwise' ? 'reverse' : ''};
    z-index: -1;
}`;
                    break;

                case 'sparkler':
                    css = `${className} {
    position: relative;
    border-radius: ${radius};
    padding: 20px;
    overflow: hidden;
}

${className}::before {
    content: '';
    position: absolute;
    inset: -${width};
    border-radius: ${radius};
    background: linear-gradient(
        45deg,
        transparent 30%,
        ${color} 50%,
        transparent 70%
    );
    animation: sparkler ${speed}s ease-in-out infinite;
    z-index: -1;
}`;
                    break;

                case 'neon':
                    css = `${className} {
    position: relative;
    border-radius: ${radius};
    padding: 20px;
    border: ${width} solid ${color};
    box-shadow: 
        0 0 10px ${color},
        0 0 20px ${color},
        0 0 40px ${color};
    animation: neon-pulse ${speed}s ease-in-out infinite alternate;
}`;
                    break;

                case 'gradient':
                    css = `${className} {
    position: relative;
    border-radius: ${radius};
    padding: 20px;
    background: linear-gradient(45deg, ${color}, ${secondaryColor});
    background-size: 200% 200%;
    animation: gradient-shift ${speed}s ease infinite;
}`;
                    break;

                case 'dashed':
                    css = `${className} {
    position: relative;
    border-radius: ${radius};
    padding: 20px;
    border: ${width} dashed ${color};
    animation: dash-rotate ${speed}s linear infinite;
}`;
                    break;

                case 'wave':
                    css = `${className} {
    position: relative;
    border-radius: ${radius};
    padding: 20px;
    border: ${width} solid transparent;
    background: 
        linear-gradient(white, white) padding-box,
        linear-gradient(45deg, ${color}, ${secondaryColor}) border-box;
    animation: wave ${speed}s ease-in-out infinite;
}`;
                    break;

                default:
                    css = `${className} {
    border: ${width} solid ${color};
    border-radius: ${radius};
    padding: 20px;
}`;
            }

            // Add keyframes
            css += this.getKeyframes(type);

            return css;
        }

        /**
         * Get keyframes for animation type
         */
        getKeyframes(type) {
            const keyframes = {
                conic: `
@keyframes spin {
    to { transform: rotate(360deg); }
}`,
                sparkler: `
@keyframes sparkler {
    0%, 100% { transform: translateX(-100%); }
    50% { transform: translateX(100%); }
}`,
                neon: `
@keyframes neon-pulse {
    0% { box-shadow: 0 0 10px currentColor, 0 0 20px currentColor, 0 0 40px currentColor; }
    100% { box-shadow: 0 0 20px currentColor, 0 0 30px currentColor, 0 0 60px currentColor; }
}`,
                gradient: `
@keyframes gradient-shift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}`,
                dashed: `
@keyframes dash-rotate {
    to { transform: rotate(360deg); }
}`,
                wave: `
@keyframes wave {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}`
            };

            return keyframes[type] || '';
        }

        /**
         * Convert speed name to value
         */
        getSpeedValue(speed) {
            const speeds = {
                'slow': 3,
                'normal': 2,
                'fast': 1,
                'very-fast': 0.5
            };
            return speeds[speed] || 2;
        }

        /**
         * Apply preset configuration
         */
        applyPreset(presetName) {
            const presets = {
                'electric': {
                    type: 'conic',
                    color: '#00ffff',
                    secondary_color: '#ff00ff',
                    width: '3px',
                    speed: 'fast'
                },
                'fire': {
                    type: 'sparkler',
                    color: '#ff4500',
                    secondary_color: '#ffd700',
                    width: '4px',
                    speed: 'normal'
                },
                'ocean': {
                    type: 'wave',
                    color: '#0077be',
                    secondary_color: '#87ceeb',
                    width: '2px',
                    speed: 'slow'
                },
                'neon-green': {
                    type: 'neon',
                    color: '#39ff14',
                    width: '2px',
                    speed: 'normal'
                }
            };

            const preset = presets[presetName];
            if (!preset) return;

            // Update form fields
            Object.keys(preset).forEach(key => {
                const input = this.container.querySelector(`[name="${key.replace(/_/g, '-')}"]`);
                if (input) {
                    input.value = preset[key];
                }
            });

            this.updatePreview();
        }

        /**
         * Copy generated code to clipboard
         */
        async copyCode() {
            const code = this.codeOutput ? this.codeOutput.textContent : '';
            
            try {
                await navigator.clipboard.writeText(code);
                this.showNotification('Code copied to clipboard!', 'success');
            } catch (err) {
                // Fallback for older browsers
                const textarea = document.createElement('textarea');
                textarea.value = code;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
                
                this.showNotification('Code copied to clipboard!', 'success');
            }
        }

        /**
         * Reset form to defaults
         */
        reset() {
            const form = this.container.querySelector('.border-animation-form');
            if (form) {
                form.reset();
                this.updatePreview();
                this.showNotification('Settings reset to defaults', 'info');
            }
        }

        /**
         * Export animation as downloadable CSS file
         */
        exportAnimation() {
            const css = this.codeOutput ? this.codeOutput.textContent : '';
            const fileName = `border-animation-${this.currentOptions.type || 'custom'}.css`;
            
            const blob = new Blob([css], { type: 'text/css' });
            const url = URL.createObjectURL(blob);
            
            const a = document.createElement('a');
            a.href = url;
            a.download = fileName;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
            
            this.showNotification(`Exported as ${fileName}`, 'success');
        }

        /**
         * Show notification message
         */
        showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `border-animation-notification ${type}`;
            notification.textContent = message;
            
            // Style the notification
            Object.assign(notification.style, {
                position: 'fixed',
                top: '20px',
                right: '20px',
                padding: '10px 20px',
                borderRadius: '4px',
                color: 'white',
                backgroundColor: type === 'success' ? '#28a745' : type === 'error' ? '#dc3545' : '#007bff',
                zIndex: '10000',
                transition: 'all 0.3s ease'
            });
            
            document.body.appendChild(notification);
            
            // Remove after 3 seconds
            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }
    }

    /**
     * Initialize builders when DOM is ready
     */
    function initBorderAnimationBuilders() {
        const builders = document.querySelectorAll('.border-animation-builder');
        builders.forEach(builder => {
            new BorderAnimationBuilder(builder);
        });
    }

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initBorderAnimationBuilders);
    } else {
        initBorderAnimationBuilders();
    }

    // Re-initialize on dynamic content load (for AJAX)
    if (typeof jQuery !== 'undefined') {
        jQuery(document).on('widget-added widget-updated', initBorderAnimationBuilders);
    }

})();
