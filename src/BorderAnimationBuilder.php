<?php

namespace Reia\BorderAnimations;

/**
 * Border Animation Builder - Core Builder Class
 * 
 * A framework-agnostic border animation builder that generates interactive
 * HTML interfaces for creating CSS border animations.
 * 
 * @package Reia\BorderAnimations
 * @version 2.0.0
 * @author REIA Development Team
 * @license MIT
 */
class BorderAnimationBuilder
{
    /**
     * Configuration options
     * 
     * @var array
     */
    private $config;

    /**
     * Available animation types
     * 
     * @var array
     */
    private $animations;

    /**
     * Animation presets
     * 
     * @var array
     */
    private $presets = [];

    /**
     * Custom animations
     * 
     * @var array
     */
    private $customAnimations = [];

    /**
     * Default configuration
     * 
     * @var array
     */
    private static $defaultConfig = [
        'theme' => 'default',
        'showPreview' => true,
        'showCode' => true,
        'showPresets' => true,
        'showAdvanced' => true,
        'cssPrefix' => 'ba-',
        'animations' => [
            'conic', 'sparkler', 'pulsing', 'gradient', 'dashed',
            'neon', 'rainbow', 'wave', 'ripple', 'glow'
        ],
        'defaults' => [
            'color' => '#0073e6',
            'secondaryColor' => '#ac3033',
            'accentColor' => '#ffd700',
            'width' => '4px',
            'radius' => '1em',
            'speed' => 'normal',
            'direction' => 'clockwise'
        ],
        'presets' => [
            'modern' => ['type' => 'conic', 'color' => '#007cba', 'width' => '3px', 'radius' => '12px'],
            'neon' => ['type' => 'neon', 'color' => '#00ff88', 'width' => '2px', 'radius' => '8px'],
            'elegant' => ['type' => 'gradient', 'color' => '#8e44ad', 'width' => '1px', 'radius' => '20px'],
            'retro' => ['type' => 'dashed', 'color' => '#ff6b6b', 'width' => '4px', 'radius' => '0'],
            'sparkle' => ['type' => 'sparkler', 'color' => '#ffd700', 'width' => '3px', 'radius' => '50%'],
            'wave' => ['type' => 'wave', 'color' => '#3498db', 'width' => '2px', 'radius' => '15px']
        ]
    ];

    /**
     * Constructor
     * 
     * @param array $config Configuration options
     */
    public function __construct(array $config = [])
    {
        $this->config = array_merge(self::$defaultConfig, $config);
        $this->animations = $this->config['animations'];
        $this->presets = $this->config['presets'];
        $this->initializeCustomAnimations();
    }

    /**
     * Initialize custom animations
     * 
     * @return void
     */
    private function initializeCustomAnimations()
    {
        if (isset($this->config['customAnimations'])) {
            foreach ($this->config['customAnimations'] as $name => $animation) {
                $this->addCustomAnimation($name, $animation);
            }
        }
    }

    /**
     * Add a custom animation
     * 
     * @param string $name Animation name
     * @param array $animation Animation configuration
     * @return self
     */
    public function addCustomAnimation(string $name, array $animation): self
    {
        $this->customAnimations[$name] = array_merge([
            'name' => ucfirst($name),
            'css' => '',
            'keyframes' => '',
            'defaultColor' => '#0073e6',
            'defaultWidth' => '4px'
        ], $animation);
        
        if (!in_array($name, $this->animations)) {
            $this->animations[] = $name;
        }
        
        return $this;
    }

    /**
     * Get available animations
     * 
     * @return array
     */
    public function getAnimations(): array
    {
        return $this->animations;
    }

    /**
     * Get animation presets
     * 
     * @return array
     */
    public function getPresets(): array
    {
        return $this->presets;
    }

    /**
     * Add animation preset
     * 
     * @param string $name Preset name
     * @param array $preset Preset configuration
     * @return self
     */
    public function addPreset(string $name, array $preset): self
    {
        $this->presets[$name] = $preset;
        return $this;
    }

    /**
     * Generate CSS for a specific border animation
     * 
     * @param string $animation Animation type
     * @param array $options Animation options
     * @return string Generated CSS
     */
    public function generateCSS(string $animation, array $options = []): string
    {
        $options = array_merge($this->config['defaults'], $options);
        $className = $this->config['cssPrefix'] . strtolower($animation);
        
        $css = "/* Generated Border Animation CSS */\n";
        $css .= ".{$className} {\n";
        $css .= "    position: relative;\n";
        $css .= "    border: {$options['width']} solid transparent;\n";
        $css .= "    border-radius: {$options['radius']};\n";
        $css .= "    background-clip: padding-box;\n";
        $css .= "}\n\n";
        
        $css .= ".{$className}::before {\n";
        $css .= "    content: '';\n";
        $css .= "    position: absolute;\n";
        $css .= "    top: -{$options['width']};\n";
        $css .= "    left: -{$options['width']};\n";
        $css .= "    right: -{$options['width']};\n";
        $css .= "    bottom: -{$options['width']};\n";
        $css .= "    border-radius: inherit;\n";
        $css .= "    z-index: -1;\n";
        $css .= $this->getAnimationCSS($animation, $options);
        $css .= "}\n\n";
        
        $css .= $this->getKeyframes($animation, $options);
        
        return $css;
    }

    /**
     * Get animation-specific CSS
     * 
     * @param string $animation Animation type
     * @param array $options Animation options
     * @return string Animation CSS
     */
    private function getAnimationCSS(string $animation, array $options): string
    {
        $speed = $this->getAnimationSpeed($options['speed'] ?? 'normal');
        
        switch ($animation) {
            case 'conic':
                return "    background: conic-gradient(from 0deg, {$options['color']}, transparent, {$options['color']});\n" .
                       "    animation: borderRotate {$speed}s linear infinite;\n";
                       
            case 'sparkler':
                return "    background: linear-gradient(90deg, transparent 30%, {$options['color']} 50%, transparent 70%);\n" .
                       "    animation: borderSparkle {$speed}s ease-in-out infinite;\n";
                       
            case 'neon':
                return "    background: {$options['color']};\n" .
                       "    box-shadow: 0 0 10px {$options['color']}, inset 0 0 10px {$options['color']};\n" .
                       "    animation: borderNeon {$speed}s ease-in-out infinite alternate;\n";
                       
            case 'gradient':
                return "    background: linear-gradient(45deg, {$options['color']}, {$options['secondaryColor']}, {$options['color']});\n" .
                       "    background-size: 200% 200%;\n" .
                       "    animation: borderGradient {$speed}s ease infinite;\n";
                       
            case 'dashed':
                return "    background: repeating-linear-gradient(0deg, {$options['color']} 0px, {$options['color']} 10px, transparent 10px, transparent 20px);\n" .
                       "    animation: borderDashed {$speed}s linear infinite;\n";
                       
            case 'wave':
                return "    background: {$options['color']};\n" .
                       "    animation: borderWave {$speed}s ease-in-out infinite;\n";
                       
            default:
                return "    background: {$options['color']};\n";
        }
    }

    /**
     * Get keyframes for an animation
     * 
     * @param string $animation Animation type
     * @param array $options Animation options
     * @return string Keyframes CSS
     */
    private function getKeyframes(string $animation, array $options): string
    {
        switch ($animation) {
            case 'conic':
                return "@keyframes borderRotate {\n" .
                       "    0% { transform: rotate(0deg); }\n" .
                       "    100% { transform: rotate(360deg); }\n" .
                       "}\n\n";
                       
            case 'sparkler':
                return "@keyframes borderSparkle {\n" .
                       "    0%, 100% { transform: translateX(-100%); }\n" .
                       "    50% { transform: translateX(100%); }\n" .
                       "}\n\n";
                       
            case 'neon':
                return "@keyframes borderNeon {\n" .
                       "    0% { box-shadow: 0 0 5px {$options['color']}, inset 0 0 5px {$options['color']}; }\n" .
                       "    100% { box-shadow: 0 0 20px {$options['color']}, inset 0 0 20px {$options['color']}; }\n" .
                       "}\n\n";
                       
            case 'gradient':
                return "@keyframes borderGradient {\n" .
                       "    0% { background-position: 0% 50%; }\n" .
                       "    50% { background-position: 100% 50%; }\n" .
                       "    100% { background-position: 0% 50%; }\n" .
                       "}\n\n";
                       
            case 'dashed':
                return "@keyframes borderDashed {\n" .
                       "    0% { background-position: 0 0; }\n" .
                       "    100% { background-position: 20px 0; }\n" .
                       "}\n\n";
                       
            case 'wave':
                return "@keyframes borderWave {\n" .
                       "    0%, 100% { border-radius: {$options['radius']}; }\n" .
                       "    25% { border-radius: {$options['radius']} 50% {$options['radius']} 50%; }\n" .
                       "    50% { border-radius: 50%; }\n" .
                       "    75% { border-radius: 50% {$options['radius']} 50% {$options['radius']}; }\n" .
                       "}\n\n";
                       
            default:
                return "";
        }
    }

    /**
     * Get animation speed in seconds
     * 
     * @param string $speed Speed setting
     * @return float Speed in seconds
     */
    private function getAnimationSpeed(string $speed): float
    {
        switch ($speed) {
            case 'slow': return 4.0;
            case 'normal': return 2.0;
            case 'fast': return 1.0;
            case 'very-fast': return 0.5;
            default: return 2.0;
        }
    }

    /**
     * Render complete HTML interface
     * 
     * @return string Generated HTML
     */
    public function renderHTML(): string
    {
        $html = $this->renderContainer();
        return $html;
    }

    /**
     * Render main container
     * 
     * @return string HTML for container
     */
    private function renderContainer(): string
    {
        $html = "<div class=\"border-animation-builder\" data-theme=\"{$this->config['theme']}\">\n";
        $html .= $this->renderHeader();
        $html .= "    <div class=\"builder-wrapper\">\n";
        $html .= $this->renderControls();
        if ($this->config['showPreview']) {
            $html .= $this->renderPreview();
        }
        $html .= "    </div>\n";
        if ($this->config['showCode']) {
            $html .= $this->renderCodeOutput();
        }
        $html .= "</div>\n";
        
        return $html;
    }

    /**
     * Render header
     * 
     * @return string HTML for header
     */
    private function renderHeader(): string
    {
        $html = "    <div class=\"builder-header\">\n";
        $html .= "        <h1>Border Animation Builder</h1>\n";
        $html .= "        <p>Create beautiful CSS border animations with live preview</p>\n";
        $html .= "    </div>\n\n";
        
        return $html;
    }

    /**
     * Render controls section
     * 
     * @return string HTML for controls
     */
    private function renderControls(): string
    {
        $html = "        <div class=\"animation-controls\">\n";
        $html .= $this->renderTabs();
        $html .= $this->renderBasicControls();
        if ($this->config['showAdvanced']) {
            $html .= $this->renderAdvancedControls();
        }
        if ($this->config['showPresets']) {
            $html .= $this->renderPresetControls();
        }
        $html .= "        </div>\n\n";
        
        return $html;
    }

    /**
     * Render control tabs
     * 
     * @return string HTML for tabs
     */
    private function renderTabs(): string
    {
        $html = "            <div class=\"control-tabs\">\n";
        $html .= "                <button class=\"tab-button active\" data-tab=\"basic\">Basic</button>\n";
        
        if ($this->config['showAdvanced']) {
            $html .= "                <button class=\"tab-button\" data-tab=\"advanced\">Advanced</button>\n";
        }
        
        if ($this->config['showPresets']) {
            $html .= "                <button class=\"tab-button\" data-tab=\"presets\">Presets</button>\n";
        }
        
        $html .= "            </div>\n\n";
        
        return $html;
    }

    /**
     * Render basic controls
     * 
     * @return string HTML for basic controls
     */
    private function renderBasicControls(): string
    {
        $html = "            <div class=\"tab-content active\" id=\"basic-tab\">\n";
        
        // Animation Type Select
        $html .= "                <div class=\"control-group\">\n";
        $html .= "                    <label for=\"border-animation-type\">Animation Type:</label>\n";
        $html .= "                    <select id=\"border-animation-type\">\n";
        
        foreach ($this->animations as $animation) {
            $name = ucfirst($animation);
            $html .= "                        <option value=\"{$animation}\">{$name}</option>\n";
        }
        
        $html .= "                    </select>\n";
        $html .= "                </div>\n\n";
        
        // Color Control
        $html .= "                <div class=\"control-group\">\n";
        $html .= "                    <label for=\"border-color\">Border Color:</label>\n";
        $html .= "                    <input type=\"color\" id=\"border-color\" value=\"{$this->config['defaults']['color']}\">\n";
        $html .= "                </div>\n\n";
        
        // Width Control
        $html .= "                <div class=\"control-group\">\n";
        $html .= "                    <label for=\"border-width\">Border Width:</label>\n";
        $html .= "                    <input type=\"range\" id=\"border-width\" min=\"1\" max=\"20\" step=\"1\" value=\"4\">\n";
        $html .= "                    <span class=\"value-display\" id=\"border-width-value\">4px</span>\n";
        $html .= "                </div>\n\n";
        
        // Radius Control
        $html .= "                <div class=\"control-group\">\n";
        $html .= "                    <label for=\"border-radius\">Border Radius:</label>\n";
        $html .= "                    <input type=\"range\" id=\"border-radius\" min=\"0\" max=\"50\" step=\"1\" value=\"16\">\n";
        $html .= "                    <span class=\"value-display\" id=\"border-radius-value\">16px</span>\n";
        $html .= "                </div>\n\n";
        
        // Speed Control
        $html .= "                <div class=\"control-group\">\n";
        $html .= "                    <label for=\"animation-speed\">Animation Speed:</label>\n";
        $html .= "                    <select id=\"animation-speed\">\n";
        $html .= "                        <option value=\"slow\">Slow</option>\n";
        $html .= "                        <option value=\"normal\" selected>Normal</option>\n";
        $html .= "                        <option value=\"fast\">Fast</option>\n";
        $html .= "                        <option value=\"very-fast\">Very Fast</option>\n";
        $html .= "                    </select>\n";
        $html .= "                </div>\n\n";
        
        $html .= "                <div class=\"control-buttons\">\n";
        $html .= "                    <button class=\"play-btn\" id=\"play-border-animation\">Apply Animation</button>\n";
        $html .= "                    <button class=\"reset-btn\" id=\"reset-border-animation\">Reset</button>\n";
        $html .= "                </div>\n";
        
        $html .= "            </div>\n\n";
        
        return $html;
    }

    /**
     * Render advanced controls
     * 
     * @return string HTML for advanced controls
     */
    private function renderAdvancedControls(): string
    {
        $html = "            <div class=\"tab-content\" id=\"advanced-tab\">\n";
        
        // Secondary Color
        $html .= "                <div class=\"control-group\">\n";
        $html .= "                    <label for=\"border-secondary-color\">Secondary Color:</label>\n";
        $html .= "                    <input type=\"color\" id=\"border-secondary-color\" value=\"{$this->config['defaults']['secondaryColor']}\">\n";
        $html .= "                </div>\n\n";
        
        // Direction
        $html .= "                <div class=\"control-group\">\n";
        $html .= "                    <label for=\"animation-direction\">Direction:</label>\n";
        $html .= "                    <select id=\"animation-direction\">\n";
        $html .= "                        <option value=\"clockwise\">Clockwise</option>\n";
        $html .= "                        <option value=\"counterclockwise\">Counterclockwise</option>\n";
        $html .= "                    </select>\n";
        $html .= "                </div>\n\n";
        
        // Custom CSS
        $html .= "                <div class=\"control-group\">\n";
        $html .= "                    <label for=\"custom-css\">Custom CSS:</label>\n";
        $html .= "                    <textarea id=\"custom-css\" rows=\"4\" placeholder=\"Add custom CSS properties...\"></textarea>\n";
        $html .= "                </div>\n\n";
        
        $html .= "                <div class=\"control-buttons\">\n";
        $html .= "                    <button class=\"play-btn\" id=\"play-border-animation-advanced\">Apply Animation</button>\n";
        $html .= "                    <button class=\"reset-btn\" id=\"reset-border-animation-advanced\">Reset</button>\n";
        $html .= "                </div>\n";
        
        $html .= "            </div>\n\n";
        
        return $html;
    }

    /**
     * Render preset controls
     * 
     * @return string HTML for preset controls
     */
    private function renderPresetControls(): string
    {
        $html = "            <div class=\"tab-content\" id=\"presets-tab\">\n";
        $html .= "                <div class=\"preset-grid\">\n";
        
        foreach ($this->presets as $key => $preset) {
            $name = ucfirst($key);
            $html .= "                    <button class=\"preset-button\" data-preset=\"{$key}\">{$name}</button>\n";
        }
        
        $html .= "                </div>\n";
        $html .= "            </div>\n\n";
        
        return $html;
    }

    /**
     * Render preview area
     * 
     * @return string HTML for preview
     */
    private function renderPreview(): string
    {
        $html = "        <div class=\"preview-area\">\n";
        $html .= "            <div class=\"preview-element\" id=\"border-preview-element\">\n";
        $html .= "                Preview Element\n";
        $html .= "            </div>\n";
        $html .= "        </div>\n\n";
        
        return $html;
    }

    /**
     * Render code output area
     * 
     * @return string HTML for code output
     */
    private function renderCodeOutput(): string
    {
        $html = "    <div class=\"css-output\">\n";
        $html .= "        <div class=\"code-section\">\n";
        $html .= "            <div class=\"code-tabs\">\n";
        $html .= "                <button class=\"code-tab active\" data-code=\"css\">CSS</button>\n";
        $html .= "                <button class=\"code-tab\" data-code=\"html\">HTML</button>\n";
        $html .= "            </div>\n";
        $html .= "            <div class=\"code-container\">\n";
        $html .= "                <button class=\"copy-btn\" id=\"copy-border-code\">Copy Code</button>\n";
        $html .= "                <pre id=\"border-css-code\">/* Click \"Apply Animation\" to generate CSS */</pre>\n";
        $html .= "            </div>\n";
        $html .= "        </div>\n";
        $html .= "    </div>\n";
        
        return $html;
    }
}
