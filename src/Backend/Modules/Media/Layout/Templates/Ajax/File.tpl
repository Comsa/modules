<li id="li-{$file.id}">
    <a class="url" target="_blank" href="{$file.url}">
        <img style="width:128px;" src="/src/Backend/Modules/Media/Layout/Images/ext/{$file.extension}.png"">
    </a>
    <div>
        <span class="filename" style=" display: block; height: 40px;">{$file.name}</span>
    </div>
    {$file.txtText}
    <a href="" style="width: 114px; text-align: center" class="delete button icon iconDelete" id="{$file.id}">{$lblDelete|ucfirst}</a>
    <input style="position: absolute; right:5px; top:0px;" type="checkbox" class="check"/>
</li>