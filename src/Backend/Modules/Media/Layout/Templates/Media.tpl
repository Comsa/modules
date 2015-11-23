<div>
    <div class="box">
        <div class="heading">
            <h3>{$lblUploadImage}</h3>
        </div>
        <div class="box">
            <div id="uploader">
                <p>Your browser doesn't have Flash, Silverlight or HTML5 support.</p>
            </div>
        </div>
    </div>

    <div class="buttonHolderRight clearfix" style="margin: 10px 0;">
        <a href="" id="addLink" class="button icon iconAdd"><span>{$lblAddLink|ucfirst}</span></a>
    </div>

    <div class="box">
        <div class="heading">
            <h3>{$lblImages|ucfirst}</h3>
        </div>
        <div class="box">
            <ul class="media images">
                {option:mediaItems.images}
                    {iteration:mediaItems.images}
                        <li id="li-{$mediaItems.images.id}">
                            <img style="width:128px;" src="{$mediaItems.images.image_128x128}" alt="{$mediaItems.images.filename}" title="{$mediaItems.images.filename}">
                            <div>
                                <span class="filename" style=" display: block; height: 40px;">{$mediaItems.images.name}</span>
                            </div>
                            {$mediaItems.images.txtText}
                            <a href="" style="width: 114px; text-align: center" data-message-id="confirmDelete-{$mediaItems.images.id}" class="delete button icon iconDelete" id="{$mediaItems.images.id}">{$lblDelete|ucfirst}</a>
                            <div id="confirmDelete-{$mediaItems.images.id}" title="{$lblDelete|ucfirst}?" style="display: none;">
                                <p>
                                    {$msgConfirmDelete|sprintf:{$mediaItems.images.name}}
                                </p>
                            </div>
                            <input style="position: absolute; right:5px; top:0px;" type="checkbox" class="check"/>
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
                        <li id="li-{$mediaItems.files.id}">
                            <a class="url" target="_blank" href="{$mediaItems.files.url}">
                                <img style="width:128px;" src="/src/Backend/Modules/Media/Layout/Images/ext/{$mediaItems.files.extension}.png"">
                            </a>
                            <div>
                                <span class="filename" style=" display: block; height: 40px;">{$mediaItems.files.name}</span>
                            </div>
                            {$mediaItems.files.txtText}
                            <a href="" style="width: 114px; text-align: center" data-message-id="confirmDelete-{$mediaItems.files.id}" class="delete button icon iconDelete" id="{$mediaItems.files.id}">{$lblDelete|ucfirst}</a>
                            <div id="confirmDelete-{$mediaItems.files.id}" title="{$lblDelete|ucfirst}?" style="display: none;">
                                <p>
                                    {$msgConfirmDelete|sprintf:{$mediaItems.files.name}}
                                </p>
                            </div>
                            <input style="position: absolute; right:5px; top:0px;" type="checkbox" class="check"/>
                        </li>
                    {/iteration:mediaItems.files}
                {/option:mediaItems.files}

            </ul>
        </div>
    </div>

    <div class="clearfix">&nbsp;</div>
    {option:mediaItems}
        <input type="checkbox" id="all-images" name="all-images"/>
        <label for="all-images">{$lblSelectedAll}</label>
        <a href="" style="width: 114px; text-align: center" class="deleteSelected button icon iconDelete" data-message-id="confirmDeleteSelected">{$lblDeleteSelected|ucfirst}</a>

        <div id="confirmDeleteSelected" title="{$lblDelete|ucfirst}?" style="display: none;">
            <p>
                {$msgConfirmDelete}
            </p>
        </div>
    {/option:mediaItems}
</div>

<div id="dialog" title="Basic dialog">
    <div class="loader" style="margin-top: 50px">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
</div>

<script type="text/javascript" src="/src/Backend/Modules/Media/Js/moxie.min.js"></script>
<script type="text/javascript" src="/src/Backend/Modules/Media/Js/plupload.full.min.js"></script>
<script type="text/javascript" src="/src/Backend/Modules/Media/Js/jquery.ui.plupload/jquery.ui.plupload.js"></script>
<script type="text/javascript" src="/src/Backend/Modules/Media/Js/i18n/nl.js"></script>
<script type="text/javascript" src="/src/Backend/Modules/Media/Js/Media.js"></script>

<link rel="stylesheet" href="/src/Backend/Modules/Media/Layout/Css/jquery.ui.plupload/jquery-ui.css"/>
<link rel="stylesheet" href="/src/Backend/Modules/Media/Layout/Css/jquery.ui.plupload/jquery.ui.plupload.css"/>
<link rel="stylesheet" href="/src/Backend/Modules/Media/Layout/Css/Media.css"/>