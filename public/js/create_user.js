$(document).ready(function() {
    // =============================
    // Employee Search (Google Style)
    // =============================

    $('#pc_user').select2({

        placeholder: '🔍 Search employee by name or ID...',

        allowClear: true,

        width: '100%',

        minimumInputLength: 1,

        templateResult: formatEmployee,

        templateSelection: formatEmployeeSelection,

        matcher: customMatcher

    });

    // Search by Name OR ID Number
    function customMatcher(params, data) {

        if ($.trim(params.term) === '') {
            return data;
        }

        if (typeof data.text === 'undefined') {
            return null;
        }

        let term = params.term.toLowerCase();

        let option = $(data.element);

        let employeeId = String(option.val()).toLowerCase();

        let employeeName = data.text.toLowerCase();

        if (
            employeeName.includes(term) ||
            employeeId.includes(term)
        ) {
            return data;
        }

        return null;
    }


    // Beautiful dropdown
    function formatEmployee(employee) {

        if (!employee.id) {
            return employee.text;
        }

        let option = $(employee.element);

        let department = option.data('department') || 'N/A';
        let designation = option.data('designation') || 'N/A';
        let company = option.data('company') || 'N/A';
        let location = option.data('location') || 'N/A';
        let workArea = option.data('work_area') || 'N/A';

        return $(`
            <div class="employee-result">

                <div class="employee-title">

                    <i class="fas fa-user-circle text-primary"></i>

                    <strong>${employee.text}</strong>

                </div>

                <div class="employee-details">

                    <span>
                        <i class="fas fa-id-card"></i>
                        ${employee.id}
                    </span>

                    <span>
                        <i class="fas fa-building"></i>
                        ${department}
                    </span>

                    <span>
                        <i class="fas fa-briefcase"></i>
                        ${designation}
                    </span>

                    <span>
                        <i class="fas fa-city"></i>
                        ${company}
                    </span>
                    <span>
                        <i class="fas fa-map-marker-alt"></i>
                        ${location}
                    </span>

                    <span>
                        <i class="fas fa-map-pin"></i>
                        ${workArea}
                    </span>
                </div>

            </div>
        `);

    }


    // Selected employee
    function formatEmployeeSelection(employee) {

        if (!employee.id) {
            return employee.text;
        }

        return employee.text;

    }


    // Auto focus search box
    $('#pc_user').on('select2:open', function () {

        setTimeout(function () {

            document.querySelector('.select2-search__field').focus();

        }, 50);

    });

    // Auto-fill employee fields
    $('#pc_user').on('change', function() {
        var selected = $(this).find(':selected');
        
        if (selected.val()) {
            var department = selected.data('department') || '';
            var designation = selected.data('designation') || '';
            var workArea = selected.data('work_area') || '';
            var location = selected.data('location') || '';
            var address = selected.data('address') || '';
            var company = selected.data('company') || '';
            
            $('#department').val(department);
            $('#job_title').val(designation);
            $('#location').val(workArea);
            $('#set_up').val(location);
            $('#address').val(address);
            $('#company').val(company);
        } else {
            $('#department').val('');
            $('#job_title').val('');
            $('#location').val('');
            $('#set_up').val('');
            $('#address').val('');
            $('#company').val('');
        }
    });

   // Auto-fill hardware asset previews with better status display
$('.asset-select').on('change', function() {
    var field = this.id;
    var selected = $(this).find(':selected');
    var preview = $('#preview_' + field);
    var previewText = $('#preview_text_' + field);
    var manualWrapper = $('#manual_' + field);
    var manualInput = $('#manual_text_' + field);
    
    if (selected.val() === 'manual') {
        // Show manual input
        manualWrapper.show();
        manualInput.focus();
        preview.hide();
        // Set the value to the manual input value
        manualInput.on('input', function() {
            if ($(this).val()) {
                previewText.text('Manual: ' + $(this).val());
                preview.show();
            } else {
                preview.hide();
            }
        });
    } else if (selected.val()) {
        // Hide manual input
        manualWrapper.hide();
        var brand = selected.data('brand') || '';
        var spec = selected.data('spec') || '';
        var status = selected.data('status') || '';
        
        // Map status to emoji
        var statusEmojis = {
            'Good Condition In-Stock': '✅',
            'Good Condition In-Use': '✅',
            'Defective/In-stock': '⚠️',
            'Defective/Sold': '⚠️',
            'Defective/Thrown': '⚠️',
            'For Repair': '🔧',
            'Obsolete/Stock': '📦',
            'Sold': '💰',
            'Missing': '❓',
            'For Testing': '🧪',
            'Return to Vendor': '🔄',
            'Under Warranty': '🛡️'
        };
        
        var statusEmoji = statusEmojis[status] || '📌';
        
        var info = selected.val();
        if (brand) info += ' - ' + brand;
        if (spec) info += ' - ' + spec;
        if (status) info += ' ' + statusEmoji + ' (' + status + ')';
        
        previewText.text(info);
        preview.show();
    } else {
        preview.hide();
    }
});
// On form submit, handle manual entries
$('#pcForm').on('submit', function() {
    $('.manual-input').each(function() {
        var field = $(this).attr('id').replace('manual_text_', '');
        var select = $('#' + field);
        var manualValue = $(this).val();
        
        if (select.val() === 'manual' && manualValue) {
            // Set the select value to the manual input
            select.append('<option value="' + manualValue + '" selected>' + manualValue + ' (Manual)</option>');
            select.val(manualValue);
        }
    });
});

    // Trigger on load for existing values
    if ($('#pc_user').val()) {
        $('#pc_user').trigger('change');
    }

    $('.asset-select').each(function() {
        if ($(this).val()) {
            $(this).trigger('change');
        }
    });

    // Auto-focus on PC Name field
    $('#pc_name').focus();

    // Confirm before leaving with unsaved changes
    let formChanged = false;
    const form = $('#pcForm');
    
    form.find('input:not([type="hidden"]), select, textarea').on('change input', function() {
        formChanged = true;
    });

    $(window).on('beforeunload', function(e) {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
            return e.returnValue;
        }
    });

    form.on('submit', function() {
        formChanged = false;
    });
});