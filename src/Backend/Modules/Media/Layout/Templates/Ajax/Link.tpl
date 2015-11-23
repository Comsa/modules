<div class="box">
    <div class="heading">
        <h3>{$lblSearchFiles|ucfirst}</h3>
    </div>
    <div class="box">
        <div class="options">
            <input type="text" id="linkSearch" class="inputText"/>
        </div>
    </div>
</div>
<div class="box">
    <div class="heading">
        <h3>{$lblImages|ucfirst}</h3>
    </div>
    <div class="box">
        <ul class="media images">
            {option:mediaItems.images}
                {iteration:mediaItems.images}
                    <li id="li-{$mediaItems.images.id}" style="height: 150px;">
                        <img style="width:128px;" src="{$mediaItems.images.image_128x128}" alt="{$mediaItems.images.filename}" title="{$mediaItems.images.filename}">
                        <div>
                            <span class="filename" style=" display: block; height: 40px;">{$mediaItems.images.name}</span>
                        </div>
                        <input style="position: absolute; right:5px; top:0px;" type="checkbox" class="checkLink" id="{$mediaItems.images.id}"/>
                    </li>
                {/iteration:mediaItems.images}
            {/option:mediaItems.images}
        </ul>
    </div>
</div>
<div class="clearfix">&nbsp;</div>
<div class="box">
    <div class="heading">
        <h3>{$lblFiles|ucfirst}</h3>
    </div>
    <div class="box">
        <ul class="media files">
            {option:mediaItems.files}
                {iteration:mediaItems.files}
                    <li id="li-{$mediaItems.files.id}" style="height: 150px;">
                        <a class="url" target="_blank" href="{$mediaItems.files.url}">
                            <img style="width:128px;" src="/src/Backend/Modules/Media/Layout/Images/ext/{$mediaItems.files.extension}.png"">
                        </a>
                        <div>
                            <span class="filename" style=" display: block; height: 40px;">{$mediaItems.files.name}</span>
                        </div>
                        <input style="position: absolute; right:5px; top:0px;" type="checkbox" class="checkLink" id="{$mediaItems.files.id}"/>
                    </li>
                {/iteration:mediaItems.files}
            {/option:mediaItems.files}
        </ul>
    </div>
</div>
<div class="clearfix">&nbsp;</div>
<div class="buttonHolderRight clearfix" style="margin: 10px 0;">
    <a href="" id="addLinks" class="button icon iconAdd"><span>{$lblAddLinks|ucfirst}</span></a>
</div>