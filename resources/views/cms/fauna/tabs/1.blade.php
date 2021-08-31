<div id="menu1" class="tab-pane fade tab-panel tab-menu active show" c="1" role="tabpanel" style="padding: 15px 35px">
    <div class="form-group" style="margin: 25px 0px">
        <p style="font-weight: bold; font-size: 25px;">
            Nama Satwa
        </p>
        <div class="row">
            <div class="col-6">
                <!-- text input -->
                <div class="form-group">
                    <label>Nama Lokal</label>
                    <input type="text" class="form-control local-name" name="local_name" required>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label>Nama Latin</label>
                    <input type="text" class="form-control latin-name" name="latin_name" style="font-style: italic;" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <!-- text input -->
                <div class="form-group">
                    <label>Label</label>
                    <input type="text" class="form-control label-name" name="label_name" required>
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
                    <textarea class="form-control description" rows="10" name="description"></textarea>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-4">
                <div class="row">
                    <div class="col-5">
                        <div class="form-group">
                            <label>Panjang Badan <br> (dalam cm)</label>
                            <input type="text" class="form-control body-length-min" name="body_length_min" required>
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
                            <input type="text" class="form-control body-length-max" name="body_length_max">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="row">
                    <div class="col-5">
                        <div class="form-group" style="width: 85%">
                            <label>Panjang Ekor <br> (dalam cm)</label>
                            <input type="text" class="form-control tail-length" name="tail_length" required>
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="form-group" style="width: 85%">
                            <label>Tinggi Satwa <br> (dalam cm)</label>
                            <input type="text" class="form-control height" name="height" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label> <br> Berat Badan </label>
                            <input type="text" class="form-control weight" name="weight" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label> <br> Unit </label>
                            <select class="form-control select2-unit" name="unit">
                                <option></option>
                                @foreach($units as $unit)
                                    <option value={{ $unit->id }}>{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label>Habitat</label>
                    <input type="text" class="form-control habitat" name="habitat" required>
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
                            <option value={{ $kingdom->id }}>{{ $kingdom->name }}</option>
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
                            <option value={{ $phylum->id }}>{{ $phylum->name }}</option>
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
                            <option value={{ $class->id }}>{{ $class->name }}</option>
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
                            <option value={{ $ordo->id }}>{{ $ordo->name }}</option>
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
                            <option value={{ $family->id }}>{{ $family->name }}</option>
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
                            <option value={{ $genus->id }}>{{ $genus->name }}</option>
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
                            <option value={{ $conservationStatus->id }}>{{ $conservationStatus->name }}</option>
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
                            <option value={{ $citesStatus->id }}>{{ $citesStatus->name }}</option>
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
                            <option value={{ $redlistStatus->id }}>{{ $redlistStatus->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-12">
        <button type="button" class="btn btn-primary class-next-tab" style="float: right; cursor: pointer;" nt="2">Lanjut ke Persebaran Satwa</button>
    </div>
</div>