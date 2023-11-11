@extends('layouts.app')

@section('meta-description', $collection->description)
@section('meta-keywords', 'AI, Stable Diffusion, Dall-e, Raúl Caro Pastorino, raupulus, ' . $collection->tags)
@section('meta-og-title', $collection->title)
@section('meta-og-description', $collection->description)
@section('meta-og-image', $firstImage?->urlImage ?? asset('images/logo/logo_640x640.webp'))
@section('meta-og-image-url', $firstImage?->urlImage ?? asset('images/logo/logo_640x640.webp'))
@section('meta-og-image-secure_url', $firstImage?->urlImage ?? asset('images/logo/logo_640x640.webp'))
@section('meta-og-url', $collection->url)
@section('meta-og-image_alt', $collection->title)
@section('meta-twitter-title', $collection->title)

@section('content')
    <div class="container-xl" style="">
        <div class="row mt-5 mb-3">
            <div class="col-12">
                <header>
                    <h1 class="text-capitalize fs-1 fw-bold text-center text-primary"
                        style="font-family: Aclonica, sans-serif;">
                        {{$collection->title}}
                    </h1>
                </header>
            </div>

            <div class="col-12 text-sm-center box-tags">
                @foreach($collection->tagsArray as $tag)
                    <div class="box-tag">
                        {{\Illuminate\Support\Str::ucfirst($tag)}}
                    </div>
                @endforeach
            </div>
        </div>

        @if(auth()->user()?->isAdmin)
            <div class="box-action-tools">
                <div class="container-action-tools">
                    <form method="POST"
                          id="form-delete-collection"
                          class="d-none"
                          action="{{route('collection.delete', $collection->id)}}">
                        @csrf
                    </form>

                    <span
                        class="action-tool tool-delete-collection btn btn-sm btn-danger">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             fill="#000"
                             height="1em" viewBox="0 0 448 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path
                                d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"/></svg>

                        Delete Collection
                    </span>

                </div>

            </div>
        @endif


        @if($collection->url_youtube)
            <div class="row">
                <div class="col text-center">
                    <iframe width="{{$collection->isHorizontal ? '560' : '315'}}"
                            height="{{$collection->isHorizontal ? '315' : '560'}}"
                            src="{{$collection->url_youtube}}"
                            style="max-width: 600px; width: 100%"
                            title="{{$collection->title}}"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen></iframe>
                </div>
            </div>
        @else
            @if($firstImage)
                <div class="row">
                    <div class="col text-center">
                        <a href="{{$firstImage->urlImage}}"
                           data-fancybox="gallery-title">
                            <img class="img-fluid"
                                 style="{{$collection->isHorizontal ? 'width: 100%; max-width: 560px;' : 'height: 100%; max-height: 560px;'}}"
                                 alt="{{$collection->title}}"
                                 src="{{$firstImage->urlThumbnail}}">
                        </a>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col text-center">
                        <div class="alert alert-warning" role="alert">
                            The images have not been uploaded yet, it is very likely that they will be ready in a few
                            minutes.
                        </div>
                    </div>
                </div>
            @endif
        @endif

        {{-- Metadatos --}}
        <div class="row mb-2">
            <div class="col-12">
                <div class="box-collection-metadata">

                    {{-- Bloque sobre AI y modelo --}}
                    <div class="row">
                        <div class="col-12">
                            <div class="collection-metadata-inline">
                                <span class="metadata-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 640 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path
                                            d="M320 0c17.7 0 32 14.3 32 32V96H472c39.8 0 72 32.2 72 72V440c0 39.8-32.2 72-72 72H168c-39.8 0-72-32.2-72-72V168c0-39.8 32.2-72 72-72H288V32c0-17.7 14.3-32 32-32zM208 384c-8.8 0-16 7.2-16 16s7.2 16 16 16h32c8.8 0 16-7.2 16-16s-7.2-16-16-16H208zm96 0c-8.8 0-16 7.2-16 16s7.2 16 16 16h32c8.8 0 16-7.2 16-16s-7.2-16-16-16H304zm96 0c-8.8 0-16 7.2-16 16s7.2 16 16 16h32c8.8 0 16-7.2 16-16s-7.2-16-16-16H400zM264 256a40 40 0 1 0 -80 0 40 40 0 1 0 80 0zm152 40a40 40 0 1 0 0-80 40 40 0 1 0 0 80zM48 224H64V416H48c-26.5 0-48-21.5-48-48V272c0-26.5 21.5-48 48-48zm544 0c26.5 0 48 21.5 48 48v96c0 26.5-21.5 48-48 48H576V224h16z"/></svg>
                                </span>

                                [AI]
                                {{$collection->ai}}
                            </div>

                            <div class="collection-metadata-inline">
                                <span class="metadata-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path
                                            d="M343.9 213.4L245.3 312l65.4 65.4c7.9 7.9 11.1 19.4 8.4 30.3s-10.8 19.6-21.5 22.9l-256 80c-11.4 3.5-23.8 .5-32.2-7.9S-2.1 481.8 1.5 470.5l80-256c3.3-10.7 12-18.9 22.9-21.5s22.4 .5 30.3 8.4L200 266.7l98.6-98.6c-14.3-14.6-14.2-38 .3-52.5l95.4-95.4c26.9-26.9 70.5-26.9 97.5 0s26.9 70.5 0 97.5l-95.4 95.4c-14.5 14.5-37.9 14.6-52.5 .3z"/></svg>
                                </span>

                                [Model]
                                {{$collection->model}}
                            </div>

                            <div class="collection-metadata-inline">
                                <span class="metadata-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path
                                            d="M342.6 9.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l9.4 9.4L28.1 342.6C10.1 360.6 0 385 0 410.5V416c0 53 43 96 96 96h5.5c25.5 0 49.9-10.1 67.9-28.1L448 205.3l9.4 9.4c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3l-32-32-96-96-32-32zM205.3 256L352 109.3 402.7 160l-96 96H205.3z"/></svg>
                                </span>

                                [Refiner]
                                {{$collection->refiner_model}}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="collection-metadata-inline">
                                <span class="metadata-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path
                                            d="M200 32H56C42.7 32 32 42.7 32 56V200c0 9.7 5.8 18.5 14.8 22.2s19.3 1.7 26.2-5.2l40-40 79 79-79 79L73 295c-6.9-6.9-17.2-8.9-26.2-5.2S32 302.3 32 312V456c0 13.3 10.7 24 24 24H200c9.7 0 18.5-5.8 22.2-14.8s1.7-19.3-5.2-26.2l-40-40 79-79 79 79-40 40c-6.9 6.9-8.9 17.2-5.2 26.2s12.5 14.8 22.2 14.8H456c13.3 0 24-10.7 24-24V312c0-9.7-5.8-18.5-14.8-22.2s-19.3-1.7-26.2 5.2l-40 40-79-79 79-79 40 40c6.9 6.9 17.2 8.9 26.2 5.2s14.8-12.5 14.8-22.2V56c0-13.3-10.7-24-24-24H312c-9.7 0-18.5 5.8-22.2 14.8s-1.7 19.3 5.2 26.2l40 40-79 79-79-79 40-40c6.9-6.9 8.9-17.2 5.2-26.2S209.7 32 200 32z"/></svg>
                                </span>

                                [Size]
                                {{$collection->size}}
                            </div>

                            <div class="collection-metadata-inline">
                                <span class="metadata-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path
                                            d="M344 0H488c13.3 0 24 10.7 24 24V168c0 9.7-5.8 18.5-14.8 22.2s-19.3 1.7-26.2-5.2l-39-39-87 87c-9.4 9.4-24.6 9.4-33.9 0l-32-32c-9.4-9.4-9.4-24.6 0-33.9l87-87L327 41c-6.9-6.9-8.9-17.2-5.2-26.2S334.3 0 344 0zM168 512H24c-13.3 0-24-10.7-24-24V344c0-9.7 5.8-18.5 14.8-22.2s19.3-1.7 26.2 5.2l39 39 87-87c9.4-9.4 24.6-9.4 33.9 0l32 32c9.4 9.4 9.4 24.6 0 33.9l-87 87 39 39c6.9 6.9 8.9 17.2 5.2 26.2s-12.5 14.8-22.2 14.8z"/></svg>
                                </span>

                                [Resized]
                                {{$collection->size_resized}}
                            </div>

                            <div class="collection-metadata-inline">
                                <span class="metadata-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path
                                            d="M192 0c17.7 0 32 14.3 32 32V144H160V32c0-17.7 14.3-32 32-32zM64 64c0-17.7 14.3-32 32-32s32 14.3 32 32v80H64V64zm192 0c0-17.7 14.3-32 32-32s32 14.3 32 32v96c0 17.7-14.3 32-32 32s-32-14.3-32-32V64zm96 64c0-17.7 14.3-32 32-32s32 14.3 32 32v64c0 17.7-14.3 32-32 32s-32-14.3-32-32V128zm-96 88l0-.6c9.4 5.4 20.3 8.6 32 8.6c13.2 0 25.4-4 35.6-10.8c8.7 24.9 32.5 42.8 60.4 42.8c11.7 0 22.6-3.1 32-8.6V256c0 52.3-25.1 98.8-64 128v96c0 17.7-14.3 32-32 32H160c-17.7 0-32-14.3-32-32V401.6c-17.3-7.9-33.2-18.8-46.9-32.5L69.5 357.5C45.5 333.5 32 300.9 32 267V240c0-35.3 28.7-64 64-64h88c22.1 0 40 17.9 40 40s-17.9 40-40 40H128c-8.8 0-16 7.2-16 16s7.2 16 16 16h56c39.8 0 72-32.2 72-72z"/></svg>
                                </span>

                                [Resizer]
                                {{$collection->resizer}}
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-12">
                            <div class="collection-metadata-inline">
                                <span class="metadata-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 640 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path
                                            d="M416 0C352.3 0 256 32 256 32V160c48 0 76 16 104 32s56 32 104 32c56.4 0 176-16 176-96S512 0 416 0zM128 96c0 35.3 28.7 64 64 64h32V32H192c-35.3 0-64 28.7-64 64zM288 512c96 0 224-48 224-128s-119.6-96-176-96c-48 0-76 16-104 32s-56 32-104 32V480s96.3 32 160 32zM0 416c0 35.3 28.7 64 64 64H96V352H64c-35.3 0-64 28.7-64 64z"/></svg>
                                </span>

                                [Steps]
                                {{$collection->steps}}
                            </div>

                            <div class="collection-metadata-inline">
                                <span class="metadata-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 640 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path
                                            d="M522.1 62.4c16.8-5.6 25.8-23.7 20.2-40.5S518.6-3.9 501.9 1.6l-113 37.7C375 15.8 349.3 0 320 0c-44.2 0-80 35.8-80 80c0 3 .2 5.9 .5 8.8L117.9 129.6c-16.8 5.6-25.8 23.7-20.2 40.5s23.7 25.8 40.5 20.2l135.5-45.2c4.5 3.2 9.3 5.9 14.4 8.2V480c0 17.7 14.3 32 32 32H512c17.7 0 32-14.3 32-32s-14.3-32-32-32H352V153.3c21-9.2 37.2-27 44.2-49l125.9-42zM439.6 288L512 163.8 584.4 288H439.6zM512 384c62.9 0 115.2-34 126-78.9c2.6-11-1-22.3-6.7-32.1L536.1 109.8c-5-8.6-14.2-13.8-24.1-13.8s-19.1 5.3-24.1 13.8L392.7 273.1c-5.7 9.8-9.3 21.1-6.7 32.1C396.8 350 449.1 384 512 384zM129.2 291.8L201.6 416H56.7l72.4-124.2zM3.2 433.1C14 478 66.3 512 129.2 512s115.2-34 126-78.9c2.6-11-1-22.3-6.7-32.1L153.2 237.8c-5-8.6-14.2-13.8-24.1-13.8s-19.1 5.3-24.1 13.8L9.9 401.1c-5.7 9.8-9.3 21.1-6.7 32.1z"/></svg>
                                </span>

                                [CFG]
                                {{$collection->cfg_scale}}
                            </div>


                            <div class="collection-metadata-inline">
                                <span class="metadata-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path
                                            d="M176 88v40H336V88c0-4.4-3.6-8-8-8H184c-4.4 0-8 3.6-8 8zm-48 40V88c0-30.9 25.1-56 56-56H328c30.9 0 56 25.1 56 56v40h28.1c12.7 0 24.9 5.1 33.9 14.1l51.9 51.9c9 9 14.1 21.2 14.1 33.9V304H384V288c0-17.7-14.3-32-32-32s-32 14.3-32 32v16H192V288c0-17.7-14.3-32-32-32s-32 14.3-32 32v16H0V227.9c0-12.7 5.1-24.9 14.1-33.9l51.9-51.9c9-9 21.2-14.1 33.9-14.1H128zM0 416V336H128v16c0 17.7 14.3 32 32 32s32-14.3 32-32V336H320v16c0 17.7 14.3 32 32 32s32-14.3 32-32V336H512v80c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64z"/></svg>
                                </span>

                                [Denoising]
                                {{$collection->denoising_strength}}
                            </div>

                            <div class="collection-metadata-inline">
                                <span class="metadata-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path
                                            d="M174.5 498.8C73.1 464.7 0 368.9 0 256C0 114.6 114.6 0 256 0S512 114.6 512 256c0 112.9-73.1 208.7-174.5 242.8C346.7 484 352 466.6 352 448V401.1c24.3-17.5 43.6-41.6 55.4-69.6c5-11.8-7-22.5-19.3-18.7c-39.7 12.2-84.5 19-131.8 19s-92.1-6.8-131.8-19c-12.3-3.8-24.3 6.9-19.3 18.7c11.7 27.8 30.8 51.7 54.8 69.2V448c0 18.6 5.3 36 14.5 50.8zm20.7-265.2c5.3 7.1 15.3 8.5 22.4 3.2s8.5-15.3 3.2-22.4c-30.4-40.5-91.2-40.5-121.6 0c-5.3 7.1-3.9 17.1 3.2 22.4s17.1 3.9 22.4-3.2c17.6-23.5 52.8-23.5 70.4 0zM336 272a64 64 0 1 0 0-128 64 64 0 1 0 0 128zM320 402.6V448c0 35.3-28.7 64-64 64s-64-28.7-64-64V402.6c0-14.7 11.9-26.6 26.6-26.6h2c11.3 0 21.1 7.9 23.6 18.9c2.8 12.6 20.8 12.6 23.6 0c2.5-11.1 12.3-18.9 23.6-18.9h2c14.7 0 26.6 11.9 26.6 26.6zM336 184a24 24 0 1 1 0 48 24 24 0 1 1 0-48z"/></svg>
                                </span>

                                [Restore Faces]
                                {{$collection->restore_faces ? 'Yes' : 'No'}}
                            </div>
                        </div>
                    </div>

                    {{-- Descripción y prompts --}}
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="box-metadata-info">
                                <div class="metadata-info-selector list-group">
                                    <span data-id="metadata-description"
                                          class="btn-info-selector list-group-item list-group-item-action active">
                                        Description
                                    </span>

                                    <span data-id="metadata-prompt"
                                          class="btn-info-selector list-group-item list-group-item-action">
                                        Prompt
                                    </span>

                                    <span data-id="metadata-negative"
                                          class="btn-info-selector list-group-item list-group-item-action">
                                        Negative
                                    </span>
                                </div>

                                <div class="metadata-info-content">
                                    <div id="metadata-description" class="collection-metadata-block">
                                        {{$collection->description}}
                                    </div>

                                    <div id="metadata-prompt"
                                         class="collection-metadata-block collection-metadata-block-hidden">
                                        {{$collection->prompt}}
                                    </div>

                                    <div id="metadata-negative"
                                         class="collection-metadata-block collection-metadata-block-hidden">
                                        {{$collection->negative_prompt}}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        {{-- Grid con las imágenes --}}
        @include('components._grid_images')

        {{-- Otras relacionadas --}}
        <div class="row">
            <div class="col">

            </div>
        </div>

    </div>
@endsection


@section('css')
    <link rel="stylesheet" href="{{asset('css/collection_show.css')}}">
@endsection

@section('js')

    <script>
        function changeInfoSection(ele) {
            let selectors = document.querySelectorAll('.btn-info-selector');

            selectors.forEach(selector => {
                selector.classList.remove('active');
            });

            ele.classList.add('active');

            const id = ele.getAttribute('data-id');

            const block = document.getElementById(id);

            document.querySelectorAll('.collection-metadata-block').forEach(block => {
                block.classList.add('collection-metadata-block-hidden');
            });

            if (block) {
                block.classList.remove('collection-metadata-block-hidden');
            }
        }

        {{-- Eliminar colecciones --}}
        @if(auth()->user()?->isAdmin)
            function confirmDeleteCollection() {
                const confirmDelete = confirm('!!!!!!!! ¿¿¿¿ SEGURO DE ELIMINAR LA COLECCIÓN ???? !!!!!!!!')
                const form = document.getElementById('form-delete-collection');

                if (confirmDelete) {
                    form.submit();
                }
            }

            function deleteImageFromCollection(ele) {
                const url = '';
                const id = parseInt("{{$collection->id}}");
                const imageId = ''; //añadir data al bloque


                const confirmDelete = confirm('!! ¿¿ SEGURO DE ELIMINAR LA IMAGEN DE LA COLECCIÓN ?? !!')


                console.log(confirmDelete);

                // TODO: Hacer fetch - Post
            }
        @endif


        window.document.addEventListener('DOMContentLoaded', () => {
            let infoOptions = document.querySelectorAll('.btn-info-selector');

            infoOptions.forEach(ele => {
                ele.addEventListener('click', () => changeInfoSection(ele));
            });


            {{-- Eliminar colecciones --}}
            @if(auth()->user()?->isAdmin)
                const btnDeleteCollection = document.querySelector('.tool-delete-collection');

                if (btnDeleteCollection) {
                    btnDeleteCollection.addEventListener('click', confirmDeleteCollection)
                }


                const btnsDelete = document.querySelectorAll('.tool-delete-image-collection');

                if (btnsDelete) {
                    btnsDelete.forEach((btnDelete) => {
                        btnDelete.addEventListener('click', (ele) => deleteImageFromCollection(ele));
                    });
                }
            @endif

        });
    </script>
@endsection
