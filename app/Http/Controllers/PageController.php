<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Class PageController
 */
class PageController extends Controller
{
    private $pages = [
        'about' => [
            'title' => 'About',
            'view' => 'pages.content._about'
        ],
        'cookies' => [
            'title' => 'Cookies',
            'view' => 'pages.content._cookies'
        ],
        'rgpd' => [
            'title' => 'RPGD',
            'view' => 'pages.content._rgpd'
        ],
        'social' => [
            'title' => 'Social Networks',
            'view' => 'pages.content._social'
        ],
    ];

    /**
     * Muestra el contenido de una página del sitio concreta.
     *
     * @param string $page Recibe el slug de la página a la que quiere acceder
     *
     * @return View|RedirectResponse
     */
    public function show(string $page): View|RedirectResponse
    {

        $page = isset($this->pages[$page]) && $this->pages[$page] ? $this->pages[$page] : null;
        $partial_view = $page ? $page['view'] : null;

        if (!$partial_view) {
            return redirect()->route('home');
        }


        $socials = [
            [
                'title' => 'Prompt Generator from AI',
                'description' => 'Tool to generate "prompts" that are basically text strings indicating/describing an image. Subsequently, several APIs can be used to use that generated "prompt".<br />To generate the prompt, the GPT API is used.',
                'gitlab' => 'https://gitlab.com/raupulus/python-ai-image-from-api-generator',
                'github' => 'https://github.com/raupulus/python-ai-image-from-api-generator'
            ],
            [
                'title' => 'Website Laravel AI Image Gallery',
                'description' => 'Website to display a gallery of images generated with artificial intelligence.',
                'gitlab' => 'https://gitlab.com/raupulus/laravel-ai-image-gallery',
                'github' => 'https://github.com/raupulus/laravel-ai-image-gallery'
            ],
            [
                'title' => 'Youtube Video Publisher',
                'description' => 'This project aims to be a tool to help upload videos to websites and social networks, being able to also send a request to your own API in which these videos are associated with content.<br /The origin of the data will be via a json with the same name as the video, in order to have all the necessary metadata both when uploading the video and when communicating in our API that we have associated it and what its data is.',
                'gitlab' => 'https://gitlab.com/raupulus/python-video-publisher',
                'github' => 'https://github.com/raupulus/python-video-publisher'
            ],

            [
                'title' => 'Ffmpeg Slideshow Creator from directory',
                'description' => 'Script to create image videos with ffmpeg including transitions and music.<br />This tool is focused on Linux and macos.
​',
                'gitlab' => 'https://gitlab.com/raupulus/ffmpeg-slideshow-from-image-directory',
                'github' => 'https://github.com/raupulus/ffmpeg-slideshow-from-image-directory'
            ],
        ];

        return view('pages.show', [
            'page' => $page,
            'partial_view' => $partial_view,
            'socials' => $socials,
        ]);
    }
}
