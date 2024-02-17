# Website Laravel AI Image Gallery

Website to display a gallery of images generated with artificial intelligence.

* Generate an idea based on the role system
* Auto-generated prompt from the generated idea
* Stable diffusion API connection or Dall-e to generate a batch of images with the **PROMPT**
* Publication of the batch of images to my website and on social networks
* Creation of slideshow with transitions and music using ffmpeg
* Publishing videos on video platforms

<p align="center">
    <img src="https://aidyslexic.raupulus.dev/images/logo/logo_640x640.webp" style="width:100%; max-width: 500px;">
</p>

The objective of this project is to generate an "idea" based on a system of roles with which later finish generating a
prompt using GPT3.5 Instruct.

This prompt will be used to generate a batch/collection of images using the Stable Diffusion API although it is also
possible to use Dall-e.

Once a batch of images is generated they will be uploaded to my own [API](https://aidyslexic.raupulus.dev) to be stored
as a gallery.

Subsequently, the images will be published on the integrated social networks.
Mostly: [Twitter](https://twitter.com/ai_automations) , [Instagram](https://www.instagram.com/ai_automations_experimental) , [Mastodon](https://mastodon.online/@ai_automations_experimental) , [Telegram](https://t.me/ai_automations_experimental)

Then, with all those images and using the **ffmpeg** video tool, you create automatically a slideshow with transitions
between images and background music.

This video will be automatically uploaded to [**tiktok**](https://www.tiktok.com/@ai_automations) with selenium and [*
*Youtube**](https://www.youtube.com/@ai_automations_experimental) using your api and, it will also be synchronized by
sending a request to my api so that the video is set as collection/gallery cover.

<p align="center">
    <img src="https://aidyslexic.raupulus.dev/images/doc/preview.webp" style="width:100%; max-width: 900px;">
</p>

* * *

## Motivation

I started this project out of interest in learning about the rise of artificial intelligence and possibilities that
these tools currently offer.

Another aspect that caught my attention when carrying out this project is the possibility of Learn to work by connecting
all the APIs of social networks and video platforms like YouTube.

Generating images is quite fun and, you can also use it for project covers or images with which to fill web developments
that I carry out.

The main objective is to experiment and learn although, I would also like to leave some passive income trying to sell
images on stock platforms and perhaps with some ads that are not intrusive for the user. cost of maintaining hardware.

* * *

## Deploy

Create postgresql database (change params)

```bash
sudo -u postgres createdb -O web -T template1 aidyslexic_raupulus_dev
```

Prepare **.env** and edit content:

```bash
cp .env.example .env
```

Execute commands:

```bash
composer install --no-dev
php artisan migrate
php artisan db:seed
```

* * *


## Associated tools

### Prompt Generator from AI

Tool to generate "prompts" that are basically text strings indicating/describing an image. Subsequently, several APIs
can be used to use that generated "prompt".  
To generate the prompt, the GPT API is used.

[python-ai-image-from-api-generator](https://gitlab.com/raupulus/python-ai-image-from-api-generator)

[python-ai-image-from-api-generator](https://github.com/raupulus/python-ai-image-from-api-generator)

### Website Laravel AI Image Gallery

Website to display a gallery of images generated with artificial intelligence.

[laravel-ai-image-gallery](https://gitlab.com/raupulus/laravel-ai-image-gallery)

[laravel-ai-image-gallery](https://github.com/raupulus/laravel-ai-image-gallery)

### Youtube Video Publisher

This project aims to be a tool to help upload videos to websites and social networks, being able to also send a request
to your own API in which these videos are associated with content.

[python-video-publisher](https://gitlab.com/raupulus/python-video-publisher)

[python-video-publisher](https://github.com/raupulus/python-video-publisher)

### Ffmpeg Slideshow Creator from directory

Script to create image videos with ffmpeg including transitions and music.  
This tool is focused on Linux and Macos. ​

[ffmpeg-slideshow-from-image-directory](https://gitlab.com/raupulus/ffmpeg-slideshow-from-image-directory)

[ffmpeg-slideshow-from-image-directory](https://github.com/raupulus/ffmpeg-slideshow-from-image-directory)


* * *

## Project Social Networks

[Twitter](https://twitter.com/ai_automations)

[Telegram](https://t.me/ai_automations_experimental)

[Youtube](https://www.youtube.com/@ai_automations_experimental)

[Mastodon](https://mastodon.online/@ai_automations_experimental)

[TikTok](https://www.tiktok.com/@ai_automations)

[Instagram](https://www.instagram.com/ai_automations_experimental)

* * *

## Author

My name is [Raúl Caro Pastorino](https://raupulus.dev) On the networks you can find me with the
nickname [@raupulus](https://social.fryntiz.dev/)

I am a backend web developer and I mainly work with **php/laravel** and **javascript/vue.js** although I also sometimes
develop in other languages such as python or bash.

I am passionate about electronics and IOT, I usually always have some project on the work table. You can find some of my
projects in the repositories of [Gitlab](https://gitlab.com/raupulus) and [Github](https://github.com/raupulus)

* * *
