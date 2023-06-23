<div id="myModalReminder" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reminder</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="error" style="display: none"></div>
            <form id="myFormReminder" method="POST" action="{{ route('events.update') }}">
                <input type="hidden" name="_token" id="csrf-token" value="{{ csrf_token() }}"/>
                <input type="number" hidden value="1" name="isReminder"/>
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
                        <label for="recurrenceType">Reminder type select:</label>
                        <select id="recurrenceType" name="recurrenceType" class="form-control">
                            @foreach(\App\Models\Event::TYPES as $type)
                                <option value="{{$type}}">{{ strtoupper($type) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="weekly" class="form-group">
                        <div class="weekDays-selector">
                            <input type="checkbox" id="weekday-mon" class="weekday" value="1" name="weekly"/>
                            <label for="weekday-mon">M</label>
                            <input type="checkbox" id="weekday-tue" class="weekday" value="2" name="weekly"/>
                            <label for="weekday-tue">T</label>
                            <input type="checkbox" id="weekday-wed" class="weekday" value="3" name="weekly"/>
                            <label for="weekday-wed">W</label>
                            <input type="checkbox" id="weekday-thu" class="weekday" value="4" name="weekly"/>
                            <label for="weekday-thu">T</label>
                            <input type="checkbox" id="weekday-fri" class="weekday" value="5" name="weekly"/>
                            <label for="weekday-fri">F</label>
                            <input type="checkbox" id="weekday-sat" class="weekday" value="6" name="weekly"/>
                            <label for="weekday-sat">S</label>
                            <input type="checkbox" id="weekday-sun" class="weekday" value="0" name="weekly"/>
                            <label for="weekday-sun">S</label>
                        </div>
                    </div>
                    <div id="monthly" class="form-group">
                        <label class="form-label" for="monthlyDay">Day of month</label>
                        <input type="number" id="monthlyDay" name="monthlyDay" min="1" class="form-control"/>
                    </div>
                    <div id="yearly" class="form-group">
                        <label class="form-label" for="yearlyDay">Day of month</label>
                        <input type="number" id="yearlyDay" name="yearlyDay" min="1" class="form-control"/>
                        <label class="form-label" for="yearlyMonth">Month</label>
                        <input type="number" id="yearlyMonth" name="yearlyMonth" min="1" max="12" class="form-control"/>
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
                    <button type="submit" class="btn btn-primary right">Save changes</button>
                </div>
            </form>

        </div>
    </div>
</div>

<style>
    #weekly, #monthly, #yearly {
        display: none;
    }

    .weekDays-selector input {
        display: none !important;
    }

    .weekDays-selector input[type=checkbox] + label {
        display: inline-block;
        border-radius: 6px;
        background: #dddddd;
        height: 40px;
        width: 30px;
        margin-right: 3px;
        line-height: 40px;
        text-align: center;
        cursor: pointer;
    }

    .weekDays-selector input[type=checkbox]:checked + label {
        background: #2AD705;
        color: #ffffff;
    }
</style>

<script>
    let enter = button => button.classList.toggle("bg-rose");
    let leave = button => button.classList.toggle("bg-rose");
</script>