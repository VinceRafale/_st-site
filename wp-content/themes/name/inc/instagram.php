
<h6>Instagram <a href="#" title="@duchessofrock">@duchessofrock</a></h6>

<ul class="slides">

	<?php

	function fetchData($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
	$result = fetchData("https://api.instagram.com/v1/users/269474143/media/recent/?access_token=16736410.ab103e5.a7db487e29a940778577469f5149abc6");
	$result = json_decode($result);
	$c = 0;
	foreach ($result->data as $post2) : ?>

		<?php if($c < 5) : ?>
			<li>
				<a href="<?php echo $post2->link; ?>">
					<img src="<?= $post2->images->standard_resolution->url ?>" width="220" height="220" />
					<span class="meta">
						<span class="comments">
							<img src="<?php bloginfo("template_directory"); ?>/images/instagram-comments.png" alt="instagram-comments" width="18" height="20" /> <span class="numbers"><?php echo $post2->comments->count; ?></span></span>
						<span class="likes"><img src="<?php bloginfo("template_directory"); ?>/images/instagram-likes.png" alt="instagram-likes" width="21" height="18" /> <span class="numbers"><?php echo $post2->likes->count; ?></span></span>
					</span>
				</a>

			</li>
		<?php  endif; ?>
	<?php $c++; endforeach; ?>
</ul>
