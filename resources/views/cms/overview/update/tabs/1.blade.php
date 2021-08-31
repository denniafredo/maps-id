<form role="form" class="update-animal" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}
    <input type="hidden" name="ct" value="1">
    <div id="menu1" class="tab-pane fade tab-panel tab-menu active show" c="1" role="tabpanel" style="padding: 15px 35px">
        <div class=col-12>
            <div class="row">
                <div class="col-6" style="padding-left: 0px;">
                    <p style="font-weight: bold; font-size: 35px;">
                        Data Satwa
                    </p>
                </div>
                <div class="col-6">
                    <div class="row" style="float: right">
                        <button class="btn btn-success btn-update-data-animal" style="margin: 0px 5px;" uid="{{ $uid }}" p="2">Edit Persebaran <i class="fas fa-arrow-circle-right"></i></button>
                        <button class="btn btn-warning btn-update-data-animal" style="margin: 0px 5px;" uid="{{ $uid }}" p="3">Edit Galeri <i class="fas fa-arrow-circle-right"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group" style="margin: 25px 0px">
            <p style="font-weight: bold; font-size: 25px;">
                Nama Satwa
            </p>
            <div class="row">
                <div class="col-6">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Nama Lokal</label>
                        <input type="text" class="form-control local-name" name="local_name" value="{{ $animal->local_name }}" required>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>Nama Latin</label>
                        <input type="text" class="form-control latin-name" name="latin_name" style="font-style: italic;" value="{{ ucfirst($animal->scientific_name) }}" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Label</label>
                        <input type="text" class="form-control label-name" name="label_name" value="{{ $animal->label_name }}" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group" style="margin: 25px 0px">
            <p style="font-weight: bold; font-size: 25px;">
                Spesifikasi Satwa
            </p>

            <div class="row">
                <div class="col-12">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea class="form-control description" rows="10" name="description"><?php echo htmlspecialchars($animal->description); ?></textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-4">
                    <div class="row">
                        <div class="col-5">
                            <div class="form-group">
                                <label>Panjang Badan <br> (dalam cm)</label>
                                <input type="text" class="form-control body-length-min" name="body_length_min" value="{{ $animal->body_length_1 }}" required>
                            </div>
                        </div>
                        <div class="col-1" style="margin-top: 37px;">
                            <div class="form-group">
                                <label> <br> s/d</label>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="form-group">
                                <label style="color:white">Panjang Badan <br> (dalam cm)</label>
                                <input type="text" class="form-control body-length-max" name="body_length_max" value="{{ $animal->body_length_2 }}" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="row">
                        <div class="col-5">
                            <div class="form-group" style="width: 85%">
                                <label>Panjang Ekor <br> (dalam cm)</label>
                                <input type="text" class="form-control tail-length" name="tail_length" value="{{ $animal->body_tail }}" required>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="form-group" style="width: 85%">
                                <label>Tinggi Satwa <br> (dalam cm)</label>
                                <input type="text" class="form-control height" name="height" value="{{ $animal->body_height }}" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label> <br> Berat Badan </label>
                                <input type="text" class="form-control weight" name="weight" value="{{ $animal->body_weight }}" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label> <br> Unit </label>
                                <select class="form-control select2-unit" name="unit">
                                    <option></option>
                                    @foreach($units as $unit)
                                        @if ($unit->id == $animal->weight_unit_id)
                                            <option value={{ $unit->id }} selected>{{ $unit->name }}</option>
                                        @else
                                            <option value={{ $unit->id }}>{{ $unit->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>Habitat</label>
                        <input type="text" class="form-control habitat" name="habitat" value="{{ $animal->habitat }}" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group" style="margin: 25px 0px">
            <p style="font-weight: bold; font-size: 25px;">
                Klasifikasi Ilmiah
            </p>

            <div class="row">
                <div class="col-4">
                    <div class="form-group" style="width: 80%;">
                        <label>Kingdom</label>
                        <select class="form-control select2-kingdom" name="kingdom">
                            <option></option>
                            @foreach($kingdoms as $kingdom)
                                @if ($kingdom->id == $animal->kingdom_id)
                                    <option value={{ $kingdom->id }} selected>{{ $kingdom->name }}</option>
                                @else
                                    <option value={{ $kingdom->id }}>{{ $kingdom->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group" style="width: 80%;">
                        <label>Phylum</label>
                        <select class="form-control select2-phylum" name="phylum">
                            <option></option>
                            @foreach($phylums as $phylum)
                                @if ($phylum->id == $animal->phylum_id)
                                    <option value={{ $phylum->id }} selected>{{ $phylum->name }}</option>
                                @else
                                    <option value={{ $phylum->id }}>{{ $phylum->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group" style="width: 80%;">
                        <label>Kelas</label>
                        <select class="form-control select2-class" name="class">
                            <option></option>
                            @foreach($classes as $class)
                                @if ($class->id == $animal->class_id)
                                    <option value={{ $class->id }} selected>{{ $class->name }}</option>
                                @else
                                    <option value={{ $class->id }}>{{ $class->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-4">
                    <div class="form-group" style="width: 80%;">
                        <label>Ordo</label>
                        <select class="form-control select2-ordo" name="ordo">
                            <option></option>
                            @foreach($ordos as $ordo)
                                @if ($ordo->id == $animal->ordo_id)
                                    <option value={{ $ordo->id }} selected>{{ $ordo->name }}</option>
                                @else
                                    <option value={{ $ordo->id }}>{{ $ordo->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group" style="width: 80%;">
                        <label>Famili</label>
                        <select class="form-control select2-family" name="family">
                            <option></option>
                                @foreach($families as $family)
                                    @if ($family->id == $animal->family_id)
                                        <option value={{ $family->id }} selected>{{ $family->name }}</option>
                                    @else
                                        <option value={{ $family->id }}>{{ $family->name }}</option>
                                    @endif
                                @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group" style="width: 80%;">
                        <label>Genus</label>
                        <select class="form-control select2-genus" name="genus">
                            <option></option>
                            @foreach($genuses as $genus)
                                @if ($genus->id == $animal->genus_id)
                                    <option value={{ $genus->id }} selected>{{ $genus->name }}</option>
                                @else
                                    <option value={{ $genus->id }}>{{ $genus->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group" style="margin: 25px 0px">
            <p style="font-weight: bold; font-size: 25px;">
                Status Konservasi Satwa
            </p>
            <div class="row">
                <div class="col-4">
                    <div class="form-group" style="width: 80%;">
                        <label>Status Konservasi</label>
                        <select class="form-control select2-conservation-status" name="conservation_status">
                            <option></option>
                            @foreach($conservationStatuses as $conservationStatus)
                                @if ($conservationStatus->id == $animal->conservation_id)
                                    <option value={{ $conservationStatus->id }} selected>{{ $conservationStatus->name }}</option>
                                @else
                                    <option value={{ $conservationStatus->id }}>{{ $conservationStatus->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group" style="width: 80%;">
                        <label>Status CITES</label>
                        <select class="form-control select2-cites-status" name="cites_status">
                            <option></option>
                            @foreach($citesStatuses as $citesStatus)
                                @if ($citesStatus->id == $animal->cites_id)
                                    <option value={{ $citesStatus->id }} selected>{{ $citesStatus->name }}</option>
                                @else
                                    <option value={{ $citesStatus->id }}>{{ $citesStatus->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group" style="width: 80%;">
                        <label>Status REDLIST</label>
                        <select class="form-control select2-redlist-status" name="redlist_status">
                            <option></option>
                            @foreach($redlistStatuses as $redlistStatus)
                                @if ($redlistStatus->id == $animal->redlist_id)
                                    <option value={{ $redlistStatus->id }} selected>{{ $redlistStatus->name }}</option>
                                @else
                                    <option value={{ $redlistStatus->id }}>{{ $redlistStatus->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12">
            <button type="button" class="btn btn-primary class-next-tab" style="float: right; cursor: pointer;" ct="1" nt="2">Simpan</button>
        </div>
    </div>
</form>