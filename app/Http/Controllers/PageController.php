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
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex
                ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat
                nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit
                anim id est laborum',
                'gitlab' => 'https://gitlab.com/raupulus/python-ai-image-from-api-generator',
                'github' => 'https://github.com/raupulus/python-ai-image-from-api-generator'
            ],
            [
                'title' => 'Website Larave AI Image Gallery',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex
                ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat
                nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit
                anim id est laborum',
                'gitlab' => 'https://gitlab.com/raupulus/laravel-ai-image-gallery',
                'github' => 'https://github.com/raupulus/laravel-ai-image-gallery'
            ],
            [
                'title' => 'Youtube Video Publisher',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex
                ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat
                nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit
                anim id est laborum',
                'gitlab' => 'https://gitlab.com/raupulus/python-video-publisher',
                'github' => 'https://github.com/raupulus/python-video-publisher'
            ],

            [
                'title' => 'Ffmpeg Slideshow Creator from directory',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex
                ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat
                nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit
                anim id est laborum',
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
