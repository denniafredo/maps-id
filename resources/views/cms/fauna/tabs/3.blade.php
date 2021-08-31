<div id="menu3" class="tab-pane fade tab-panel tab-menu" c="3" role="tabpanel">
    <p style="font-weight: bold; font-size: 45px;">
        <span class="current-local-name"></span>
    </p>
    <div class="form-group" style="margin: 25px 0px">
        <p style="font-weight: bold; font-size: 25px;">
            Kelola Foto
        </p>
        <div class="row">
            <div class="col-12" for="image">
                <input id="input-images" name="images[]" type="file" accept=".jpg,.jpeg,.png" multiple>
                <input type="hidden" id="list-image-contributor" name="image_contributors[]">

                <div class="col-8 offset-2 card image-contributors" style="margin-top: 25px">
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

                <div class="col-8 offset-2 card video-contributors" style="margin-top: 25px">
                    {{-- Generated --}}
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-primary class-next-tab" style="float: right; cursor: pointer;" nt="end">Selesai</button>
    </div>
</div>