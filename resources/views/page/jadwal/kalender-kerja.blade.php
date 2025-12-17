@extends('layouts.master')
@section('title', 'Kalender Kerja')
@section('PageTitle', 'Kalender Kerja')

@section('head')
    <!-- FullCalendar -->
    <link rel="stylesheet" href="{{ asset('plugins/fullcalendar/main.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}">
@endsection

@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('v1.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">Kalender Kerja</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="sticky-top mb-3">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Keterangan</h4>
                    </div>
                    <div class="card-body">
                        <div id="external-events">
                            <p><i class="fas fa-square" style="color: #dc3545;"></i> Libur</p>
                            <p><i class="fas fa-square" style="color: #28a745;"></i> Kegiatan</p>
                            <p><i class="fas fa-square" style="color: #007bff;"></i> Info</p>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Aksi</h3>
                    </div>
                    <div class="card-body">
                        <button id="import-holidays-btn" class="btn btn-block btn-info">
                            <i class="fas fa-download"></i> Import Hari Libur Nasional
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card card-primary">
                <div class="card-body p-0">
                    <!-- THE CALENDAR -->
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah/Edit Acara -->
    <div class="modal fade" id="modal-event" tabindex="-1" role="dialog" aria-labelledby="modalEventLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEventLabel">Form Acara</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-event">
                    <div class="modal-body">
                        <input type="hidden" id="event_id" name="id">
                        <div class="form-group">
                            <label for="title">Judul Acara</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="start">Waktu Mulai</label>
                            <input type="text" class="form-control" id="start" name="start" required>
                        </div>
                        <div class="form-group">
                            <label for="end">Waktu Selesai</label>
                            <input type="text" class="form-control" id="end" name="end">
                        </div>
                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description"></textarea>
                        </div>

                        <div class="form-group">
                            <label>Jenis Acara</label>
                            <div class="form-check">
                                <input class="form-check-input event-type" type="radio" name="event_type" id="type_libur" value="#dc3545">
                                <label class="form-check-label" for="type_libur">Libur</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input event-type" type="radio" name="event_type" id="type_kegiatan" value="#28a745">
                                <label class="form-check-label" for="type_kegiatan">Kegiatan</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input event-type" type="radio" name="event_type" id="type_info" value="#007bff">
                                <label class="form-check-label" for="type_info">Info</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input event-type" type="radio" name="event_type" id="type_custom" value="custom" checked>
                                <label class="form-check-label" for="type_custom">Lainnya (Pilih Warna)</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="color">Warna</label>
                            <div class="input-group" id="color-picker">
                                <input type="text" class="form-control" id="color" name="color">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-square"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="all_day" name="all_day">
                                <label class="form-check-label" for="all_day">Sepanjang Hari</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                        <div>
                            <button type="button" id="delete-event-btn" class="btn btn-danger" style="display: none;">Hapus</button>
                            <button type="submit" id="submit-event-btn" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Moment -->
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <!-- FullCalendar -->
    <script src="{{ asset('plugins/fullcalendar/main.js') }}"></script>
    <!-- Daterange picker -->
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Bootstrap Color Picker -->
    <script src="{{ asset('plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

            // Initialize plugins
            $('#start, #end').daterangepicker({
                timePicker: true,
                singleDatePicker: true,
                timePicker24Hour: true,
                timePickerSeconds: true,
                showDropdowns: true,
                locale: {
                    format: 'YYYY-MM-DD HH:mm:ss',
                    cancelLabel: 'Clear'
                }
            });
            $('#start, #end').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });


            $('#color-picker').colorpicker();
            $('#color-picker').on('colorpickerChange', function(event) {
                $(this).find('.fa-square').css('color', event.color.toString());
            });

            const typeColors = {
                '#dc3545': 'type_libur',
                '#28a745': 'type_kegiatan',
                '#007bff': 'type_info'
            };

            // Handle event type radio button changes
            $('.event-type').on('change', function() {
                var value = $(this).val();
                if (value === 'custom') {
                    $('#color').prop('disabled', false);
                    $('#color-picker').colorpicker('enable');
                } else {
                    $('#color').prop('disabled', true);
                    $('#color-picker').colorpicker('disable');
                    $('#color').val(value);
                    $('#color-picker').colorpicker('setValue', value);
                }
            });


            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                themeSystem: 'bootstrap',
                events: '{{ route("presensi.master.kalender-kerja.events") }}',
                editable: true,
                droppable: true,
                dateClick: function(info) {
                    $('#form-event')[0].reset();
                    $('#event_id').val('');

                    var start = moment(info.date);
                    var end = moment(info.date).add(1, 'hour');

                    $('#start').data('daterangepicker').setStartDate(start);
                    $('#start').data('daterangepicker').setEndDate(start);
                    $('#end').data('daterangepicker').setStartDate(end);
                    $('#end').data('daterangepicker').setEndDate(end);

                    $('#all_day').prop('checked', info.allDay);
                    if(info.allDay) {
                        $('#end').val('');
                    }

                    $('#type_custom').prop('checked', true).trigger('change');
                    $('#color').val('#007bff'); // Default color
                    $('#color-picker').colorpicker('setValue', '#007bff');


                    $('#modalEventLabel').text('Tambah Acara Baru');
                    $('#delete-event-btn').hide();
                    $('#submit-event-btn').text('Simpan');
                    $('#modal-event').modal('show');
                },
                eventClick: function(info) {
                    $('#form-event')[0].reset();
                    $('#event_id').val(info.event.id);
                    $('#title').val(info.event.title);
                    $('#description').val(info.event.extendedProps.description);

                    var start = moment(info.event.start);
                    $('#start').data('daterangepicker').setStartDate(start);
                    $('#start').data('daterangepicker').setEndDate(start);

                    if (info.event.end) {
                        var end = moment(info.event.end);
                        $('#end').data('daterangepicker').setStartDate(end);
                        $('#end').data('daterangepicker').setEndDate(end);
                    } else {
                        $('#end').val('');
                    }

                    $('#all_day').prop('checked', info.event.allDay);

                    if (info.event.backgroundColor) {
                        const color = info.event.backgroundColor;
                        const typeId = typeColors[color.toLowerCase()];
                        if (typeId) {
                            $('#' + typeId).prop('checked', true).trigger('change');
                        } else {
                            $('#type_custom').prop('checked', true).trigger('change');
                            $('#color').val(color);
                            $('#color-picker').colorpicker('setValue', color);
                        }
                    } else {
                        $('#type_custom').prop('checked', true).trigger('change');
                        $('#color').val('');
                        $('#color-picker').colorpicker('setValue', '#ffffff');
                    }

                    $('#modalEventLabel').text('Edit Acara');
                    $('#delete-event-btn').show();
                    $('#submit-event-btn').text('Update');
                    $('#modal-event').modal('show');
                },
                eventDrop: function(info) {
                    var eventId = info.event.id;
                    var newStart = moment(info.event.start).format('YYYY-MM-DD HH:mm:ss');
                    var newEnd = info.event.end ? moment(info.event.end).format('YYYY-MM-DD HH:mm:ss') : null;

                    $.ajax({
                        url: '{{ url("presensi/master/kalender-kerja") }}/' + eventId,
                        type: 'PUT',
                        data: {
                            title: info.event.title,
                            start: newStart,
                            end: newEnd,
                            all_day: info.event.allDay,
                            description: info.event.extendedProps.description,
                            color: info.event.backgroundColor
                        },
                        success: function(response) {
                            Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Tanggal acara berhasil diperbarui.', timer: 1500, showConfirmButton: false });
                            calendar.refetchEvents();
                        },
                        error: function(xhr) {
                            Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Gagal memperbarui tanggal acara.' });
                            info.revert();
                        }
                    });
                }
            });

            calendar.render();

            // Form submission
            $('#form-event').on('submit', function(e) {
                e.preventDefault();
                var id = $('#event_id').val();
                var url = id ? '{{ url("presensi/master/kalender-kerja") }}/' + id : '{{ route("presensi.master.kalender-kerja.store") }}';
                var method = id ? 'PUT' : 'POST';

                $('#color').prop('disabled', false); // Re-enable before serializing

                $.ajax({
                    url: url,
                    method: method,
                    data: $(this).serialize(),
                    beforeSend: function() {
                        $('#submit-event-btn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');
                    },
                    success: function(response) {
                        $('#modal-event').modal('hide');
                        Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.success, timer: 1500, showConfirmButton: false });
                        calendar.refetchEvents();
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        var errorString = '';
                        $.each(errors, function(key, value) {
                            errorString += '<li>' + value + '</li>';
                        });
                        Swal.fire({ icon: 'error', title: 'Gagal!', html: '<ul>' + errorString + '</ul>'});
                    },
                    complete: function() {
                        $('#submit-event-btn').prop('disabled', false).text(id ? 'Update' : 'Simpan');
                        // Re-disable color input if a type is selected
                        if ($('.event-type:checked').val() !== 'custom') {
                            $('#color').prop('disabled', true);
                        }
                    }
                });
            });

            // Delete event
            $('#delete-event-btn').on('click', function() {
                var id = $('#event_id').val();
                if (id) {
                    Swal.fire({
                        title: 'Anda Yakin?',
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '{{ url("presensi/master/kalender-kerja") }}/' + id,
                                type: 'DELETE',
                                success: function(response) {
                                    $('#modal-event').modal('hide');
                                    Swal.fire({ icon: 'success', title: 'Dihapus!', text: response.success, timer: 1500, showConfirmButton: false });
                                    calendar.refetchEvents();
                                },
                                error: function(xhr) {
                                    Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Terjadi kesalahan saat menghapus data.' });
                                }
                            });
                        }
                    });
                }
            });

            // Import holidays
            $('#import-holidays-btn').on('click', function() {
                 $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Mengimpor...');
                $.ajax({
                    url: '{{ route("presensi.master.kalender-kerja.import-api") }}',
                    type: 'POST',
                    success: function(response) {
                        Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.success });
                        calendar.refetchEvents();
                    },
                    error: function(xhr) {
                        Swal.fire({ icon: 'error', title: 'Gagal!', text: xhr.responseJSON.error || 'Gagal mengimpor hari libur.' });
                    },
                    complete: function() {
                        $('#import-holidays-btn').prop('disabled', false).html('<i class="fas fa-download"></i> Import Hari Libur Nasional');
                    }
                });
            });
        });
    </script>
@endsection
