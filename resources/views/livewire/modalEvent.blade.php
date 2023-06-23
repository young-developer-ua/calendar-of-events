<div id="myModalEvent" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Event</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="error" style="display: none"></div>
            <form id="myFormEvent" method="POST" action="{{ route('events.update') }}">
                <input type="hidden" name="_token" id="csrf-token" value="{{ csrf_token() }}"/>
                <input type="number" hidden value="0" name="isReminder"/>
                <div class="modal-body">
                    <input type="number" class="form-control" hidden id="id" name="eventId">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" id="title" name="title">
                    </div>
                    <div class="form-group">
                        <label for="color" class="form-label">Color</label>
                        <input type="color" class="form-control form-control-color" id="color" name="color"
                               value="#563d7c" title="Choose your color">
                    </div>
                    <div class="form-group">
                        <label for="startDate">Start:</label>
                        <input type="datetime-local" class="form-control" id="startDate" name="startDate">
                    </div>
                    <div class="form-group">
                        <label for="endDate">End:</label>
                        <input type="datetime-local" class="form-control" id="endDate" name="endDate">
                    </div>
                    <div class="form-group">
                        <label for="isDone">Is done:</label>
                        <label class="switch">
                            <input type="checkbox" id="isDone" name="isDone">
                            <span class="slider round"></span>
                        </label><br><br>
                    </div>
                </div>
                <div id="modalFooter" class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>

        </div>
    </div>
</div>