{{--
Esta vista parcial es para cada bloque que representa una colección en miniatura.

Recibimos siempre "$collection" que es una instancia de tupla con el modelo Collection y la tabla "collections"
--}}

@php($image = $collection->primaryImage)
@php($imagesCount = $collection->images_count ?? $collection->images->count())

<div class="col-md-3 text-center col-sm-4 col-6 mt-1 mb-2">
    <a href="{{$collection->url}}"
       class="collection-block-box-btn-show-collection">
        Show {{$imagesCount}} images
    </a>

    <div class="collection-block-head">
        <a href="{{$collection->url}}"
           class="text-bg-danger small p-1 rounded-3 d-block">
            {{$imagesCount}}

            Images in Collection
        </a>
    </div>

    @if($image)
        <div class="collection-block-image image-crop-container">
            <a href="{{$image->urlImage}}"
               class="box-image-crop-link"
               data-fancybox="gallery">
                <img class="img-crop"
                     alt="{{$collection->title}}"
                     src="{{$image->urlThumbnail}}">
            </a>
        </div>
    @endif

    <div class="collection-block-footer bg-dark text-info">
        <a href="{{$collection->url}}">
            <p class="text-center text-info">
                {{$collection->titleResume}}
            </p>
        </a>
    </div>

</div>
