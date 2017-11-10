window.contactsPro = {
    baseFormValidation: {
        highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function(error, element) {
            if(element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },  
        rules: {
            name: {
              required: true,
              maxlength: 255
            },
            surname: {
              required: true,
              maxlength: 255
            },
            email: {
              required: true,
              maxlength: 255
            },
            phone: {
              required: true,
              maxlength: 255,
              number: true
            }
        }
    },

    // Build a string of the CustomField values to insert into
    // the table
    buildCustomFieldsString: function (customFields) {
        var customFieldsString = '';
        var customFieldLength = (customFields.length - 1);
        $.each(customFields, function(key, value) {
            if (key == customFieldLength) {
                customFieldsString += value.value;
            } else {
                customFieldsString += value.value + ', ';
            }
        });

        return customFieldsString;
    },

    createFormSubmit: {
        submitHandler: function(form) {
            var data = $(form).serialize();
        
            $.ajax({
                url: '/api/contacts',
                method: 'POST',
                data: data
            })
            .done(function (res) {
                // Close the modal and append the new contact information
                $('#createContactModal').modal('hide');

                var customFieldsString = window.contactsPro.buildCustomFieldsString(res.data.customFields);
                var customField = _.template($('#newRowTemplate').html());
                $('table tbody').append(customField({
                    id: res.data.id,
                    name: res.data.name,
                    surname: res.data.surname,
                    email: res.data.email,
                    phone: res.data.phone,
                    customFields: customFieldsString
                }));

                // If there were no contacts before, hide the no contact message and
                // show the contacts container.
                $('.no-contacts').addClass('hidden');
                $('.contacts-container').removeClass('hidden');
                $('#createContactForm .custom-fields-container > .form-group').not(':first').remove();
                $('#createContactForm').trigger('reset');

                $.toast({
                    heading: 'Success!',
                    text: 'Conatct has been created.',
                    position: 'top-right',
                    icon: 'success'
                });
            })
            .fail(function (err) {
                var errorObject = {}
                $.each(err.responseJSON.errors, function(key, value){
                    errorObject[key] = value;
                });

                var validator = $("#createContactForm").validate();
                validator.showErrors(errorObject);

                // Internal Error. Show generic error message and preferably
                // log to some sort of logging service
                if (err.status >= 500) {
                    $.toast({
                        heading: 'Error!',
                        text: 'An Error as occurred. Please contact support.',
                        position: 'top-right',
                        icon: 'error'
                    });
                }
            });
        }
    },

    updateFormSubmit: {
        submitHandler: function(form) {
            var data = $(form).serialize();
            var conactId = $(form).find('input[name="id"]').val();

            $.ajax({
                url: '/api/contacts/' + conactId,
                method: 'PUT',
                data: data
            })
            .done(function (res) {                
                var customFieldsString = window.contactsPro.buildCustomFieldsString(res.data.customFields);

                $('table tr[contactRow="'+ res.data.id +'"]').find('td[name]').text(res.data.name);
                $('table tr[contactRow="'+ res.data.id +'"]').find('td[surname]').text(res.data.surname);
                $('table tr[contactRow="'+ res.data.id +'"]').find('td[email]').text(res.data.email);
                $('table tr[contactRow="'+ res.data.id +'"]').find('td[phone]').text(res.data.phone);
                $('table tr[contactRow="'+ res.data.id +'"]').find('td[customFields]').text(customFieldsString);
                               
                $('#updateContactModal').modal('hide');
                $('#updateContactForm .custom-fields-container > .form-group').remove();
                $('#updateContactForm').trigger('reset');

                $.toast({
                    heading: 'Success!',
                    text: 'Conatct has been updated.',
                    position: 'top-right',
                    icon: 'success'
                });
            })
            .fail(function (err) {
                var errorObject = {}
                $.each(err.responseJSON.errors, function(key, value){
                    errorObject[key] = value;
                });

                var validator = $("#updateContactForm").validate();
                validator.showErrors(errorObject);

                // Internal Error. Show generic error message and preferably
                // log to some sort of logging service
                if (err.status >= 500) {
                    $.toast({
                        heading: 'Error!',
                        text: 'An Error as occurred. Please contact support.',
                        position: 'top-right',
                        icon: 'error'
                    });
                }
            });
        }
    }
}


$(document).ready(function () {

    // Init 
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('table').tableable({
        'filter': {
            'filterInputSelector': '#searchBox',
            'ignoreCase': true
        }
    });


    /*
     * Handle Custom Fields
     */
    // Add custom Field
    $(document).on('click', 'button[add-custom-field]', function() {
        var formId = $(this).closest('form').attr('id');
        var customFieldCount = $('#'+ formId +' .custom-fields-container input').length;

        if (customFieldCount < 5) {
            var customFieldTemplate = _.template($('#customFieldTemplate').html());
            $('#'+ formId +' .custom-fields-container').append(customFieldTemplate({
                fieldName: ++customFieldCount,
                value: ''
            }));
        }
    });

    // Remove custom Field
    $(document).on('click', 'button[remove-custom-field]', function() {
        var formId = $(this).closest('form').attr('id');
        var customFieldCount = $('#'+ formId +' .custom-fields-container input').length;

        if (customFieldCount > 1) {
            $(this).closest('.form-group').remove();
        }
    });


    /**
     * Modal
     */
    // Hide modal, reset form.
    $('button[cancel-contact]').on('click', function() {
        var formId = $(this).closest('form').attr('id');

        // createContactForm has am empty customField by default, editCreateForm does not
        if (formId === 'createContactForm') {
            $('#'+ formId +' .custom-fields-container > .form-group').not(':first').remove();
        } else {
            $('#'+ formId +' .custom-fields-container > .form-group').remove();
        }

        $('#'+ formId).trigger('reset');
    })


    // Edit Contact
    $(document).on('click', '.contacts-container table button[editContact]', function () {
        $.ajax({
            url: '/api/contacts/' + $(this).attr('editContact'),
            method: 'GET'
        })
        .done(function (res) {
            // Populate form values
            $('#updateContactForm input[name="id"]').val(res.data.id);
            $('#updateContactForm input[name="name"]').val(res.data.name);
            $('#updateContactForm input[name="surname"]').val(res.data.surname);
            $('#updateContactForm input[name="email"]').val(res.data.email);
            $('#updateContactForm input[name="phone"]').val(res.data.phone);

            // Loop over customFields and add them to the form. If the contact
            // doesn't have any, add an empty by default.
            var customField = _.template($('#customFieldTemplate').html());
            if (res.data.customFields.length > 0) {
                $.each(res.data.customFields, function (key, value) {
                    $('#updateContactForm .custom-fields-container').append(customField({
                        fieldName: ++key,
                        value: value.value
                    }));
                });
            } else {
                $('#updateContactForm .custom-fields-container').append(customField({
                    fieldName: 1,
                    value: ''
                }));
            }

            $('#updateContactModal').modal('show');
        })
        .fail(function (err) {
            // Show message, preferably log to some sort of 
            // erorr logger.
            if (err.status >= 500) {
                $.toast({
                    heading: 'Error!',
                    text: 'An Error as occurred. Please contact support.',
                    position: 'top-right',
                    icon: 'error'
                });
            }
        })
    });


    // Form Validation/Submit
    $("#createContactForm").validate($.extend({}, window.contactsPro.baseFormValidation, window.contactsPro.createFormSubmit));
    $("#updateContactForm").validate($.extend({}, window.contactsPro.baseFormValidation, window.contactsPro.updateFormSubmit));


    // Delete Contact
    $(document).on('click', 'table button[delete-contact-button]', function (e){
        e.preventDefault();
        $('button[delete-contact-id]').attr('delete-contact-id', $(this).attr('delete-contact-button'));
    });

    $('button[delete-contact-id]').on('click', function () {
        $('table button[delete-contact-button="'+ $(this).attr('delete-contact-id') +'"]').parent().submit();
    });
});
