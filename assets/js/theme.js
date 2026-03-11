/**
 * Theme Switcher (Dark / Light)
 * 
 * - Checks localStorage for saved theme (default: light)
 * - Applies theme to <html> data-theme attribute
 * - Enables/disables dark.css stylesheet accordingly
 * - Provides global toggleTheme() function
 */

(function() {
    // Get current theme from localStorage or default to 'light'
    const currentTheme = localStorage.getItem('theme') || 'light';
    
    // Apply to root element
    document.documentElement.setAttribute('data-theme', currentTheme);
    
    // Enable/disable dark.css stylesheet
    const darkStylesheet = document.getElementById('dark-theme');
    if (darkStylesheet) {
        darkStylesheet.disabled = (currentTheme !== 'dark');
    }
})();

/**
 * Global function to toggle theme (called from sidebar/topnav)
 */
window.toggleTheme = function() {
    const html = document.documentElement;
    const currentTheme = html.getAttribute('data-theme');
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
    
    // Update attribute
    html.setAttribute('data-theme', newTheme);
    
    // Save preference
    localStorage.setItem('theme', newTheme);
    
    // Toggle dark.css
    const darkStylesheet = document.getElementById('dark-theme');
    if (darkStylesheet) {
        darkStylesheet.disabled = (newTheme !== 'dark');
    }
};