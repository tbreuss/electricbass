<?php
use app\assets\AlphaTabAsset;

AlphaTabAsset::register($this);
?>
<div class="shortcode shortcode--alphatab">
    <div class="alphaTabAlphaTex" data-tex="true"><?= trim($alphatab) ?></div>
</div>

<?php
$this->registerJs(<<<JS

    var at = $("div.alphaTabAlphaTex");
    
    $(at).on('resize', function(e) {
            
        var info = e.originalEvent.detail;
        var newSize = info.NewWidth;
        
        return;
        
        if(newSize < 600) {
            info.Settings.Layout.Mode = "page";
            info.Settings.Layout.AdditionalSettings['barsPerRow'] = 2;            
        }
        /*else if(newSize < 800) {
            info.Settings.Layout.Mode = "page";
            delete info.Settings.Layout.AdditionalSettings['barsPerRow'];
        }
        else if(newSize < 1000) {
            info.Settings.Layout.Mode = "page";
            info.Settings.Layout.AdditionalSettings['barsPerRow'] = 3;
        }*/
        else {
            info.Settings.Layout.Mode = "page";
            //delete info.Settings.Layout.AdditionalSettings['barsPerRow'];
            info.Settings.Layout.AdditionalSettings['barsPerRow'] = 4;            
        }
    });
    
    at.alphaTab({
        autoSize:true,
        scale: 0.75,
        layout: {
            //mode: 'page',
            additionalSettings: {
                autoSize: true,
                //barsPerRow: 4,
                hideInfo: true
            }
        },
        width: -1, // negative width enables auto sizing        
    });

JS
);
?>

<style>

    .alphaTabAlphaTex {
        /*width: 100%;
        height: auto;
        display: block;
        margin: auto;*/
        background-color: #f6f6f5;
        margin-bottom: 20px;
    }

    .alphaTabSurface {
        border: 1px solid #f3f5f6;
        background: #FFF;
        box-shadow: 0 3px 5px rgba(0, 0, 0, 0.2);
        margin-bottom: 20px;
    }

</style>
