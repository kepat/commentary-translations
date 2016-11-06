<!-- javascript used for this page -->
<script>

    // Main function that modify the table contents
    function loadData(page) {
        // Display the loading modal
        $('.modal-loading-main').show();

        var record_size = $('#record_size').val();
        var record_search = $('#record_search').val();

        // Get the table body and recreate it
        var body = $('#table-body');
        body.html('');

        // Get the additional details and clear it
        var record_details = $('#record_details');
        var record_navigation = $('#record_navigation');

        var users_api = '/api/users/' + record_size + '?';

        // If search record exists then add
        if (record_search != '') {
            users_api = users_api + '&search=' + record_search;
        }

        // Check if page number is existing
        if (typeof page !== 'undefined') {
            users_api = users_api + '&page=' + page;
        }

        // Get the users data
        $.get(users_api, function (result) {

            // Store the data to a variable
            var data = result['data'];

            var row_data = '';

            // Display the retrieved users
            for (var i = 0; i < data.length; i++) {

                row_data = '<tr>';

                // Create the row with the needed details
                row_data = row_data + '<td>' + data[i]['email'] + '</td>';
                row_data = row_data + '<td>' + data[i]['username'] + '</td>';
                row_data = row_data + '<td>' + data[i]['role']['name'] + '</td>';
                row_data = row_data + '<td>' + data[i]['last_name'] + ', ' + data[i]['first_name'] + '</td>';

                // Check if the user is a driver or customer
                if (data[i]['driver'] != null) {
                    row_data = row_data + '<td>' + data[i]['driver']['code'] + '</td>';
                } else if (data[i]['customer'] != null) {
                    row_data = row_data + '<td>' + data[i]['customer']['code'] + '</td>';
                } else {
                    row_data = row_data + '<td> N/A </td>';
                }

                // Add the actions
                row_data = row_data + '<td>'

                row_data = row_data + '<a href="users/' + data[i]['id'] + '" class="button-margin"><button type="button" class="btn btn-default"><span class="pull-left btn-icon"><i class="fa fa-tasks"></i></span>View</button></a>';
                row_data = row_data + '<a href="users/' + data[i]['id'] + '/edit" class="button-margin"><button type="button" class="btn btn-default"><span class="pull-left btn-icon"><i class="fa fa-pencil"></i></span>Edit</button></a>';
                row_data = row_data + '<a href="users/' + data[i]['id'] + '/password" class="button-margin"><button type="button" class="btn btn-default"><span class="pull-left btn-icon"><i class="fa fa-lock"></i></span>Change Password</button></a>';

                row_data = row_data + '</td></tr>';

                body.append(row_data);

            }

            // Check if there is no data then display something
            if (data.length < 1) {

                row_data = '<tr><td colspan="6" class="text-center">No Available Data.</td></tr>';

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
    $('#record_size').on('change', function () {
        loadData();
    });

    // Re-populate the table when search value is changed
    $('#record_search').on('change', function () {
        loadData();
    });

    // Populate the table on initial opening of page
    $(document).ready(function () {
        loadData();
    });

</script>