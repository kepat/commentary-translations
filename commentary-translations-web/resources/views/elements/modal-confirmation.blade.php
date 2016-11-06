<!-- Confirm Modal -->
<div class="modal fade" id="confirm_modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="modal-title"></h4>
            </div>
            <div class="modal-body" id="modal-body">
                <div class="modal-body-header" id="modal-body-header"></div>
                <div class="form-group">
                    {!! Form::label('deliveries_status') !!}
                    {!! Form::select('deliveries_map_status', ['new' => 'New', 'delayed' => 'Delayed', 'fail trip' => 'Fail Trip'], null, ['class' => 'form-control', 'id' => 'deliveries_map_status']) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="modal-submit">Yes</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
