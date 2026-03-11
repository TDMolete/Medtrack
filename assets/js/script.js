/**
 * MedTrack – General Utilities
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // ---------- Auto‑hide alerts after 5 seconds ----------
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.classList.add('fade');
            setTimeout(() => {
                alert.remove();
            }, 500);
        }, 5000);
    });

    // ---------- Smooth scroll for anchor links ----------
    document.querySelectorAll('a[href^="#"]:not([href="#"])').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

    // ---------- Bootstrap tooltips initialization ----------
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // ---------- Active sidebar link (already handled by PHP, but ensure no double highlight) ----------
    // Remove any active class from previous page if needed (Bootstrap handles it)
});

// Optional: Add a small console greeting (for fun)
console.log('🚀 MedTrack JS loaded – ready to help you manage health!');