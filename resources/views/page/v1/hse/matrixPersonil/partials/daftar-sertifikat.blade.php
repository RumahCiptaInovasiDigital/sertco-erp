<div class="row">
    @foreach ($sertifikat as $item)
    @php
        $punya = $karyawan->sertifikat
            ->where('idSertifikat', $item->id_sertifikat)
            ->first();
    @endphp
    <div class="col-12">
        {{-- <form action="{{ route('v1.input-sertifikat.store', $item->id_sertifikat) }}" method="post" enctype="multipart/form-data">
            @csrf --}}
            <input class="field-nik d-none" id="nik_{{ $item->id_sertifikat }}">
            <div class="row">
                <div class="col-12 col-md-2">
                    <div class="form-group">
                        <label for="pic">PIC</label>
                        <input type="text" class="form-control" value="{{ $item->jabatan->name ?? '' }}" readonly>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="form-group">
                        <label for="name">Jenis Sertifikat</label>
                        <input type="text" class="form-control" value="{{ $item->name }}" readonly>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="form-group">
                        <label for="file_serti">File Sertifikat</label>
                        <input type="file" class="form-control departemen" name="file_serti" id="file_serti_{{ $item->id_sertifikat }}" value="{{ $item->name }}" accept=".pdf" {{ $punya ? 'disabled' : '' }}>
                    </div>
                </div>
                <div class="col-12 col-md-2">
                    <div class="form-group">
                        <label for="due_date">Due Date</label>
                        <input type="date" class="form-control departemen" name="due_date" id="due_date_{{ $item->id_sertifikat }}" value="{{ $punya->due_date ?? '' }}" {{ $punya ? 'disabled' : '' }}>
                    </div>
                </div>
                <div class="col-12 col-md-2 d-flex align-items-end">
                    <div class="form-group" id="action_area_{{ $item->id_sertifikat }}"> 

                
                        @if ($punya)
                            <i class="fas fa-check-square text-success"></i>
                        @else
                            @if (auth()->user()->jabatan == $item->jabatan->name || auth()->user()->jabatan == 'Administrator')                              
                                <button class="btn btn-md btn-success"
                                    id="btn_submit_{{ $item->id_sertifikat }}"
                                    onclick="SimpanData('{{ $item->id_sertifikat }}')">
                                    Submit
                                </button>
                            @else
                                <button class="btn btn-md btn-success" disabled>
                                    Submit
                                </button>
                            @endif
                    
                        @endif
                    </div>
                </div>
                
            </div>
        {{-- </form> --}}
    </div>
    @endforeach
</div>