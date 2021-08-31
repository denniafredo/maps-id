<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title>Animal Mapping</title>

  <link rel="stylesheet" href="{{ url('css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ url('css/style.css') }}">
  <link rel="icon" type="image/png" href="{{ url('dist/img/logo/icon.png') }}">
  <!-- MAPS -->
  <link rel="stylesheet" href="{{ url('css/ol.css') }}" type="text/css">
  <!-- Font Awesome JS -->
  <script src="{{ url('js/solid.js') }}"></script>
  <script src="{{ url('js/fontawesome.js') }}"></script>
</head>

<body>
<div class="wrapper">
  <div class="banner-title">
    <p>SEBARAN INDIKATIF SATWA PRIORITAS NASIONAL DI INDONESIA</p>
  </div>
  <!-- MINI SIDEBAR -->
  <nav id="sidebarMini" class="shadow-lg bg-white rounded sidebar-hidden">
      <div class="d-block">
        <div class="form-group justify-content-center text-center sidebar-logo-mini mt-2">
          <img width="50px" src="{{url('/logo.png')}}" alt="">
        </div>
        <div id="animals-data-mini" class="mt-4 ml-1 scrollbar-animal-mini">
          {{-- <div class="form-group justify-content-center text-center">
            <img width="35px" src="{{url('/logo.jpg')}}" alt="" class="rounded">
            <span class="fas fa-circle form-control-feedback" style="color:red;font-size:75%"></span>
          </div> --}}
        </div>
      </div>
  </nav>
  <a id="sidebarButton"><span class="fas fa-bars form-control-feedback"></span></a>
  <!-- Sidebar  -->
  <nav id="sidebar" class="shadow-lg bg-white rounded">
    <div class="d-flex justify-content-center row">
      <div class="form-group input-icon col-sm-11 mt-3 sidebar-logo row pr-0">
        <div class="form-group col-sm-2 justify-content-center text-center p-0 m-0">
          <img src="{{url('/logo.png')}}" alt="">
        </div>
        <label class="col-sm-10 pr-0">Direktorat Konservasi Keanekaragaman Hayati
          <br> Direktorat Jenderal Konservasi Sumber Daya Alam dan Ekosistem
          <br> Kementrian Lingkungan Hidup dan Kehutanan
        </label>
      </div>
      <div class="form-group input-icon col-sm-11">
        <span class="fas fa-map-marker-alt form-control-feedback"></span>
        <select id="select-province" class="form-control">
          <option value="">Pilih Semua Provinsi</option>
          {{-- <option value="Sulawesi Barat">Sulawesi Barat</option>
          <option value="Sulawesi Timur">Sulawesi Timur</option>
          <option value="Sulawesi Selatan">Sulawesi Selatan</option> --}}
        </select>
      </div>
      <div class="form-group input-icon col-sm-11">
        <span class="fas fa-search form-control-feedback"></span>
        <input type="text" class="form-control mb-3" id="search-animal" placeholder="Cari Satwa . . .">
      </div>
      <div class="d-block scrollbar-animal" id='animals-data'>
        <!-- Animal 1 -->
        {{-- <div class="form-group col-sm-12  justify-content-center text-center">
          <img src="{{url('plugins/img/loading.gif')}}" alt="">
        </div> --}}
      </div>
    </div>
  </nav>

  {{-- MODAL IMAGE --}}
  <div id="zoomImage" class="modal" style="z-index: 1050" >
    <label for="" class="closeImage" data-dismiss="modal">Tutup Galeri<span >&times;</span></label>
    <div id="indicatorsImage" class="carousel slide" data-ride="carousel">
      <div class="indicator-carousel justify-content-md-center" id="indicator-carousel-img">
        <img src="{{url('plugins/img/loading.gif')}}" class="indicator-img" data-target="#indicatorsImage" data-slide-to="0" >
        <img src="{{url('plugins/img/loading.gif')}}" class="indicator-img" data-target="#indicatorsImage" data-slide-to="1" >
        <img src="{{url('plugins/img/loading.gif')}}" class="indicator-img" data-target="#indicatorsImage" data-slide-to="2" >
      </div>
      <div class="carousel-inner" id='carousel-inner-img'>
        <div class="carousel-item active">
          <img class="modal-content" src="{{url('plugins/img/loading.gif')}}" alt="First slide">
          <div class="modal-by row justify-content-center">
            <label class="d-flex">Foto Oleh : <div class="author"><img src="{{url('plugins/img/loading.gif')}}"></div></label>
          </div>
        </div>
        <div class="carousel-item">
          <img class="modal-content" src="{{url('plugins/img/loading.gif')}}" alt="First slide">
          <div class="modal-by row justify-content-center">
            <label class="d-flex">Foto Oleh : <div class="author"><img src="{{url('plugins/img/loading.gif')}}"></div></label>
          </div>
        </div>
        <div class="carousel-item">
          <img class="modal-content" src="{{url('plugins/img/loading.gif')}}" alt="First slide">
          <div class="modal-by row justify-content-center">
            <label class="d-flex">Foto Oleh : <div class="author"> <img src="{{url('plugins/img/loading.gif')}}"></div></label>
          </div>
        </div>
      </div>
      <a class="carousel-control-prev" href="#indicatorsImage" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="carousel-control-next" href="#indicatorsImage" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div>
  </div>
  {{-- MODAL VIDEO --}}
  <div id="zoomVideo" class="modal" style="z-index: 1050" >
    <label for="" class="closeImage" data-dismiss="modal" onclick="pauseAllVideo()">Tutup Galeri<span >&times;</span></label>
    <div id="indicatorsVideo" class="carousel slide" data-ride="carousel">
      <div class="indicator-carousel justify-content-md-center" id="indicator-carousel-video">
        {{-- <video src="{{url('/test.mp4')}}" class="indicator-img" data-target="#indicatorsVideo" data-slide-to="0" ></video>
        <video src="{{url('/video.mp4')}}" class="indicator-img" data-target="#indicatorsVideo" data-slide-to="1" ></video>
        <video src="{{url('dist/video.mp4')}}" class="indicator-img" data-target="#indicatorsVideo" data-slide-to="2" ></video> --}}
      </div>
      <div class="carousel-inner" id="carousel-inner-video">
        <div class="carousel-item active">
          <video controls class="modal-content">
            {{-- <source src="{{url('dist/video.mp4')}}" type="video/mp4"> --}}
          </video>
          <div class="modal-by row justify-content-center">
            <label class="d-flex">Video Oleh : <div class="author"> 1</div></label>
          </div>
        </div>
        <div class="carousel-item">
          <video controls  class="modal-content">
            {{-- <source src="{{url('dist/video.mp4')}}" type="video/mp4"> --}}
          </video>
          <div class="modal-by row justify-content-center">
            <label class="d-flex">Video Oleh : <div class="author">  2</div></label>
          </div>
        </div>
        <div class="carousel-item">
          <video controls  class="modal-content">
            {{-- <source src="{{url('dist/video.mp4')}}" type="video/mp4"> --}}
          </video>
          <div class="modal-by row justify-content-center">
            <label class="d-flex">Video Oleh : <div class="author"> 3</div></label>
          </div>
        </div>
      </div>
      <a class="carousel-control-prev" href="#indicatorsVideo" role="button" data-slide="prev" onclick="pauseAllVideo()">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="carousel-control-next" href="#indicatorsVideo" role="button" data-slide="next" onclick="pauseAllVideo()">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div>
  </div>

  {{-- <div id="modalLoading" class="modal" style="z-index: 1050" >
    <img src="{{url('plugins/img/loading.gif')}}">
  </div> --}}
  <!-- SideBar Overview -->
  <nav id="sidebarOverview" class="shadow-lg bg-white rounded sidebar-hidden">
    <!-- sidebar-hidden -->
    <div class="d-flex justify-content-center row m-0" id="detail-data">
      {{-- <div class="form-group col-sm-12 mt-2 pb-2 p-0" style="border-bottom: 1px solid #BDBDBD;">
        <span class="fas fa-arrow-left form-control-feedback arrow-back" onclick="closeOverview()"></span>
        <label id="label-name" class="label-control animal-name m-0">Tarcius</label>
      </div>
      <div class="form-group col-sm-12 p-0 m-0 text-center">
        <h1 id="local-name" class="label-control animal-name-title">Monyet Hantu</h1>
      </div>
      <div class="form-group col-sm-12 p-0 m-0 text-center">
        <h1 id="scientific-name" class="label-control animal-science-name">Tarcius Tarsier</h1>
      </div>
      <div class="d-block scrollbar-overview">
        <div class="form-group col-sm-12 p-0 m-0">
          <div class="overview-content row">
            <label class="label-control col-sm-5 animal-attr text-left">Tinggi Badan</label>
            <label class="label-control col-sm-6 animal-attr-value text-right"><span id="body-height"></span></label>
          </div>
          <div class="overview-content row">
            <label class="label-control col-sm-5 animal-attr text-left">Ukuran Badan</label>
            <label class="label-control col-sm-6 animal-attr-value text-right"><span id="body-length-1"></span>-<span id="body-length-2"></span> cm</label>
          </div>
          <div class="overview-content row">
            <label class="label-control col-sm-5 animal-attr text-left">Panjang Ekor</label>
            <label class="label-control col-sm-6 animal-attr-value text-right"><span id="body-tail"></span> cm</label>
          </div>
          <div class="overview-content row">
            <label class="label-control col-sm-5 animal-attr text-left">Berat Badan</label>
            <label class="label-control col-sm-6 animal-attr-value text-right"><span id="body-weight"></span> <span id="weight-unit"></span></label>
          </div>
          <div class="overview-content row">
            <label class="label-control col-sm-5 animal-attr text-left">Habitat</label>
            <label id="habitat" class="label-control col-sm-6 animal-attr-value text-right">Hutan Pegunungan</label>
          </div>
          <div class="overview-content row">
            <label class="label-control col-sm-5 animal-attr text-left">Status Konsevasi</label>
            <label id="conservation-status" class="label-control col-sm-6 animal-attr-value text-right">Dilindungi</label>
          </div>
        </div>
        <div class="form-group col-sm-12 p-0 m-0 mt-3 pb-2 text-center  justify-content-center row" style="border-bottom: 1px solid #BDBDBD;">
          <div class="col-sm-6" >
            <img id="img-cites-status" src="{{url('dist/img/logo/CITES.jpg')}}" height="50px">
            <label id="cites-status" class="label-control mt-1">Apendix II</label>
          </div>
          <!-- if cites sama redlist nya ada -->
          <div class="col-sm-6" style="border-left: 1px solid #BDBDBD;">
            <!-- <div class="col-sm-6"> -->
            <img src="{{url('dist/img/logo/red-list.png')}}" height="50px" width="50px">
            <img id="img-redlist-status" src="{{url('dist/img/logo/VU.png')}}" heigth="50px" width="50px">
            <label id="redlist-status" class="label-control mt-1">Vulnerable</label>
          </div>
        </div>
        <div class="form-group col-sm-12 mt-2 pb-2 mb-2 p-0" style="border-bottom: 1px solid #BDBDBD;">
          <div class="overview-content">
            <label class="label-control animal-attr-value mb-1">Deskripsi Singkat Satwa</label>
          </div>
          <div class="overview-content">
            <label id="description" class="label-control animal-attr text-justify" style="width: 95%">
              Tarsius merupakan satwa yang bersifat nokturnal.
              Tarsius memiliki mata yang besar dengan diameter 16 mm dan
              memliki panjang kaki hampir mencapai dua kali panjang tubuhnya.
              Tarsius dapat memutar kepala hingga 180 derajat.
              Ekor yang dimilikinya ramping dengan panjang berkisar 20 - 25 cm.
              Tarsisus memiliki kuku yang pada jari kedua dan ketiga kaki belakang berupa cakar.
              Bulu badan berwarna coklat abi-abu,
              coklat muda atau kuning jingga yang lembut seperti beludru.
            </label>
          </div>
        </div>
        <div class="form-group col-sm-12 p-0 m-0 pb-2 mb-2" style="border-bottom: 1px solid #BDBDBD;">
          <div class="overview-content mb-1">
            <label class="label-control animal-attr-value text-left">Klasifikasi Ilmiah</label>
          </div>
          <div class="overview-content row">
            <label class="label-control col-sm-5 animal-attr text-left">Kingdom</label>
            <label id="kingdom" class="label-control col-sm-6 animal-attr-value text-right font-italic">Animalia</label>
          </div>
          <div class="overview-content row">
            <label class="label-control col-sm-5 animal-attr text-left">Filum</label>
            <label id="phylum" class="label-control col-sm-6 animal-attr-value text-right font-italic">Chordata</label>
          </div>
          <div class="overview-content row">
            <label class="label-control col-sm-5 animal-attr text-left">Kelas</label>
            <label id="class" class="label-control col-sm-6 animal-attr-value text-right font-italic">Mammalia</label>
          </div>
          <div class="overview-content row">
            <label class="label-control col-sm-5 animal-attr text-left">Ordo</label>
            <label id="ordo" class="label-control col-sm-6 animal-attr-value text-right font-italic">Primata</label>
          </div>
          <div class="overview-content row">
            <label class="label-control col-sm-5 animal-attr text-left">Family</label>
            <label id="family" class="label-control col-sm-6 animal-attr-value text-right  font-italic">Tarsiidae</label>
          </div>
          <div class="overview-content row">
            <label class="label-control col-sm-5 animal-attr text-left">Genus</label>
            <label id="genus" class="label-control col-sm-6 animal-attr-value text-right font-italic">Tarcius</label>
          </div>
        </div>
        <div class="form-group col-sm-12 p-0 m-0">
          <div class="overview-content mb-2">
            <label class="label-control animal-attr-value text-left">Gallery Foto dan Video</label>
          </div>
          <div class="form-group col-sm-12 row">
            <div class="col-sm-5 justify-content-center">
              <img id="img-gallery" src="{{url('/logo.jpg')}}" class="rounded animal-image" data-toggle="modal" data-target="#zoomImage-Badak">
            </div>
            <div class="overlayWrapper rounded col-sm-7" data-toggle="modal" data-target="#zoomVideo-Badak">
              <div class="overlay"></div>
              <video class="rounded animal-video" >
                <source id="video-gallery" src="{{url('/test.mp4')}}" type="video/mp4">
              </video>
              <div class="cont h-100" >
                <div class="d-flex h-100 text-center align-items-center">
                  <div class="w-100 text-white">
                    <span class="fas fa-play-circle form-control-feedback videoicon" ></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div> --}}
    </div>
  </nav>
  <!-- Page Content  -->
    <div id="placeholder" class="btn-placeholder" >
      <img src="{{url('dist/img/logo/location-icon.png')}}">
    </div>
    <div id="map" class="map"></div>
    <div id="popup" class="ol-popup">
      <a href="#" id="popup-closer" class="ol-popup-closer"></a>
      <div id="popup-content"></div>
    </div>

</div>

</body>

<!-- jQuery CDN - Slim version (=without AJAX) -->
<script src={{url('js/jquery-3.5.0.js')}}></script>
<script src={{url('js/ol.js')}}></script>
<!-- Popper.JS -->
<script src={{url('js/popper.min.js')}}></script>
<script src={{url('js/bootstrap.min.js')}}></script>
<!-- Bootstrap JS -->

<script type="text/javascript">
  var vl;
  var loadingAnimal = '<div class="form-group col-sm-12 justify-content-center text-center"><img src="{{url('plugins/img/loading.gif')}}" alt=""></div>';
  var oldSelectedProvinceId;
  var oldSelectedAnimalName;
  var urlPlaceholder = "{{url('/dist/marker/')}}";
  $(function () {
    $('[data-toggle="tooltip"]').tooltip()
  })
    $(document).ready(function () {
      $.ajax({
          url: 'ajax/province',
          type: 'get',
          success: function (result) {
              $('#select-province').append(result);
          }, error: function () {
              alert('error');
          }
      });
      // searchAnimal('','');
    });

    function setMarkers(Markers) {
      map.removeLayer(vl);
      var features = [];
              for (var i = 0; i < Markers.length; i++) {
                  var item = Markers[i];
                  var longitude = item.lng;
                  var latitude = item.lat;
                  var marker = item.marker;
                  var animalName = item.animalName;
                  var animalFilename = item.animalFilename;
                  var animalState = item.animalState;
                  var animalId = item.id;
                  var animalColor = item.animalColor;

                  var iconFeature = new ol.Feature({
                      geometry: new ol.geom.Point(ol.proj.transform([longitude, latitude], 'EPSG:4326', 'EPSG:3857')),
                      animalName: animalName,
                      animalFilename: animalFilename,
                      animalState: animalState,
                      animalId: animalId,
                      animalColor: animalColor
                  });

                  var iconStyle = new ol.style.Style({
                      image: new ol.style.Icon(({
                          anchor: [0.5, 1],
                          src: marker
                      }))
                  });

                  iconFeature.setStyle(iconStyle);
                  features.push(iconFeature);
              }

                  var vectorSource = new ol.source.Vector({
                      features: features
                  });

                  var vectorLayer = new ol.layer.Vector({
                      source: vectorSource
                  });
                  vl=vectorLayer;
                  map.addLayer(vectorLayer);


    }
    $('#select-province').on('change',function(event){
          $('#search-animal').val('');
          id = $('#select-province option:selected').val();
          name = $('#search-animal').val();
          oldSelectedProvinceId = id;
          oldSelectedAnimalName = name;
          searchAnimal(id,name);
    });
    // SEARCH
    $('#search-animal').keypress(function(event){
          id = $('#select-province option:selected').val();
          name = $('#search-animal').val();
          oldSelectedProvinceId = id;
          oldSelectedAnimalName = name;
          searchAnimal(id,name);
    });
    function searchAnimal(id,name) {
      $.ajax({
          url: 'ajax/animals',
          type: 'get',
          data: {stateId: id,animalName: name},
          beforeSend: function () {
            $('#animals-data').html(loadingAnimal);
          },
          success: function (result) {
            // if(result[0]==)
            if(result[0]==''){
              result[0] = '<div class="form-group col-sm-12 mb-0 text-center"><i>No Data Found</i></div>';
            }
              $('#animals-data').html(result[0]);
              $('#animals-data-mini').html(result[1]);
              var Markers = [];
              for (index = 0; index < result[2].length; index++) {
                var markerValue = {};
                markerValue.lat = result[2][index].latitude;
                markerValue.lng = result[2][index].longitude;
                markerValue.marker = urlPlaceholder+'/'+result[2][index].color+'.svg';
                markerValue.animalName = result[2][index].label_name;
                markerValue.animalFilename = result[2][index].image_path;
                markerValue.animalState = result[2][index].state;
                markerValue.id = result[2][index].uid;
                markerValue.animalColor = result[2][index].color;
                Markers.push(markerValue);
              }
              setMarkers(Markers);
          }, error: function () {
              alert('error');
          }
    });

    }
    function resetModal(){
      indicatorHTML = '<img src="{{url('plugins/img/loading.gif')}}" class="indicator-img" data-target="#indicatorsImage" data-slide-to="0" >';
      $('#indicator-carousel-img').html(indicatorHTML);
      $('#indicator-carousel-video').html(indicatorHTML);
      contentHTML = '<div class="carousel-item"><img class="modal-content" src="{{url('plugins/img/loading.gif')}}" alt="First slide"><div class="modal-by row justify-content-center"><label class="d-flex">Foto Oleh : <div class="author"><img src="{{url('plugins/img/loading.gif')}}"></div></label></div></div>';
      $('#carousel-inner-video').html(contentHTML);
      $('#carousel-inner-img').html(contentHTML);
    }

    function loadZoomImage(id){
      $.ajax({
          url: 'ajax/modalImage',
          type: 'get',
          data: {id: id},
          beforeSend: function () {
            resetModal();
          },
          success: function (result) {
            indicatorHTML='';
            contentHTML='';
            for(i=0;i<result.length;i++){
              indicatorHTML+='<img src="'+result[i].image_path+'" class="indicator-img" data-target="#indicatorsImage" data-slide-to="'+i+'" >';
              if(i==0){
                contentHTML += '<div class="carousel-item active"><img class="modal-content" src="'+result[i].image_path+'" alt="'+result[i].name+'"><div class="modal-by row justify-content-center"><label class="d-flex">Foto Oleh : <div class="author"> '+result[i].contributor+'</div></label></div></div>';
              }
              else{
                contentHTML += '<div class="carousel-item"><img class="modal-content" src="'+result[i].image_path+'" alt="'+result[i].name+'"><div class="modal-by row justify-content-center"><label class="d-flex">Foto Oleh : <div class="author"> '+result[i].contributor+'</div></label></div></div>';
              }
            }
            $('#indicator-carousel-img').html(indicatorHTML);
            $('#carousel-inner-img').html(contentHTML);
          }, error: function () {
              alert('error');
          }
      });
    }
    function loadZoomVideo(id){
      $.ajax({
          url: 'ajax/modalVideo',
          type: 'get',
          data: {id: id},
          beforeSend: function () {
            resetModal();
          },
          success: function (result) {
            indicatorHTML='';
            contentHTML='';
            for(i=0;i<result.length;i++){
              indicatorHTML+='<video src="'+result[i].video_path+'" class="indicator-img" data-target="#indicatorsVideo" data-slide-to="'+i+'" ></video>';
              if(i==0){
                contentHTML += '<div class="carousel-item active"><video controls class="modal-content"><source src="'+result[i].video_path+'" type="video/mp4"></video><div class="modal-by row justify-content-center"><label class="d-flex">Video Oleh : <div class="author"> '+result[i].contributor+'</div></label></div></div>';
              }
              else{
                contentHTML += '<div class="carousel-item"><video controls class="modal-content"><source src="'+result[i].video_path+'" type="video/mp4"></video><div class="modal-by row justify-content-center"><label class="d-flex">Video Oleh : <div class="author"> '+result[i].contributor+'</div></label></div></div>';
              }
            }
            $('#indicator-carousel-video').html(indicatorHTML);
            $('#carousel-inner-video').html(contentHTML);
          }, error: function () {
              alert('error');
          }
      });
    }


    function openOverview(id,color){
        $.ajax({
          url: 'ajax/detail',
          type: 'get',
          data: {id: id,color: color},
          beforeSend: function () {
            $('#detail-data').html(loadingAnimal);
          },
          success: function (result) {
            $('#detail-data').html(result[0]);
            var Markers = [];
              for (index = 0; index < result[1].length; index++) {
                var markerValue = {};
                markerValue.lat = result[1][index].latitude;
                markerValue.lng = result[1][index].longitude;
                markerValue.marker = urlPlaceholder+'/'+result[1][index].color+'.svg';
                markerValue.animalName = result[1][index].label_name;
                markerValue.animalFilename = result[1][index].image_path;
                markerValue.animalState = result[1][index].state;
                markerValue.id = result[1][index].uid;
                markerValue.animalColor = result[1][index].color;
                Markers.push(markerValue);
              }
              setMarkers(Markers);
          }, error: function () {
              alert('error');
          }
      });
      if($('#sidebarOverview').hasClass( "sidebar-hidden" )){
        $('#sidebar').toggleClass('sidebar-hidden');
        $('#sidebarOverview').toggleClass('sidebar-hidden');
        $('#sidebarButton').hide();
      }
    }
    function closeOverview(){
      if(!$('#sidebarOverview').hasClass( "sidebar-hidden" )){
        $('#sidebar').toggleClass('sidebar-hidden');
        $('#sidebarOverview').toggleClass('sidebar-hidden');
        $('#sidebarButton').show();
        searchAnimal(oldSelectedProvinceId,oldSelectedAnimalName);
      }
    }
    $('#sidebarButton').on('click',function(event){
        $('#sidebarMini').toggleClass('sidebar-hidden');
        $('#sidebar').toggleClass('sidebar-hidden');
        $('#sidebarButton').toggleClass('mini');
        $('#map').toggleClass('map-mini');
        $('.banner-title').toggleClass('banner-mini');
        map.updateSize();
    });
    function pauseAllVideo(){
        $('.modal').find('video').each(function( index ) {
            $(this).get(0).pause();
        });
    }

</script>

<script type="text/javascript">
    var view = new ol.View({
        center: ol.proj.fromLonLat([118 , -0.42003019915661355]),
        zoom: 5
    });

    var mapSource = new ol.source.Vector({
        url: '{!! url("geojson/indonesia.geojson") !!}',
        format: new ol.format.GeoJSON()
    });

    var map = new ol.Map({
        target: 'map',
        layers: [
            new ol.layer.Tile({
                source: new ol.source.OSM()
            })
        ],
        view: view
    });

    // Add Vector
    var vector = new ol.layer.Vector({
        source: mapSource
    });
    map.addLayer(vector);

    function flickrStyle(feature) {
      var style = new ol.style.Style({
        stroke: new ol.style.Stroke({
            color: 'purple',
            width: 2
          }),
          fill: new ol.style.Fill({
            color: 'purple'
          })
      });
      return [style];
    }

    $.ajax({
      url: 'ajax/kml',
      type: 'get',
      success: function (result) {
        for (index = 0; index < result.length; index++) {
          var vectorKML1 = new ol.layer.Vector({
            source: new ol.source.Vector({
              url: result[index]['path'],
              // url: "kml/doc.kml",
              format: new ol.format.KML({
                extractStyles: false
              })
            }),
            style: flickrStyle,
            opacity: 0.5
          });
          map.addLayer(vectorKML1);
        }

      }, error: function () {
          alert('error');
      }
    });
    // url: '{!! url('kml/doc.kml') !!}',



    // Popup
    var container = document.getElementById('popup');
    var content = document.getElementById('popup-content');
    var closer = document.getElementById('popup-closer');

    var overlay = new ol.Overlay({
        element: container,
        autoPan: true,
        autoPanAnimation: {
            duration: 250
        }
    });
    map.addOverlay(overlay);

    closer.onclick = function() {
        overlay.setPosition(undefined);
        closer.blur();
        return false;
    };

    // map on click
    map.on('singleclick', function (event) {
        if (map.hasFeatureAtPixel(event.pixel) === true) {
            var coordinate = event.coordinate;

            var feature = map.forEachFeatureAtPixel(event.pixel, function (feat, layer) {
                    return feat;
                }
            );

            if(feature.get('name') !=null){
              content.innerHTML = feature.get('name');
              overlay.setPosition(coordinate);
            }

            if(feature.values_['animalName'] !=null){
                var animalName = feature.values_['animalName'];
                var animalFilename = feature.values_['animalFilename'];
                var animalState = feature.values_['animalState'];
                var animalId = feature.values_['animalId'];
                var animalColor = '\''+feature.values_['animalColor']+'\'';
                content.innerHTML = '<div class="popup-card row justify-content-md-center" onclick="openOverview('+animalId+','+animalColor+')"><div class="col-sm-4"><img class="popup-animalImage" src="{{url('')}}/'+animalFilename+'"></div><div class="col-sm-8 justify-content-md-center"><div class="popup-state text-center">' + animalState + '</div><div class="popup-animalName text-center">'+animalName+'</div></div></div>';
                overlay.setPosition(coordinate);
            }
            else{
              $("#select-province option:contains(" + feature.values_['state'] +")").attr("selected", true);
              name = $('#search-animal').val();
              searchAnimal(feature.values_['state'],name);
            }
        } else {
            overlay.setPosition(undefined);
            closer.blur();
        }
    });

    map.on('pointermove', function(event) {
        map.getTargetElement().style.cursor = map.hasFeatureAtPixel(event.pixel) ? 'pointer' : '';

        if (map.hasFeatureAtPixel(event.pixel) === true) {
            var coordinate = event.coordinate;

            var feature = map.forEachFeatureAtPixel(event.pixel, function (feat, layer) {
                    return feat;
                }
            );
            if(feature.get('name') !=null){
              content.innerHTML = feature.get('name');
              overlay.setPosition(coordinate);
            }else{
              overlay.setPosition(undefined);
              closer.blur();
            }
        } else {
            overlay.setPosition(undefined);
            closer.blur();
        }
    });

    $(document).on('click', '.btn-placeholder', function() {
        $.ajax({
          url: 'ajax/zoomIn',
          type: 'get',
          success: function (result) {
              CenterMap(result.long , result.lat, result.zoom);
          }, error: function () {
              alert('error');
          }
      });
    });

    function CenterMap(long, lat, zoom) {
        flyTo(ol.proj.transform([long, lat], 'EPSG:4326', 'EPSG:3857'),zoom)
    }

    function flyTo(location,z) {
        var duration = 2000;
        var zoom = z;
        var parts = 2;
        var called = false;
        function callback(complete) {
            --parts;
            if (called) {
                return;
            }
            if (parts === 0 || !complete) {
                called = true;
            }
        }
        view.animate({
            center: location,
            duration: duration
        }, callback);
        view.animate({
            zoom: zoom - 1,
            duration: duration / 2
        }, {
            zoom: zoom,
            duration: duration / 2
        }, callback);
    }
</script>
</body>
