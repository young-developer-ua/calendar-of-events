<div>
    <div id='calendar-container' wire:ignore>
        <div id='calendar'></div>
    </div>
</div>
@include('livewire.modalEvent')
@include('livewire.modalReminder')
@push('scripts')
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css' rel='stylesheet'>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                themeSystem: 'bootstrap5',
                customButtons: {
                    createEvent: {
                        text: 'Create event',
                        click: function () {
                            $('#myFormEvent').trigger('reset');
                            $('#myFormEvent').attr('action', '/events/store');
                            $('#myModalEvent').modal()
                        }
                    },
                    createReminder: {
                        text: 'Create reminder',
                        click: function () {
                            $('#myFormReminder').trigger('reset');
                            $('#myFormReminder').attr('action', '/events/store');
                            $('#myModalReminder').modal()
                        }
                    },
                },
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'createEvent createReminder'
                },
                eventSources: [
                    '{{ route('events.get') }}'
                ],
                eventDidMount: function (data) {
                    if (data.event.extendedProps.isDone) {
                        data.el.getElementsByClassName('fc-event-title')[0].innerHTML =
                            "<s>"+data.event.title+"</s>"
                    }
                },
                editable: false,
                overlap: true,
                initialView: 'dayGridMonth',
                eventClick: function (info) {
                    if (info.event.extendedProps.isReminder) {
                        $('#myModalReminder').find('#id').val(info.event.id)
                        $('#myModalReminder').find('#title').val(info.event.title)
                        $('#myModalReminder').find('#color').val(info.event.borderColor)
                        $('#myModalReminder').find('#startDate').val(getDate(info.event.start))
                        $('#myModalReminder').find('#isDone').prop('checked', info.event.extendedProps.isDone)
                        $('#myModalReminder').find('#recurrenceType').val(info.event.extendedProps.recurrenceType)
                        $('#myModalReminder').find('#recurrenceType').trigger('change');
                        switch (info.event.extendedProps.recurrenceType) {
                            case 'weekly':
                                var data = info.event.extendedProps.recurrenceValue.split(',')
                                var weekdays = $('#myModalReminder').find('.weekday');
                                weekdays.each(function (key, item) {
                                    if (data.includes(item.value.toString())) {
                                        item.checked = '1'
                                    }
                                })
                                break
                            case 'monthly':
                                $('#myModalReminder').find('#monthlyDay').val(info.event.extendedProps.recurrenceValue)
                                break
                            case 'yearly':
                                var data = info.event.extendedProps.recurrenceValue.split('.')
                                $('#myModalReminder').find('#yearlyDay').val(data[0])
                                $('#myModalReminder').find('#yearlyMonth').val(data[1])
                                break
                            default:
                                break
                        }
                        $('#myModalReminder').modal()
                    } else {
                        $('#myModalEvent').find('#id').val(info.event.id)
                        $('#myModalEvent').find('#title').val(info.event.title)
                        $('#myModalEvent').find('#color').val(info.event.borderColor)
                        $('#myModalEvent').find('#startDate').val(getDate(info.event.start))
                        $('#myModalEvent').find('#isDone').prop('checked', info.event.extendedProps.isDone)
                        $('#myModalEvent').find('#endDate').val(info.event.end ? getDate(info.event.end) : getDate(info.event.start))
                        $('#myModalEvent').modal()
                    }
                },
                eventDrop: function (info) {
                    var data = {
                        "eventId": info.event.id,
                        "isReminder": info.event.extendedProps.isReminder,
                        "title": info.event.title,
                        "color": info.event.borderColor,
                        "startDate": getDate(info.event.start),
                        "recurrenceType": info.event.extendedProps.recurrenceType ?? null,
                        "recurrenceValue": info.event.extendedProps.recurrenceValue ?? null,
                        "isDone": info.event.extendedProps.isDone
                    }
                    if (!data.isReminder) {
                        data['endDate'] = info.event.end ? getDate(info.event.end) : getDate(info.event.start)
                    }
                    $.ajax({
                        type: 'POST',
                        url: '/events/update',
                        data: data,
                        success: function () {
                            calendar.refetchEvents()
                        },
                        error: function (data) {
                        }
                    });
                },
                datesSet: function (event) {
                }
            });

            function getDate(date) {
                const d = new Date(date);
                d.setMinutes(d.getMinutes() - d.getTimezoneOffset())
                return d.toISOString().slice(0, 16)
            }

            calendar.render();

            $("form").on("submit", function (e) {
                var dataString = $(this).serializeArray();
                var self = $(this);
                var data = {};
                dataString.forEach(function (item) {
                    if (item.name === 'weekly') {
                        data[item.name] = data[item.name] ? data[item.name] + ',' + item.value : item.value;
                    } else {
                        data[item.name] = item.value;
                    }
                });
                switch (data.recurrenceType) {
                    case 'weekly':
                        data['recurrenceValue'] = data.weekly
                        break
                    case 'monthly':
                        data['recurrenceValue'] = data.monthlyDay
                        break
                    case 'yearly':
                        data['recurrenceValue'] = data.yearlyDay + '.' + data.yearlyMonth
                        break
                    default:
                        data['recurrenceValue'] = null
                        break
                }
                if (!data.isDone) {
                    data['isDone'] = 0
                } else {
                    data['isDone'] = 1
                }
                console.log(data)
                delete data.weekly;
                delete data.monthlyDay;
                delete data.yearlyDay;
                delete data.yearlyMonth;
                $.ajax({
                    type: this.method,
                    url: this.action,
                    data: data,
                    success: function () {
                        self.closest('.modal').modal('hide')
                        self.trigger('reset');
                        calendar.refetchEvents()
                    },
                    error: function (data) {
                        var errors = data.responseJSON.errors;
                        errorsHtml = '<div class="alert alert-danger"><ul>';

                        Object.values(errors).forEach(function (item) {
                            errorsHtml += '<li>' + item[0] + '</li>'; //showing only the first error.
                        });
                        errorsHtml += '</ul></div>';

                        $(this).find('#error').html(errorsHtml);
                        $(this).find('#error').css('display', 'block')
                    }
                });
                e.preventDefault();
            });

        });

    </script>

    <script>
        $('#myModalReminder').find('select[name="recurrenceType"]').on('change', function () {
            var selectedVal = $(this).val();
            switch (selectedVal) {
                case 'monthly':
                    $('#myModalReminder').find('#weekly').hide();
                    $('#myModalReminder').find('#yearly').hide();
                    break;
                case 'weekly':
                    $('#myModalReminder').find('#monthly').hide();
                    $('#myModalReminder').find('#yearly').hide();
                    break;
                case 'yealry':
                    $('#myModalReminder').find('#monthly').hide();
                    $('#myModalReminder').find('#weekly').hide();
                    break;
                default:
                    $('#myModalReminder').find('#monthly').hide();
                    $('#myModalReminder').find('#weekly').hide();
                    $('#myModalReminder').find('#yearly').hide();
                    break;
            }
            if (selectedVal !== 'none') {
                $('#myModalReminder').find('#' + selectedVal).show();
            }
        });
    </script>
@endpush
<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 40px;
        height: 23px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 15px;
        width: 15px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked + .slider {
        background-color: #2196F3;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
        -webkit-transform: translateX(15px);
        -ms-transform: translateX(15px);
        transform: translateX(15px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 23px;
    }

    .slider.round:before {
        border-radius: 50%;
    }
</style>