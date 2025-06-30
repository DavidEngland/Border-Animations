/**
 * Typewriter Border Animation
 * Creates an infinite loop that types out text character by character around the border
 */

class TypewriterBorderAnimation {
    constructor(element, options = {}) {
        this.element = element;
        this.originalText = element.textContent || element.innerText || 'Sample Text';
        
        // Configuration
        this.config = {
            speed: options.speed || 150, // milliseconds per character
            pauseEnd: options.pauseEnd || 1000, // pause at end before restart
            pauseStart: options.pauseStart || 500, // pause at start
            cursor: options.cursor || '|',
            showCursor: options.showCursor !== false,
            loop: options.loop !== false,
            ...options
        };
        
        this.currentIndex = 0;
        this.isTyping = false;
        this.isDeleting = false;
        this.timeoutId = null;
        
        this.init();
    }
    
    init() {
        // Clear any existing content and set up
        this.element.textContent = '';
        this.element.classList.add('ba-typewriter-active');
        
        // Start the animation
        this.startAnimation();
    }
    
    startAnimation() {
        if (this.isTyping) return;
        
        this.isTyping = true;
        this.typeCharacter();
    }
    
    typeCharacter() {
        if (this.currentIndex < this.originalText.length) {
            // Typing forward
            const currentText = this.originalText.substring(0, this.currentIndex + 1);
            const cursor = this.config.showCursor ? this.config.cursor : '';
            this.element.textContent = currentText + cursor;
            
            this.currentIndex++;
            this.timeoutId = setTimeout(() => this.typeCharacter(), this.config.speed);
        } else {
            // Finished typing, pause then start deleting
            if (this.config.showCursor) {
                this.element.textContent = this.originalText + this.config.cursor;
            }
            
            this.timeoutId = setTimeout(() => {
                if (this.config.loop) {
                    this.deleteCharacter();
                } else {
                    this.finish();
                }
            }, this.config.pauseEnd);
        }
    }
    
    deleteCharacter() {
        if (this.currentIndex > 0) {
            // Deleting backward
            this.currentIndex--;
            const currentText = this.originalText.substring(0, this.currentIndex);
            const cursor = this.config.showCursor ? this.config.cursor : '';
            this.element.textContent = currentText + cursor;
            
            this.timeoutId = setTimeout(() => this.deleteCharacter(), this.config.speed * 0.5);
        } else {
            // Finished deleting, pause then restart
            this.element.textContent = this.config.showCursor ? this.config.cursor : '';
            
            this.timeoutId = setTimeout(() => {
                this.typeCharacter();
            }, this.config.pauseStart);
        }
    }
    
    finish() {
        this.isTyping = false;
        if (this.config.showCursor) {
            // Remove cursor at the end
            this.element.textContent = this.originalText;
        }
        this.element.classList.remove('ba-typewriter-active');
    }
    
    destroy() {
        if (this.timeoutId) {
            clearTimeout(this.timeoutId);
        }
        this.isTyping = false;
        this.element.textContent = this.originalText;
        this.element.classList.remove('ba-typewriter-active');
    }
    
    restart() {
        this.destroy();
        this.currentIndex = 0;
        this.init();
    }
}

// Auto-initialize on DOM ready
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all elements with ba-typewriter class
    const typewriterElements = document.querySelectorAll('.ba-typewriter');
    
    typewriterElements.forEach(element => {
        // Get configuration from data attributes
        const config = {
            speed: parseInt(element.dataset.typewriterSpeed) || 150,
            pauseEnd: parseInt(element.dataset.typewriterPauseEnd) || 1000,
            pauseStart: parseInt(element.dataset.typewriterPauseStart) || 500,
            cursor: element.dataset.typewriterCursor || '|',
            showCursor: element.dataset.typewriterShowCursor !== 'false',
            loop: element.dataset.typewriterLoop !== 'false'
        };
        
        // Store instance on element for potential later access
        element.typewriterInstance = new TypewriterBorderAnimation(element, config);
    });
});

// Export for manual usage
window.TypewriterBorderAnimation = TypewriterBorderAnimation;
