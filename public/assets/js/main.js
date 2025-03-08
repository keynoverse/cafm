document.addEventListener('DOMContentLoaded', function() {
    // Toggle Sidebar
    const toggleSidebar = document.querySelector('.toggle-sidebar');
    const sidebar = document.querySelector('.sidebar');
    
    if (toggleSidebar) {
        toggleSidebar.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        if (window.innerWidth <= 768) {
            if (!sidebar.contains(event.target) && !toggleSidebar.contains(event.target)) {
                sidebar.classList.remove('active');
            }
        }
    });

    // Initialize tooltips
    const tooltips = document.querySelectorAll('[data-tooltip]');
    tooltips.forEach(element => {
        element.addEventListener('mouseenter', function() {
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            tooltip.textContent = this.getAttribute('data-tooltip');
            document.body.appendChild(tooltip);

            const rect = this.getBoundingClientRect();
            tooltip.style.top = rect.top - tooltip.offsetHeight - 5 + 'px';
            tooltip.style.left = rect.left + (rect.width - tooltip.offsetWidth) / 2 + 'px';
        });

        element.addEventListener('mouseleave', function() {
            const tooltip = document.querySelector('.tooltip');
            if (tooltip) {
                tooltip.remove();
            }
        });
    });

    // Initialize charts
    const charts = document.querySelectorAll('canvas[data-chart]');
    charts.forEach(canvas => {
        const chartData = JSON.parse(canvas.getAttribute('data-chart'));
        new Chart(canvas, chartData);
    });

    // Form validation
    const forms = document.querySelectorAll('form[data-validate]');
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            let isValid = true;
            const requiredFields = form.querySelectorAll('[required]');

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('error');
                } else {
                    field.classList.remove('error');
                }
            });

            if (!isValid) {
                event.preventDefault();
                alert('Please fill in all required fields.');
            }
        });
    });

    // File upload preview
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            const preview = this.nextElementSibling;
            if (preview && preview.classList.contains('file-preview')) {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            }
        });
    });

    // Dynamic table sorting
    const tables = document.querySelectorAll('table[data-sortable]');
    tables.forEach(table => {
        const headers = table.querySelectorAll('th[data-sort]');
        headers.forEach(header => {
            header.addEventListener('click', function() {
                const column = this.getAttribute('data-sort');
                const rows = Array.from(table.querySelectorAll('tbody tr'));
                const direction = this.getAttribute('data-direction') === 'asc' ? -1 : 1;

                rows.sort((a, b) => {
                    const aValue = a.querySelector(`td[data-${column}]`).textContent;
                    const bValue = b.querySelector(`td[data-${column}]`).textContent;
                    return direction * aValue.localeCompare(bValue);
                });

                const tbody = table.querySelector('tbody');
                tbody.innerHTML = '';
                rows.forEach(row => tbody.appendChild(row));

                this.setAttribute('data-direction', direction === 1 ? 'asc' : 'desc');
            });
        });
    });

    // AJAX form submission
    const ajaxForms = document.querySelectorAll('form[data-ajax]');
    ajaxForms.forEach(form => {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            const url = this.getAttribute('action');

            fetch(url, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('success', data.message);
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                } else {
                    showNotification('error', data.message);
                }
            })
            .catch(error => {
                showNotification('error', 'An error occurred. Please try again.');
            });
        });
    });

    // Notification system
    function showNotification(type, message) {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.textContent = message;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.classList.add('show');
        }, 100);

        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }

    // Search functionality
    const searchInput = document.querySelector('.search-box input');
    if (searchInput) {
        let timeout = null;
        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                const query = this.value.trim();
                if (query.length >= 2) {
                    fetch(`/api/search?q=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(data => {
                            // Handle search results
                            console.log(data);
                        });
                }
            }, 300);
        });
    }
}); 