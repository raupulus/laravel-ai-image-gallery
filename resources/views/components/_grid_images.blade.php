{{-- Grid con las imágenes --}}
<div class="row">

    @foreach($images as $image)

        <div class="col-md-3 text-center col-sm-4 col-6 mt-1 mb-2">
            <a href="{{$image->urlImage}}"
               data-fancybox="gallery">
                <img class="img-fluid"
                     alt="{{$collection->title}}"
                     src="{{$image->urlThumbnail}}">
            </a>

            <p class="text-center">
                <svg xmlns="http://www.w3.org/2000/svg"
                     fill="#df6919"
                     height="1em"
                     viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M512 32c0 113.6-84.6 207.5-194.2 222c-7.1-53.4-30.6-101.6-65.3-139.3C290.8 46.3 364 0 448 0h32c17.7 0 32 14.3 32 32zM0 96C0 78.3 14.3 64 32 64H64c123.7 0 224 100.3 224 224v32V480c0 17.7-14.3 32-32 32s-32-14.3-32-32V320C100.3 320 0 219.7 0 96z"/></svg>
                {{$image->seed}}
            </p>
        </div>

    @endforeach

</div>
