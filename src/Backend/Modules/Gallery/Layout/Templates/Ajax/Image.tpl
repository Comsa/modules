<li id="li-{$image.id}">
    <img style="width:128px;" src="{$image.image_128x128}" alt="{$image.filename}" title="{$image.filename}">
    <div>
        <span class="filename" style=" display: block; height: 40px;">{$image.name}</span>
    </div>
    {$image.field_description}
    <a href="" style="width: 114px; text-align: center" data-message-id="confirmDelete-{$image.id}" class="delete button icon iconDelete" id="{$image.id}">{$lblDelete|ucfirst}</a>
    <div id="confirmDelete-{$image.id}" title="{$lblDelete|ucfirst}?" style="display: none;">
        <p>
            {$msgConfirmDelete|sprintf:{$image.name}}
        </p>
    </div>
    <input style="position: absolute; right:5px; top:0px;" type="checkbox" class="check"/>
</li>