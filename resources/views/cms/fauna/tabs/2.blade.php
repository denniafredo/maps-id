<div id="menu2" class="tab-pane fade tab-panel tab-menu" c="2"  role="tabpanel" style="padding: 15px 35px">
    <p style="font-weight: bold; font-size: 45px;">
        <span class="current-local-name"></span>
    </p>
    <div class="form-group" style="margin: 25px 0px">
        <p style="font-weight: bold; font-size: 25px;">
            Tambah Data Persebaran
        </p>
        <div class="row">
            <div class="col-4">
                <!-- text input -->
                <div class="form-group" style="width: 80%;">
                    <label>Provinsi</label>
                    <select class="form-control select2-provinces">
                        <option></option>
                        
                        @foreach($provinces as $province)
                            <option class="option-province" data-lat={{ $province->latitude }} data-long={{ $province->longitude}}>{{ $province->name }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" class="list-province" name="province[]">
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label>Longitude</label>
                    <input type="text" class="form-control input-long">
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label>Latitude</label>
                    <input type="text" class="form-control input-lat">
                </div>
            </div>
            <div class="col-2">
                <div class="form-group">
                    <button class="btn btn-info btn-manual-input-latlong" style="margin-top: 32px;">Tambah</button>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group" style="margin: 25px 0px">
        <p style="font-weight: bold; font-size: 24px;">Provinsi: <span class="current-province" style="font-weight: bold; font-size: 24px;"></span></p>
        <div id="map" class="map"></div>
        <div id="popup" class="ol-popup">
            <a href="#" id="popup-closer" class="ol-popup-closer"></a>
            <div id="popup-content"></div>
        </div>
    </div>

    <div class="form-group" style="margin: 25px 0px">
        <p style="font-weight: bold; font-size: 24px;">Data Persebaran</p>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>Provinsi</th>
                        <th>Longitude</th>
                        <th>Latitude</th>
                        <th class="text-center">Hapus</th>
                    </tr>
                </thead>
                <tbody class="tbody-animal-mapping">
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-12">
        <button type="button" class="btn btn-primary class-next-tab" style="float: right; cursor: pointer;" nt="3">Lanjut ke Galeri Satwa</button>
    </div>
</div>