
            <div class="wrap">
			<h1>Welcome to Ananogram</h1>

			<p class="version">Made with codeigniter version <?= CodeIgniter\CodeIgniter::CI_VERSION ?></p>

			<div class="guide">
                            <div class="container">
                            <?php if (! empty($posts) && is_array($posts)) : ?>
                                <div class="row">
                                <?php foreach ($posts as $key => $post): ?>
                                <?=(($key+1)%3 == 0?'</div><div class="row">':'');?>
                                    <div class="col post">
                                       <img src="/media/<?= $post['image_name'] ?>" width="400" height="400" alt="<?= $post['heading'] ?>">
                                       <h3><?= $post['heading'] ?></h3>
                                       <div class="main">
                                               <?= $post['body'] ?>
                                       </div>                                   
                                    </div>
                                <?php endforeach; ?>
                                </div>
                            <?php else : ?>
                                <h3>No Posts</h3>
                                <p>Unable to find any new posts for you.</p>
                            <?php endif ?>
                            </div>
			</div>
            </div>
