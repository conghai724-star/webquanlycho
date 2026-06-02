<?php require "header_new.php"; ?>
</section>
<div id="breadcrumbs">
	<div class="ctnc">
		<div id="crumbs"><a href="<?php echo XC_URL; ?>">Trang chủ</a> <span>/</span> <a href="#"><?php echo $events->event_name;?></a><span></span></div>
	</div>
</div>
<div class="ctnc">
	<div class="row">
		<div class="col-12 clg-2 is_pc">

		</div>
		<div id="main" class="col-12 clg-8">


			<article id="catePts">

				<div class="is_pc">

				</div>

				<div id="pstDetail">
					<h2 id="pstpTitle"><?php echo $events->event_name;?></h2>

					<?php echo $events->event_content;?>
			</article>
			<div id="pstSim2" class=" bgGreen" style="
    background-color: #f2f8fd;">
				<div id="pstSim-tit"><span>Bài viết nổi bật</span></div>
				<div class="row">
					    <?php foreach($event_hot as $event_hot){?>
					<div class="col-12 clg-4">
						<div class="sec4Box">
							<a href="<?php echo $this->helper->permalink($event_hot->id, 'event');?>" class="sec4Box-thumb">
								<img src="<?php echo XC_URL; ?>/uploads/events/<?php echo $event_hot->event_image; ?>" alt="<?php echo $event_hot->event_name;?>">
							</a>
							<div class="sec4Box-txt">
								<a href="<?php echo $this->helper->limit_text($event_hot->event_name,50);?>" class="sec4Box-name"><?php echo $this->helper->limit_text($event_hot->event_name,50);?></a>
								<p><?php echo $this->helper->limit_text($event_hot->event_content,200);?></p>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>


			
			
		</div>
		<!-- sidebar -->
		<aside id="sidebar" class="col-12 clg-2 hidden_mobi">
			<!-- <div id="sbSrch" class="sbbox">
		<form class="sbSearch" method="get" action="#" role="search">
			<input class="search-input" type="search" name="s" placeholder="Tìm kiếm">
			<button class="search-submit" type="submit" role="button"><i class="fas fa-search"></i></button>
		</form>
	</div> -->
			<div id="sbPost" class="sbbox">
				<div class="sbHead">
					<!-- <i class="sbHead-ic allicon"></i> -->
					<span style="color:#fff;">Tin mới nhất</span>
				</div>
				<div class="sbCntn">
					    <?php foreach($event_new as $event_new){?>
					<a href="<?php echo $this->helper->permalink($event_new->id, 'event');?>" class="sbPost">
						<div class="sbPost-thumb">
							<img src="<?php echo XC_URL; ?>/uploads/events/<?php echo $event_new->event_image; ?>" alt="<?php echo $event_new->event_name;?>">
						</div>
						<div class="sbPost-inf">
							<h5 class="sbPost-tit" style="color:#fff;"><?php echo $event_new->event_name;?></h5>
							<!-- <div class="sbPost-meta">
                            <span><i class="far fa-clock"></i> 05/01/2026</span>
                            <span><i class="fas fa-eye"></i> 69</span>   
                        </div> -->
						</div>
					</a>
					<?php } ?>
					
				</div>
			</div>
			
		</aside>
		<!-- /sidebar -->
	</div>
</div>
</main>


<style>
	.change-font {
		position: absolute;
		/* display: flex; */
		/* justify-content: end; */
		/* margin: 0; */
		top: -35px;
		right: 3%;
	}

	button.btn-show-change-fontsize {
		outline: none;
		border: none;
		background: none;
		padding: 0;
		cursor: pointer;
	}

	.show-change-w {
		display: flex;
	}

	.show-change-font-size {
		position: absolute;
		display: none;
		top: calc(100%);
		right: 0;
		width: 290px;
		z-index: 2;
		background: white;
		padding: 5px;
		border-radius: 5px;
		box-shadow: 0px 1px 5px 1px rgba(0, 0, 0, 0.1);
	}

	.show-change-font-size input {
		width: 100%;
		box-shadow: none;
		outline: none;
		border: none;
	}
</style>

<?php require "footer_new.php"; ?>