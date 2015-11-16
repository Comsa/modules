{*
	variables that are available:
	- {$widgetSlideshow}: contains all the data for this widget
*}
{option:widgetSlideshow}
<ul class="gallery-slideshow list-unstyled" data-cycle-fx="fade" data-cycle-slides="li" data-cycle-timeout="5000">
    {iteration:widgetSlideshow}
        <li>
            <img src="{$widgetSlideshow.image_400x300}" alt="{$widgetSlideshow.filename}" title="{$widgetSlideshow.filename}">
        </li>
    {/iteration:widgetSlideshow}
</ul>
{/option:widgetSlideshow}