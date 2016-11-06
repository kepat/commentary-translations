<!-- Modal -->
<div class="modal fade" id="table_modal" tabindex="-1" role="dialog" aria-labelledby="
" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="modalLabel"></h4>
            </div>
            <div class="modal-body" id="modalBody">

                <div class="panel-body">
                    <div class="panel-body-body">

                        <div class="row row-margin">
                            <div class="col-sm-6">
                                Show
                                <select id="modal_record_size" class="input-sm body-white">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                </select>
                                entries
                            </div>

                            <div class="col-sm-6">
                                <div class="float-right">
                                    Search:
                                    <input id="modal_record_search" type="search" class="border-solid input-sm"
                                           placeholder="Search" aria-controls="table">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-hover table-margin">
                                    <thead id="modal-table-header">
                                    </thead>
                                    <tbody id="modal-table-body">
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div id="modal_record_details" class="col-sm-6 display-entries-margin">
                            </div>
                            <div class="col-sm-6">
                                <div id="modal_record_navigation" class="float-right">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>