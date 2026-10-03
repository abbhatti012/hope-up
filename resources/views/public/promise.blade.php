@php
    $setting = DB::table('settings')->first();
@endphp
<style>
    .text-white span {
        color: white !important;
    }
</style>
<div data-content-type="row" data-appearance="full-bleed" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src data-element="main" data-pb-style="OWSOY69">
    <div data-content-type="block" data-appearance="default" data-element="main">
        <div class="widget block block-static-block">
        <style>#html-body [data-pb-style=TYJ13DA]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}</style>
        <div data-content-type="row" data-appearance="full-bleed" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{\&quot;desktop_image\&quot;:\&quot;./media/home/kes-bg.jpg\&quot;}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src data-element="main" data-pb-style="TYJ13DA" class="background-image-6687b8c010832 background-image-6687b8c025dab">
            <div data-content-type="html" data-appearance="default" data-element="main" data-decoded="true">
                <div class="flex md:flex-nowrap flex-wrap text-white">
                    <div class="w-full md:w-[57%] md:!pr-4 2xl:px-24 xl:px-16 lg:px-10 md:px-6 sm:px-12 xxs:px-8 px-5 xl:py-12 md:py-10 sm:py-12 py-10 my-auto">
                        <div class="lg:max-w-[95%] xl:max-w-[635px] 2xl:max-w-[715px]">
                            <p><?= $setting->promise ?></p>
                        </div>
                    </div>
                    <div class="w-full md:w-[43%]">
                        <img class="h-full object-cover" src="{{ asset($setting->promise_image) }}" alt="The KES Promise">
                    </div>
                </div>
            </div>
        </div>
        <style type="text/css">.background-image-6687b8c010832 {background-image: url({{ asset('public_assets/media/home/kes-bg.jpg') }});}</style>
        <style type="text/css">.background-image-6687b8c025dab {background-image: url({{ asset('public_assets/media/home/kes-bg.jpg') }});}</style>
        </div>
    </div>
</div>