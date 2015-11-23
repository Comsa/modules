<li id="li-{$image.id}">
    <img style="width:128px;" src="{$image.image_128x128}" alt="{$image.filename}" title="{$image.filename}">
    <div>
        <span class="filename" style=" display: block; height: 40px;">{$image.name}</span>
    </div>
    {$image.txtText}
    <a href="" style="width: 114px; text-align: center" class="delete button icon iconDelete" id="{$image.id}">{$lblDelete|ucfirst}</a>
    <input style="position: absolute; right:5px; top:0px;" type="checkbox" class="check"/>
</li>