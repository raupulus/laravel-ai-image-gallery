<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Class PageController
 */
class PageController extends Controller
{
    private static array $pages = [
        'about' => [
            'slug' => 'about',
            'title' => 'About',
            'description' => 'About AI Automations Experimental project and author Raúl Caro Pastorino.',
            'view' => 'pages.content._about',
            'active' => true,
        ],
        'cookies' => [
            'slug' => 'cookies',
            'title' => 'Cookies',
            'description' => 'Cookies Policy for AI Dyslexic.',
            'view' => 'pages.content._cookies',
            'active' => false,
        ],
        'rgpd' => [
            'slug' => 'rgpd',
            'title' => 'RGPD',
            'description' => 'Privacy and RGPD/GDPR Policy for AI Dyslexic.',
            'view' => 'pages.content._rgpd',
            'active' => false,
        ],
        'social' => [
            'slug' => 'social',
            'title' => 'Social Networks',
            'description' => 'Social networks and project links for AI Automations Experimental.',
            'view' => 'pages.content._social',
            'active' => true,
        ],
    ];

    /**
     * Devuelve todas las páginas configuradas.
     *
     * @return array
     */
    public static function getPages(): array
    {
        return self::$pages;
    }

    /**
     * Devuelve únicamente las páginas activas con contenido para indexar en sitemap.
     *
     * @return array
     */
    public static function getActivePages(): array
    {
        return array_filter(self::$pages, function (array $page) {
            return !empty($page['active']);
        });
    }

    /**
     * Muestra el contenido de una página del sitio concreta.
     *
     * @param string $page Recibe el slug de la página a la que quiere acceder
     *
     * @return View|RedirectResponse
     */
    public function show(string $page): View|RedirectResponse
    {
        $pageSlug = $page;
        $pageData = isset(self::$pages[$pageSlug]) && self::$pages[$pageSlug] ? self::$pages[$pageSlug] : null;
        $partial_view = $pageData ? $pageData['view'] : null;

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
            'slug' => $pageSlug,
            'page' => $pageData,
            'partial_view' => $partial_view,
            'socials' => $socials,
        ]);
    }
}
