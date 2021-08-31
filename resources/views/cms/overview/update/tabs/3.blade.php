<form role="form" class="update-animal" method="POST" enctype="multipart/form-data" did={{ $animalImageDefaultID }}>
    {{ csrf_field() }}
    <input type="hidden" name="ct" value="1">
    <div id="menu3" class="tab-pane fade tab-panel tab-menu active show" c="3" role="tabpanel">
        <div class=col-12>
            <div class="row">
                <div class="col-6" style="padding-left: 0px;">
                    <p class="btn-update-data-animal" style="font-weight: bold; font-size: 25px; cursor: pointer;" uid="{{ $uid }}" p="2">
                        <i class="fas fa-arrow-circle-left"></i> Galeri Satwa
                    </p>
                </div>
            </div>
        </div>
        <p style="font-weight: bold; font-size: 45px;">
            <span class="current-local-name">{{ $animal->local_name }}</span>
        </p>
        <div class="form-group" style="margin: 25px 0px">
            <p style="font-weight: bold; font-size: 25px;">
                Kelola Foto
            </p>
            <div class="row">
                <div class="col-12" for="image">
                    <input id="input-images" name="images[]" type="file" accept=".jpg,.jpeg,.png" multiple>
                    <input type="hidden" id="list-image-contributor" name="image_contributors[]">
                    <input type="hidden" id="list-old-image-contributor" name="old_image_contributors[]">

                    <div class="col-10 offset-1 card image-contributors" style="margin-top: 25px">
                        {{-- Generated --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group" style="margin: 25px 0px">
            <p style="font-weight: bold; font-size: 25px;">
                Kelola Video
            </p>
            <div class="row">
                <div class="col-12" for="video">
                    <input id="input-videos" name="videos[]" type="file" accept=".mp4"  multiple>
                    <input type="hidden" id="list-video-contributor" name="video_contributors[]">
                    <input type="hidden" id="list-old-video-contributor" name="old_video_contributors[]">

                    <div class="col-10 offset-1 card video-contributors" style="margin-top: 25px">
                        {{-- Generated --}}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary class-next-tab" style="float: right; cursor: pointer;" ct="3" nt="end">Selesai</button>
        </div>
    </div>
</form>