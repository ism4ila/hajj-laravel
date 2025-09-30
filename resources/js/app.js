// Import Bootstrap JavaScript
import * as bootstrap from 'bootstrap';

// Make Bootstrap available globally
window.bootstrap = bootstrap;

// Import Alpine.js
import Alpine from 'alpinejs';

// Make Alpine available globally
window.Alpine = Alpine;

// Initialize Alpine
Alpine.start();

// Custom JavaScript for Hajj Management System
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips with error handling
    try {
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
    } catch (error) {
        console.log('Bootstrap Tooltip initialization failed:', error);
    }

    // Initialize popovers with error handling
    try {
        if (typeof bootstrap !== 'undefined' && bootstrap.Popover) {
            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });
        }
    } catch (error) {
        console.log('Bootstrap Popover initialization failed:', error);
    }

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert-floating');
        alerts.forEach(function(alert) {
            try {
                if (typeof bootstrap !== 'undefined' && bootstrap.Alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            } catch (error) {
                console.log('Bootstrap Alert failed:', error);
            }
        });
    }, 5000);
});

// Alpine.js data components
document.addEventListener('alpine:init', () => {
    // Pagination component
    Alpine.data('pagination', () => ({
        currentPage: 1,
        totalPages: 1,
        perPage: 15,
        goToPage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.currentPage = page;
                // Reload data or navigate
                window.location.href = `?page=${page}`;
            }
        }
    }));

    // Table row selection
    Alpine.data('tableSelection', () => ({
        selectedRows: [],
        selectAll: false,
        toggleSelectAll() {
            if (this.selectAll) {
                this.selectedRows = Array.from(document.querySelectorAll('input[name="selected_rows[]"]'))
                    .map(input => input.value);
            } else {
                this.selectedRows = [];
            }
        },
        toggleRow(id) {
            if (this.selectedRows.includes(id)) {
                this.selectedRows = this.selectedRows.filter(rowId => rowId !== id);
            } else {
                this.selectedRows.push(id);
            }
            this.selectAll = this.selectedRows.length === document.querySelectorAll('input[name="selected_rows[]"]').length;
        }
    }));

    // Form wizard
    Alpine.data('formWizard', (totalSteps) => ({
        currentStep: 1,
        totalSteps: totalSteps,
        nextStep() {
            if (this.currentStep < this.totalSteps) {
                this.currentStep++;
            }
        },
        previousStep() {
            if (this.currentStep > 1) {
                this.currentStep--;
            }
        },
        goToStep(step) {
            if (step >= 1 && step <= this.totalSteps) {
                this.currentStep = step;
            }
        }
    }));

    // File upload with preview
    Alpine.data('fileUpload', () => ({
        files: [],
        dragOver: false,
        handleFiles(event) {
            const files = Array.from(event.target.files || event.dataTransfer.files);
            files.forEach(file => {
                if (file.type.startsWith('image/') || file.type === 'application/pdf') {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.files.push({
                            name: file.name,
                            size: file.size,
                            type: file.type,
                            preview: e.target.result,
                            file: file
                        });
                    };
                    reader.readAsDataURL(file);
                }
            });
        },
        removeFile(index) {
            this.files.splice(index, 1);
        },
        formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
    }));
});
