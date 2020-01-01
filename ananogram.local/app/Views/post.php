<div class="wrap" id="post-page">
    <h2><?= esc($title); ?></h2>
    <?=$errors;?>
    <div class="container">
        <div class="row">
            <div class="col">
                <img src="https://via.placeholder.com/400" alt="placeholder image" id="placeholder-image">
                <?=$post_form;?>            
            </div>
            <div class="col">
                <h3>Instructions:</h3>
                <p><strong>Image: </strong> JPG,PNG and GIF images only.</p>
            </div>
        </div>
    </div>
    
</div>