<!-- javascript used for this page -->
<script>

    // Display the needed details or hide them depending on the role
    $('#role').on('change', function () {
        clearCodes();
        details();
    });

    // Trigger the display on the initial load of the role
    $('#role').trigger('custom', details());

    // Clear the driver code and customer code
    function clearCodes() {
        $('#driver_code').val('');
        $('#customer_code').val('');
    }

    // Display the required details
    function details() {
        var value = $('#role option:selected').text();

        if (value.indexOf("Driver") > -1) {
            $('#driver-panel').show();
            $('#customer-panel').hide();
        } else if (value.indexOf("Customer") > -1) {
            $('#customer-panel').show();
            $('#driver-panel').hide();
        } else {
            $('#driver-panel').hide();
            $('#customer-panel').hide();
        }
    }

    // Global variable for the modal table type
    var record_settings = '';

    // Main function that modify the table contents
    function loadData(page) {
        // Display the loading modal
        $('.modal-loading-main').show();

        var record_size = $('#modal_record_size').val();
        var record_search = $('#modal_record_search').val();

        // Get the table body and table header then recreate it
        var header = $('#modal-table-header');
        var body = $('#modal-table-body');
        header.html('');
        body.html('');

        // Get the additional details and clear it
        var record_details = $('#modal_record_details');
        var record_navigation = $('#modal_record_navigation');

        var api = '';
        var table_headers = '';

        // Check the record needed
        if (record_settings == 'drivers') {

            // Set the api for the drivers
            api = '/api/drivers/' + record_size + '?';

            // Set the header details
            table_headers = '<tr><th>Actions</th><th>Code</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Mobile</th><th>Remarks</th></tr>'

        } else {

            // Set the api for the customers
            api = '/api/customers/' + record_size + '?';

            // Set the header details
            table_headers = '<tr><th>Actions</th><th>Code</th><th>Name</th><th>Address</th><th>Email</th><th>Mobile</th><th>Remarks</th></tr>'

        }

        // Display the header in the table
        header.append(table_headers);

        // If search record exists then add
        if (record_search != '') {
            api = api + '&search=' + record_search;
        }

        // Check if page number is existing
        if (typeof page !== 'undefined') {
            api = api + '&page=' + page;
        }

        // Get the  data
        $.get(api, function (result) {

            // Store the data to a variable
            var data = result['data'];

            var row_data = '';

            // Display the retrieved users
            for (var i = 0; i < data.length; i++) {

                row_data = '<tr>';

                // Check the data format
                if (record_settings == 'drivers') {

                    // Add the actions
                    row_data = row_data + '<td><button type="button" class="btn btn-default" data-model="select_driver" data-value="' + data[i]['code'] + '" data-dismiss="modal">Select</button></td>';

                    // Create the row with the needed details
                    row_data = row_data + '<td>' + data[i]['code'] + '</td>';
                    row_data = row_data + '<td>' + data[i]['first_name'] + '</td>';
                    row_data = row_data + '<td>' + data[i]['last_name'] + '</td>';
                    row_data = row_data + '<td>' + data[i]['email'] + '</td>';
                    row_data = row_data + '<td>' + data[i]['mobile'] + '</td>';
                    row_data = row_data + '<td>' + data[i]['remarks'] + '</td>';

                } else {

                    // Add the actions
                    row_data = row_data + '<td><button type="button" class="btn btn-default" data-model="select_customer" data-value="' + data[i]['code'] + '" data-dismiss="modal">Select</button></td>';

                    // Create the row with the needed details
                    row_data = row_data + '<td>' + data[i]['code'] + '</td>';
                    row_data = row_data + '<td>' + data[i]['name'] + '</td>';
                    row_data = row_data + '<td>' + data[i]['address'] + '</td>';
                    row_data = row_data + '<td>' + data[i]['email'] + '</td>';
                    row_data = row_data + '<td>' + data[i]['mobile'] + '</td>';
                    row_data = row_data + '<td>' + data[i]['remarks'] + '</td>';

                }

                row_data = row_data + '</tr>';

                body.append(row_data);

            }

            // Check if there is no data then display something
            if (data.length < 1) {

                row_data = '<tr><td colspan="7" class="text-center">No Available Data.</td></tr>';

                body.append(row_data);

            }

            // Display the record details and navigation details
            record_details.html('Showing ' + result['from'] + ' to ' + result['to'] + ' of ' + result['total'] + ' entries');
            record_navigation.html(result['rendered_html']);

            // Display the loading modal
            $('.modal-loading-main').hide();

            // Re-populate when moving to another page
            $('[data-model=page]').on('click', function () {
                var page = $(this).attr('data-value');
                loadData(page);
            });

        });
    }

    // Re-populate the table when size is changed
    $('#modal_record_size').on('change', function () {
        loadData();
    });

    // Re-populate the table when search value is changed
    $('#modal_record_search').on('change', function () {
        loadData();
    });


    // Populating driver
    $('[data-model=driver_table]').on('click', function () {

        // Get the title of the modal
        var element = $(this);
        var title = element.attr('data-title');

        // Set the title of the modal
        $('#modalLabel').html(title);

        // Set the settings and load the data
        record_settings = 'drivers';

        $('#modal_record_size').val('5');
        $('#modal_record_search').val('');

        loadData();

    });

    // Display the driver to the input
    $(document).on('click', '[data-model=select_driver]', function () {
        var element = $(this);
        var value = element.attr('data-value');

        $('#driver_code').val(value);
    });

    // Populating the customer
    $('[data-model=customer_table]').on('click', function () {

        // Get the title of the modal
        var element = $(this);
        var title = element.attr('data-title');

        // Set the title of the modal
        $('#modalLabel').html(title);

        // Set the settings and load the data
        record_settings = 'customers';

        $('#modal_record_size').val('5');
        $('#modal_record_search').val('');

        loadData();

    });

    // Display the customer to the input
    $(document).on('click', '[data-model=select_customer]', function () {
        var element = $(this);
        var value = element.attr('data-value');

        $('#customer_code').val(value);
    });

</script>