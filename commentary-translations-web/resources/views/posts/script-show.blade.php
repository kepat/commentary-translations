<!-- javascript used for this page -->
<script>

   // Run the javascript one the page is ready
    $(document).ready(function () {

        update_content();

        function update_content(){
            $('.modal-loading-main').show();

            // Language ID
            var language_id = $('#languages').val();

            // Get the table body and recreate it
            var body = $('#translated_language');
            body.html('');

            var api = '/api/posts/' + language_id;

            // Get the users data
            $.get(api, function (result) {

                 // Store the data to a variable
                var data = result['data'];

                body.html(data['content']);

                $('.modal-loading-main').hide();

            });
        }

        // Re-populate the table when size is changed
        $('#languages').on('change', function () {

            update_content();

        });
    });

</script>