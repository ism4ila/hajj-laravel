/* ============================================
   HAJJ MANAGEMENT - DATATABLES INITIALIZATION
   Configuration JavaScript pour DataTables responsive
   ============================================ */

// Configuration globale DataTables
$(document).ready(function() {

    // Configuration par défaut pour tous les DataTables
    $.extend(true, $.fn.dataTable.defaults, {
        // Langue française
        language: {
            "sEmptyTable": "Aucune donnée disponible dans le tableau",
            "sInfo": "Affichage de _START_ à _END_ sur _TOTAL_ entrées",
            "sInfoEmpty": "Affichage de 0 à 0 sur 0 entrées",
            "sInfoFiltered": "(filtré à partir de _MAX_ entrées au total)",
            "sInfoPostFix": "",
            "sInfoThousands": " ",
            "sLengthMenu": "Afficher _MENU_ entrées",
            "sLoadingRecords": "Chargement...",
            "sProcessing": "Traitement...",
            "sSearch": "Rechercher :",
            "sZeroRecords": "Aucune entrée correspondante trouvée",
            "oPaginate": {
                "sFirst": "Première",
                "sLast": "Dernière",
                "sNext": "Suivante",
                "sPrevious": "Précédente"
            },
            "oAria": {
                "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                "sSortDescending": ": activer pour trier la colonne par ordre décroissant"
            },
            "select": {
                "rows": {
                    "_": "%d lignes sélectionnées",
                    "0": "Cliquez sur une ligne pour la sélectionner",
                    "1": "1 ligne sélectionnée"
                }
            }
        },

        // Configuration responsive
        responsive: {
            details: {
                type: 'column',
                target: 'tr',
                renderer: function (api, rowIdx, columns) {
                    var data = $.map(columns, function (col, i) {
                        return col.hidden ?
                            '<li class="dtr-detail-item">' +
                                '<span class="dtr-title">' + col.title + ':</span> ' +
                                '<span class="dtr-data">' + col.data + '</span>' +
                            '</li>' :
                            '';
                    }).join('');

                    return data ? $('<ul class="dtr-details"/>').append(data) : false;
                }
            }
        },

        // Configuration de l'affichage
        dom: '<"dt-controls-responsive"<"dt-length-search"lf><"dt-buttons-wrapper"B>>rtip',

        // Pagination
        pageLength: 15,
        lengthMenu: [[10, 15, 25, 50, 100, -1], [10, 15, 25, 50, 100, "Tout"]],

        // Style
        searching: true,
        ordering: true,
        info: true,
        autoWidth: false,
        processing: true,

        // Classes Bootstrap 5
        pagingType: "full_numbers",

        // Configuration des boutons
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel me-1"></i> Excel',
                className: 'btn btn-success btn-sm dt-button',
                exportOptions: {
                    columns: ':visible:not(.no-export)'
                }
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf me-1"></i> PDF',
                className: 'btn btn-danger btn-sm dt-button',
                exportOptions: {
                    columns: ':visible:not(.no-export)'
                },
                customize: function(doc) {
                    doc.defaultStyle.fontSize = 9;
                    doc.styles.tableHeader.fontSize = 10;
                    doc.styles.tableHeader.fillColor = '#0d6efd';
                }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print me-1"></i> Imprimer',
                className: 'btn btn-info btn-sm dt-button',
                exportOptions: {
                    columns: ':visible:not(.no-export)'
                }
            },
            {
                extend: 'copy',
                text: '<i class="fas fa-copy me-1"></i> Copier',
                className: 'btn btn-secondary btn-sm dt-button',
                exportOptions: {
                    columns: ':visible:not(.no-export)'
                }
            }
        ],

        // Configuration responsive avancée
        columnDefs: [
            {
                className: 'dtr-control',
                orderable: false,
                targets: 0,
                visible: false,
                responsivePriority: 1
            }
        ],

        // Callbacks pour les événements
        drawCallback: function(settings) {
            // Réinitialiser les tooltips Bootstrap après chaque draw
            $('[data-bs-toggle="tooltip"]').tooltip();

            // Ajouter des classes Bootstrap aux éléments de pagination
            $('.dataTables_paginate .paginate_button').addClass('page-link');
            $('.dataTables_paginate .paginate_button.current').addClass('active');
            $('.dataTables_paginate .paginate_button.disabled').addClass('disabled');
        },

        initComplete: function(settings, json) {
            // Configuration finale après initialisation
            console.log('DataTable initialisé:', settings.sTableId);

            // Ajouter les classes CSS personnalisées
            $(this).addClass('table table-striped table-hover');

            // Optimisation responsive
            this.api().responsive.recalc();
        }
    });

    // ============================================
    // INITIALISATION AUTOMATIQUE DES DATATABLES
    // ============================================

    // Auto-initialisation pour les tableaux avec classe .datatable
    $('.datatable').each(function() {
        if (!$.fn.DataTable.isDataTable(this)) {
            $(this).DataTable();
        }
    });

    // ============================================
    // DATATABLES SPÉCIFIQUES PAR PAGE
    // ============================================

    // IMPORTANT: Les pages avec pagination Laravel ne doivent PAS utiliser DataTables
    // pour éviter les conflits. On active DataTables uniquement sur les pages
    // qui n'ont pas de pagination Laravel côté serveur.

    // DataTable pour la page clients (désactivé - utilise pagination Laravel)
    // if ($('#clientsTable').length) {
    //     initClientsDataTable();
    // }

    // DataTable pour la page pèlerins (désactivé - utilise pagination Laravel)
    // if ($('#pilgrimsTable').length) {
    //     initPilgrimsDataTable();
    // }

    // DataTable pour la page paiements (désactivé - utilise pagination Laravel)
    // if ($('#paymentsTable').length) {
    //     initPaymentsDataTable();
    // }

    // DataTable pour les rapports
    if ($('#reportsTable').length) {
        initReportsDataTable();
    }
});

// ============================================
// FONCTIONS D'INITIALISATION SPÉCIFIQUES
// ============================================

function initClientsDataTable() {
    $('#clientsTable').DataTable({
        responsive: true,
        columnDefs: [
            {
                targets: [0], // Colonne de sélection
                orderable: false,
                className: 'select-checkbox text-center',
                width: '50px'
            },
            {
                targets: [1], // Nom du client
                responsivePriority: 1,
                width: '200px'
            },
            {
                targets: [2], // Contact
                responsivePriority: 3,
                className: 'd-none d-md-table-cell'
            },
            {
                targets: [3], // Pèlerinages
                responsivePriority: 4,
                className: 'd-none d-lg-table-cell',
                width: '100px'
            },
            {
                targets: [4], // Statut
                responsivePriority: 2,
                width: '80px'
            },
            {
                targets: [5], // Actions
                responsivePriority: 1,
                orderable: false,
                className: 'text-center no-export',
                width: '120px'
            }
        ],
        order: [[1, 'asc']], // Tri par nom par défaut
        pageLength: 15,
        stateSave: true, // Sauvegarde des préférences utilisateur

        // Configuration de sélection multiple
        select: {
            style: 'multi',
            selector: 'td:first-child'
        },

        // Callback pour les actions
        drawCallback: function() {
            // Réinitialiser les événements après redraw
            initClientRowActions();
        }
    });
}

function initPilgrimsDataTable() {
    $('#pilgrimsTable').DataTable({
        responsive: true,
        columnDefs: [
            {
                targets: [0], // Sélection
                orderable: false,
                className: 'select-checkbox text-center',
                width: '50px'
            },
            {
                targets: [1], // Pèlerin
                responsivePriority: 1,
                width: '180px'
            },
            {
                targets: [2], // Client
                responsivePriority: 3,
                className: 'd-none d-md-table-cell'
            },
            {
                targets: [3], // Campagne
                responsivePriority: 4,
                className: 'd-none d-lg-table-cell'
            },
            {
                targets: [4], // Statut
                responsivePriority: 2,
                width: '100px'
            },
            {
                targets: [5], // Actions
                responsivePriority: 1,
                orderable: false,
                className: 'text-center no-export',
                width: '120px'
            }
        ],
        order: [[1, 'asc']],
        pageLength: 15,
        stateSave: true
    });
}

function initPaymentsDataTable() {
    $('#paymentsTable').DataTable({
        responsive: true,
        columnDefs: [
            {
                targets: [0], // Date
                responsivePriority: 1,
                width: '100px',
                type: 'date'
            },
            {
                targets: [1], // Pèlerin
                responsivePriority: 2,
                width: '150px'
            },
            {
                targets: [2], // Montant
                responsivePriority: 1,
                width: '120px',
                className: 'text-end'
            },
            {
                targets: [3], // Type
                responsivePriority: 3,
                className: 'd-none d-md-table-cell'
            },
            {
                targets: [4], // Statut
                responsivePriority: 2,
                width: '100px'
            },
            {
                targets: [5], // Actions
                responsivePriority: 1,
                orderable: false,
                className: 'text-center no-export',
                width: '100px'
            }
        ],
        order: [[0, 'desc']], // Tri par date décroissant
        pageLength: 20,
        stateSave: true
    });
}

function initReportsDataTable() {
    $('#reportsTable').DataTable({
        responsive: true,
        pageLength: 25,
        stateSave: true,
        // Configuration export avancée pour les rapports
        buttons: [
            {
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel me-1"></i> Excel Détaillé',
                className: 'btn btn-success btn-sm',
                title: 'Rapport Hajj Management - ' + new Date().toLocaleDateString('fr-FR')
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fas fa-file-pdf me-1"></i> PDF Complet',
                className: 'btn btn-danger btn-sm',
                orientation: 'landscape',
                pageSize: 'A4',
                title: 'Rapport Hajj Management',
                customize: function(doc) {
                    doc.defaultStyle.fontSize = 8;
                    doc.styles.tableHeader.fontSize = 9;
                    doc.styles.tableHeader.fillColor = '#0d6efd';
                    doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                }
            }
        ]
    });
}

// ============================================
// FONCTIONS UTILITAIRES
// ============================================

function initClientRowActions() {
    // Gestion des checkboxes de sélection
    $('.client-checkbox').off('change').on('change', function() {
        const table = $('#clientsTable').DataTable();
        const row = $(this).closest('tr');

        if (this.checked) {
            row.addClass('selected');
        } else {
            row.removeClass('selected');
        }

        updateBulkActionsVisibility();
    });

    // Select All checkbox
    $('#selectAll').off('change').on('change', function() {
        const table = $('#clientsTable').DataTable();
        const isChecked = this.checked;

        $('.client-checkbox:visible').prop('checked', isChecked);

        if (isChecked) {
            table.rows(':visible').nodes().to$().addClass('selected');
        } else {
            table.rows().nodes().to$().removeClass('selected');
        }

        updateBulkActionsVisibility();
    });
}

function updateBulkActionsVisibility() {
    const selectedCount = $('.client-checkbox:checked').length;
    const bulkActionsBar = $('#bulkActionsBar');
    const selectedCountSpan = $('#selectedCount');

    if (selectedCount > 0) {
        bulkActionsBar.show();
        selectedCountSpan.text(selectedCount);
    } else {
        bulkActionsBar.hide();
    }
}

// ============================================
// RESPONSIVE UTILS
// ============================================

// Recalculer les colonnes responsive au redimensionnement
$(window).on('resize', function() {
    $('.dataTable').each(function() {
        if ($.fn.DataTable.isDataTable(this)) {
            $(this).DataTable().responsive.recalc();
        }
    });
});

// ============================================
// EXPORT PERSONALISE
// ============================================

function exportToExcel(tableId) {
    const table = $(tableId).DataTable();
    const button = table.button('.buttons-excel');
    button.trigger();
}

function exportToPDF(tableId) {
    const table = $(tableId).DataTable();
    const button = table.button('.buttons-pdf');
    button.trigger();
}

function printTable(tableId) {
    const table = $(tableId).DataTable();
    const button = table.button('.buttons-print');
    button.trigger();
}

// ============================================
// AJAX REFRESH
// ============================================

function refreshDataTable(tableId) {
    if ($.fn.DataTable.isDataTable(tableId)) {
        $(tableId).DataTable().ajax.reload(null, false);
    }
}

// ============================================
// SEARCH GLOBAL
// ============================================

function globalSearch(searchTerm) {
    $('.dataTable').each(function() {
        if ($.fn.DataTable.isDataTable(this)) {
            $(this).DataTable().search(searchTerm).draw();
        }
    });
}

// ============================================
// DEBUG FUNCTIONS
// ============================================

function debugDataTable(tableId) {
    if ($.fn.DataTable.isDataTable(tableId)) {
        const table = $(tableId).DataTable();
        console.log('DataTable Info:', {
            id: tableId,
            rows: table.rows().count(),
            columns: table.columns().count(),
            responsive: table.responsive.hasHidden(),
            state: table.state()
        });
    }
}

// ============================================
// TOAST NOTIFICATIONS
// ============================================

function showDataTableToast(message, type = 'info') {
    const toastHtml = `
        <div class="toast align-items-center text-white bg-${type} border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-${type === 'success' ? 'check' : 'info'}-circle me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;

    // Ajouter le toast au container
    let toastContainer = $('#toast-container');
    if (!toastContainer.length) {
        toastContainer = $('<div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3"></div>');
        $('body').append(toastContainer);
    }

    const toast = $(toastHtml);
    toastContainer.append(toast);

    // Initialiser et afficher le toast
    const bsToast = new bootstrap.Toast(toast[0]);
    bsToast.show();

    // Supprimer après affichage
    toast.on('hidden.bs.toast', function() {
        $(this).remove();
    });
}

// Export des fonctions pour usage global
window.HajjDataTables = {
    init: {
        clients: initClientsDataTable,
        pilgrims: initPilgrimsDataTable,
        payments: initPaymentsDataTable,
        reports: initReportsDataTable
    },
    utils: {
        refresh: refreshDataTable,
        search: globalSearch,
        export: {
            excel: exportToExcel,
            pdf: exportToPDF,
            print: printTable
        },
        debug: debugDataTable,
        toast: showDataTableToast
    }
};