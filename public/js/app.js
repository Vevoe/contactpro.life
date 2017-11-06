/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(1);
module.exports = __webpack_require__(2);


/***/ }),
/* 1 */
/***/ (function(module, exports) {

window.contactsPro = {
    baseFormValidation: {
        highlight: function highlight(element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function unhighlight(element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function errorPlacement(error, element) {
            if (element.parent('.input-group').length) {
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
    buildCustomFieldsString: function buildCustomFieldsString(customFields) {
        var customFieldsString = '';
        var customFieldLength = customFields.length - 1;
        $.each(customFields, function (key, value) {
            if (key == customFieldLength) {
                customFieldsString += value.value;
            } else {
                customFieldsString += value.value + ', ';
            }
        });

        return customFieldsString;
    },

    createFormSubmit: {
        submitHandler: function submitHandler(form) {
            var data = $(form).serialize();

            $.ajax({
                url: '/api/contacts',
                method: 'POST',
                data: data
            }).done(function (res) {
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
            }).fail(function (err) {
                var errorObject = {};
                $.each(err.responseJSON.errors, function (key, value) {
                    errorObject[key] = value;
                });

                var validator = $("#createContactForm").validate();
                validator.showErrors(errorObject);

                // Internal Error, probably with Active Campaign
                if (err.status >= 500) {
                    alert(err.responseJSON.message);
                }
            });
        }
    },

    updateFormSubmit: {
        submitHandler: function submitHandler(form) {
            var data = $(form).serialize();
            var conactId = $(form).find('input[name="id"]').val();

            $.ajax({
                url: '/api/contacts/' + conactId,
                method: 'PUT',
                data: data
            }).done(function (res) {
                var customFieldsString = window.contactsPro.buildCustomFieldsString(res.data.customFields);

                $('table tr[contactRow="' + res.data.id + '"]').find('td[name]').html(res.data.name);
                $('table tr[contactRow="' + res.data.id + '"]').find('td[surname]').html(res.data.surname);
                $('table tr[contactRow="' + res.data.id + '"]').find('td[email]').html(res.data.email);
                $('table tr[contactRow="' + res.data.id + '"]').find('td[phone]').html(res.data.phone);
                $('table tr[contactRow="' + res.data.id + '"]').find('td[customFields]').html(customFieldsString);

                $('#updateContactModal').modal('hide');
            }).fail(function (err) {
                var errorObject = {};
                $.each(err.responseJSON.errors, function (key, value) {
                    errorObject[key] = value;
                });

                var validator = $("#createContactForm").validate();
                validator.showErrors(errorObject);

                // Internal Error, probably with Active Campaign
                if (err.status >= 500) {
                    alert(err.responseJSON.message);
                }
            });
        }
    }
};

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
    $(document).on('click', 'button[add-custom-field]', function () {
        var formId = $(this).closest('form').attr('id');
        var customFieldCount = $('#' + formId + ' input[name="customFields[]"]').length;

        if (customFieldCount < 5) {
            var customFieldTemplate = _.template($('#customFieldTemplate').html());
            $('#' + formId + ' .custom-fields-container').append(customFieldTemplate({ value: '' }));
        }
    });

    // Remove custom Field
    $(document).on('click', 'button[remove-custom-field]', function () {
        var formId = $(this).closest('form').attr('id');
        var customFieldCount = $('#' + formId + ' input[name="customFields[]"]').length;

        if (customFieldCount > 1) {
            $(this).closest('.form-group').remove();
        }
    });

    // Hide modal, reset form.
    $('button[cancel-contact]').on('click', function () {
        var formId = $(this).closest('form').attr('id');

        // createContactForm has am empty customField by default, editCreateForm does not
        if (formId === 'createContactForm') {
            $('#' + formId + ' .custom-fields-container > .form-group').not(':first').remove();
        } else {
            $('#' + formId + ' .custom-fields-container > .form-group').remove();
        }

        $('#' + formId).trigger('reset');
    });

    // Edit Contact
    $(document).on('click', '.contacts-container table button[editContact]', function () {
        $.ajax({
            url: '/api/contacts/' + $(this).attr('editContact'),
            method: 'GET'
        }).done(function (res) {
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
                    $('#updateContactForm .custom-fields-container').append(customField({ value: value.value }));
                });
            } else {
                $('#updateContactForm .custom-fields-container').append(customField({ value: '' }));
            }

            $('#updateContactModal').modal('show');
        }).fail(function (err) {
            console.log("Error here: ", err);
        });
    });

    // Form Validation/Submit
    $("#createContactForm").validate($.extend({}, window.contactsPro.baseFormValidation, window.contactsPro.createFormSubmit));
    $("#updateContactForm").validate($.extend({}, window.contactsPro.baseFormValidation, window.contactsPro.updateFormSubmit));

    // Delete Contact
    $(document).on('click', 'table button[delete-contact-button]', function (e) {
        e.preventDefault();
        $('button[delete-contact-id]').attr('delete-contact-id', $(this).attr('delete-contact-button'));
    });

    $('button[delete-contact-id]').on('click', function () {
        $('table button[delete-contact-button="' + $(this).attr('delete-contact-id') + '"]').parent().submit();
    });
});

/***/ }),
/* 2 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ })
/******/ ]);