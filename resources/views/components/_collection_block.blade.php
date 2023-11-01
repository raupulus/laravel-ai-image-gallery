{{--
Esta vista parcial es para cada bloque que representa una colección en miniatura.

Recibimos siempre "$collection" que es una instancia de tupla con el modelo Collection y la tabla "collections"
--}}

@php($image = $collection->primaryImage)

<div class="col-md-3 text-center col-sm-4 col-6 mt-1 mb-2">
    <a href="{{$collection->url}}"
       class="collection-block-box-btn-show-collection">
        Show {{$collection->images->count()}} images
    </a>

    <div class="collection-block-head">
        <a href="{{$collection->url}}"
           class="text-bg-danger small p-1 rounded-3 d-block">
            {{$collection->images->count()}}

            Images in Collection
        </a>
    </div>

    <div class="collection-block-image image-crop-container">
        <a href="{{$image->urlImage}}"
           class="box-image-crop-link"
           data-fancybox="gallery">
            <img class="img-crop"
                 alt="{{$collection->title}}"
                 src="{{$image->urlThumbnail}}">
        </a>
    </div>

    <div class="collection-block-footer bg-dark text-info">
        <a href="{{$collection->url}}">
            <p class="text-center text-info">
                {{$collection->titleResume}}
            </p>
        </a>
    </div>

</div>
